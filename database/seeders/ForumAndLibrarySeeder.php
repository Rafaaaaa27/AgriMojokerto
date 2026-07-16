<?php

namespace Database\Seeders;

use App\Models\ForumPost;
use App\Models\ForumComment;
use App\Models\EducationalInfo;
use Illuminate\Database\Seeder;

class ForumAndLibrarySeeder extends Seeder
{
    public function run(): void
    {
        $penyuluhId = 4; // Ibu Penyuluh Hebat
        $petaniId   = 2; // Bpk Imam Petani Abiez
        $penjualId  = 3; // Toko Pertanian Berkah Jaya

        // ==================== FORUM POSTS ====================
        $posts = [
            [
                'user_id' => $penyuluhId, 'title' => 'Pemberitahuan Pupuk Subsidi',
                'description' => 'Pemerintah akan menyalurkan pupuk subsidi untuk petani padi di Mojokerto. Segera daftar di Dinas Pertanian setempat dengan membawa KTP dan KK.',
                'category' => 'pengumuman',
            ],
            [
                'user_id' => $petaniId, 'title' => 'Cara Mengatasi Wereng Batang Coklat',
                'description' => 'Wereng batang coklat lagi menyerang sawah saya di daerah Mojoanyar. Ada yang punya pengalaman mengatasinya? Sudah pakai insektisida tapi belum mempan.',
                'category' => 'hama',
            ],
            [
                'user_id' => $penyuluhId, 'title' => 'Tips Menanam Padi Musim Hujan',
                'description' => 'Musim hujan sudah tiba. Berikut tips agar sawah Anda tetap produktif: atur drainase dengan baik, gunakan varietas tahan genangan seperti Ciherang atau Inpari, kurangi dosis pupuk nitrogen 20%, dan waspadai penyakit blast yang sering muncul di musim hujan.',
                'category' => 'budidaya',
            ],
            [
                'user_id' => $petaniId, 'title' => 'Hasil Panen Padi Menurun drastis',
                'description' => 'Selamat sore teman-teman petani. Saya baru panen padi di lahan 1 hektar dan cuma dapat 4,5 ton. Padahal biasanya bisa 6-7 ton. Ada yang tahu penyebabnya? Pemupukan sudah sesuai anjuran penyuluh.',
                'category' => 'budidaya',
            ],
            [
                'user_id' => $penjualId, 'title' => 'Info Harga Pupuk Terbaru',
                'description' => 'Harga pupuk di toko kami per 1 Juli 2026: Urea Rp120.000/sak, NPK 16-16-16 Rp650.000/sak, SP-36 Rp140.000/sak, Pupuk Organik Rp35.000/sak. Stok terbatas, silakan order.',
                'category' => 'pupuk',
            ],
            [
                'user_id' => $petaniId, 'title' => 'Bibit Jagung Manis Hibrida Tumbuh Kerdil',
                'description' => 'Bibit jagung manis umur 2 minggu tumbuhnya kerdil dan daun menguning. Apa kekurangan unsur hara atau ada hama? Mohon pencerahan dari penyuluh dan rekan-rekan.',
                'category' => 'hama',
            ],
            [
                'user_id' => $penyuluhId, 'title' => 'Panduan Pembuatan Pupuk Organik Cair',
                'description' => 'Pupuk organik cair (POC) bisa dibuat sendiri dari bahan-bahan sederhana seperti sisa sayuran, air cucian beras, dan molase. Berikut panduan lengkap cara pembuatannya beserta takaran yang tepat.',
                'category' => 'pupuk',
            ],
            [
                'user_id' => $petaniId, 'title' => 'Pengairan Sawah Saat Kemarau',
                'description' => 'Musim kemarau tahun ini cukup panjang. Ada rekomendasi sistem irigasi yang efisien? Saya punya lahan 2 hektar dan debit air sungai mulai menurun.',
                'category' => 'teknologi',
            ],
            [
                'user_id' => $penyuluhId, 'title' => 'Jadwal Penyuluhan Bulan Ini',
                'description' => 'Jadwal penyuluhan bulan Juli 2026: 12 Juli - Teknik Pengendalian Hama Terpadu (Balai Desa Mojoanyar), 19 Juli - Pembuatan Pupuk Organik (Balai Desa Sooko), 26 Juli - Manajemen Keuangan Usaha Tani (Aula Kecamatan).',
                'category' => 'pengumuman',
            ],
            [
                'user_id' => $petaniId, 'title' => 'Terima Kasih Atas Informasi Pupuk Subsidi',
                'description' => 'Terima kasih Bu Penyuluh atas informasi pupuk subsidinya. Saya sudah mendaftar dan tinggal menunggu distribusi. Semoga tepat waktu untuk musim tanam ini.',
                'category' => 'pengumuman',
            ],
            [
                'user_id' => $penyuluhId, 'title' => 'Ciri-Ciri Tanaman Kekurangan Unsur Hara',
                'description' => 'Daun menguning (kekurangan N), daun merah keunguan (kekurangan P), tepi daun menguning dan kering (kekurangan K). Ciri-ciri ini penting dikenali agar bisa segera ditangani.',
                'category' => 'budidaya',
            ],
            [
                'user_id' => $petaniId, 'title' => 'Pengalaman Menggunakan Drone untuk Semprot',
                'description' => 'Saya baru nyoba drone penyemprot pestisida. Sangat efisien! Untuk lahan 2 hektar cuma perlu 2 jam. Hasilnya juga lebih merata dibanding sprayer manual. Recommended!',
                'category' => 'teknologi',
            ],
            [
                'user_id' => $petaniId, 'title' => 'Cara Bertani Cabai di Musim Hujan',
                'description' => 'Teman-teman yang punya pengalaman bertani cabai di musim hujan, bagi tipsnya dong. Tanaman cabai saya banyak busuk karena kelebihan air.',
                'category' => 'budidaya',
            ],
            [
                'user_id' => $penyuluhId, 'title' => 'Hasil Penelitian: Varietas Padi Tahan Kekeringan',
                'description' => 'Balai Penelitian Tanaman Padi telah merilis varietas Inpago 12 yang tahan kekeringan. Cocok ditanam di lahan tadah hujan. Produktivitas mencapai 7 ton per hektar.',
                'category' => 'teknologi',
            ],
            [
                'user_id' => $penjualId, 'title' => 'Tersedia Alsintan Baru untuk Disewa',
                'description' => 'Toko kami kedatangan alat pertanian baru: Cultivator Mini GX390, Mesin Bajak Rotary, dan Pompa Air Diesel 5 PK. Harga sewa mulai Rp100.000/hari. Silakan hubungi kami untuk info lebih lanjut.',
                'category' => 'teknologi',
            ],
        ];

        foreach ($posts as $p) {
            $existing = ForumPost::where('title', $p['title'])->first();
            if (!$existing) {
                ForumPost::create([
                    'user_id' => $p['user_id'],
                    'title' => $p['title'],
                    'description' => $p['description'],
                    'category' => $p['category'],
                    'views' => rand(10, 200),
                    'is_pinned' => $p['category'] === 'pengumuman' && $p['user_id'] === $penyuluhId,
                ]);
                $this->command->info("Created forum post: {$p['title']}");
            }
        }

        // ==================== FORUM COMMENTS ====================
        $postMap = ForumPost::pluck('id', 'title')->toArray();

        $comments = [
            // Respon untuk "Cara Mengatasi Wereng Batang Coklat" (post id 2)
            ['post_title' => 'Cara Mengatasi Wereng Batang Coklat', 'user_id' => $penyuluhId,
             'content' => 'Untuk wereng batang coklat, coba gunakan insektisida berbahan aktif imidakloprid atau pimetrozin. Semprot pada pagi hari saat wereng masih aktif. Jangan lupa atur drainase dan kurangi dosis pupuk nitrogen agar tanaman tidak terlalu rimbun.'],
            ['post_title' => 'Cara Mengatasi Wereng Batang Coklat', 'user_id' => $petaniId,
             'content' => 'Terima kasih sarannya Bu. Saya coba pakai imidakloprid besok pagi. Apakah perlu dikombinasi dengan pestisida nabati?'],
            ['post_title' => 'Cara Mengatasi Wereng Batang Coklat', 'user_id' => $penyuluhId,
             'content' => 'Bisa dikombinasikan dengan pestisida nabati dari daun mimba atau serai wangi. Itu membantu mengurangi populasi secara alami. Tapi untuk serangan berat, tetap gunakan insektisida kimia sesuai dosis.'],

            // Respon untuk "Hasil Panen Padi Menurun drastis"
            ['post_title' => 'Hasil Panen Padi Menurun drastis', 'user_id' => $penyuluhId,
             'content' => 'Bapak Imam, penurunan hasil bisa disebabkan beberapa faktor: 1) Kualitas benih kurang baik, 2) Serangan hama/penyakit tidak terdeteksi awal, 3) Kesalahan pemupukan, 4) Kondisi cuaca ekstrem. Saya sarankan bapak membawa sampel tanah ke BPTP setempat untuk diuji kadar haranya.'],
            ['post_title' => 'Hasil Panen Padi Menurun drastis', 'user_id' => $petaniId,
             'content' => 'Baik Bu, saya akan ambil sampel tanah besok. Apakah ada rekomendasi varietas padi yang lebih cocok untuk musim seperti ini?'],
            ['post_title' => 'Hasil Panen Padi Menurun drastis', 'user_id' => $penyuluhId,
             'content' => 'Coba gunakan varietas Inpari 42 atau Ciherang. Keduanya cukup adaptif terhadap perubahan cuaca. Untuk musim seperti ini, tambahkan pupuk kalium 20% lebih banyak dari dosis biasa untuk memperkuat batang.'],

            // Respon untuk "Bibit Jagung Manis Hibrida Tumbuh Kerdil"
            ['post_title' => 'Bibit Jagung Manis Hibrida Tumbuh Kerdil', 'user_id' => $penyuluhId,
             'content' => 'Daun menguning pada jagung umur 2 minggu biasanya menandakan kekurangan nitrogen. Coba berikan pupuk Urea 50 kg/ha saat tanaman umur 2-3 minggu. Juga pastikan pH tanah antara 5,5-6,5.'],
            ['post_title' => 'Bibit Jagung Manis Hibrida Tumbuh Kerdil', 'user_id' => $petaniId,
             'content' => 'Sudah saya coba tambah Urea, sekarang mulai hijau kembali. Ternyata kurang nitrogen. Terima kasih penjelasannya.'],

            // Respon untuk "Cara Bertani Cabai di Musim Hujan"
            ['post_title' => 'Cara Bertani Cabai di Musim Hujan', 'user_id' => $penyuluhId,
             'content' => 'Busuk buah cabai di musim hujan umumnya disebabkan oleh penyakit antraknosa dan busuk phytophthora. Solusinya: 1) Buat bedengan tinggi minimal 40 cm, 2) Tutup bedengan dengan mulsa plastik, 3) Beri fungisida berbahan aktif mankozeb atau tembaga hidroksida, 4) Pangkas daun bagian bawah untuk sirkulasi udara.'],
            ['post_title' => 'Cara Bertani Cabai di Musim Hujan', 'user_id' => $petaniId,
             'content' => 'Wah lengkap sekali. Saya belum pakai mulsa plastik, mungkin itu penyebabnya. Besok saya pasang mulsa dulu.'],

            // Respon untuk "Pengairan Sawah Saat Kemarau"
            ['post_title' => 'Pengairan Sawah Saat Kemarau', 'user_id' => $penyuluhId,
             'content' => 'Untuk efisiensi air di musim kemarau, terapkan sistem irigasi berselang (intermittent irrigation). Genangi sawah 3-5 cm selama 3 hari, kemudian keringkan 2-3 hari. Ini bisa menghemat air hingga 30% dan merangsang pertumbuhan akar.'],
            ['post_title' => 'Pengairan Sawah Saat Kemarau', 'user_id' => $penjualId,
             'content' => 'Kami di toko ada pompa air celup 3" yang bisa disewa Rp100.000/hari. Kapasitasnya cukup untuk lahan 2 hektar.'],
        ];

        foreach ($comments as $c) {
            $postId = $postMap[$c['post_title']] ?? null;
            if (!$postId) continue;

            ForumComment::create([
                'forum_post_id' => $postId,
                'user_id' => $c['user_id'],
                'content' => $c['content'],
            ]);
            $this->command->info("Created comment on: {$c['post_title']}");
        }

        // ==================== EDUCATIONAL INFO ====================
        $infos = [
            [
                'user_id' => $penyuluhId, 'category' => 'panduan',
                'title' => 'Panduan Lengkap Pemupukan Padi',
                'content' => "Panduan Pemupukan Padi\n\nPemupukan merupakan salah satu faktor kunci dalam budidaya padi yang baik. Berikut panduan pemupukan padi yang tepat:\n\n1. Pemupukan Dasar (Saat Olah Tanah)\n   - Pupuk kandang: 5-10 ton/ha\n   - NPK 16-16-16: 200-250 kg/ha\n   - SP-36: 100 kg/ha\n\n2. Pemupukan Susulan I (14-21 HST)\n   - Urea: 100-150 kg/ha\n   - KCL: 75-100 kg/ha\n\n3. Pemupukan Susulan II (35-42 HST)\n   - Urea: 50-75 kg/ha\n   - NPK 16-16-16: 100-150 kg/ha\n\n4. Pemupukan Susulan III (50-60 HST)\n   - Urea: 50 kg/ha (jika diperlukan)\n   - KCL: 50 kg/ha (fase pembentukan malai)\n\nCatatan: Dosis pupuk dapat disesuaikan berdasarkan hasil uji tanah dan kondisi tanaman.",
            ],
            [
                'user_id' => $penyuluhId, 'category' => 'panduan',
                'title' => 'Cara Membuat Pupuk Organik Cair (POC)',
                'content' => "Cara Membuat Pupuk Organik Cair (POC)\n\nPupuk organik cair sangat bermanfaat untuk menyuburkan tanaman secara alami. Berikut cara membuatnya:\n\nBahan:\n- 10 kg sisa sayuran (kangkung, bayam, sawi)\n- 5 kg buah-buahan busuk\n- 2 kg gula merah atau molase\n- 1 liter air cucian beras\n- 10 liter air bersih\n- Ember tertutup\n\nCara Pembuatan:\n1. Cincang halus semua bahan organik\n2. Masukkan ke dalam ember\n3. Tambahkan gula merah/molase dan air cucian beras\n4. Tambahkan air bersih hingga semua bahan terendam\n5. Aduk rata dan tutup rapat\n6. Biarkan selama 10-14 hari\n7. Aduk setiap 2-3 hari sekali\n8. Setelah 14 hari, saring dan pisahkan cairan\n\nAplikasi:\n- Campur 200-300 ml POC dengan 10 liter air\n- Semprotkan ke tanaman setiap 7-10 hari sekali\n- Waktu terbaik pagi atau sore hari",
            ],
            [
                'user_id' => $penyuluhId, 'category' => 'hama',
                'title' => 'Pengendalian Hama Wereng Batang Coklat',
                'content' => "Pengendalian Hama Wereng Batang Coklat\n\nWereng batang coklat merupakan hama utama padi yang dapat menyebabkan penurunan hasil hingga gagal panen.\n\nPengendalian:\n\n1. Kultur Teknis\n   - Tanam varietas tahan wereng (Ciherang, Inpari 13, Mekongga)\n   - Pengaturan jarak tanam (25x25 cm)\n   - Pemupukan berimbang (jangan berlebihan Nitrogen)\n   - Sanitasi lahan dari gulma\n\n2. Pengendalian Hayati\n   - Memanfaatkan musuh alami: laba-laba, kumbang Coccinella\n   - Menggunakan agens hayati Beauveria bassiana\n   - Pestisida nabati dari daun mimba\n\n3. Pengendalian Kimiawi\n   - Insektisida berbahan aktif: Imidakloprid, Pimetrozin, BPMC\n   - Rotasi insektisida untuk mencegah resistensi\n   - Aplikasi pada pagi hari saat wereng aktif\n\nAmbang Ekonomi:\n- 5 ekor wereng/rumpun pada fase vegetatif\n- 10 ekor wereng/rumpun pada fase generatif",
            ],
            [
                'user_id' => $penyuluhId, 'category' => 'teknologi',
                'title' => 'Manfaat Drone untuk Pertanian Modern',
                'content' => "Manfaat Drone untuk Pertanian Modern\n\nTeknologi drone semakin populer di dunia pertanian. Berikut manfaatnya:\n\n1. Pemetaan Lahan\n   - Drone dapat memetakan lahan secara detail\n   - Mendeteksi area yang kekurangan air atau hara\n   - Membantu perencanaan tata tanam\n\n2. Penyemprotan Presisi\n   - Dapat menyemprot pestisida/pupuk secara akurat\n   - Hemat waktu: 2 hektar hanya 1-2 jam\n   - Hemat bahan: 30% lebih efisien dari sprayer manual\n   - Operator tidak terpapar bahan kimia\n\n3. Monitoring Tanaman\n   - Pantau pertumbuhan tanaman secara real-time\n   - Deteksi dini serangan hama dan penyakit\n   - Evaluasi hasil panen\n\nDi AgriMojokerto, drone pertanian bisa disewa dengan harga Rp1.200.000/hari termasuk operator.",
            ],
            [
                'user_id' => $penyuluhId, 'category' => 'budidaya',
                'title' => 'Panduan Lengkap Budidaya Cabai Rawit',
                'content' => "Panduan Lengkap Budidaya Cabai Rawit\n\nCabai rawit memiliki nilai ekonomi tinggi jika dibudidayakan dengan benar.\n\n1. Persiapan Lahan\n   - Olah tanah sedalam 25-30 cm\n   - Buat bedengan lebar 80-100 cm, tinggi 30-40 cm\n   - Taburkan pupuk kandang 10-15 ton/ha\n   - Pasang mulsa plastik hitam perak\n\n2. Penyemaian (30 hari)\n   - Rendam benih dalam air hangat 6 jam\n   - Semai di tray yang berisi media tanam\n   - Siram 2 kali sehari\n   - Bibit siap tanam umur 28-30 hari\n\n3. Penanaman\n   - Jarak tanam 50x60 cm\n   - Tanam pada sore hari\n   - Siram segera setelah tanam\n\n4. Pemupukan\n   - Pupuk dasar: NPK 15-15-15 200 kg/ha\n   - Susulan I (14 HST): Urea 100 kg/ha\n   - Susulan II (35 HST): KCL 100 kg/ha\n\n5. Panen\n   - Mulai panen umur 75-90 HST\n   - Panen setiap 3-5 hari sekali\n   - Produktivitas: 15-20 kg/tanaman\n\n6. Pengendalian Hama\n   - Kutu daun: insektisida imidakloprid\n   - Ulat: insektisida klorantraniliprol\n   - Penyakit busuk buah: fungisida mankozeb",
            ],
            [
                'user_id' => $penyuluhId, 'category' => 'budidaya',
                'title' => 'Teknik Irigasi Berselang untuk Hemat Air',
                'content' => "Teknik Irigasi Berselang untuk Hemat Air\n\nIrigasi berselang adalah teknik pengairan yang dilakukan secara periodik, tidak terus-menerus. Teknik ini sangat cocok untuk daerah yang mengalami kelangkaan air.\n\nManfaat:\n- Hemat air hingga 30-40%\n- Merangsang pertumbuhan akar lebih dalam\n- Mengurangi produksi metana (ramah lingkungan)\n- Mengurangi serangan hama keong mas\n- Memudahkan pemupukan dan penyiangan\n\nCara Penerapan:\n1. Genangi sawah setinggi 3-5 cm selama 3 hari\n2. Keringkan selama 2-3 hari (tanah mulai retak halus)\n3. Genangi kembali 3-5 cm\n4. Ulangi siklus ini hingga fase bunting\n5. Saat fase pengisian bulir, pertahankan genangan 3-5 cm terus-menerus\n6. Keringkan sawah 10-14 hari sebelum panen\n\nCatatan: Irigasi berselang tidak dianjurkan pada tanah berpasir yang mudah kehilangan air.",
            ],
            [
                'user_id' => $penyuluhId, 'category' => 'pengumuman',
                'title' => 'Program Bantuan Alsintan 2026',
                'content' => "Program Bantuan Alsintan 2026\n\nPemerintah Kabupaten Mojokerto melalui Dinas Pertanian membuka program bantuan alat dan mesin pertanian (Alsintan) tahun 2026.\n\nAlat yang Dibantu:\n1. Traktor Roda Dua (hand traktor): 50 unit\n2. Mesin Perontok Padi (thresher): 30 unit\n3. Pompa Air: 100 unit\n4. Cultivator Mini: 20 unit\n5. Sprayer Elektrik: 200 unit\n\nPersyaratan:\n1. Terdaftar di sistem elektronik RDKK\n2. Memiliki lahan minimal 0,5 hektar\n3. Bergabung dengan kelompok tani (Gapoktan)\n4. Tidak pernah menerima bantuan alsintan 3 tahun terakhir\n\nCara Pendaftaran:\n- Datang ke Balai Desa setempat\n- Membawa KTP dan KK\n- Surat keterangan dari kelompok tani\nPendaftaran dibuka hingga 31 Agustus 2026.",
            ],
            [
                'user_id' => $penyuluhId, 'category' => 'panduan',
                'title' => 'Panduan Lengkap Budidaya Jagung Manis',
                'content' => "Panduan Lengkap Budidaya Jagung Manis\n\n1. Pengolahan Tanah\n   - Bajak lahan sedalam 20-25 cm\n   - Buat bedengan lebar 60-80 cm\n   - Tabur pupuk kandang 10 ton/ha\n\n2. Penanaman\n   - Jarak tanam 75x25 cm (2 benih/lubang)\n   - Kedalaman 2-3 cm\n   - Herbisida pra-tanam jika perlu\n\n3. Pemupukan\n   - Dasar: NPK 15-15-15 200 kg/ha\n   - Susulan I (21 HST): Urea 100 kg/ha\n   - Susulan II (42 HST): Urea 50 kg/ha\n\n4. Pemeliharaan\n   - Penyiangan gulma 2-3 kali\n   - Pembumbunan tanah\n   - Pengendalian hama ulat tongkol\n\n5. Panen\n   - Umur 65-75 HST (tergantung varietas)\n   - Tongkol rambut kering kecoklatan\n   - Kadar air biji 20-25%\n\nProduktivitas: 12-15 ton/ha untuk varietas unggul.",
            ],
            [
                'user_id' => $penyuluhId, 'category' => 'teknologi',
                'title' => 'Penggunaan Aplikasi AgriMojokerto untuk Petani',
                'content' => "Penggunaan Aplikasi AgriMojokerto\n\nAgriMojokerto hadir untuk membantu petani Mojokerto dalam mengelola usaha tani secara digital.\n\nFitur Utama:\n\n1. Manajemen Panen\n   - Catat hasil panen harian\n   - Pantau produktivitas lahan\n   - Laporan otomatis\n\n2. Jadwal Tani\n   - Jadwal tanam terintegrasi dengan kalender\n   - Panduan perawatan per komoditas\n   - Pengingat pemupukan dan panen\n\n3. Marketplace\n   - Jual hasil panen langsung ke pembeli\n   - Sewa alat pertanian\n   - Harga transparan\n\n4. Forum\n   - Tanya jawab dengan penyuluh\n   - Diskusi antar petani\n   - Informasi terbaru\n\n5. E-Library\n   - Panduan budidaya lengkap\n   - Informasi hama dan penyakit\n   - Artikel dari penyuluh ahli\n\nAyo segera daftar dan manfaatkan fitur-fitur ini untuk pertanian yang lebih modern!",
            ],
            [
                'user_id' => $penyuluhId, 'category' => 'hama',
                'title' => 'Penyakit Blas pada Padi: Gejala dan Pengendalian',
                'content' => "Penyakit Blas pada Padi\n\nPenyakit blas disebabkan oleh jamur Pyricularia oryzae. Penyakit ini sangat merugikan dan bisa menyebabkan gagal panen.\n\nGejala:\n1. Blas Daun\n   - Bercak berbentuk belah ketupat\n   - Bagian tengah abu-abu, tepi coklat\n   - Daun mengering dan mati\n\n2. Blas Leher (Neck Blast)\n   - Leher malai membusuk dan patah\n   - Malai mengering dan berwarna abu-abu\n   - Gabah hampa/tidak berisi\n\n3. Blas Gabah\n   - Bercak pada gabah\n   - Gabah berwarna coklat kehitaman\n\nPengendalian:\n1. Tanam varietas tahan blas\n2. Pemupukan N berimbang (jangan berlebih)\n3. Pengaturan jarak tanam tidak terlalu rapat\n4. Gunakan fungisida trisiklazol atau karbendazim\n5. Semprot pada gejala awal setiap 7-10 hari\n\nKondisi yang Mendukung:\n- Kelembaban tinggi >90%\n- Suhu 24-28°C\n- Tanaman terlalu rimbun\n- Pemupukan N berlebihan",
            ],
        ];

        foreach ($infos as $info) {
            $existing = EducationalInfo::where('title', $info['title'])->first();
            if (!$existing) {
                EducationalInfo::create([
                    'user_id' => $info['user_id'],
                    'category' => $info['category'],
                    'title' => $info['title'],
                    'content' => $info['content'],
                    'views' => rand(20, 500),
                ]);
                $this->command->info("Created educational info: {$info['title']}");
            }
        }

        $this->command->info("Forum & Library seeding complete!");
    }
}
