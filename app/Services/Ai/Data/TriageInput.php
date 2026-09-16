<?php

namespace App\Services\Ai\Data;

use InvalidArgumentException;

final readonly class TriageInput
{
    public string $complaint;

    public ?string $medicalHistory;

    public function __construct(
        string $complaint,
        public int $painLevel,
        public bool $hasSwelling = false,
        public bool $hasBleeding = false,
        public bool $hadTrauma = false,
        public bool $hasFever = false,
        public bool $hasBreathingDifficulty = false,
        ?string $medicalHistory = null,
    ) {
        $this->complaint = trim($complaint);
        $this->medicalHistory = filled($medicalHistory) ? trim($medicalHistory) : null;

        if ($this->complaint === '') {
            throw new InvalidArgumentException('A queixa principal é obrigatória.');
        }

        if ($this->painLevel < 0 || $this->painLevel > 10) {
            throw new InvalidArgumentException('O nível de dor deve estar entre 0 e 10.');
        }
    }

    /**
     * @return array<string, bool|int|string|null>
     */
    public function toArray(): array
    {
        return [
            'complaint' => $this->complaint,
            'pain_level' => $this->painLevel,
            'has_swelling' => $this->hasSwelling,
            'has_bleeding' => $this->hasBleeding,
            'had_trauma' => $this->hadTrauma,
            'has_fever' => $this->hasFever,
            'has_breathing_difficulty' => $this->hasBreathingDifficulty,
            'medical_history' => $this->medicalHistory,
        ];
    }

    /**
     * @return list<string>
     */
    public function reportedSigns(): array
    {
        return array_keys(array_filter([
            'inchaço' => $this->hasSwelling,
            'sangramento' => $this->hasBleeding,
            'trauma' => $this->hadTrauma,
            'febre' => $this->hasFever,
            'dificuldade respiratória' => $this->hasBreathingDifficulty,
        ]));
    }
}
