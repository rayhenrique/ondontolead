<?php

namespace App\Services\Ai\Data;

use Illuminate\Support\Str;
use UnexpectedValueException;

final readonly class TriageResult
{
    private const URGENCY_LEVELS = ['low', 'medium', 'high'];

    public function __construct(
        public string $summary,
        public string $urgencyLevel,
        public string $suggestedProcedure,
        public bool $processedByAi,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromProviderPayload(array $payload): self
    {
        $summary = trim((string) ($payload['summary'] ?? ''));
        $urgencyLevel = (string) ($payload['urgency_level'] ?? '');
        $suggestedProcedure = trim((string) ($payload['suggested_procedure'] ?? ''));

        if ($summary === ''
            || ! in_array($urgencyLevel, self::URGENCY_LEVELS, true)
            || $suggestedProcedure === '') {
            throw new UnexpectedValueException('A resposta da IA não corresponde ao contrato de triagem.');
        }

        return new self(
            summary: Str::limit($summary, 2000, ''),
            urgencyLevel: $urgencyLevel,
            suggestedProcedure: Str::limit($suggestedProcedure, 150, ''),
            processedByAi: true,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toTriageRecordAttributes(TriageInput $input): array
    {
        return [
            'raw_complaint' => $input->complaint,
            'pain_level' => $input->painLevel,
            'urgency_level' => $this->urgencyLevel,
            'suggested_procedure' => $this->suggestedProcedure,
            'ai_summary' => $this->summary,
            'processed_by_ai' => $this->processedByAi,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function jsonSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'summary' => ['type' => 'string'],
                'urgency_level' => [
                    'type' => 'string',
                    'enum' => self::URGENCY_LEVELS,
                ],
                'suggested_procedure' => ['type' => 'string'],
            ],
            'required' => ['summary', 'urgency_level', 'suggested_procedure'],
            'additionalProperties' => false,
        ];
    }
}
