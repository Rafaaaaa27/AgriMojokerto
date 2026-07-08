<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CropTemplate;

class CropTemplateSeeder extends Seeder
{
    public function run(): void
    {
        // ============ PADI (Rice) ============
        $padi = CropTemplate::create([
            'name' => 'Padi',
            'slug' => 'padi',
            'description' => 'Siklus pertanian padi sawah dari pengolahan tanah hingga panen. Durasi rata-rata 120-150 hari.',
            'duration_days' => 140,
            'icon' => 'seedling',
            'is_active' => true,
        ]);

        // ============ JAGUNG (Corn) ============
        CropTemplate::create([
            'name' => 'Jagung',
            'slug' => 'jagung',
            'description' => 'Siklus pertanian jagung dari pengolahan tanah hingga panen. Durasi rata-rata 100-120 hari.',
            'duration_days' => 110,
            'icon' => 'wheat-awn',
            'is_active' => true,
        ]);

        // ============ KEDELAI (Soybean) ============
        CropTemplate::create([
            'name' => 'Kedelai',
            'slug' => 'kedelai',
            'description' => 'Siklus pertanian kedelai dari pengolahan tanah hingga panen. Durasi rata-rata 80-100 hari.',
            'duration_days' => 90,
            'icon' => 'leaf',
            'is_active' => true,
        ]);

        // ============ CABE (Chili) ============
        CropTemplate::create([
            'name' => 'Cabe',
            'slug' => 'cabe',
            'description' => 'Siklus pertanian cabe dari penyemaian hingga panen raya. Durasi rata-rata 120-150 hari.',
            'duration_days' => 135,
            'icon' => 'pepper-hot',
            'is_active' => true,
        ]);

        // ============ KANGKUNG (Water Spinach) ============
        CropTemplate::create([
            'name' => 'Kangkung',
            'slug' => 'kangkung',
            'description' => 'Siklus pertanian kangkung dari pengolahan tanah hingga panen. Durasi rata-rata 30-40 hari.',
            'duration_days' => 35,
            'icon' => 'salad',
            'is_active' => true,
        ]);
    }
}
