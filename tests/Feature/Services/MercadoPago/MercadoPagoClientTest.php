<?php

namespace Tests\Feature\Services\MercadoPago;

use App\Services\MercadoPago\MercadoPagoClient;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use LogicException;
use Tests\TestCase;

class MercadoPagoClientTest extends TestCase
{
    public function test_fetches_subscription_with_configured_access_token(): void
    {
        config()->set('services.mercado_pago.access_token', 'mp-access-token');
        Http::preventStrayRequests();
        Http::fake([
            'https://api.mercadopago.com/preapproval/subscription-123' => Http::response([
                'id' => 'subscription-123',
                'status' => 'authorized',
            ]),
        ]);

        $subscription = app(MercadoPagoClient::class)->subscription('subscription-123');

        $this->assertSame('subscription-123', $subscription['id']);
        $this->assertSame('authorized', $subscription['status']);
        Http::assertSent(fn (Request $request): bool => $request->hasHeader(
            'Authorization',
            'Bearer mp-access-token',
        ));
    }

    public function test_missing_access_token_prevents_outbound_request(): void
    {
        config()->set('services.mercado_pago.access_token');
        Http::preventStrayRequests();

        try {
            app(MercadoPagoClient::class)->subscription('subscription-123');
            $this->fail('O cliente deveria exigir o token de acesso.');
        } catch (LogicException $exception) {
            $this->assertSame(
                'O token de acesso do Mercado Pago não está configurado.',
                $exception->getMessage(),
            );
        }

        Http::assertNothingSent();
    }
}
