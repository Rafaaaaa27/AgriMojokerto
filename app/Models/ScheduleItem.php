<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_stage_id',
        'user_id',
        'activity',
        'date',
        'time',
        'status',
        'notes',
        'recommendations',
        'products',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(ScheduleStage::class, 'schedule_stage_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
