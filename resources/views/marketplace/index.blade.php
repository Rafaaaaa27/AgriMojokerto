@extends('layouts.native')

@section('content')
<div class="hero-sub" style="padding-top: 8rem; padding-bottom: 4rem; text-align: center;">
    <div class="container animate-fade-up">
        <h1 style="font-size: 3rem; font-weight: 900; margin-bottom: 1rem;">Marketplace Mandiri</h1>
        <p style="font-size: 1.2rem; opacity: 0.85; max-width: 600px; margin: 0 auto;">Pusat sarana produksi tani dan hasil bumi berkualitas dari petani Mojokerto.</p>
    </div>
</div>

<div class="container section">
    <div style="display: grid; grid-template-columns: 300px 1fr; gap: 3rem;">
        <!-- Mobile Filter Overlay -->
        <div class="mobile-filter-overlay" id="mobileFilterOverlay" onclick="closeMobileFilter()"></div>
        <div class="mobile-filter-panel" id="mobileFilterPanel">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h3 style="font-weight: 800; font-size: 1.2rem; color: var(--text-main);"><i class="fas fa-filter" style="color: var(--primary);"></i> Filter Produk</h3>
                <button onclick="closeMobileFilter()" style="background: none; border: none; font-size: 1.25rem; color: var(--text-muted); cursor: pointer;"><i class="fas fa-times"></i></button>
            </div>
            <form action="{{ route('marketplace.index') }}" method="GET" id="mobileFilterForm">
                <div style="margin-bottom: 2rem;">
                    <label class="filter-label">Kategori</label>
                    <div style="display: grid; gap: 0.75rem;">
                        @foreach(['benih' => 'Benih Unggul', 'pupuk' => 'Pupuk & Nutrisi', 'pestisida' => 'Pestisida', 'obat' => 'Obat Pertanian', 'alat' => 'Alat Tani', 'panen' => 'Alat Panen', 'hasil_panen' => 'Hasil Bumi'] as $val => $label)
                        <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; font-weight: 600; color: var(--text-muted);">
                            <input type="checkbox" name="categories[]" value="{{ $val }}" style="width: 18px; height: 18px; accent-color: var(--primary);" {{ in_array($val, (array)request('categories')) ? 'checked' : '' }}>
                            {{ $label }}
                        </label>
                        @endforeach
                    </div>
                </div>
                <div style="margin-bottom: 2rem;">
                    <label class="filter-label">Urutkan Harga</label>
                    <select name="sort" class="form-input">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Termurah</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Termahal</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">Terapkan Filter</button>
                <a href="{{ route('marketplace.index') }}" style="display: block; text-align: center; margin-top: 1rem; font-size: 0.9rem; color: var(--text-muted); text-decoration: none;">Reset Filter</a>
            </form>
        </div>

        <!-- FILTERS -->
        <aside class="animate-fade" id="desktopFilters">
            <div class="glass-card" style="padding: 2rem; position: sticky; top: 8rem;">
                <h3 style="font-weight: 800; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem;">
                    <i class="fas fa-filter" style="color: var(--primary);"></i> Filter Produk
                </h3>
                
                <form action="{{ route('marketplace.index') }}" method="GET">
                    <div style="margin-bottom: 2rem;">
                        <label class="filter-label">Kategori</label>
                        <div style="display: grid; gap: 0.75rem;">
                            @foreach(['benih' => 'Benih Unggul', 'pupuk' => 'Pupuk & Nutrisi', 'pestisida' => 'Pestisida', 'obat' => 'Obat Pertanian', 'alat' => 'Alat Tani', 'panen' => 'Alat Panen', 'hasil_panen' => 'Hasil Bumi'] as $val => $label)
                            <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; font-weight: 600; color: var(--text-muted);">
                                <input type="checkbox" name="categories[]" value="{{ $val }}" style="width: 18px; height: 18px; accent-color: var(--primary);">
                                {{ $label }}
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div style="margin-bottom: 2rem;">
                        <label class="filter-label">Urutkan Harga</label>
                        <select name="sort" class="form-input">
                            <option value="latest">Terbaru</option>
                            <option value="price_asc">Termurah</option>
                            <option value="price_desc">Termahal</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">Terapkan Filter</button>
                    <a href="{{ route('marketplace.index') }}" style="display: block; text-align: center; margin-top: 1rem; font-size: 0.9rem; color: var(--text-muted); text-decoration: none;">Reset Filter</a>
                </form>
            </div>
        </aside>

        <!-- PRODUCT GRID -->
        <main>
            <form method="GET" action="{{ route('marketplace.index') }}" style="margin-bottom: 2rem; display: flex; gap: 0.75rem; align-items: center;">
                <div style="flex: 1; position: relative;">
                    <i class="fas fa-search" style="position: absolute; left: 1.5rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari benih, pupuk, atau alat tani..." class="form-input" style="padding-left: 3.5rem; background: white; border-radius: 15px;">
                </div>
                <button type="submit" style="display:none;">Cari</button>
                <button type="button" class="mobile-filter-btn btn btn-secondary" id="mobileFilterBtn" style="display:none; padding: 0.75rem 1rem; border-radius: 12px;" onclick="toggleMobileFilter()">
                    <i class="fas fa-filter"></i>
                </button>
            </form>

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 2rem;">
                @forelse($products as $product)
                <div class="glass-card product-card animate-fade">
                    <div class="product-image">
                        @if($product->image_path)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                        @else
                            <img src="{{ asset('market_category_seeds_1781576070975.png') }}" alt="Default" style="filter: grayscale(0.2);">
                        @endif
                        <span class="category-badge">{{ ucfirst($product->category) }}</span>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">{{ $product->name }}</h3>
                        <p class="product-seller">Oleh: <strong>{{ $product->user->name ?? 'Toko Agri' }}</strong></p>
                        <div class="product-footer">
                            <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            @if(!auth()->check() || auth()->user()->role !== 'admin')
                                <a href="{{ route('checkout', ['type' => 'product', 'id' => $product->id]) }}" class="buy-btn">
                                    <i class="fas fa-shopping-cart"></i>
                                </a>
                            @else
                                <span style="font-size: 0.7rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">View Mode</span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 5rem 0;">
                    <img src="https://illustrations.popsy.co/emerald/empty-cart.svg" style="width: 250px; margin-bottom: 2rem;">
                    <h2 style="font-weight: 800; color: var(--primary-dark);">Belum ada produk</h2>
                    <p style="color: var(--text-muted);">Coba ubah filter atau kategori pencarian Anda.</p>
                </div>
                @endforelse
            </div>
            
            <div style="margin-top: 4rem; text-align: center;">
                <h2 style="font-weight: 800; margin-bottom: 2rem;">Sewa Alat Berat & Tani</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 2rem;">
                    @foreach($equipments as $eq)
                    <div class="glass-card equipment-card">
                        <div class="eq-image">
                            @if($eq->image_path)
                                <img src="{{ $eq->image_url }}" alt="{{ $eq->name }}">
                            @else
                                <img src="{{ asset('market_category_tools_1781576089307.png') }}" alt="Default Tool">
                            @endif
                        </div>
                        <div class="eq-info" style="padding: 1.5rem;">
                            <h4 style="font-weight: 800; margin-bottom:0.5rem;">{{ $eq->name }}</h4>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div style="color: var(--primary); font-weight: 800;">Rp {{ number_format($eq->price, 0, ',', '.') }}<span style="font-size: 0.8rem; color: var(--text-muted);">/{{ $eq->unit }}</span></div>
                                @if(!auth()->check() || auth()->user()->role !== 'admin')
                                    <a href="{{ route('checkout', ['type' => 'equipment', 'id' => $eq->id]) }}" class="btn btn-secondary btn-sm" style="padding: 0.5rem 1.25rem;">Sewa</a>
                                @else
                                    <span style="font-size: 0.7rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">View Mode</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script>
function toggleMobileFilter() {
    const panel = document.getElementById('mobileFilterPanel');
    const overlay = document.getElementById('mobileFilterOverlay');
    if (panel.classList.contains('show')) {
        closeMobileFilter();
    } else {
        panel.classList.add('show');
        overlay.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}
function closeMobileFilter() {
    document.getElementById('mobileFilterPanel').classList.remove('show');
    document.getElementById('mobileFilterOverlay').classList.remove('show');
    document.body.style.overflow = '';
}
</script>
@endpush

@push('styles')
<style>
    .mobile-filter-overlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 2000;
        background: rgba(15, 23, 42, 0.5);
        backdrop-filter: blur(4px);
    }
    .mobile-filter-overlay.show {
        display: block;
    }
    .mobile-filter-panel {
        display: none;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 2001;
        background: var(--surface);
        border-radius: var(--radius-lg) var(--radius-lg) 0 0;
        padding: 1.5rem;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 -10px 40px rgba(0,0,0,0.15);
        animation: filterSlideUp 0.3s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .mobile-filter-panel.show {
        display: block;
    }
    @keyframes filterSlideUp {
        from { transform: translateY(100%); }
        to { transform: translateY(0); }
    }

    .filter-label {
        display: block;
        margin-bottom: 1rem;
        font-weight: 800;
        font-size: 0.85rem;
        text-transform: uppercase;
        color: var(--text-muted);
        letter-spacing: 1px;
    }
    .product-card {
        padding: 0;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(16, 185, 129, 0.15);
    }
    .product-image {
        height: 220px;
        background: var(--surface-2);
        position: relative;
        overflow: hidden;
    }
    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .product-card:hover .product-image img {
        transform: scale(1.1);
    }
    .category-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: rgba(255,255,255,0.9);
        padding: 0.4rem 0.8rem;
        border-radius: 99px;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        color: var(--primary-dark);
        backdrop-filter: blur(4px);
    }
    .product-info {
        padding: 1.5rem;
    }
    .product-title {
        font-weight: 800;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
        color: var(--text-main);
    }
    .product-seller {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-bottom: 1.5rem;
    }
    .product-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .product-price {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--primary);
    }
    .buy-btn {
        width: 45px;
        height: 45px;
        background: var(--primary);
        color: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 8px 15px rgba(16, 185, 129, 0.2);
    }
    .buy-btn:hover {
        background: var(--primary-dark);
        transform: rotate(15deg);
    }
    .equipment-card {
        padding: 0;
        overflow: hidden;
    }
    .eq-image {
        height: 180px;
        background: var(--surface-2);
    }
    .eq-image img { width: 100%; height: 100%; object-fit: cover; }
    .placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #cbd5e1; }
</style>
@endpush
@endsection
