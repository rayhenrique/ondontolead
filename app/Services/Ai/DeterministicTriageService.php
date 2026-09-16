<?php

namespace App\Services\Ai;

use App\Services\Ai\Data\TriageInput;
use App\Services\Ai\Data\TriageResult;

class DeterministicTriageService
{
    public function analyze(TriageInput $input): TriageResult
    {
        $urgencyLevel = $this->urgencyLevel($input);
        $signs = $input->reportedSigns();
        $signsSummary = $signs === [] ? 'nenhum sinal adicional' : implode(', ', $signs);
        $historySummary = $input->medicalHistory === null
            ? 'Histórico médico não informado.'
            : 'Histórico informado: '.$input->medicalHistory.'.';

        return new TriageResult(
            summary: sprintf(
                'Queixa: %s. Dor: %d/10. Sinais: %s. %s',
                $input->complaint,
                $input->painLevel,
                $signsSummary,
                $historySummary,
            ),
            urgencyLevel: $urgencyLevel,
            suggestedProcedure: match ($urgencyLevel) {
                'high' => 'Avaliação odontológica de urgência',
                'medium' => 'Avaliação odontológica prioritária',
                default => 'Avaliação odontológica',
            },
            processedByAi: false,
        );
    }

    private function urgencyLevel(TriageInput $input): string
    {
        if ($input->hasBreathingDifficulty
            || $input->hasBleeding
            || $input->hadTrauma
            || $input->painLevel >= 8
            || ($input->hasSwelling && $input->hasFever)) {
            return 'high';
        }

        if ($input->painLevel >= 4 || $input->hasSwelling || $input->hasFever) {
            return 'medium';
        }

        return 'low';
    }
}
