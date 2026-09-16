<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TriageRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'raw_complaint',
        'pain_level',
        'urgency_level',
        'suggested_procedure',
        'ai_summary',
        'processed_by_ai',
    ];

    protected function casts(): array
    {
        return [
            'appointment_id' => 'integer',
            'pain_level' => 'integer',
            'processed_by_ai' => 'boolean',
        ];
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }
}
