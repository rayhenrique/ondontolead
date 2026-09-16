<?php

namespace Tests\Feature\Services\Ai\Providers;

use App\Services\Ai\Data\TriageInput;
use App\Services\Ai\Providers\OpenAiTriageProvider;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OpenAiTriageProviderTest extends TestCase
{
    public function test_sends_byok_key_and_parses_structured_response(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'https://api.openai.com/v1/responses' => Http::response([
                'output' => [[
                    'type' => 'message',
                    'content' => [[
                        'type' => 'output_text',
                        'text' => json_encode([
                            'summary' => 'Paciente relata dor moderada.',
                            'urgency_level' => 'medium',
                            'suggested_procedure' => 'Avaliação odontológica prioritária',
                        ], JSON_THROW_ON_ERROR),
                    ]],
                ]],
            ]),
        ]);
        $input = new TriageInput('Dor ao mastigar', 6);

        $result = app(OpenAiTriageProvider::class)->analyze('tenant-api-key', $input);

        $this->assertSame('medium', $result->urgencyLevel);
        $this->assertTrue($result->processedByAi);
        Http::assertSent(function (Request $request): bool {
            return $request->url() === 'https://api.openai.com/v1/responses'
                && $request->hasHeader('Authorization', 'Bearer tenant-api-key')
                && data_get($request->data(), 'store') === false
                && data_get($request->data(), 'text.format.type') === 'json_schema'
                && data_get($request->data(), 'text.format.strict') === true;
        });
    }

    public function test_invalid_provider_payload_is_rejected(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'https://api.openai.com/v1/responses' => Http::response([
                'output' => [[
                    'content' => [[
                        'type' => 'output_text',
                        'text' => '{"urgency_level":"critical"}',
                    ]],
                ]],
            ]),
        ]);

        $this->expectException(\UnexpectedValueException::class);

        app(OpenAiTriageProvider::class)->analyze(
            'tenant-api-key',
            new TriageInput('Dor ao mastigar', 6),
        );
    }
}
