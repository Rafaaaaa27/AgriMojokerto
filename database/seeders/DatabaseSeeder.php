<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Equipment;
use App\Models\Order;
use App\Models\Booking;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@agrimojokerto.id'],
            [
                'name' => 'Admin AgriMojokerto',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // Petani
        $petani = User::updateOrCreate(
            ['email' => 'petani@example.com'],
            [
                'name' => 'Bapak Tani Utama',
                'password' => Hash::make('password'),
                'role' => 'petani',
                'is_active' => true,
                'city' => 'Mojokerto',
            ]
        );

        // Penjual
        $penjual = User::updateOrCreate(
            ['email' => 'penjual@example.com'],
            [
                'name' => 'Toko Pertanian Berkah Jaya',
                'password' => Hash::make('password'),
                'role' => 'penjual',
                'is_active' => true,
            ]
        );

        // Penyuluh
        $penyuluh = User::updateOrCreate(
            ['email' => 'penyuluh@example.com'],
            [
                'name' => 'Ibu Penyuluh Hebat',
                'password' => Hash::make('password'),
                'role' => 'penyuluh',
                'is_active' => true,
            ]
        );

        // Clear existing related data to avoid duplicates if re-seeding
        DB::table('orders')->delete();
        DB::table('bookings')->delete();
        DB::table('products')->delete();
        DB::table('equipments')->delete();

        // Sample Products - SEEDS
        Product::create([
            'user_id' => $penjual->id,
            'name' => 'Benih Padi Ciherang Premium',
            'category' => 'benih',
            'price' => 75000,
            'quantity' => 200,
            'description' => 'Benih padi kualitas ekspor, tahan wereng dan produktivitas tinggi.',
            'image_path' => 'assets/img/products/rice_seeds.png',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        Product::create([
            'user_id' => $penjual->id,
            'name' => 'Bibit Jagung Manis Hibrida',
            'category' => 'benih',
            'price' => 120000,
            'quantity' => 150,
            'description' => 'Bibit jagung hibrida F1, rasa manis dan tongkol besar.',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        // Sample Products - FERTILIZERS
        Product::create([
            'user_id' => $penjual->id,
            'name' => 'Pupuk Organik Cair Bio-Agri',
            'category' => 'pupuk',
            'price' => 45000,
            'quantity' => 500,
            'description' => 'Pupuk organik cair untuk mempercepat pertumbuhan akar dan daun.',
            'image_path' => 'assets/img/products/fertilizer.png',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        Product::create([
            'user_id' => $penjual->id,
            'name' => 'Pupuk NPK Mutiara 16-16-16',
            'category' => 'pupuk',
            'price' => 650000,
            'quantity' => 50,
            'description' => 'Pupuk NPK berkualitas untuk segala jenis tanaman buah dan sayur.',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        // Sample Products - PESTICIDES
        Product::create([
            'user_id' => $penjual->id,
            'name' => 'Insektisida Pembasmi Hama Padi',
            'category' => 'pestisida',
            'price' => 85000,
            'quantity' => 120,
            'description' => 'Efektif membasmi wereng, ulat grayak, dan penggerek batang.',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        // Sample Products - TOOLS
        Product::create([
            'user_id' => $penjual->id,
            'name' => 'Cangkul Baja Tempa Super',
            'category' => 'panen',
            'price' => 125000,
            'quantity' => 30,
            'description' => 'Cangkul baja kuat dan tajam, tahan lama untuk lahan kering.',
            'approval_status' => 'approved',
            'is_active' => true,
        ]);

        // Sample Equipment
        Equipment::create([
            'user_id' => $penjual->id,
            'name' => 'Hand Trakor Modern HT-500',
            'type' => 'Traktor',
            'price' => 350000,
            'unit' => 'hari',
            'quantity' => 4,
            'location' => 'Mojoanyar, Mojokerto',
            'image_path' => 'assets/img/products/tiller.png',
            'approval_status' => 'approved',
            'is_available' => true,
        ]);

        Equipment::create([
            'user_id' => $penjual->id,
            'name' => 'Drone Penyemprot Pestisida',
            'type' => 'Drone',
            'price' => 1200000,
            'unit' => 'hari',
            'quantity' => 1,
            'location' => 'Sooko, Mojokerto',
            'description' => 'Drone kapasitas 10L untuk penyemprotan presisi lahan luas.',
            'approval_status' => 'approved',
            'is_available' => true,
        ]);

        Equipment::create([
            'user_id' => $penjual->id,
            'name' => 'Mesin Perontok Padi Portable',
            'type' => 'Thresher',
            'price' => 200000,
            'unit' => 'hari',
            'quantity' => 2,
            'location' => 'Jetis, Mojokerto',
            'approval_status' => 'approved',
            'is_available' => true,
        ]);

        // Sample Order for history
        $p1 = Product::first();
        Order::create([
            'product_id' => $p1->id,
            'user_id' => $petani->id,
            'seller_id' => $penjual->id,
            'quantity' => 2,
            'total_price' => $p1->price * 2,
            'status' => 'completed',
            'shipping_address' => 'Dusun Krajan, Mojokerto',
        ]);

        // Sample Booking for history
        $e1 = Equipment::first();
        Booking::create([
            'equipment_id' => $e1->id,
            'user_id' => $petani->id,
            'seller_id' => $penjual->id,
            'quantity' => 3,
            'total_price' => $e1->price * 3,
            'status' => 'pending',
            'booking_date' => date('Y-m-d', strtotime('+2 days')),
        ]);

        $this->call([
            MarketPriceSeeder::class,
            ProductSeeder::class,
            CropTemplateSeeder::class,
        ]);
    }
}
