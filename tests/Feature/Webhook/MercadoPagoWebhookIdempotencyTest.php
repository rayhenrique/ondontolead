<?php

namespace Tests\Feature\Webhook;

use App\Models\Clinic;
use App\Models\PaymentLog;
use App\Services\MercadoPagoWebhookService;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class MercadoPagoWebhookIdempotencyTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_duplicate_webhook_requests_maintain_idempotency_and_do_not_duplicate_payment_logs(): void
    {
        config(['services.mercado_pago.webhook_secret' => null]);
        config(['services.mercado_pago.access_token' => 'test-mp-token']);

        $clinic = Clinic::factory()->trial()->create([
            'mp_subscription_id' => 'sub_idempotente_999',
            'subscription_status' => 'trial',
        ]);

        Http::fake([
            'https://api.mercadopago.com/preapproval/sub_idempotente_999' => Http::response([
                'id' => 'sub_idempotente_999',
                'status' => 'authorized',
            ], 200),
        ]);

        $payload = [
            'type' => 'subscription_preapproval',
            'action' => 'updated',
            'data' => [
                'id' => 'sub_idempotente_999',
            ],
        ];

        // 1ª Entrega HTTP do Webhook
        $response1 = $this->postJson('/api/webhooks/mercadopago', $payload);
        $response1->assertAccepted();
        $eventId1 = $response1->json('event_id');

        // Processa o job da primeira entrega
        $service = app(MercadoPagoWebhookService::class);
        $log1 = $service->process($eventId1, $payload);

        $this->assertSame(PaymentLog::STATUS_PROCESSED, $log1->status);
        $this->assertSame('active', $clinic->fresh()->subscription_status);
        $this->assertSame(1, PaymentLog::where('event_id', $eventId1)->count());

        // 2ª Entrega HTTP do Webhook (Retransmissão idêntica pelo Mercado Pago)
        $response2 = $this->postJson('/api/webhooks/mercadopago', $payload);
        $response2->assertAccepted();
        $eventId2 = $response2->json('event_id');

        $this->assertSame($eventId1, $eventId2);

        // Processa o job da segunda entrega
        $log2 = $service->process($eventId2, $payload);

        $this->assertSame($log1->id, $log2->id);
        $this->assertSame(PaymentLog::STATUS_PROCESSED, $log2->status);
        $this->assertSame('active', $clinic->fresh()->subscription_status);

        // Garante que estritamente 1 único registro de PaymentLog persiste no banco
        $this->assertSame(1, PaymentLog::where('event_id', $eventId1)->count());
    }

    public function test_job_idempotency_detects_already_processed_event_and_does_not_call_external_api(): void
    {
        config(['services.mercado_pago.access_token' => 'test-mp-token']);

        $clinic = Clinic::factory()->trial()->create([
            'mp_subscription_id' => 'sub_cached_888',
        ]);

        // Simula apenas 1 requisição permitida à API do Mercado Pago
        Http::fake([
            'https://api.mercadopago.com/preapproval/sub_cached_888' => Http::sequence()
                ->push(['id' => 'sub_cached_888', 'status' => 'authorized'], 200)
                ->whenEmpty(Http::response(['error' => 'Not expected to be called again'], 500)),
        ]);

        $payload = [
            'type' => 'subscription_preapproval',
            'data' => ['id' => 'sub_cached_888'],
        ];

        $service = app(MercadoPagoWebhookService::class);
        $eventId = $service->eventId($payload);

        // Execução 1: consome a API e processa
        $firstResult = $service->process($eventId, $payload);
        $this->assertSame(PaymentLog::STATUS_PROCESSED, $firstResult->status);

        // Execução 2: detecta idempotência e não chama a API externa novamente
        $secondResult = $service->process($eventId, $payload);
        $this->assertSame(PaymentLog::STATUS_PROCESSED, $secondResult->status);
        $this->assertSame($firstResult->id, $secondResult->id);

        Http::assertSentCount(1);
    }

    public function test_unhandled_webhook_type_marks_log_as_processed_idempotently(): void
    {
        $payload = [
            'type' => 'payment',
            'data' => ['id' => 'payment_99999'],
        ];

        $service = app(MercadoPagoWebhookService::class);
        $eventId = $service->eventId($payload);

        $log1 = $service->process($eventId, $payload);
        $this->assertSame(PaymentLog::STATUS_PROCESSED, $log1->status);

        // Reprocessamento retorna o mesmo registro
        $log2 = $service->process($eventId, $payload);
        $this->assertSame($log1->id, $log2->id);
        $this->assertSame(PaymentLog::STATUS_PROCESSED, $log2->status);
        $this->assertSame(1, PaymentLog::where('event_id', $eventId)->count());
    }

    public function test_invalid_hmac_signature_rejects_before_queue_or_log_creation(): void
    {
        Queue::fake();
        config(['services.mercado_pago.webhook_secret' => 'super_secret_key_xyz']);

        $payload = [
            'type' => 'subscription_preapproval',
            'data' => ['id' => 'sub_unauthorized_123'],
        ];

        // Requisição com assinatura forjada
        $response = $this->withHeaders([
            'x-signature' => 'ts=1726000000,v1=assinatura_falsa_invalida',
        ])->postJson('/api/webhooks/mercadopago', $payload);

        $response->assertUnauthorized();

        Queue::assertNothingPushed();
        $this->assertSame(0, PaymentLog::count());
    }
}
