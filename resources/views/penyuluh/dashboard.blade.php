@extends('layouts.native')

@section('content')
<div class="container" style="padding-top: 7rem;">
    <div class="pen-layout">
        @include('layouts.sidebar')

        <div class="animate-fade">

            {{-- HEADER --}}
            <div class="pen-header">
                <div class="pen-header-icon">🌾</div>
                <div>
                    <h1 class="pen-title">Dashboard Penyuluh</h1>
                    <p class="pen-desc">Pantau harga pasar dan kelola informasi edukasi.</p>
                </div>
            </div>

            {{-- STATS ROW --}}
            <div class="pen-stats">
                <a href="{{ route('admin.market-prices.index') }}" class="pen-stat-card">
                    <div class="pen-stat-icon pen-stat-icon-green">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <div class="pen-stat-label">Harga Pasar</div>
                        <div class="pen-stat-value">Kelola harga komoditas</div>
                    </div>
                </a>
                <a href="{{ route('library.index') }}" class="pen-stat-card">
                    <div class="pen-stat-icon pen-stat-icon-blue">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <div>
                        <div class="pen-stat-label">Sosialisasi & Info</div>
                        <div class="pen-stat-value">{{ $totalEducational }} konten edukasi</div>
                    </div>
                </a>
            </div>

            {{-- MARKET PRICE CHARTS --}}
            <div class="pen-section">
                <div class="pen-section-head">
                    <div class="pen-section-bar"></div>
                    <h3 class="pen-section-title">Grafik Harga Pasar</h3>
                    <span class="pen-section-source">Sumber: Dinas Pertanian Mojokerto</span>
                </div>
                @php
                $chartCommodities = [
                    ['key' => 'padi', 'id' => 'Padi', 'label' => 'Padi', 'color' => '#34d399'],
                ];
                @endphp
                <div class="pen-chart-grid">
                    @foreach($chartCommodities as $c)
                    <div class="glass-card pen-chart-card">
                        <div class="pen-chart-head">
                            <div>
                                <div class="pen-chart-label">
                                    <span class="pen-chart-dot" style="background: {{ $c['color'] }};"></span>
                                    <span>{{ $c['label'] }} /kg</span>
                                </div>
                                <div class="pen-chart-price" id="price-{{ $c['key'] }}">—</div>
                            </div>
                            <span class="pen-chart-change" id="change-{{ $c['key'] }}">—</span>
                        </div>
                        <div class="pen-chart-canvas">
                            <canvas id="chart{{ $c['id'] }}"></canvas>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- MANAJEMEN HARGA --}}
            <div class="pen-section">
                <div class="pen-section-head">
                    <div class="pen-section-bar"></div>
                    <h3 class="pen-section-title">Manajemen Harga Pasar</h3>
                    <a href="{{ route('admin.market-prices.index') }}" class="pen-edukasi-link" style="margin-left: auto;">
                        Kelola Semua <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="pen-harga-grid">
                    @php
                        $hargaKomoditas = [
                            ['key' => 'padi', 'label' => 'Padi', 'icon' => '🌾', 'color' => '#34d399'],
                        ];
                    @endphp
                    @foreach($hargaKomoditas as $h)
                    <div class="glass-card pen-harga-card">
                        <div class="pen-harga-head">
                            <div class="pen-harga-icon" style="background: {{ $h['color'] }}15;">{{ $h['icon'] }}</div>
                            <span class="pen-harga-label">{{ $h['label'] }}</span>
                        </div>
                        <div class="pen-harga-price" id="harga-{{ $h['key'] }}">—</div>
                        <div class="pen-harga-change" id="harga-change-{{ $h['key'] }}">—</div>
                        <button onclick="document.location='{{ route('admin.market-prices.index') }}'" class="pen-harga-btn">
                            <i class="fas fa-plus-circle"></i> Tambah Harga
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- EDUKASI TERBARU --}}
            <div class="glass-card pen-edukasi">
                <div class="pen-edukasi-head">
                    <div class="pen-edukasi-title">
                        <i class="fas fa-bullhorn"></i>
                        <h3>Konten Edukasi Terbaru</h3>
                    </div>
                    <a href="{{ route('profile.edit', ['menu' => 'information']) }}" class="pen-edukasi-link">Kelola <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="pen-edukasi-list">
                    @forelse($recentEducational as $edu)
                    <div class="pen-edukasi-item">
                        <div>
                            <div class="pen-edukasi-item-title">{{ $edu->title }}</div>
                            <div class="pen-edukasi-item-date">{{ $edu->created_at->diffForHumans() }}</div>
                        </div>
                        <span class="pen-edukasi-item-badge">{{ $edu->category }}</span>
                    </div>
                    @empty
                    <div class="pen-edukasi-empty">
                        <i class="fas fa-bullhorn"></i>
                        <p>Belum ada konten edukasi</p>
                        <a href="{{ route('profile.edit', ['menu' => 'information']) }}" class="btn btn-primary btn-sm">Buat Konten</a>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>

@push('styles')
<style>
.pen-layout { display: grid; grid-template-columns: 260px 1fr; gap: 2rem; align-items: start; }
.pen-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 2.5rem; }
.pen-header-icon { width: 48px; height: 48px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; box-shadow: 0 4px 16px rgba(5,150,105,0.2); flex-shrink: 0; }
.pen-title { font-size: 1.6rem; font-weight: 900; color: var(--text-main); letter-spacing: -0.02em; }
.pen-desc { color: var(--text-secondary); font-size: 0.9rem; margin-top: 0.15rem; }

.pen-stats { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem; margin-bottom: 2.5rem; }
.pen-stat-card { text-decoration: none; display: block; background: var(--surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 1.5rem; transition: all 0.35s cubic-bezier(0.22,1,0.36,1); }
.pen-stat-card:hover { transform: translateY(-3px); box-shadow: 0 12px 30px rgba(0,0,0,0.06); }
.pen-stat-icon { width: 46px; height: 46px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
.pen-stat-icon-green { background: linear-gradient(135deg, rgba(16,185,129,0.12), rgba(16,185,129,0.04)); color: var(--primary); }
.pen-stat-icon-blue { background: linear-gradient(135deg, rgba(59,130,246,0.12), rgba(59,130,246,0.04)); color: #3b82f6; }
.pen-stat-label { font-size: 0.7rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; }
.pen-stat-value { font-size: 0.85rem; font-weight: 600; color: var(--text-main); margin-top: 0.15rem; }

.pen-section { margin-bottom: 2.5rem; }
.pen-section-head { display: flex; align-items: center; gap: 0.6rem; margin-bottom: 1.25rem; }
.pen-section-bar { width: 3px; height: 18px; background: var(--primary-gradient); border-radius: 99px; flex-shrink: 0; }
.pen-section-title { font-weight: 900; font-size: 1rem; color: var(--text-main); letter-spacing: -0.01em; }
.pen-section-source { font-size: 0.7rem; color: var(--text-muted); font-weight: 600; margin-left: auto; }

.pen-chart-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.pen-chart-card { padding: 1.25rem; }
.pen-chart-head { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem; }
.pen-chart-label { display: flex; align-items: center; gap: 0.35rem; font-size: 0.65rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px; }
.pen-chart-dot { width: 6px; height: 6px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
.pen-chart-price { font-size: 1.25rem; font-weight: 900; color: var(--text-main); margin-top: 0.2rem; letter-spacing: -0.02em; }
.pen-chart-change { font-size: 0.72rem; font-weight: 700; padding: 0.15rem 0.6rem; border-radius: 99px; background: rgba(5,150,105,0.1); color: #059669; white-space: nowrap; }
.pen-chart-canvas { position: relative; height: 130px; }

.pen-edukasi { padding: 1.5rem; }
.pen-edukasi-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; }
.pen-edukasi-title { display: flex; align-items: center; gap: 0.5rem; }
.pen-edukasi-title i { color: var(--primary); font-size: 0.85rem; }
.pen-edukasi-title h3 { font-weight: 800; font-size: 0.95rem; color: var(--text-main); }
.pen-edukasi-link { font-size: 0.7rem; color: var(--text-muted); font-weight: 600; text-decoration: none; transition: color 0.2s; display: inline-flex; align-items: center; gap: 0.3rem; }
.pen-edukasi-link:hover { color: var(--primary); }
.pen-edukasi-link i { font-size: 0.55rem; }

.pen-edukasi-list { display: grid; gap: 0.6rem; }
.pen-edukasi-item { padding: 0.8rem 1rem; background: var(--background); border-radius: var(--radius-sm); border-left: 3px solid var(--primary); display: flex; justify-content: space-between; align-items: center; }
.pen-edukasi-item-title { font-weight: 700; font-size: 0.85rem; color: var(--text-main); }
.pen-edukasi-item-date { font-size: 0.72rem; color: var(--text-muted); margin-top: 0.15rem; }
.pen-edukasi-item-badge { font-size: 0.6rem; font-weight: 700; padding: 0.2rem 0.55rem; border-radius: 99px; background: rgba(5,150,105,0.1); color: var(--primary); text-transform: uppercase; letter-spacing: 0.3px; white-space: nowrap; }

.pen-edukasi-empty { text-align: center; padding: 2rem 0; }
.pen-edukasi-empty i { font-size: 1.5rem; color: var(--text-muted); opacity: 0.2; margin-bottom: 0.75rem; display: block; }
.pen-edukasi-empty p { color: var(--text-muted); font-size: 0.85rem; font-weight: 500; margin-bottom: 0.75rem; }

.pen-harga-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1rem; }
.pen-harga-card { padding: 1.25rem; display: flex; flex-direction: column; gap: 0.5rem; }
.pen-harga-head { display: flex; align-items: center; gap: 0.6rem; }
.pen-harga-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
.pen-harga-label { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px; color: var(--text-muted); }
.pen-harga-price { font-size: 1.4rem; font-weight: 900; color: var(--text-main); letter-spacing: -0.02em; }
.pen-harga-change { font-size: 0.72rem; font-weight: 700; padding: 0.15rem 0.6rem; border-radius: 99px; background: rgba(5,150,105,0.1); color: #059669; width: fit-content; }
.pen-harga-btn { margin-top: 0.25rem; display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.5rem 1rem; background: var(--primary); color: #fff; border: none; border-radius: 8px; font-weight: 700; font-size: 0.75rem; cursor: pointer; transition: all 0.25s; width: 100%; justify-content: center; }
.pen-harga-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(5,150,105,0.25); }
.pen-harga-btn i { font-size: 0.7rem; }

</style>
@endpush

@push('scripts')
<script>
function initMarketChart(canvasId, priceId, changeId, commodity, color) {
    fetch('{{ url("api/market-prices") }}/' + commodity)
        .then(r => r.json())
        .then(data => {
            document.getElementById(priceId).textContent = 'Rp ' + Number(data.latest).toLocaleString('id-ID');
            const changeEl = document.getElementById(changeId);
            changeEl.textContent = data.changeLabel;
            const up = data.change >= 0;
            changeEl.style.background = up ? 'rgba(5,150,105,0.1)' : 'rgba(239,68,68,0.1)';
            changeEl.style.color = up ? '#10b981' : '#ef4444';

            const ctx = document.getElementById(canvasId).getContext('2d');
            const gradient = ctx.createLinearGradient(0, 0, 0, 155);
            gradient.addColorStop(0, color + '33');
            gradient.addColorStop(1, color + '00');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        data: data.prices,
                        borderColor: color,
                        backgroundColor: gradient,
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35,
                        pointRadius: 0,
                        pointHitRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(15,26,31,0.92)',
                            titleFont: { size: 11, weight: '700' },
                            bodyFont: { size: 12, weight: '600' },
                            padding: { x: 12, y: 8 },
                            cornerRadius: 8,
                            displayColors: false,
                            callbacks: { label: ctx => 'Rp ' + Number(ctx.parsed.y).toLocaleString('id-ID') }
                        }
                    },
                    scales: {
                        x: { display: true, grid: { display: false }, border: { display: false }, ticks: { maxTicksLimit: 5, color: '#637e84', font: { size: 9, weight: '500' }, maxRotation: 0 } },
                        y: { display: true, grid: { color: 'rgba(255,255,255,0.03)', drawBorder: false }, border: { display: false }, ticks: { color: '#637e84', font: { size: 9, weight: '500' }, maxTicksLimit: 5, callback: v => 'Rp' + Number(v).toLocaleString('id-ID') } }
                    },
                    interaction: { intersect: false, mode: 'index' },
                    animations: { tension: { duration: 1000, easing: 'easeOutQuart' } }
                }
            });
        });
}

document.addEventListener('DOMContentLoaded', () => {
    const charts = [
        { canvas: 'chartPadi', price: 'price-padi', change: 'change-padi', com: 'padi', color: '#34d399' },
    ];
    charts.forEach(c => initMarketChart(c.canvas, c.price, c.change, c.com, c.color));

    // Latest prices for management cards
    fetch('{{ url("api/market-prices") }}')
        .then(r => r.json())
        .then(data => {
            const fmt = (v) => 'Rp ' + Number(v).toLocaleString('id-ID');
            ['padi'].forEach(k => {
                const el = document.getElementById('harga-' + k);
                if (el) el.textContent = fmt(data[k] || 0);
            });
        });
});
</script>
@endpush
@endSection