<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FarmingCycle extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'crop_template_id',
        'name',
        'start_date',
        'estimated_end_date',
        'actual_end_date',
        'location',
        'area_hectares',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'estimated_end_date' => 'date',
            'actual_end_date' => 'date',
            'area_hectares' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cropTemplate(): BelongsTo
    {
        return $this->belongsTo(CropTemplate::class);
    }

    public function stages(): HasMany
    {
        return $this->hasMany(ScheduleStage::class)->orderBy('order');
    }

    public function getProgressAttribute(): float
    {
        $total = $this->stages->count();
        if ($total === 0) return 0;
        $completed = $this->stages->where('status', 'completed')->count();
        return round(($completed / $total) * 100, 1);
    }

    public function getCurrentStageAttribute()
    {
        return $this->stages->where('status', '!=', 'completed')->first();
    }
}
