<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $seller = User::where('email', 'penjual@example.com')->first();
        if (!$seller) return;

        $products = [];

        foreach ($products as $p) {
            Product::updateOrCreate(
                ['name' => $p['name']],
                [
                    'user_id' => $seller->id,
                    'category' => $p['category'],
                    'price' => $p['price'],
                    'quantity' => $p['quantity'],
                    'description' => $p['description'],
                    'approval_status' => 'approved',
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('Produk berhasil ditambahkan!');
    }
}
