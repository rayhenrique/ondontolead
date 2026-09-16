<?php

namespace App\Services\Ai\Providers;

use App\Services\Ai\Contracts\TriageProvider;
use App\Services\Ai\Data\TriageInput;
use App\Services\Ai\Data\TriageResult;
use App\Services\Ai\TriagePrompt;
use Illuminate\Support\Facades\Http;
use JsonException;
use UnexpectedValueException;

class GeminiTriageProvider implements TriageProvider
{
    /**
     * @throws JsonException
     */
    public function analyze(string $apiKey, TriageInput $input): TriageResult
    {
        $baseUrl = rtrim((string) config('services.ai.gemini.base_url'), '/');
        $model = rawurlencode((string) config('services.ai.gemini.model'));

        $response = Http::withHeaders([
            'x-goog-api-key' => $apiKey,
            'x-goog-api-client' => 'kltecnologia-odontolead/0.4',
        ])
            ->acceptJson()
            ->connectTimeout((int) config('services.ai.connect_timeout'))
            ->timeout((int) config('services.ai.timeout'))
            ->post($baseUrl.'/models/'.$model.':generateContent', [
                'systemInstruction' => [
                    'parts' => [['text' => TriagePrompt::instructions()]],
                ],
                'contents' => [[
                    'role' => 'user',
                    'parts' => [[
                        'text' => json_encode(
                            $input->toArray(),
                            JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE,
                        ),
                    ]],
                ]],
                'generationConfig' => [
                    'responseMimeType' => 'application/json',
                    'responseJsonSchema' => TriageResult::jsonSchema(),
                ],
            ])
            ->throw();

        $content = $response->json('candidates.0.content.parts.0.text');

        if (! is_string($content)) {
            throw new UnexpectedValueException('O Gemini não retornou conteúdo de triagem.');
        }

        $payload = json_decode($content, true, flags: JSON_THROW_ON_ERROR);

        if (! is_array($payload)) {
            throw new UnexpectedValueException('O Gemini retornou uma triagem inválida.');
        }

        return TriageResult::fromProviderPayload($payload);
    }
}
