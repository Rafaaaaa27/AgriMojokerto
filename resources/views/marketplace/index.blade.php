@extends('layouts.native')

@section('content')
<div class="mkp-wrap">
    <div class="mkp-hero">
        <h1 class="mkp-hero-title">Marketplace Mandiri</h1>
        <p class="mkp-hero-sub">Pusat sarana produksi tani dan hasil bumi berkualitas dari petani Mojokerto.</p>
    </div>

    <div class="mkp-toolbar">
        <form action="{{ route('marketplace.index') }}" method="GET" class="mkp-search">
            <i class="fas fa-search mkp-search-icon"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari benih, pupuk, atau alat tani..." class="mkp-search-input">
            @if(request('categories'))
                @foreach((array)request('categories') as $cat)
                    <input type="hidden" name="categories[]" value="{{ $cat }}">
                @endforeach
            @endif
            @if(request('sort') && request('sort') !== 'latest')
                <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif
        </form>
        <button type="button" class="mkp-filter-toggle" id="mkpFilterToggle" onclick="toggleMobileFilter()">
            <i class="fas fa-sliders-h"></i> Filter
        </button>
    </div>

    <div class="mkp-layout">
        <aside class="mkp-sidebar" id="mkpSidebar">
            <div class="mkp-sidebar-card">
                <div class="mkp-sidebar-head">
                    <h3>Filter Produk</h3>
                    <button class="mkp-sidebar-close" id="mkpSidebarClose" onclick="closeMobileFilter()"><i class="fas fa-times"></i></button>
                </div>
                @include('marketplace._filter')
            </div>
        </aside>

        <!-- Mobile filter overlay -->
        <div class="mkp-mobile-overlay" id="mkpMobileOverlay" onclick="closeMobileFilter()"></div>

        <main class="mkp-main">
            <div class="mkp-grid">
                @forelse($products as $product)
                <div class="mkp-card">
                    <div class="mkp-card-img">
                        @if($product->image_path)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                        @else
                            <img src="{{ asset('market_category_seeds_1781576070975.png') }}" alt="Default">
                        @endif
                        <span class="mkp-card-badge">{{ ucfirst($product->category) }}</span>
                    </div>
                    <div class="mkp-card-body">
                        <h3 class="mkp-card-title">{{ $product->name }}</h3>
                        <p class="mkp-card-seller">{{ $product->user->name ?? 'Toko Agri' }}</p>
                        <div class="mkp-card-footer">
                            <span class="mkp-card-price">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                            @if(!auth()->check() || auth()->user()->role !== 'admin')
                                <a href="{{ route('checkout', ['type' => 'product', 'id' => $product->id]) }}" class="mkp-card-btn">Beli</a>
                            @else
                                <span class="mkp-card-view">View Mode</span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="mkp-empty">
                    <img src="https://illustrations.popsy.co/emerald/empty-cart.svg" alt="Kosong">
                    <h3>Belum ada produk</h3>
                    <p>Coba ubah filter atau kata kunci pencarian Anda.</p>
                </div>
                @endforelse
            </div>

            @if($products->hasPages())
            <div class="mkp-pagination">
                {{ $products->links('vendor.pagination.agri') }}
            </div>
            @endif

            <div class="mkp-eq-section">
                <div class="mkp-eq-head">
                    <div class="mkp-eq-bar"></div>
                    <h2 class="mkp-eq-title">Sewa Alat Tani</h2>
                </div>
                <div class="mkp-eq-grid">
                    @forelse($equipments as $eq)
                    <div class="mkp-eq-card">
                        <div class="mkp-eq-img">
                            @if($eq->image_path)
                                <img src="{{ $eq->image_url }}" alt="{{ $eq->name }}">
                            @else
                                <img src="{{ asset('market_category_tools_1781576089307.png') }}" alt="Default Tool">
                            @endif
                        </div>
                        <div class="mkp-eq-body">
                            <h4 class="mkp-eq-name">{{ $eq->name }}</h4>
                            <div class="mkp-eq-row">
                                <span class="mkp-eq-price">
                                    Rp{{ number_format($eq->price, 0, ',', '.') }}<span class="mkp-eq-unit">/{{ $eq->unit }}</span>
                                </span>
                                @if(!auth()->check() || auth()->user()->role !== 'admin')
                                    <a href="{{ route('checkout', ['type' => 'equipment', 'id' => $eq->id]) }}" class="mkp-eq-btn">Sewa</a>
                                @else
                                    <span class="mkp-card-view">View Mode</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="mkp-eq-empty">Belum ada alat tani tersedia.</p>
                    @endforelse
                </div>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script>
function toggleMobileFilter() {
    const sidebar = document.getElementById('mkpSidebar');
    const overlay = document.getElementById('mkpMobileOverlay');
    sidebar.classList.toggle('show');
    overlay.classList.toggle('show');
    document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
}
function closeMobileFilter() {
    document.getElementById('mkpSidebar')?.classList.remove('show');
    document.getElementById('mkpMobileOverlay')?.classList.remove('show');
    document.body.style.overflow = '';
}
</script>
@endpush

@push('styles')
<style>
/* ============ MARKETPLACE ============ */

.mkp-wrap {
    max-width: 1200px;
    margin: 0 auto;
    padding: 8rem 1.5rem 4rem;
}

.mkp-hero {
    margin-bottom: 2rem;
}
.mkp-hero-title {
    font-size: 1.75rem;
    font-weight: 900;
    color: var(--text-main);
    letter-spacing: -0.02em;
    margin-bottom: 0.3rem;
}
.mkp-hero-sub {
    color: var(--text-muted);
    font-size: 0.95rem;
    max-width: 520px;
}

/* Toolbar */
.mkp-toolbar {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}
.mkp-search {
    flex: 1;
    position: relative;
    min-width: 200px;
}
.mkp-search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 0.85rem;
    pointer-events: none;
}
.mkp-search-input {
    width: 100%;
    padding: 0.7rem 1rem 0.7rem 2.5rem;
    border-radius: 12px;
    border: 1.5px solid var(--border-color);
    background: var(--surface);
    color: var(--text-main);
    font-size: 0.88rem;
    font-weight: 500;
    outline: none;
    transition: border-color 0.2s;
}
.mkp-search-input:focus {
    border-color: var(--primary);
}
.mkp-filter-toggle {
    display: none;
    align-items: center;
    gap: 0.45rem;
    padding: 0.65rem 1.1rem;
    border-radius: 10px;
    background: var(--surface-2);
    color: var(--text-secondary);
    border: 1.5px solid var(--border-color);
    font-weight: 700;
    font-size: 0.82rem;
    cursor: pointer;
    font-family: inherit;
    transition: all 0.2s;
}
.mkp-filter-toggle:hover {
    background: var(--border-color);
    color: var(--text-main);
}

/* Layout */
.mkp-layout {
    display: grid;
    grid-template-columns: 270px 1fr;
    gap: 2rem;
    align-items: start;
}

/* Sidebar */
.mkp-sidebar {
    position: sticky;
    top: 6rem;
}
.mkp-sidebar-card {
    background: var(--surface);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
}
.mkp-sidebar-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.25rem;
}
.mkp-sidebar-head h3 {
    font-weight: 800;
    font-size: 0.95rem;
    color: var(--text-main);
}
.mkp-sidebar-close {
    display: none;
    width: 30px;
    height: 30px;
    align-items: center;
    justify-content: center;
    background: var(--background);
    border: none;
    border-radius: 8px;
    color: var(--text-muted);
    cursor: pointer;
    transition: all 0.2s;
}

/* Product Grid */
.mkp-main {
    min-width: 0;
}
.mkp-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 1.25rem;
}

.mkp-card {
    background: var(--surface);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    overflow: hidden;
    transition: transform 0.3s cubic-bezier(0.22,1,0.36,1), box-shadow 0.3s;
}
.mkp-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.08);
}

.mkp-card-img {
    height: 200px;
    background: var(--surface-2);
    position: relative;
    overflow: hidden;
}
.mkp-card-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s;
}
.mkp-card:hover .mkp-card-img img {
    transform: scale(1.08);
}

.mkp-card-badge {
    position: absolute;
    top: 0.75rem;
    left: 0.75rem;
    background: rgba(255,255,255,0.92);
    padding: 0.3rem 0.7rem;
    border-radius: 99px;
    font-size: 0.6rem;
    font-weight: 800;
    text-transform: uppercase;
    color: var(--primary-dark);
    backdrop-filter: blur(4px);
}

.mkp-card-body {
    padding: 1.25rem;
}
.mkp-card-title {
    font-weight: 800;
    font-size: 1rem;
    color: var(--text-main);
    margin-bottom: 0.3rem;
    line-height: 1.3;
}
.mkp-card-seller {
    font-size: 0.8rem;
    color: var(--text-muted);
    margin-bottom: 1rem;
}
.mkp-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.mkp-card-price {
    font-size: 1.1rem;
    font-weight: 800;
    color: var(--primary);
}
.mkp-card-btn {
    padding: 0.5rem 1.1rem;
    background: var(--primary);
    color: white;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.82rem;
    text-decoration: none;
    transition: all 0.25s;
}
.mkp-card-btn:hover {
    background: var(--primary-dark);
    box-shadow: 0 4px 12px rgba(5,150,105,0.3);
}
.mkp-card-view {
    font-size: 0.65rem;
    color: var(--text-muted);
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Empty */
.mkp-empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 4rem 0;
}
.mkp-empty img {
    width: 200px;
    margin-bottom: 1.5rem;
    opacity: 0.8;
}
.mkp-empty h3 {
    font-weight: 800;
    color: var(--text-main);
    margin-bottom: 0.4rem;
}
.mkp-empty p {
    color: var(--text-muted);
    font-size: 0.9rem;
}

/* Pagination */
.mkp-pagination {
    margin-top: 2.5rem;
    display: flex;
    justify-content: center;
}

/* Equipment Section */
.mkp-eq-section {
    margin-top: 4rem;
    padding-top: 2rem;
    border-top: 1px solid var(--border-color);
}
.mkp-eq-head {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    margin-bottom: 1.5rem;
}
.mkp-eq-bar {
    width: 3px;
    height: 20px;
    background: var(--primary-gradient);
    border-radius: 99px;
    flex-shrink: 0;
}
.mkp-eq-title {
    font-weight: 900;
    font-size: 1.1rem;
    color: var(--text-main);
    letter-spacing: -0.01em;
}

.mkp-eq-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.25rem;
}

.mkp-eq-card {
    background: var(--surface);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    overflow: hidden;
    transition: transform 0.3s cubic-bezier(0.22,1,0.36,1), box-shadow 0.3s;
}
.mkp-eq-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.06);
}

.mkp-eq-img {
    height: 170px;
    background: var(--surface-2);
    overflow: hidden;
}
.mkp-eq-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s;
}
.mkp-eq-card:hover .mkp-eq-img img {
    transform: scale(1.06);
}

.mkp-eq-body {
    padding: 1.25rem;
}
.mkp-eq-name {
    font-weight: 800;
    font-size: 0.95rem;
    color: var(--text-main);
    margin-bottom: 0.75rem;
}
.mkp-eq-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 0.75rem;
}
.mkp-eq-price {
    font-weight: 800;
    font-size: 1.05rem;
    color: var(--primary);
    white-space: nowrap;
}
.mkp-eq-unit {
    font-size: 0.75rem;
    color: var(--text-muted);
    font-weight: 600;
}
.mkp-eq-btn {
    padding: 0.5rem 1.1rem;
    background: var(--surface-2);
    color: var(--text-main);
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.82rem;
    text-decoration: none;
    border: 1px solid var(--border-color);
    transition: all 0.25s;
    white-space: nowrap;
}
.mkp-eq-btn:hover {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
}
.mkp-eq-empty {
    grid-column: 1 / -1;
    text-align: center;
    color: var(--text-muted);
    padding: 3rem 0;
}

/* Mobile filter overlay */
.mkp-mobile-overlay {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 998;
    background: rgba(0,0,0,0.35);
    backdrop-filter: blur(4px);
}
.mkp-mobile-overlay.show {
    display: block;
    animation: fadeIn 0.25s ease;
}

/* Responsive */
@media (max-width: 1024px) {
    .mkp-layout {
        grid-template-columns: 240px 1fr;
        gap: 1.5rem;
    }
}

@media (max-width: 768px) {
    .mkp-wrap {
        padding-top: 6.5rem;
    }
    .mkp-layout {
        grid-template-columns: 1fr;
    }
    .mkp-sidebar {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        width: 300px;
        max-width: 85vw;
        z-index: 999;
        background: var(--surface);
        padding: 1.5rem;
        overflow-y: auto;
        transform: translateX(100%);
        transition: transform 0.3s cubic-bezier(0.22,1,0.36,1);
        box-shadow: -4px 0 30px rgba(0,0,0,0.1);
        margin: 0;
    }
    .mkp-sidebar.show {
        transform: translateX(0);
        display: block;
    }
    .mkp-sidebar-card {
        border: none;
        padding: 0;
    }
    .mkp-sidebar-close {
        display: flex;
    }
    .mkp-filter-toggle {
        display: inline-flex;
    }
    .mkp-hero-title {
        font-size: 1.4rem;
    }
    .mkp-grid {
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 1rem;
    }
}

@media (max-width: 480px) {
    .mkp-wrap {
        padding-top: 5.5rem;
        padding-left: 1rem;
        padding-right: 1rem;
    }
    .mkp-toolbar {
        gap: 0.5rem;
    }
    .mkp-grid {
        grid-template-columns: 1fr;
    }
    .mkp-card-img {
        height: 180px;
    }
    .mkp-card-body {
        padding: 1rem;
    }
    .mkp-eq-grid {
        grid-template-columns: 1fr;
    }
    .mkp-eq-body {
        padding: 1rem;
    }
}

@media (max-width: 380px) {
    .mkp-wrap {
        padding-top: 5rem;
    }
}
</style>
@endpush
@endsection