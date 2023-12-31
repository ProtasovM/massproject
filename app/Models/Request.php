<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Request extends Model
{
    use HasFactory;

    public const ACTIVE_STATUS = 0;
    public const RESOLVED_STATUS = 1;

    public const STATUS_HUMAN_NAMES = [
        self::ACTIVE_STATUS => 'active',
        self::RESOLVED_STATUS => 'resolve',
    ];

    public const STATUSES = [
        self::ACTIVE_STATUS,
        self::RESOLVED_STATUS,
    ];

    protected $casts = [
        'answered_at' => 'timestamp',
    ];

    protected $guarded = ['id'];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function respondent(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
