<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'max_appointments_per_month',
        'mp_plan_id',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'max_appointments_per_month' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function clinics(): HasMany
    {
        return $this->hasMany(Clinic::class);
    }
}
