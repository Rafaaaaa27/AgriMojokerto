@extends('layouts.native')

@section('content')
<!-- HERO SECTION -->
<section class="hero" style="min-height: 100vh; display: flex; align-items: center; background: linear-gradient(135deg, rgba(0,0,0,0.7), rgba(0,0,0,0.5), rgba(0,0,0,0.35)), url('{{ asset('hero_farming_mojokerto_1781576055255.png') }}'); background-size: cover; background-position: center; position: relative; overflow: hidden;">
    <div style="position: absolute; inset: 0; background: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.03\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E'); pointer-events: none;"></div>
    <div class="container animate-fade-up" style="position: relative; z-index: 1;">
        <div style="max-width: 850px;">
            <h1 class="hero-title" style="font-size: 4.5rem; line-height: 1; font-weight: 900; color: white; margin-bottom: 2rem; text-shadow: 0 10px 30px rgba(0,0,0,0.3);">
                Masa Depan Pertanian <br><span style="color: white;">Kini Lebih Cerdas.</span>
            </h1>
            <p class="hero-subtitle" style="font-size: 1.35rem; color: rgba(255,255,255,0.9); margin-bottom: 3.5rem; line-height: 1.7; max-width: 680px; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                Ekosistem digital terintegrasi untuk produktivitas tanpa batas. Kelola panen, pantau pasar, dan kembangkan potensi tani Anda dalam satu genggaman.
            </p>
            <div class="hero-actions" style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
                <a href="{{ route('marketplace.index') }}" class="btn btn-primary btn-lg" style="box-shadow: 0 15px 35px rgba(5, 150, 105, 0.4);">
                    Mulai Jelajah <i class="fas fa-arrow-right" style="margin-left:0.5rem;"></i>
                </a>
                <a href="#features" class="btn btn-lg" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.2); color: white;">
                    Pelajari Fitur
                </a>
            </div>
        </div>
    </div>
</section>

<!-- STATS SECTION -->
<section style="margin-top: -5rem; position: relative; z-index: 10;">
    <div class="container">
        <div class="stats-grid glass-card" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 2rem; text-align: center; padding: 3rem; backdrop-filter: blur(16px);">
            <div class="stats-item">
                <div style="font-size: 2.5rem; font-weight: 900; background: linear-gradient(135deg, #065f46, #059669); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: 0.5rem;">500+</div>
                <div style="font-weight: 700; color: var(--text-muted); text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px;">Petani Terdaftar</div>
            </div>
            <div class="stats-item" style="border-left: 1px solid var(--border-color);">
                <div style="font-size: 2.5rem; font-weight: 900; background: linear-gradient(135deg, #065f46, #059669); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: 0.5rem;">12.5k</div>
                <div style="font-weight: 700; color: var(--text-muted); text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px;">Ton Hasil Bumi</div>
            </div>
            <div class="stats-item" style="border-left: 1px solid var(--border-color);">
                <div style="font-size: 2.5rem; font-weight: 900; background: linear-gradient(135deg, #065f46, #059669); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: 0.5rem;">45+</div>
                <div style="font-weight: 700; color: var(--text-muted); text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px;">Alat Berat Modern</div>
            </div>
            <div class="stats-item" style="border-left: 1px solid var(--border-color);">
                <div style="font-size: 2.5rem; font-weight: 900; background: linear-gradient(135deg, #065f46, #059669); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: 0.5rem;">100%</div>
                <div style="font-weight: 700; color: var(--text-muted); text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px;">Petani Sejahtera</div>
            </div>
        </div>
    </div>
</section>

<!-- FEATURES SECTION -->
<section id="features" class="section">
    <div class="container">
        <div class="text-center" style="margin-bottom: 5rem;">
            <h2 class="section-title" style="font-size: 3rem; font-weight: 900; color: var(--primary-dark); margin-bottom: 1.5rem;">Satu Platform, <span style="background: linear-gradient(135deg, #065f46, #059669); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Semua Solusi</span></h2>
            <p class="section-desc" style="color: var(--text-muted); font-size: 1.2rem; max-width: 700px; margin: 0 auto;">Dirancang khusus untuk memenuhi kebutuhan ekosistem pertanian dari hulu ke hilir.</p>
        </div>

        <div class="features-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2.5rem;">
            <div class="glass-card feature-hover">
                <div class="icon-box" style="background: rgba(5, 150, 105, 0.1); color: var(--primary);"><i class="fas fa-store"></i></div>
                <h3 style="font-weight: 800; margin-bottom: 1rem; font-size: 1.5rem;">Marketplace Tani</h3>
                <p style="color: var(--text-muted); margin-bottom: 2rem; line-height: 1.7;">Beli benih, pupuk, dan alat tani langsung dari toko terpercaya atau jual hasil panen Anda ke masyarakat.</p>
                <a href="{{ route('marketplace.index') }}" class="feature-link" style="color: var(--primary);">Lihat Produk <i class="fas fa-arrow-right"></i></a>
            </div>

            <div class="glass-card feature-hover">
                <div class="icon-box" style="background: rgba(59, 130, 246, 0.1); color: var(--secondary);"><i class="fas fa-tasks"></i></div>
                <h3 style="font-weight: 800; margin-bottom: 1rem; font-size: 1.5rem;">Digital Farm Ops</h3>
                <p style="color: var(--text-muted); margin-bottom: 2rem; line-height: 1.7;">Pantau jadwal pemupukan, catat hasil panen, dan kelola operasional pertanian secara digital dan terstruktur.</p>
                <a href="{{ route('profile.edit') }}" class="feature-link" style="color: var(--secondary);">Kelola Tani <i class="fas fa-arrow-right"></i></a>
            </div>

            <div class="glass-card feature-hover">
                <div class="icon-box" style="background: rgba(245, 158, 11, 0.1); color: var(--accent);"><i class="fas fa-users-cog"></i></div>
                <h3 style="font-weight: 800; margin-bottom: 1rem; font-size: 1.5rem;">Forum & Konsultasi</h3>
                <p style="color: var(--text-muted); margin-bottom: 2rem; line-height: 1.7;">Diskusi bersama pakar dan sesama petani. Bagikan pengalaman dan dapatkan solusi atas kendala pertanian Anda.</p>
                <a href="{{ route('forum.index') }}" class="feature-link" style="color: var(--accent);">Mulai Diskusi <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</section>

<!-- IMPACT SECTION -->
<section class="section" style="background: var(--surface);">
    <div class="container">
        <div class="impact-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 5rem; align-items: center;">
            <div class="animate-fade">
                <div style="position: relative;">
                    <img src="{{ asset('hero_farming_mojokerto_1781576055255.png') }}" style="width: 100%; border-radius: 30px; box-shadow: 0 30px 60px rgba(0,0,0,0.1); filter: saturate(1.2);">
                    <div style="position: absolute; bottom: -1.5rem; right: -1.5rem; background: var(--primary-gradient); color: white; padding: 1.5rem 2rem; border-radius: 20px; box-shadow: 0 20px 40px rgba(5,150,105,0.3);">
                        <div style="font-size: 1.8rem; font-weight: 900;">5,000+</div>
                        <div style="font-size: 0.85rem; opacity: 0.9;">Pengguna Aktif</div>
                    </div>
                </div>
            </div>
            <div class="animate-fade">
                <span class="badge badge-success" style="margin-bottom: 1rem;">Tentang Kami</span>
                <h2 class="impact-title" style="font-size: 2.5rem; font-weight: 900; color: var(--primary-dark); margin-bottom: 2rem;">Membangun <span style="background: linear-gradient(135deg, #065f46, #059669); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Kedaulatan Pangan</span> dari Mojokerto</h2>
                <p style="color: var(--text-muted); font-size: 1.1rem; line-height: 1.8; margin-bottom: 2.5rem;">
                    AgriMojokerto bukan sekadar aplikasi, melainkan gerakan digitalisasi pertanian untuk memangkas rantai distribusi yang panjang, memberikan harga yang adil bagi petani, dan menjamin ketersediaan pangan bagi masyarakat Mojokerto.
                </p>
                <div style="display: grid; gap: 1.5rem;">
                    <div style="display: flex; gap: 1rem; align-items: flex-start;">
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(5,150,105,0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0;"><i class="fas fa-check"></i></div>
                        <div>
                            <h4 style="font-weight: 800; margin-bottom: 0.25rem;">Transparansi Harga</h4>
                            <p style="font-size: 0.9rem; color: var(--text-muted);">Melihat harga pasar secara real-time tanpa permainan spekulan.</p>
                        </div>
                    </div>
                    <div style="display: flex; gap: 1rem; align-items: flex-start;">
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(5,150,105,0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0;"><i class="fas fa-check"></i></div>
                        <div>
                            <h4 style="font-weight: 800; margin-bottom: 0.25rem;">Pendampingan Ahli</h4>
                            <p style="font-size: 0.9rem; color: var(--text-muted);">Akses langsung ke penyuluh lapangan untuk konsultasi penyakit tanaman.</p>
                        </div>
                    </div>
                    <div style="display: flex; gap: 1rem; align-items: flex-start;">
                        <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(5,150,105,0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; flex-shrink: 0;"><i class="fas fa-check"></i></div>
                        <div>
                            <h4 style="font-weight: 800; margin-bottom: 0.25rem;">Ekosistem Terpadu</h4>
                            <p style="font-size: 0.9rem; color: var(--text-muted);">Semua kebutuhan pertanian dalam satu platform digital.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA SECTION -->
<section class="section" style="background: var(--background);">
    <div class="container text-center">
        <div class="cta-card" style="padding: 5rem; background: linear-gradient(135deg, #022c22, #064e3b, #065f46); border-radius: var(--radius-2xl); color: white; position: relative; overflow: hidden;">
            <div style="position: absolute; inset: 0; background: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.03\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E'); pointer-events: none;"></div>
            <h2 class="cta-title" style="font-size: 3rem; font-weight: 900; margin-bottom: 1.5rem; position: relative; z-index: 1;">Siap Memulai Transformasi Tani?</h2>
            <p class="cta-desc" style="font-size: 1.2rem; opacity: 0.85; max-width: 600px; margin: 0 auto 3.5rem auto; line-height: 1.7; position: relative; z-index: 1;">Bergabunglah dengan ribuan petani lain di Mojokerto dan nikmati kemudahan bertani modern.</p>
            <div style="position: relative; z-index: 1;">
                <a href="{{ route('register') }}" class="btn btn-lg" style="background: white; color: #022c22; padding: 1.25rem 3rem; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">Daftar Sekarang Gratis <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    .icon-box {
        width: 70px;
        height: 70px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        margin-bottom: 2rem;
    }
    .feature-hover {
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .feature-hover:hover {
        transform: translateY(-12px);
        box-shadow: 0 30px 60px rgba(5, 150, 105, 0.12);
        border-color: rgba(5, 150, 105, 0.2);
    }
    .hero-title {
        animation: fadeInUp 1s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }
    .hero-subtitle {
        animation: fadeInUp 1s cubic-bezier(0.22, 1, 0.36, 1) 0.2s forwards;
        opacity: 0;
    }
    .hero-actions {
        animation: fadeInUp 1s cubic-bezier(0.22, 1, 0.36, 1) 0.4s forwards;
        opacity: 0;
    }
    .hero-badge {
        animation: fadeInUp 0.8s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }
    .feature-link {
        font-weight: 800;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: gap 0.3s ease;
    }
    .feature-link:hover {
        gap: 0.75rem;
    }
</style>
@endpush
@endsection