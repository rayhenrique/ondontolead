<?php

namespace App\Services\Ai\Providers;

use App\Services\Ai\Contracts\TriageProvider;
use App\Services\Ai\Data\TriageInput;
use App\Services\Ai\Data\TriageResult;
use App\Services\Ai\TriagePrompt;
use Illuminate\Support\Facades\Http;
use JsonException;
use UnexpectedValueException;

class OpenAiTriageProvider implements TriageProvider
{
    /**
     * @throws JsonException
     */
    public function analyze(string $apiKey, TriageInput $input): TriageResult
    {
        $response = Http::withToken($apiKey)
            ->acceptJson()
            ->connectTimeout((int) config('services.ai.connect_timeout'))
            ->timeout((int) config('services.ai.timeout'))
            ->post(rtrim((string) config('services.ai.openai.base_url'), '/').'/responses', [
                'model' => config('services.ai.openai.model'),
                'store' => false,
                'input' => [
                    [
                        'role' => 'developer',
                        'content' => TriagePrompt::instructions(),
                    ],
                    [
                        'role' => 'user',
                        'content' => json_encode(
                            $input->toArray(),
                            JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE,
                        ),
                    ],
                ],
                'text' => [
                    'format' => [
                        'type' => 'json_schema',
                        'name' => 'dental_triage',
                        'strict' => true,
                        'schema' => TriageResult::jsonSchema(),
                    ],
                ],
                'max_output_tokens' => 350,
            ])
            ->throw();

        $content = $this->extractOutputText($response->json('output', []));
        $payload = json_decode($content, true, flags: JSON_THROW_ON_ERROR);

        if (! is_array($payload)) {
            throw new UnexpectedValueException('A OpenAI retornou uma triagem inválida.');
        }

        return TriageResult::fromProviderPayload($payload);
    }

    private function extractOutputText(mixed $output): string
    {
        if (! is_array($output)) {
            throw new UnexpectedValueException('A OpenAI não retornou conteúdo de triagem.');
        }

        foreach ($output as $item) {
            foreach (($item['content'] ?? []) as $content) {
                if (($content['type'] ?? null) === 'output_text' && is_string($content['text'] ?? null)) {
                    return $content['text'];
                }
            }
        }

        throw new UnexpectedValueException('A OpenAI não retornou conteúdo de triagem.');
    }
}
