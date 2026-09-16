<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicBlockedDate extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'blocked_date',
        'reason',
    ];

    protected function casts(): array
    {
        return [
            'clinic_id' => 'integer',
            'blocked_date' => 'date',
        ];
    }
}
