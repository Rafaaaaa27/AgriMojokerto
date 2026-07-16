<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Equipment;
use Illuminate\Database\Seeder;

class MarketplaceSeeder extends Seeder
{
    public function run(): void
    {
        $sellerId = 3; // Toko Pertanian Berkah Jaya
        $farmerId = 2; // Bpk Imam Petani Abiez

        $products = [
            // Benih
            ['user_id' => $sellerId, 'category' => 'benih', 'name' => 'Benih Padi Ciherang Premium', 'price' => 75000, 'quantity' => 200, 'description' => 'Benih padi kualitas ekspor, tahan wereng dan produktivitas tinggi.'],
            ['user_id' => $sellerId, 'category' => 'benih', 'name' => 'Bibit Jagung Manis Hibrida', 'price' => 120000, 'quantity' => 150, 'description' => 'Bibit jagung hibrida F1, rasa manis dan tongkol besar.'],
            ['user_id' => $sellerId, 'category' => 'benih', 'name' => 'Benih Kedelai Anjasmoro', 'price' => 45000, 'quantity' => 180, 'description' => 'Benih kedelai varietas unggul, cocok untuk tempe dan tahu.'],
            ['user_id' => $sellerId, 'category' => 'benih', 'name' => 'Bibit Cabai Rawit Setan', 'price' => 35000, 'quantity' => 300, 'description' => 'Bibit cabai rawit pedas, produktivitas tinggi per pohon.'],
            ['user_id' => $sellerId, 'category' => 'benih', 'name' => 'Benih Kangkung Darjo', 'price' => 15000, 'quantity' => 500, 'description' => 'Benih kangkung darat, panen cepat 25 hari.'],
            ['user_id' => $sellerId, 'category' => 'benih', 'name' => 'Bibit Terung Ungu', 'price' => 25000, 'quantity' => 200, 'description' => 'Bibit terung ungu lokal, buah lebat dan tahan hama.'],
            ['user_id' => $sellerId, 'category' => 'benih', 'name' => 'Benih Bawang Merah Brebes', 'price' => 55000, 'quantity' => 100, 'description' => 'Benih bawang merah asli Brebes, umbi besar dan merah cerah.'],

            // Pupuk
            ['user_id' => $sellerId, 'category' => 'pupuk', 'name' => 'Pupuk Organik Cair Bio-Agri', 'price' => 45000, 'quantity' => 500, 'description' => 'Pupuk organik cair untuk mempercepat pertumbuhan akar dan daun.'],
            ['user_id' => $sellerId, 'category' => 'pupuk', 'name' => 'Pupuk NPK Mutiara 16-16-16', 'price' => 650000, 'quantity' => 50, 'description' => 'Pupuk NPK berkualitas untuk segala jenis tanaman buah dan sayur.'],
            ['user_id' => $sellerId, 'category' => 'pupuk', 'name' => 'Pupuk Urea Prill 46%', 'price' => 120000, 'quantity' => 80, 'description' => 'Pupuk urea nitrogen tinggi untuk pertumbuhan vegetatif optimal.'],
            ['user_id' => $sellerId, 'category' => 'pupuk', 'name' => 'Pupuk KCL 60%', 'price' => 180000, 'quantity' => 40, 'description' => 'Pupuk kalium untuk pembentukan buah dan ketahanan tanaman.'],
            ['user_id' => $sellerId, 'category' => 'pupuk', 'name' => 'Pupuk SP-36', 'price' => 140000, 'quantity' => 60, 'description' => 'Pupuk fosfat untuk merangsang pembentukan bunga dan akar.'],
            ['user_id' => $sellerId, 'category' => 'pupuk', 'name' => 'Pupuk Kandang Kambing Premium', 'price' => 35000, 'quantity' => 200, 'description' => 'Pupuk kandang kambing matang, kemasan 10kg.'],
            ['user_id' => $sellerId, 'category' => 'pupuk', 'name' => 'Pupuk Daun Cair Multiguna', 'price' => 55000, 'quantity' => 300, 'description' => 'Pupuk daun dengan kandungan mikro lengkap untuk semua tanaman.'],

            // Pestisida
            ['user_id' => $sellerId, 'category' => 'pestisida', 'name' => 'Insektisida Pembasmi Hama Padi', 'price' => 85000, 'quantity' => 120, 'description' => 'Efektif membasmi wereng, ulat grayak, dan penggerek batang.'],
            ['user_id' => $sellerId, 'category' => 'pestisida', 'name' => 'Fungisida Antracol 70WP', 'price' => 95000, 'quantity' => 90, 'description' => 'Mengatasi penyakit blas, busuk daun, dan karat pada tanaman.'],
            ['user_id' => $sellerId, 'category' => 'pestisida', 'name' => 'Herbisida Roundup 480SL', 'price' => 110000, 'quantity' => 60, 'description' => 'Herbisida sistemik untuk membasmi gulma di lahan pertanian.'],
            ['user_id' => $sellerId, 'category' => 'pestisida', 'name' => 'Moluskisida Pembasmi Keong Emas', 'price' => 65000, 'quantity' => 100, 'description' => 'Khusus untuk memberantas keong mas pada tanaman padi.'],
            ['user_id' => $sellerId, 'category' => 'pestisida', 'name' => 'Insektisida Nabati Daun Mimba', 'price' => 40000, 'quantity' => 150, 'description' => 'Pestisida organik dari ekstrak daun mimba, aman untuk lingkungan.'],

            // Alat Pertanian (kategori: panen / alat)
            ['user_id' => $sellerId, 'category' => 'alat', 'name' => 'Cangkul Baja Tempa Super', 'price' => 125000, 'quantity' => 30, 'description' => 'Cangkul baja kuat dan tajam, tahan lama untuk lahan kering.'],
            ['user_id' => $sellerId, 'category' => 'alat', 'name' => 'Sabit Bergigi Premium', 'price' => 55000, 'quantity' => 45, 'description' => 'Sabit bergigi tajam, ergonomis untuk memanen padi dan rumput.'],
            ['user_id' => $sellerId, 'category' => 'alat', 'name' => 'Gembor Plastik 10L', 'price' => 35000, 'quantity' => 60, 'description' => 'Gembor penyiram tanaman kapasitas 10 liter, kuat dan ringan.'],
            ['user_id' => $sellerId, 'category' => 'alat', 'name' => 'Sprayer Elektrik 20L', 'price' => 350000, 'quantity' => 20, 'description' => 'Sprayer elektrik isi ulang untuk penyemprotan pestisida dan pupuk.'],
            ['user_id' => $sellerId, 'category' => 'alat', 'name' => 'Arit Babat Rumput', 'price' => 45000, 'quantity' => 35, 'description' => 'Arit babat tradisional, cocok untuk membersihkan lahan dan rumput.'],
            ['user_id' => $sellerId, 'category' => 'alat', 'name' => 'Timbangan Gantung 50kg', 'price' => 85000, 'quantity' => 25, 'description' => 'Timbangan gantung mekanik untuk menimbang hasil panen.'],

            // Hasil Panen (dari petani)
            ['user_id' => $farmerId, 'category' => 'hasil_panen', 'name' => 'Beras Super', 'price' => 85000, 'quantity' => 100, 'description' => 'Beras kualitas premium, pulen dan wangi.'],
            ['user_id' => $farmerId, 'category' => 'hasil_panen', 'name' => 'Jagung Pipil Kering', 'price' => 55000, 'quantity' => 200, 'description' => 'Jagung pipil kering siap olah, kualitas grade A.'],
            ['user_id' => $farmerId, 'category' => 'hasil_panen', 'name' => 'Cabai Rawit Segar', 'price' => 40000, 'quantity' => 50, 'description' => 'Cabai rawit segar petik hari ini, pedas dan segar.'],
            ['user_id' => $farmerId, 'category' => 'hasil_panen', 'name' => 'Kangkung Organik', 'price' => 10000, 'quantity' => 80, 'description' => 'Kangkung organik tanpa pestisida, panen pagi hari.'],
            ['user_id' => $farmerId, 'category' => 'hasil_panen', 'name' => 'Tomat Sayur Segar', 'price' => 15000, 'quantity' => 60, 'description' => 'Tomat segar merah merata, cocok untuk dapur dan industri.'],
            ['user_id' => $farmerId, 'category' => 'hasil_panen', 'name' => 'Terung Ungu Segar', 'price' => 12000, 'quantity' => 70, 'description' => 'Terung ungu segar, kulit mengkilap, daging padat.'],
            ['user_id' => $farmerId, 'category' => 'hasil_panen', 'name' => 'Bawang Merah Kering', 'price' => 35000, 'quantity' => 40, 'description' => 'Bawang merah kering siap jual, ukuran sedang.'],
            ['user_id' => $farmerId, 'category' => 'hasil_panen', 'name' => 'Mentimun Hibrida', 'price' => 8000, 'quantity' => 100, 'description' => 'Mentimun segar, panjang rata-rata 20cm, kualitas ekspor.'],
            ['user_id' => $farmerId, 'category' => 'hasil_panen', 'name' => 'Bayam Cabut Segar', 'price' => 7000, 'quantity' => 90, 'description' => 'Bayam cabut segar, akar masih utuh, tanpa bahan kimia.'],
        ];

        foreach ($products as $p) {
            $existing = Product::where('name', $p['name'])->first();
            if ($existing) {
                $existing->update([
                    'user_id' => $p['user_id'],
                    'category' => $p['category'],
                    'price' => $p['price'],
                    'quantity' => $p['quantity'],
                    'description' => $p['description'],
                    'approval_status' => 'approved',
                    'is_active' => true,
                ]);
                $this->command->info("Updated: {$p['name']}");
            } else {
                Product::create([
                    'user_id' => $p['user_id'],
                    'category' => $p['category'],
                    'name' => $p['name'],
                    'price' => $p['price'],
                    'quantity' => $p['quantity'],
                    'description' => $p['description'],
                    'approval_status' => 'approved',
                    'is_active' => true,
                ]);
                $this->command->info("Created: {$p['name']}");
            }
        }

        // Equipment
        $equipments = [
            ['user_id' => $sellerId, 'type' => 'Traktor', 'name' => 'Hand Traktor Modern HT-500', 'price' => 350000, 'quantity' => 4, 'unit' => 'hari', 'location' => 'Mojoanyar, Mojokerto', 'description' => 'Traktor tangan 7 HP, cocok untuk sawah dan ladang kering.'],
            ['user_id' => $sellerId, 'type' => 'Traktor', 'name' => 'Traktor Roda Empat Mini', 'price' => 600000, 'quantity' => 2, 'unit' => 'hari', 'location' => 'Sooko, Mojokerto', 'description' => 'Traktor roda empat 25 HP, untuk lahan luas hingga 2 hektar.'],
            ['user_id' => $sellerId, 'type' => 'Drone', 'name' => 'Drone Penyemprot Pestisida', 'price' => 1200000, 'quantity' => 1, 'unit' => 'hari', 'location' => 'Sooko, Mojokerto', 'description' => 'Drone kapasitas 10L untuk penyemprotan presisi lahan luas.'],
            ['user_id' => $sellerId, 'type' => 'Drone', 'name' => 'Drone Pemetaan Lahan DJI', 'price' => 1500000, 'quantity' => 1, 'unit' => 'hari', 'location' => 'Mojokerto Kota', 'description' => 'Drone pemetaan untuk analisis lahan, pemupukan presisi.'],
            ['user_id' => $sellerId, 'type' => 'Thresher', 'name' => 'Mesin Perontok Padi Portable', 'price' => 200000, 'quantity' => 2, 'unit' => 'hari', 'location' => 'Jetis, Mojokerto', 'description' => 'Mesin perontok padi portabel, kapasitas 1 ton/jam.'],
            ['user_id' => $sellerId, 'type' => 'Thresher', 'name' => 'Mesin Perontok Jagung', 'price' => 250000, 'quantity' => 1, 'unit' => 'hari', 'location' => 'Jetis, Mojokerto', 'description' => 'Perontok jagung otomatis, hasil pipilan bersih.'],
            ['user_id' => $sellerId, 'type' => 'Pompa', 'name' => 'Pompa Air Celup 3\"', 'price' => 100000, 'quantity' => 3, 'unit' => 'hari', 'location' => 'Mojoanyar, Mojokerto', 'description' => 'Pompa air celup untuk irigasi sawah dan ladang.'],
            ['user_id' => $sellerId, 'type' => 'Pompa', 'name' => 'Pompa Air Diesel 5 PK', 'price' => 150000, 'quantity' => 2, 'unit' => 'hari', 'location' => 'Trowulan, Mojokerto', 'description' => 'Pompa air diesel 5 PK, untuk daerah tanpa listrik.'],
            ['user_id' => $sellerId, 'type' => 'Cultivator', 'name' => 'Cultivator Mini GX390', 'price' => 200000, 'quantity' => 2, 'unit' => 'hari', 'location' => 'Dlanggu, Mojokerto', 'description' => 'Cultivator mini untuk penggemburan tanah dan pembuatan bedengan.'],
            ['user_id' => $sellerId, 'type' => 'Cultivator', 'name' => 'Mesin Bajak Rotary', 'price' => 300000, 'quantity' => 2, 'unit' => 'hari', 'location' => 'Mojokerto', 'description' => 'Bajak rotary untuk pengolahan tanah kedua, hasil lebih halus.'],
            ['user_id' => $sellerId, 'type' => 'Sprayer', 'name' => 'Sprayer Elektrik 20L', 'price' => 50000, 'quantity' => 5, 'unit' => 'hari', 'location' => 'Kemlagi, Mojokerto', 'description' => 'Sprayer elektrik gendong 20 liter, untuk penyemprotan hama.'],
            ['user_id' => $sellerId, 'type' => 'Sprayer', 'name' => 'Mist Blower Semi-Profesional', 'price' => 100000, 'quantity' => 2, 'unit' => 'hari', 'location' => 'Mojokerto', 'description' => 'Mist blower untuk penyemprotan area luas, efektif untuk tanaman tinggi.'],
            ['user_id' => $farmerId, 'type' => 'Traktor', 'name' => 'Traktor', 'price' => 100000, 'quantity' => 2, 'unit' => 'hari', 'location' => 'Mojokerto', 'description' => 'Traktor untuk pengolahan lahan sawah dan tegal.'],
            ['user_id' => $farmerId, 'type' => 'Pompa', 'name' => 'Pompa Air Dasar', 'price' => 75000, 'quantity' => 1, 'unit' => 'hari', 'location' => 'Mojokerto', 'description' => 'Pompa air untuk irigasi sederhana.'],
            ['user_id' => $farmerId, 'type' => 'Thresher', 'name' => 'Perontok Padi Manual', 'price' => 50000, 'quantity' => 3, 'unit' => 'hari', 'location' => 'Mojokerto', 'description' => 'Alat perontok padi manual, cocok untuk lahan kecil.'],
        ];

        foreach ($equipments as $e) {
            $existing = Equipment::where('name', $e['name'])->first();
            if ($existing) {
                $existing->update([
                    'user_id' => $e['user_id'],
                    'type' => $e['type'],
                    'price' => $e['price'],
                    'quantity' => $e['quantity'],
                    'unit' => $e['unit'],
                    'location' => $e['location'],
                    'description' => $e['description'],
                    'approval_status' => 'approved',
                    'is_available' => true,
                ]);
                $this->command->info("Updated equipment: {$e['name']}");
            } else {
                Equipment::create([
                    'user_id' => $e['user_id'],
                    'type' => $e['type'],
                    'name' => $e['name'],
                    'price' => $e['price'],
                    'quantity' => $e['quantity'],
                    'unit' => $e['unit'],
                    'location' => $e['location'],
                    'description' => $e['description'] ?? '',
                    'approval_status' => 'approved',
                    'is_available' => true,
                ]);
                $this->command->info("Created equipment: {$e['name']}");
            }
        }

        $this->command->info("Marketplace seeding complete!");
    }
}
