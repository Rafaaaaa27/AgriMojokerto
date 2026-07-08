<?php

namespace Database\Seeders;

use App\Models\MarketPrice;
use Illuminate\Database\Seeder;

class MarketPriceSeeder extends Seeder
{
    public function run(): void
    {
        $commodities = [
            'padi' => ['base' => 5200, 'amplitude' => 400, 'noise' => 80],
        ];

        for ($i = 60; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();

            foreach ($commodities as $key => $cfg) {
                MarketPrice::create([
                    'commodity' => $key,
                    'price' => $cfg['base'] + (int)(sin($i * 0.12) * $cfg['amplitude']) + rand(-$cfg['noise'], $cfg['noise']),
                    'date' => $date,
                    'source' => 'Dinas Pertanian Mojokerto',
                ]);
            }
        }
    }
}
