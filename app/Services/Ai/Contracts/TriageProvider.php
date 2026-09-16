<?php

namespace App\Services\Ai\Contracts;

use App\Services\Ai\Data\TriageInput;
use App\Services\Ai\Data\TriageResult;

interface TriageProvider
{
    public function analyze(string $apiKey, TriageInput $input): TriageResult;
}
