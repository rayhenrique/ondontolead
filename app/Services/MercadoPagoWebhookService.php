<?php

namespace App\Services;

use App\Exceptions\InvalidWebhookPayloadException;
use App\Jobs\ProcessMercadoPagoWebhook;
use App\Models\Clinic;
use App\Models\PaymentLog;
use App\Services\MercadoPago\MercadoPagoClient;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use JsonException;
use Throwable;
use UnexpectedValueException;

class MercadoPagoWebhookService
{
    public function __construct(private MercadoPagoClient $client) {}

    /**
     * @param  array<string, mixed>  $payload
     *
     * @throws JsonException
     */
    public function dispatch(array $payload): string
    {
        $this->validatePayload($payload);
        $eventId = $this->eventId($payload);

        ProcessMercadoPagoWebhook::dispatch($eventId, $payload);

        return $eventId;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function process(string $eventId, array $payload): PaymentLog
    {
        $this->validatePayload($payload);
        $paymentLog = $this->claimEvent($eventId, $payload);

        if ($paymentLog->status === PaymentLog::STATUS_PROCESSED) {
            return $paymentLog;
        }

        try {
            if ($payload['type'] !== 'subscription_preapproval') {
                return $this->markProcessed($eventId);
            }

            $subscription = $this->client->subscription((string) data_get($payload, 'data.id'));
            $subscriptionId = $subscription['id'];
            $subscriptionStatus = $this->subscriptionStatus($subscription['status']);

            return DB::transaction(function () use (
                $eventId,
                $subscriptionId,
                $subscriptionStatus,
            ): PaymentLog {
                $lockedLog = PaymentLog::query()
                    ->where('event_id', $eventId)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($lockedLog->status === PaymentLog::STATUS_PROCESSED) {
                    return $lockedLog;
                }

                $clinic = Clinic::query()
                    ->where('mp_subscription_id', $subscriptionId)
                    ->lockForUpdate()
                    ->firstOrFail();

                $clinic->update(['subscription_status' => $subscriptionStatus]);
                $lockedLog->update(['status' => PaymentLog::STATUS_PROCESSED]);

                return $lockedLog->refresh();
            }, 3);
        } catch (Throwable $exception) {
            PaymentLog::query()
                ->where('event_id', $eventId)
                ->update(['status' => PaymentLog::STATUS_FAILED]);

            throw $exception;
        }
    }

    /**
     * @param  array<string, mixed>  $payload
     *
     * @throws JsonException
     */
    public function eventId(array $payload): string
    {
        return hash('sha256', json_encode(
            $this->canonicalize($payload),
            JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
        ));
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function validatePayload(array $payload): void
    {
        if (! is_string($payload['type'] ?? null)
            || blank($payload['type'])
            || ! is_scalar(data_get($payload, 'data.id'))
            || blank((string) data_get($payload, 'data.id'))) {
            throw new InvalidWebhookPayloadException('Payload de webhook do Mercado Pago inválido.');
        }
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function claimEvent(string $eventId, array $payload): PaymentLog
    {
        try {
            return DB::transaction(function () use ($eventId, $payload): PaymentLog {
                $paymentLog = PaymentLog::query()
                    ->where('event_id', $eventId)
                    ->lockForUpdate()
                    ->first();

                return $paymentLog ?? PaymentLog::query()->create([
                    'event_id' => $eventId,
                    'payload' => $payload,
                    'status' => PaymentLog::STATUS_PROCESSING,
                ]);
            }, 3);
        } catch (UniqueConstraintViolationException) {
            return PaymentLog::query()->where('event_id', $eventId)->firstOrFail();
        }
    }

    private function markProcessed(string $eventId): PaymentLog
    {
        return DB::transaction(function () use ($eventId): PaymentLog {
            $paymentLog = PaymentLog::query()
                ->where('event_id', $eventId)
                ->lockForUpdate()
                ->firstOrFail();

            $paymentLog->update(['status' => PaymentLog::STATUS_PROCESSED]);

            return $paymentLog->refresh();
        }, 3);
    }

    private function subscriptionStatus(string $mercadoPagoStatus): string
    {
        return match ($mercadoPagoStatus) {
            'authorized' => 'active',
            'pending', 'paused' => 'past_due',
            'canceled', 'cancelled' => 'canceled',
            default => throw new UnexpectedValueException(
                'Status de assinatura do Mercado Pago não reconhecido.',
            ),
        };
    }

    private function canonicalize(mixed $value): mixed
    {
        if (! is_array($value)) {
            return $value;
        }

        foreach ($value as $key => $item) {
            $value[$key] = $this->canonicalize($item);
        }

        if (! array_is_list($value)) {
            ksort($value);
        }

        return $value;
    }
}
