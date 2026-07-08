<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketPrice extends Model
{
    protected $fillable = ['commodity', 'price', 'date', 'source'];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }
}
