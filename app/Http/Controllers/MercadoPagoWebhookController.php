<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidWebhookPayloadException;
use App\Services\MercadoPagoWebhookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MercadoPagoWebhookController extends Controller
{
    public function __construct(private MercadoPagoWebhookService $webhookService) {}

    public function __invoke(Request $request): JsonResponse
    {
        $secret = config('services.mercado_pago.webhook_secret');

        if (! empty($secret) && ! $this->isValidSignature($request, (string) $secret)) {
            return response()->json([
                'error' => 'Assinatura de webhook inválida.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $payload = $request->all();

        // Ensure data.id structure if flattened by query parameters or form data
        if (! isset($payload['data']) && $request->has('data.id')) {
            $payload['data'] = ['id' => $request->input('data.id')];
        }

        try {
            $eventId = $this->webhookService->dispatch($payload);

            return response()->json([
                'status' => 'queued',
                'event_id' => $eventId,
            ], Response::HTTP_ACCEPTED);
        } catch (InvalidWebhookPayloadException $exception) {
            return response()->json([
                'error' => $exception->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    private function isValidSignature(Request $request, string $secret): bool
    {
        $signatureHeader = $request->header('x-signature');

        if (blank($signatureHeader)) {
            return false;
        }

        $parts = [];
        foreach (preg_split('/[,;]/', (string) $signatureHeader) ?: [] as $part) {
            $split = explode('=', trim($part), 2);
            if (count($split) === 2) {
                $parts[$split[0]] = $split[1];
            }
        }

        $ts = $parts['ts'] ?? null;
        $hash = $parts['v1'] ?? null;

        $rawContent = (string) $request->getContent();
        $fallbackContent = json_encode($request->all(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: '';

        if ($ts !== null && $hash !== null) {
            $dataId = (string) data_get($request->all(), 'data.id', $request->query('data.id', ''));
            $requestId = (string) $request->header('x-request-id');
            $manifest = "id:{$dataId};request-id:{$requestId};ts:{$ts};";
            $expected = hash_hmac('sha256', $manifest, $secret);

            if (hash_equals($expected, $hash)) {
                return true;
            }

            if (hash_equals(hash_hmac('sha256', $rawContent, $secret), $hash)
                || hash_equals(hash_hmac('sha256', $fallbackContent, $secret), $hash)) {
                return true;
            }
        }

        return hash_equals(hash_hmac('sha256', $rawContent, $secret), (string) $signatureHeader)
            || hash_equals(hash_hmac('sha256', $fallbackContent, $secret), (string) $signatureHeader);
    }
}
