<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScheduleStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'farming_cycle_id',
        'name',
        'order',
        'start_date',
        'end_date',
        'duration_days',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'order' => 'integer',
            'duration_days' => 'integer',
        ];
    }

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(FarmingCycle::class, 'farming_cycle_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ScheduleItem::class, 'schedule_stage_id')->orderBy('date');
    }

    public function getProgressAttribute(): float
    {
        $total = $this->items->count();
        if ($total === 0) return 0;
        $completed = $this->items->whereIn('status', ['completed', 'skipped'])->count();
        return round(($completed / $total) * 100, 1);
    }
}
