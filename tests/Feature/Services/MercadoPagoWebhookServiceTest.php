<?php

namespace Tests\Feature\Services;

use App\Exceptions\InvalidWebhookPayloadException;
use App\Jobs\ProcessMercadoPagoWebhook;
use App\Models\Clinic;
use App\Models\PaymentLog;
use App\Services\MercadoPagoWebhookService;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class MercadoPagoWebhookServiceTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_valid_webhook_is_dispatched_with_stable_payload_hash(): void
    {
        Queue::fake([ProcessMercadoPagoWebhook::class]);
        $service = app(MercadoPagoWebhookService::class);
        $firstPayload = [
            'type' => 'subscription_preapproval',
            'action' => 'updated',
            'data' => ['id' => 'subscription-123'],
        ];
        $samePayloadWithDifferentKeyOrder = [
            'data' => ['id' => 'subscription-123'],
            'action' => 'updated',
            'type' => 'subscription_preapproval',
        ];

        $firstEventId = $service->dispatch($firstPayload);
        $secondEventId = $service->dispatch($samePayloadWithDifferentKeyOrder);

        $this->assertSame($firstEventId, $secondEventId);
        $this->assertSame(64, strlen($firstEventId));
        Queue::assertPushed(
            ProcessMercadoPagoWebhook::class,
            fn (ProcessMercadoPagoWebhook $job): bool => $job->eventId === $firstEventId
                && $job->payload === $firstPayload,
        );
    }

    public function test_invalid_webhook_is_not_dispatched(): void
    {
        Queue::fake([ProcessMercadoPagoWebhook::class]);

        try {
            app(MercadoPagoWebhookService::class)->dispatch([
                'type' => 'subscription_preapproval',
                'data' => [],
            ]);
            $this->fail('O serviço deveria rejeitar o payload inválido.');
        } catch (InvalidWebhookPayloadException $exception) {
            $this->assertSame(
                'Payload de webhook do Mercado Pago inválido.',
                $exception->getMessage(),
            );
        }

        Queue::assertNothingPushed();
    }

    #[DataProvider('subscriptionStatuses')]
    public function test_subscription_status_updates_clinic_and_marks_event_processed(
        string $mercadoPagoStatus,
        string $expectedClinicStatus,
    ): void {
        config()->set('services.mercado_pago.access_token', 'mp-access-token');
        Http::preventStrayRequests();
        Http::fake([
            'https://api.mercadopago.com/preapproval/subscription-123' => Http::response([
                'id' => 'subscription-123',
                'status' => $mercadoPagoStatus,
            ]),
        ]);
        $clinic = Clinic::factory()->create([
            'mp_subscription_id' => 'subscription-123',
            'subscription_status' => 'trial',
        ]);
        $service = app(MercadoPagoWebhookService::class);
        $payload = $this->subscriptionPayload();
        $eventId = $service->eventId($payload);

        $paymentLog = $service->process($eventId, $payload);

        $this->assertSame($expectedClinicStatus, $clinic->refresh()->subscription_status);
        $this->assertSame(PaymentLog::STATUS_PROCESSED, $paymentLog->status);
        $this->assertSame($payload, $paymentLog->payload);
        $this->assertDatabaseHas('payment_logs', [
            'event_id' => $eventId,
            'status' => PaymentLog::STATUS_PROCESSED,
        ]);
    }

    public function test_processed_event_is_idempotent_and_does_not_repeat_remote_query(): void
    {
        config()->set('services.mercado_pago.access_token', 'mp-access-token');
        Http::preventStrayRequests();
        Http::fake([
            'https://api.mercadopago.com/preapproval/subscription-123' => Http::response([
                'id' => 'subscription-123',
                'status' => 'authorized',
            ]),
        ]);
        Clinic::factory()->create(['mp_subscription_id' => 'subscription-123']);
        $service = app(MercadoPagoWebhookService::class);
        $payload = $this->subscriptionPayload();
        $eventId = $service->eventId($payload);

        $firstLog = $service->process($eventId, $payload);
        $secondLog = $service->process($eventId, $payload);

        $this->assertTrue($firstLog->is($secondLog));
        $this->assertDatabaseCount('payment_logs', 1);
        Http::assertSentCount(1);
    }

    public function test_failed_event_is_recorded_and_can_be_retried(): void
    {
        config()->set('services.mercado_pago.access_token', 'mp-access-token');
        Http::preventStrayRequests();
        Http::fake([
            'https://api.mercadopago.com/preapproval/subscription-123' => Http::sequence()
                ->push(['error' => 'temporary'], 500)
                ->push(['id' => 'subscription-123', 'status' => 'authorized']),
        ]);
        $clinic = Clinic::factory()->create([
            'mp_subscription_id' => 'subscription-123',
            'subscription_status' => 'trial',
        ]);
        $service = app(MercadoPagoWebhookService::class);
        $payload = $this->subscriptionPayload();
        $eventId = $service->eventId($payload);

        try {
            $service->process($eventId, $payload);
            $this->fail('A primeira tentativa deveria falhar.');
        } catch (RequestException) {
            $this->assertDatabaseHas('payment_logs', [
                'event_id' => $eventId,
                'status' => PaymentLog::STATUS_FAILED,
            ]);
            $this->assertSame('trial', $clinic->refresh()->subscription_status);
        }

        $paymentLog = $service->process($eventId, $payload);

        $this->assertSame(PaymentLog::STATUS_PROCESSED, $paymentLog->status);
        $this->assertSame('active', $clinic->refresh()->subscription_status);
        $this->assertDatabaseCount('payment_logs', 1);
        Http::assertSentCount(2);
    }

    public function test_unrelated_topic_is_logged_without_remote_request(): void
    {
        Http::preventStrayRequests();
        $service = app(MercadoPagoWebhookService::class);
        $payload = [
            'type' => 'payment',
            'data' => ['id' => 'payment-123'],
        ];
        $eventId = $service->eventId($payload);

        $paymentLog = $service->process($eventId, $payload);

        $this->assertSame(PaymentLog::STATUS_PROCESSED, $paymentLog->status);
        Http::assertNothingSent();
    }

    public static function subscriptionStatuses(): array
    {
        return [
            'authorized becomes active' => ['authorized', 'active'],
            'pending becomes past due' => ['pending', 'past_due'],
            'paused becomes past due' => ['paused', 'past_due'],
            'canceled becomes canceled' => ['canceled', 'canceled'],
            'cancelled becomes canceled' => ['cancelled', 'canceled'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function subscriptionPayload(): array
    {
        return [
            'id' => 987654,
            'type' => 'subscription_preapproval',
            'action' => 'updated',
            'data' => ['id' => 'subscription-123'],
        ];
    }
}
