@extends('layouts.native')

@push('styles')
<style>
    .eq-filter-card {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-lg);
        padding: 1.5rem 2rem;
        margin-bottom: 2.5rem;
        display: flex;
        align-items: flex-end;
        gap: 1.5rem;
        flex-wrap: wrap;
    }
    .eq-filter-card .form-group {
        flex: 1;
        min-width: 160px;
        margin-bottom: 0;
    }
    .eq-filter-card label {
        display: block;
        font-size: 0.8rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-muted);
        margin-bottom: 0.5rem;
    }
    .eq-filter-card select {
        width: 100%;
        padding: 0.65rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: var(--radius-md);
        font-size: 0.9rem;
        font-family: inherit;
        color: var(--text-main);
        background: var(--surface);
        outline: none;
        transition: border-color 0.2s;
    }
    .eq-filter-card select:focus { border-color: var(--primary); }
    .eq-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
    }
    .eq-card {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: transform 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.35s ease;
    }
    .eq-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 24px 48px rgba(16, 185, 129, 0.15);
    }
    .eq-image {
        height: 210px;
        background: var(--surface-2);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }
    .eq-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .eq-card:hover .eq-image img { transform: scale(1.06); }
    .eq-icon-placeholder {
        font-size: 2.5rem;
        font-weight: 900;
        color: var(--primary);
        opacity: 0.5;
        letter-spacing: 0.05em;
    }
    .eq-type-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(255,255,255,0.92);
        padding: 0.35rem 0.75rem;
        border-radius: 99px;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        color: var(--primary-dark);
        backdrop-filter: blur(4px);
    }
    .eq-body {
        padding: 1.5rem;
    }
    .eq-name {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--primary-dark);
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }
    .eq-meta {
        font-size: 0.82rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 0.4rem;
        margin-bottom: 0.4rem;
    }
    .eq-meta::before { content: "•"; color: var(--primary); font-weight: 900; margin-right: 0.4rem; }
    .eq-price-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1.25rem;
        padding-top: 1.25rem;
        border-top: 1px solid var(--border-color);
    }
    .eq-price {
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--primary);
    }
    .eq-price span {
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--text-muted);
    }
    .btn-sewa {
        padding: 0.55rem 1.25rem;
        background: var(--primary);
        color: white;
        border-radius: var(--radius-md);
        text-decoration: none;
        font-weight: 700;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.3s ease;
    }
    .btn-sewa:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
    }
    .btn-login-sewa {
        padding: 0.55rem 1.25rem;
        background: var(--glass-bg);
        color: var(--primary);
        border: 2px solid var(--primary);
        border-radius: var(--radius-md);
        text-decoration: none;
        font-weight: 700;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.3s ease;
    }
    .btn-login-sewa:hover {
        background: var(--primary);
        color: white;
    }
    .empty-state {
        grid-column: 1/-1;
        text-align: center;
        padding: 5rem 2rem;
        background: var(--glass-bg);
        border-radius: var(--radius-lg);
        border: 2px dashed var(--border-color);
    }

    .empty-state h3 { color: var(--primary-dark); font-size: 1.5rem; margin-bottom: 0.5rem; }
    .empty-state p { color: var(--text-muted); }
    .pagination-wrap { margin-top: 3rem; display: flex; justify-content: center; }

</style>
@endpush

@section('content')

<div class="hero-sub" style="text-align: center;">
    <div class="container animate-fade-up">
        <h1 style="font-size: 3rem; font-weight: 900; margin-bottom: 1rem;">
            Penyewaan Alat Pertanian
        </h1>
        <p style="font-size: 1.2rem; opacity: 0.85; max-width: 600px; margin: 0 auto;">
            Sewa alat pertanian modern dari petani dan penyedia lokal di sekitar Mojokerto.
        </p>
    </div>
</div>

<div class="container section">

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('equipments.index') }}" id="equipmentFilterForm">
        <div class="eq-filter-card animate-fade">
            <div class="form-group">
                <label>Jenis Alat</label>
                <select name="type" onchange="document.getElementById('equipmentFilterForm').submit()">
                    <option value="all" {{ !request('type') || request('type') === 'all' ? 'selected' : '' }}>Semua Jenis</option>
                    <option value="traktor" {{ request('type') === 'traktor' ? 'selected' : '' }}>Traktor</option>
                    <option value="combine" {{ request('type') === 'combine' ? 'selected' : '' }}>Combine Harvester</option>
                    <option value="truk" {{ request('type') === 'truk' ? 'selected' : '' }}>Truk</option>
                    <option value="pemotong" {{ request('type') === 'pemotong' ? 'selected' : '' }}>Alat Pemotong</option>
                    <option value="drone" {{ request('type') === 'drone' ? 'selected' : '' }}>Drone</option>
                    <option value="thresher" {{ request('type') === 'thresher' ? 'selected' : '' }}>Thresher / Perontok</option>
                </select>
            </div>
            <div class="form-group">
                <label>Untuk Tanaman</label>
                <select name="crop" onchange="document.getElementById('equipmentFilterForm').submit()">
                    <option value="all" {{ !request('crop') || request('crop') === 'all' ? 'selected' : '' }}>Semua Tanaman</option>
                    <option value="padi" {{ request('crop') === 'padi' ? 'selected' : '' }}>Padi</option>
                    <option value="tebu" {{ request('crop') === 'tebu' ? 'selected' : '' }}>Tebu</option>
                </select>
            </div>
            <div class="form-group">
                <label>Lokasi</label>
                <select name="location" onchange="document.getElementById('equipmentFilterForm').submit()">
                    <option value="all" {{ !request('location') || request('location') === 'all' ? 'selected' : '' }}>Semua Lokasi</option>
                    <option value="Mojokerto Kota" {{ request('location') === 'Mojokerto Kota' ? 'selected' : '' }}>Mojokerto Kota</option>
                    <option value="Mojosari" {{ request('location') === 'Mojosari' ? 'selected' : '' }}>Mojosari</option>
                    <option value="Bangsal" {{ request('location') === 'Bangsal' ? 'selected' : '' }}>Bangsal</option>
                    <option value="Sooko" {{ request('location') === 'Sooko' ? 'selected' : '' }}>Sooko</option>
                    <option value="Trawas" {{ request('location') === 'Trawas' ? 'selected' : '' }}>Trawas</option>
                    <option value="Pacet" {{ request('location') === 'Pacet' ? 'selected' : '' }}>Pacet</option>
                    <option value="Mojoanyar" {{ request('location') === 'Mojoanyar' ? 'selected' : '' }}>Mojoanyar</option>
                    <option value="Jetis" {{ request('location') === 'Jetis' ? 'selected' : '' }}>Jetis</option>
                </select>
            </div>
            @if(request('type') && request('type') !== 'all' || request('crop') && request('crop') !== 'all' || request('location') && request('location') !== 'all')
            <div>
                <a href="{{ route('equipments.index') }}" class="btn btn-secondary" style="white-space:nowrap;">
                    Reset
                </a>
            </div>
            @endif
        </div>
    </form>

    {{-- Equipment Grid --}}
    <div class="eq-grid">
        @forelse($equipments as $equipment)
        <div class="eq-card animate-fade">
            <div class="eq-image">
                @if($equipment->image_url)
                    <img src="{{ $equipment->image_url }}" alt="{{ $equipment->name }}">
                @else
                    <div class="eq-icon-placeholder">{{ strtoupper(substr($equipment->type, 0, 2)) }}</div>
                @endif
                <span class="eq-type-badge">{{ ucfirst($equipment->type) }}</span>
            </div>
            <div class="eq-body">
                <h3 class="eq-name">{{ $equipment->name }}</h3>
                @if($equipment->location)
                <div class="eq-meta">{{ $equipment->location }}</div>
                @endif
                <div class="eq-meta">{{ $equipment->user->name ?? 'Penyedia Alat' }}</div>
                @if($equipment->phone)
                <div class="eq-meta">{{ $equipment->phone }}</div>
                @endif
                @if($equipment->description)
                <p style="font-size:0.85rem; color:var(--text-muted); margin-top:0.75rem; line-height:1.5; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                    {{ $equipment->description }}
                </p>
                @endif

                <div class="eq-price-row">
                    <div>
                        <div class="eq-price">Rp {{ number_format($equipment->price, 0, ',', '.') }}</div>
                        <div style="font-size:0.78rem; color:var(--text-muted);">per {{ $equipment->unit }}</div>
                    </div>
                    @auth
                        <a href="{{ route('checkout', ['type' => 'equipment', 'id' => $equipment->id]) }}" class="btn-sewa">
                            Sewa
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-login-sewa">
                            Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <h3>Tidak ada alat yang tersedia</h3>
            <p>Coba ubah filter atau cek kembali nanti.</p>
            @if(request('type') || request('crop') || request('location'))
                <a href="{{ route('equipments.index') }}" class="btn btn-primary" style="margin-top:1.5rem; display:inline-flex;">
                    Reset Filter
                </a>
            @endif
        </div>
        @endforelse
    </div>

    <div class="pagination-wrap">
        {{ $equipments->links() }}
    </div>
</div>

@endsection
