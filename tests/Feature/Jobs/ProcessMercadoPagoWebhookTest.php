<?php

namespace Tests\Feature\Jobs;

use App\Jobs\ProcessMercadoPagoWebhook;
use App\Services\MercadoPagoWebhookService;
use Tests\TestCase;

class ProcessMercadoPagoWebhookTest extends TestCase
{
    public function test_job_processes_payload_through_webhook_service(): void
    {
        $payload = [
            'type' => 'subscription_preapproval',
            'data' => ['id' => 'subscription-123'],
        ];
        $service = $this->mock(MercadoPagoWebhookService::class);
        $service->shouldReceive('process')
            ->once()
            ->with('event-hash', $payload);
        $job = new ProcessMercadoPagoWebhook('event-hash', $payload);

        $job->handle($service);

        $this->assertSame('event-hash', $job->uniqueId());
        $this->assertSame('webhooks', $job->queue);
        $this->assertSame(4, $job->tries);
        $this->assertSame(30, $job->timeout);
        $this->assertSame([10, 60, 300], $job->backoff);
    }
}
