@extends('layouts.native')

@section('content')
<div class="container seller-dashboard-container" style="padding-bottom: 4rem; min-height: 100vh;">
    <div style="display: grid; grid-template-columns: 260px 1fr; gap: 2rem; align-items: start;">
        @include('layouts.sidebar')

        <main style="max-width: 960px;">
            <div class="animate-fade">

                <div class="seller-greeting-block">
                    <div class="seller-greeting-row">
                        <div class="seller-greeting-icon">
                            <i class="fas fa-store" style="color: white;"></i>
                        </div>
                        <div class="seller-greeting-copy">
                            <h1 class="seller-greeting-text">Selamat <span id="greeting-time">pagi</span>, {{ explode(' ', $user->name)[0] }}!</h1>
                            <p class="seller-greeting-sub">Kelola produk, pantau penjualan, dan tingkatkan omzet Anda.</p>
                        </div>
                    </div>
                </div>

                <div class="seller-stats-grid">
                    <a href="{{ route('profile.edit', ['menu' => 'products']) }}" class="seller-stat-card-link">
                        <div class="seller-stat-card">
                            <div class="seller-stat-icon" style="background: rgba(16,185,129,0.1); color: #10b981;">
                                <i class="fas fa-box"></i>
                            </div>
                            <div>
                                <div class="seller-stat-value">{{ $stats['approved_listings'] ?? 0 }}</div>
                                <div class="seller-stat-label">Produk Aktif</div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('profile.edit', ['menu' => 'products']) }}" class="seller-stat-card-link">
                        <div class="seller-stat-card">
                            <div class="seller-stat-icon" style="background: rgba(245,158,11,0.1); color: #f59e0b;">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <div class="seller-stat-value">{{ $stats['pending_listings'] ?? 0 }}</div>
                                <div class="seller-stat-label">Menunggu Review</div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('profile.edit', ['menu' => 'incoming']) }}" class="seller-stat-card-link">
                        <div class="seller-stat-card">
                            <div class="seller-stat-icon" style="background: rgba(59,130,246,0.1); color: #3b82f6;">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div>
                                <div class="seller-stat-value">{{ $stats['sales_count'] ?? 0 }}</div>
                                <div class="seller-stat-label">Penjualan Selesai</div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('profile.edit', ['menu' => 'incoming']) }}" class="seller-stat-card-link">
                        <div class="seller-stat-card">
                            <div class="seller-stat-icon" style="background: rgba(139,92,246,0.1); color: #8b5cf6;">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div>
                                <div class="seller-stat-value">Rp{{ number_format($stats['revenue'] ?? 0, 0, ',', '.') }}</div>
                                <div class="seller-stat-label">Total Pendapatan</div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="seller-section">
                    <div class="seller-section-head">
                        <div class="seller-section-bar"></div>
                        <h3 class="seller-section-title">Pesanan Masuk</h3>
                        <a href="{{ route('profile.edit', ['menu' => 'incoming']) }}" class="seller-section-link">Lihat Semua <i class="fas fa-arrow-right"></i></a>
                    </div>
                    <div class="glass-card" style="padding: 1.5rem;">
                        <div style="display: grid; gap: 0.6rem;">
                            @forelse($incomingOrders->take(5) as $ord)
                            <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.7rem 1rem; background: var(--background); border-radius: var(--radius-sm);">
                                <div style="display: flex; align-items: center; gap: 0.75rem; min-width: 0;">
                                    <div style="width: 32px; height: 32px; background: rgba(16,185,129,0.08); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 0.75rem; flex-shrink: 0;">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <div style="min-width: 0;">
                                        <div style="font-weight: 700; font-size: 0.82rem; color: var(--text-main); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $ord->product->name ?? '-' }}</div>
                                        <div style="font-size: 0.68rem; color: var(--text-muted);">{{ $ord->user->name ?? 'Pembeli' }} &middot; {{ $ord->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                <div style="text-align: right; flex-shrink: 0;">
                                    <div style="font-weight: 800; font-size: 0.82rem; color: var(--text-main); letter-spacing: -0.01em;">Rp{{ number_format($ord->total_price, 0, ',', '.') }}</div>
                                    <span style="font-size: 0.6rem; font-weight: 700; padding: 0.12rem 0.5rem; border-radius: 99px; background: {{ $ord->status === 'pending' ? 'rgba(245,158,11,0.1)' : ($ord->status === 'confirmed' ? 'rgba(5,150,105,0.1)' : 'rgba(239,68,68,0.1)') }}; color: {{ $ord->status === 'pending' ? 'var(--warning)' : ($ord->status === 'confirmed' ? '#10b981' : '#ef4444') }}; text-transform: uppercase; letter-spacing: 0.3px; display: inline-block; margin-top: 0.15rem;">{{ $ord->status }}</span>
                                </div>
                            </div>
                            @empty
                            <div style="text-align: center; padding: 2rem 0;">
                                <i class="fas fa-inbox" style="font-size: 1.5rem; color: var(--text-muted); opacity: 0.2; margin-bottom: 0.75rem; display: block;"></i>
                                <p style="color: var(--text-muted); font-size: 0.85rem; font-weight: 500;">Belum ada pesanan masuk</p>
                                <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">Promosikan produk Anda untuk mendapatkan pesanan pertama!</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="seller-section">
                    <div class="seller-section-head">
                        <div class="seller-section-bar"></div>
                        <h3 class="seller-section-title">Akses Cepat</h3>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <a href="{{ route('profile.edit', ['menu' => 'products']) }}" class="glass-card" style="padding: 1.25rem; display: flex; align-items: center; gap: 1rem; text-decoration: none;">
                            <div style="width: 44px; height: 44px; background: rgba(16,185,129,0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #10b981; font-size: 1.1rem; flex-shrink: 0;">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <div>
                                <div style="font-weight: 800; font-size: 0.9rem; color: var(--text-main);">Kelola Produk & Alat</div>
                                <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 0.1rem;">Tambah, edit, atau hapus produk</div>
                            </div>
                        </a>
                        <a href="{{ route('profile.edit', ['menu' => 'incoming']) }}" class="glass-card" style="padding: 1.25rem; display: flex; align-items: center; gap: 1rem; text-decoration: none;">
                            <div style="width: 44px; height: 44px; background: rgba(59,130,246,0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #3b82f6; font-size: 1.1rem; flex-shrink: 0;">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </div>
                            <div>
                                <div style="font-weight: 800; font-size: 0.9rem; color: var(--text-main);">Lihat Penjualan</div>
                                <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 0.1rem;">Pantau pesanan dan konfirmasi</div>
                            </div>
                        </a>
                        <a href="{{ route('profile.edit', ['menu' => 'settings']) }}" class="glass-card" style="padding: 1.25rem; display: flex; align-items: center; gap: 1rem; text-decoration: none;">
                            <div style="width: 44px; height: 44px; background: rgba(139,92,246,0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #8b5cf6; font-size: 1.1rem; flex-shrink: 0;">
                                <i class="fas fa-cog"></i>
                            </div>
                            <div>
                                <div style="font-weight: 800; font-size: 0.9rem; color: var(--text-main);">Pengaturan Profil</div>
                                <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 0.1rem;">Ubah data toko dan informasi akun</div>
                            </div>
                        </a>
                        <a href="{{ route('profile.edit', ['menu' => 'orders']) }}" class="glass-card" style="padding: 1.25rem; display: flex; align-items: center; gap: 1rem; text-decoration: none;">
                            <div style="width: 44px; height: 44px; background: rgba(245,158,11,0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #f59e0b; font-size: 1.1rem; flex-shrink: 0;">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <div>
                                <div style="font-weight: 800; font-size: 0.9rem; color: var(--text-main);">Pesanan Saya</div>
                                <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 0.1rem;">Riwayat pembelian Anda</div>
                            </div>
                        </a>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

@push('styles')
<style>
.seller-dashboard-container {
    padding-top: 8rem;
}
.seller-greeting-row {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 2rem;
}
.seller-greeting-icon {
    width: 52px;
    height: 52px;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    box-shadow: 0 4px 16px rgba(245,158,11,0.25);
    flex-shrink: 0;
}
.seller-greeting-copy {
    min-width: 0;
}
.seller-greeting-text {
    font-size: 1.6rem;
    font-weight: 900;
    color: var(--text-main);
    letter-spacing: -0.02em;
    line-height: 1.25;
    word-break: break-word;
}
.seller-greeting-sub {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-top: 0.2rem;
}
.seller-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.25rem;
    margin-bottom: 2.5rem;
}
.seller-stat-card-link {
    text-decoration: none;
    display: block;
}
.seller-stat-card {
    background: var(--surface);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.35s cubic-bezier(0.22,1,0.36,1);
}
.seller-stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.06);
}
.seller-stat-icon {
    width: 46px;
    height: 46px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}
.seller-stat-value {
    font-size: 1.1rem;
    font-weight: 800;
    color: var(--text-main);
    letter-spacing: -0.02em;
}
.seller-stat-label {
    font-size: 0.68rem;
    color: var(--text-muted);
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 0.1rem;
}
.seller-section {
    margin-bottom: 2.5rem;
}
.seller-section-head {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    margin-bottom: 1.25rem;
}
.seller-section-bar {
    width: 3px;
    height: 18px;
    background: var(--primary-gradient);
    border-radius: 99px;
    flex-shrink: 0;
}
.seller-section-title {
    font-weight: 900;
    font-size: 1rem;
    color: var(--text-main);
    letter-spacing: -0.01em;
}
.seller-section-link {
    font-size: 0.72rem;
    color: var(--text-muted);
    font-weight: 600;
    text-decoration: none;
    margin-left: auto;
    transition: color 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
}
.seller-section-link:hover {
    color: var(--primary);
}
.seller-section-link i {
    font-size: 0.6rem;
}

@media (max-width: 900px) {
    .seller-stats-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 1rem !important;
    }
}
@media (max-width: 680px) {
    .seller-greeting-text {
        font-size: 1.2rem;
    }
    .seller-greeting-sub {
        font-size: 0.8rem;
    }
    div[style*="grid-template-columns: 1fr 1fr"]:has(a.glass-card) {
        grid-template-columns: 1fr !important;
    }
}
@media (max-width: 480px) {
    .seller-stats-grid {
        grid-template-columns: 1fr !important;
        gap: 0.85rem !important;
    }
    .seller-dashboard-container {
        padding-top: 7rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
(function() {
    var h = (new Date()).getHours();
    var el = document.getElementById('greeting-time');
    if (el) el.textContent = h < 12 ? 'pagi' : h < 15 ? 'siang' : h < 18 ? 'sore' : 'malam';
})();
</script>
@endpush
@endsection
