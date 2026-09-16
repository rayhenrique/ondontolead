<?php

namespace App\Services;

use App\Models\Clinic;
use App\Services\Ai\Contracts\TriageProvider;
use App\Services\Ai\Data\TriageInput;
use App\Services\Ai\Data\TriageResult;
use App\Services\Ai\DeterministicTriageService;
use App\Services\Ai\Providers\GeminiTriageProvider;
use App\Services\Ai\Providers\OpenAiTriageProvider;
use Illuminate\Support\Facades\Log;
use Throwable;

class AiTriageService
{
    public function __construct(
        private OpenAiTriageProvider $openAi,
        private GeminiTriageProvider $gemini,
        private DeterministicTriageService $fallback,
    ) {}

    public function analyze(Clinic $clinic, TriageInput $input): TriageResult
    {
        $apiKey = $clinic->ai_api_key;
        $provider = $this->providerFor($clinic->ai_provider);

        if ($provider === null || ! is_string($apiKey) || blank($apiKey)) {
            return $this->fallback->analyze($input);
        }

        try {
            return $provider->analyze($apiKey, $input);
        } catch (Throwable) {
            Log::warning('AI triage failed; deterministic fallback used.', [
                'clinic_id' => $clinic->getKey(),
                'provider' => $clinic->ai_provider,
            ]);

            return $this->fallback->analyze($input);
        }
    }

    private function providerFor(string $provider): ?TriageProvider
    {
        return match ($provider) {
            'openai' => $this->openAi,
            'gemini' => $this->gemini,
            default => null,
        };
    }
}
