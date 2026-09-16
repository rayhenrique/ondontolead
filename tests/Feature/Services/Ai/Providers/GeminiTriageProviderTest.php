<?php

namespace Tests\Feature\Services\Ai\Providers;

use App\Services\Ai\Data\TriageInput;
use App\Services\Ai\Providers\GeminiTriageProvider;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GeminiTriageProviderTest extends TestCase
{
    public function test_sends_byok_key_in_header_and_parses_structured_response(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash:generateContent' => Http::response([
                'candidates' => [[
                    'content' => [
                        'parts' => [[
                            'text' => json_encode([
                                'summary' => 'Paciente relata desconforto leve.',
                                'urgency_level' => 'low',
                                'suggested_procedure' => 'Avaliação odontológica',
                            ], JSON_THROW_ON_ERROR),
                        ]],
                    ],
                ]],
            ]),
        ]);
        $input = new TriageInput('Sensibilidade ao frio', 2);

        $result = app(GeminiTriageProvider::class)->analyze('tenant-gemini-key', $input);

        $this->assertSame('low', $result->urgencyLevel);
        $this->assertTrue($result->processedByAi);
        Http::assertSent(function (Request $request): bool {
            return $request->url() === 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash:generateContent'
                && $request->hasHeader('x-goog-api-key', 'tenant-gemini-key')
                && ! str_contains($request->url(), 'tenant-gemini-key')
                && is_string(data_get($request->data(), 'systemInstruction.parts.0.text'))
                && data_get($request->data(), 'generationConfig.responseMimeType') === 'application/json'
                && data_get($request->data(), 'generationConfig.responseJsonSchema.additionalProperties') === false;
        });
    }

    public function test_missing_response_content_is_rejected(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash:generateContent' => Http::response([
                'candidates' => [],
            ]),
        ]);

        $this->expectException(\UnexpectedValueException::class);

        app(GeminiTriageProvider::class)->analyze(
            'tenant-gemini-key',
            new TriageInput('Sensibilidade ao frio', 2),
        );
    }
}
