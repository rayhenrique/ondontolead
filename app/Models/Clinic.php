<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clinic extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'plan_id',
        'name',
        'slug',
        'whatsapp_number',
        'ai_provider',
        'ai_api_key',
        'subscription_status',
        'trial_ends_at',
        'mp_subscription_id',
    ];

    protected $hidden = [
        'ai_api_key',
    ];

    protected function casts(): array
    {
        return [
            'ai_api_key' => 'encrypted',
            'trial_ends_at' => 'datetime',
        ];
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(ClinicSchedule::class);
    }

    public function blockedDates(): HasMany
    {
        return $this->hasMany(ClinicBlockedDate::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
