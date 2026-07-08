<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Harvest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'crop_type',
        'harvest_date',
        'quantity',
        'unit',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'harvest_date' => 'date',
            'quantity' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
