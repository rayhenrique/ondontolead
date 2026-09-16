<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    use HasFactory;

    public const STATUS_FAILED = 'failed';

    public const STATUS_PROCESSED = 'processed';

    public const STATUS_PROCESSING = 'processing';

    public const UPDATED_AT = null;

    protected $fillable = [
        'event_id',
        'payload',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
        ];
    }
}
