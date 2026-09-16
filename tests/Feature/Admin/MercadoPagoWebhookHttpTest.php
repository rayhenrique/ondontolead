<?php

namespace Tests\Feature\Admin;

use App\Jobs\ProcessMercadoPagoWebhook;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class MercadoPagoWebhookHttpTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_valid_webhook_payload_queues_job_and_returns_accepted(): void
    {
        Queue::fake();
        config(['services.mercado_pago.webhook_secret' => null]);

        $payload = [
            'type' => 'subscription_preapproval',
            'data' => [
                'id' => 'sub_12345678',
            ],
        ];

        $response = $this->postJson('/api/webhooks/mercadopago', $payload);

        $response->assertAccepted()
            ->assertJsonStructure(['status', 'event_id']);

        Queue::assertPushed(ProcessMercadoPagoWebhook::class, function ($job) use ($payload): bool {
            return $job->payload === $payload;
        });
    }

    public function test_invalid_webhook_payload_returns_unprocessable_entity(): void
    {
        Queue::fake();
        config(['services.mercado_pago.webhook_secret' => null]);

        // Missing type and data.id
        $response = $this->postJson('/api/webhooks/mercadopago', ['foo' => 'bar']);

        $response->assertUnprocessable()
            ->assertJsonStructure(['error']);

        Queue::assertNothingPushed();
    }

    public function test_webhook_with_secret_rejects_missing_or_invalid_signature(): void
    {
        Queue::fake();
        config(['services.mercado_pago.webhook_secret' => 'test_secret_key']);

        $payload = [
            'type' => 'subscription_preapproval',
            'data' => ['id' => 'sub_99999'],
        ];

        // Request without header
        $this->postJson('/api/webhooks/mercadopago', $payload)
            ->assertUnauthorized();

        // Request with invalid signature
        $this->withHeaders(['x-signature' => 'invalid_signature'])
            ->postJson('/api/webhooks/mercadopago', $payload)
            ->assertUnauthorized();

        Queue::assertNothingPushed();
    }

    public function test_webhook_with_secret_accepts_valid_signature(): void
    {
        Queue::fake();
        $secret = 'test_secret_key';
        config(['services.mercado_pago.webhook_secret' => $secret]);

        $payload = [
            'type' => 'subscription_preapproval',
            'data' => ['id' => 'sub_99999'],
        ];

        $ts = (string) time();
        $manifest = 'id:sub_99999;request-id:;ts:'.$ts.';';
        $hash = hash_hmac('sha256', $manifest, $secret);
        $signatureHeader = "ts={$ts},v1={$hash}";

        $response = $this->postJson('/api/webhooks/mercadopago', $payload, [
            'x-signature' => $signatureHeader,
        ]);

        $response->assertAccepted()
            ->assertJsonStructure(['status', 'event_id']);

        Queue::assertPushed(ProcessMercadoPagoWebhook::class);
    }
}
