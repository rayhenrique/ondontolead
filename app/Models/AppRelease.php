<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AppRelease extends Model
{
    use HasFactory;

    protected $fillable = [
        'version',
        'title',
        'content',
        'show_modal',
        'released_at',
    ];

    protected function casts(): array
    {
        return [
            'show_modal' => 'boolean',
            'released_at' => 'datetime',
        ];
    }

    public function userReleaseReads(): HasMany
    {
        return $this->hasMany(UserReleaseRead::class);
    }
}
