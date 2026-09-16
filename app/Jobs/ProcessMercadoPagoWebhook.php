<?php

namespace App\Jobs;

use App\Services\MercadoPagoWebhookService;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Illuminate\Foundation\Queue\Queueable;

class ProcessMercadoPagoWebhook implements ShouldBeEncrypted, ShouldBeUnique, ShouldQueueAfterCommit
{
    use Queueable;

    public int $tries = 4;

    public int $timeout = 30;

    public int $uniqueFor = 3600;

    /** @var list<int> */
    public array $backoff = [10, 60, 300];

    /**
     * @param  array<string, mixed>  $payload
     */
    public function __construct(
        public string $eventId,
        public array $payload,
    ) {
        $this->onQueue('webhooks');
    }

    public function handle(MercadoPagoWebhookService $service): void
    {
        $service->process($this->eventId, $this->payload);
    }

    public function uniqueId(): string
    {
        return $this->eventId;
    }
}
