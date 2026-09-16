<?php

namespace App\Services\MercadoPago;

use Illuminate\Support\Facades\Http;
use LogicException;
use UnexpectedValueException;

class MercadoPagoClient
{
    /**
     * @return array<string, mixed>
     */
    public function subscription(string $subscriptionId): array
    {
        $accessToken = config('services.mercado_pago.access_token');

        if (! is_string($accessToken) || blank($accessToken)) {
            throw new LogicException('O token de acesso do Mercado Pago não está configurado.');
        }

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->connectTimeout((int) config('services.mercado_pago.connect_timeout'))
            ->timeout((int) config('services.mercado_pago.timeout'))
            ->get(
                rtrim((string) config('services.mercado_pago.base_url'), '/')
                .'/preapproval/'.rawurlencode($subscriptionId),
            )
            ->throw();

        $subscription = $response->json();

        if (! is_array($subscription)
            || ! is_string($subscription['id'] ?? null)
            || ! is_string($subscription['status'] ?? null)) {
            throw new UnexpectedValueException('O Mercado Pago retornou uma assinatura inválida.');
        }

        return $subscription;
    }
}
