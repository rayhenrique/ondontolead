<?php

namespace Tests\Feature\Services;

use App\Models\Clinic;
use App\Services\Ai\Data\TriageInput;
use App\Services\AiTriageService;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AiTriageServiceTest extends TestCase
{
    use LazilyRefreshDatabase;

    #[DataProvider('fallbackScenarios')]
    public function test_missing_api_key_uses_deterministic_fallback(
        int $painLevel,
        bool $hasSwelling,
        bool $hasBleeding,
        bool $hadTrauma,
        bool $hasFever,
        bool $hasBreathingDifficulty,
        string $expectedUrgency,
    ): void {
        Http::preventStrayRequests();
        $clinic = Clinic::factory()->create([
            'ai_provider' => 'openai',
            'ai_api_key' => null,
        ]);
        $input = new TriageInput(
            complaint: 'Dor no dente',
            painLevel: $painLevel,
            hasSwelling: $hasSwelling,
            hasBleeding: $hasBleeding,
            hadTrauma: $hadTrauma,
            hasFever: $hasFever,
            hasBreathingDifficulty: $hasBreathingDifficulty,
            medicalHistory: 'Hipertensão controlada',
        );

        $result = app(AiTriageService::class)->analyze($clinic, $input);

        $this->assertSame($expectedUrgency, $result->urgencyLevel);
        $this->assertFalse($result->processedByAi);
        $this->assertStringContainsString('Dor no dente', $result->summary);
        $this->assertSame('Dor no dente', $result->toTriageRecordAttributes($input)['raw_complaint']);
        Http::assertNothingSent();
    }

    public function test_configured_openai_provider_returns_structured_ai_result(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'https://api.openai.com/v1/responses' => Http::response([
                'output' => [[
                    'content' => [[
                        'type' => 'output_text',
                        'text' => json_encode([
                            'summary' => 'Dor intensa relatada pelo paciente.',
                            'urgency_level' => 'high',
                            'suggested_procedure' => 'Avaliação odontológica de urgência',
                        ], JSON_THROW_ON_ERROR),
                    ]],
                ]],
            ]),
        ]);
        $clinic = Clinic::factory()->withOpenAi('clinic-openai-key')->create();
        $input = new TriageInput('Dor intensa', 9);

        $result = app(AiTriageService::class)->analyze($clinic, $input);

        $this->assertSame('high', $result->urgencyLevel);
        $this->assertTrue($result->processedByAi);
        $this->assertSame('Dor intensa relatada pelo paciente.', $result->summary);
        Http::assertSentCount(1);
    }

    public function test_configured_gemini_provider_returns_structured_ai_result(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash:generateContent' => Http::response([
                'candidates' => [[
                    'content' => [
                        'parts' => [[
                            'text' => json_encode([
                                'summary' => 'Paciente relata sensibilidade leve.',
                                'urgency_level' => 'low',
                                'suggested_procedure' => 'Avaliação odontológica',
                            ], JSON_THROW_ON_ERROR),
                        ]],
                    ],
                ]],
            ]),
        ]);
        $clinic = Clinic::factory()->withGemini('clinic-gemini-key')->create();
        $input = new TriageInput('Sensibilidade ao frio', 2);

        $result = app(AiTriageService::class)->analyze($clinic, $input);

        $this->assertSame('low', $result->urgencyLevel);
        $this->assertTrue($result->processedByAi);
        $this->assertSame('Paciente relata sensibilidade leve.', $result->summary);
        Http::assertSentCount(1);
    }

    public function test_provider_failure_falls_back_without_exposing_api_key(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            'https://api.openai.com/v1/responses' => Http::response(['error' => 'invalid key'], 401),
        ]);
        $clinic = Clinic::factory()->withOpenAi('secret-clinic-key')->create();
        $input = new TriageInput('Sensibilidade leve', 2);

        $result = app(AiTriageService::class)->analyze($clinic, $input);

        $this->assertSame('low', $result->urgencyLevel);
        $this->assertFalse($result->processedByAi);
        $this->assertStringNotContainsString('secret-clinic-key', $result->summary);
        Http::assertSentCount(1);
    }

    public static function fallbackScenarios(): array
    {
        return [
            'low without warning signs' => [2, false, false, false, false, false, 'low'],
            'medium pain' => [5, false, false, false, false, false, 'medium'],
            'high pain' => [8, false, false, false, false, false, 'high'],
            'high breathing difficulty' => [1, false, false, false, false, true, 'high'],
            'high swelling with fever' => [3, true, false, false, true, false, 'high'],
        ];
    }
}
