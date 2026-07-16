@extends('layouts.native')

@section('content')
<div class="container farmer-dashboard-container">
    <div class="farmer-layout">
        @include('layouts.sidebar')

        <main class="farmer-main">
            <div class="animate-fade">

                <!-- WELCOME HEADER -->
                <div class="farmer-greeting-block">
                    <div class="farmer-greeting-row">
                        <div class="farmer-greeting-icon" id="greeting-icon"><i class="fas fa-sun"></i></div>
                        <div class="farmer-greeting-copy">
                            <h1 class="farmer-greeting-text">Selamat <span id="greeting-time">pagi</span>, {{ explode(' ', $user->name)[0] }}!</h1>
                            <p class="farmer-greeting-sub">Kelola harga, pantau stok, dan catat hasil panen Anda dengan mudah.</p>
                        </div>
                    </div>
                </div>

                <!-- STATS ROW -->
                <div class="farmer-stats-grid">
                    <a href="{{ route('profile.edit', ['menu' => 'incoming']) }}" class="farmer-stat-card-link">
                        <div class="farmer-stat-card">
                            <div class="farmer-stat-icon green">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div>
                                <div class="farmer-stat-value">{{ $myOrders->count() + $myBookings->count() }}</div>
                                <div class="farmer-stat-label">Total Pesanan</div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('harvest.index') }}" class="farmer-stat-card-link">
                        <div class="farmer-stat-card">
                            <div class="farmer-stat-icon amber">
                                <i class="fas fa-seedling"></i>
                            </div>
                            <div>
                                <div class="farmer-stat-value">{{ $harvestCount ?? \App\Models\Harvest::where('user_id', $user->id)->count() }}</div>
                                <div class="farmer-stat-label">Data Panen</div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('schedule.index') }}" class="farmer-stat-card-link">
                        <div class="farmer-stat-card">
                            <div class="farmer-stat-icon blue">
                                <i class="fas fa-sync-alt"></i>
                            </div>
                            <div>
                                <div class="farmer-stat-value">{{ $activeCyclesCount ?? \App\Models\FarmingCycle::where('user_id', $user->id)->where('status', 'active')->count() }}</div>
                                <div class="farmer-stat-label">Siklus Aktif</div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- WEATHER WIDGET -->
                <div class="farmer-weather" id="weatherWidget">
                    <div class="glass-card farmer-weather-card">
                        <div class="farmer-weather-row">
                            <div class="farmer-weather-primary">
                                <div class="farmer-weather-icon-wrap" id="weather-icon-wrap">
                                    <i class="fas fa-sun" id="weather-icon"></i>
                                </div>
                                <div>
                                    <div class="farmer-weather-temp-row">
                                        <span class="farmer-weather-temp" id="weather-temp">—</span>
                                        <span class="farmer-weather-unit">°C</span>
                                    </div>
                                    <div class="farmer-weather-desc" id="weather-desc">Memuat...</div>
                                </div>
                            </div>
                            <div class="farmer-weather-stats">
                                <div class="weather-stat">
                                    <div class="weather-stat-icon"><i class="fas fa-tint"></i></div>
                                    <div>
                                        <div class="weather-stat-val" id="weather-humidity">—</div>
                                        <div class="weather-stat-lbl">Kelembapan</div>
                                    </div>
                                </div>
                                <div class="weather-stat">
                                    <div class="weather-stat-icon"><i class="fas fa-wind"></i></div>
                                    <div>
                                        <div class="weather-stat-val" id="weather-wind">—</div>
                                        <div class="weather-stat-lbl">Angin (km/h)</div>
                                    </div>
                                </div>
                                <div class="weather-stat">
                                    <div class="weather-stat-icon"><i class="fas fa-cloud-rain"></i></div>
                                    <div>
                                        <div class="weather-stat-val" id="weather-rain">—</div>
                                        <div class="weather-stat-lbl">Curah Hujan (mm)</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="farmer-weather-tip" id="weather-tip">
                            <i class="fas fa-lightbulb"></i>
                            <span id="weather-tip-text">Tip pertanian akan muncul berdasarkan cuaca hari ini.</span>
                        </div>
                    </div>
                </div>

                <!-- MARKET PRICE CHARTS -->
                <div class="farmer-section">
                    <div class="farmer-section-head">
                        <div class="farmer-section-bar"></div>
                        <h3 class="farmer-section-title">Grafik Harga Pasar</h3>
                        <span class="farmer-section-source">Sumber: Dinas Pertanian Mojokerto</span>
                    </div>
                    <div class="farmer-chart-grid">
                        @php
                        $chartCommodities = [
                            ['key' => 'padi', 'id' => 'Padi', 'label' => 'Padi', 'color' => '#34d399'],
                        ];
                        @endphp
                        @foreach($chartCommodities as $c)
                        <div class="glass-card farmer-chart-card">
                            <div class="farmer-chart-head">
                                <div>
                                    <div class="farmer-chart-label">
                                        <span class="farmer-chart-dot" style="background: {{ $c['color'] }};"></span>
                                        <span>{{ $c['label'] }} /kg</span>
                                    </div>
                                    <div class="farmer-chart-price" id="price-{{ Str::lower($c['id']) }}">—</div>
                                </div>
                                <span class="farmer-chart-change" id="change-{{ Str::lower($c['id']) }}">—</span>
                            </div>
                            <div class="farmer-chart-canvas-wrap">
                                <canvas id="chart{{ $c['id'] }}"></canvas>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- BOTTOM GRID -->
                <div class="farmer-bottom-grid">
                    <div class="glass-card farmer-card">
                        <div class="farmer-card-head">
                            <div class="farmer-card-title">
                                <i class="fas fa-calendar-alt farmer-card-icon"></i>
                                <h3>Jadwal Terdekat</h3>
                            </div>
                            <a href="{{ route('schedule.index') }}" class="farmer-link">Lihat <i class="fas fa-arrow-right"></i></a>
                        </div>
                        <div class="farmer-card-body">
                            @php
                                $upcomingItems = \App\Models\ScheduleItem::where('user_id', $user->id)
                                    ->where('status', 'pending')
                                    ->where('date', '>=', now())
                                    ->orderBy('date')
                                    ->take(5)
                                    ->get();
                            @endphp
                            @forelse($upcomingItems as $item)
                            <div class="farmer-card-item">
                                <div>
                                    <div class="farmer-card-item-title">{{ $item->activity }}</div>
                                    <div class="farmer-card-item-meta">{{ $item->date->format('d M Y') }} @if($item->time) | {{ $item->time }} @endif</div>
                                </div>
                                <span class="farmer-badge-warning">{{ $item->stage->name ?? '-' }}</span>
                            </div>
                            @empty
                            <div class="farmer-card-empty">
                                <i class="fas fa-calendar-times farmer-empty-icon"></i>
                                <p>Belum ada jadwal</p>
                                <a href="{{ route('schedule.index') }}" class="btn btn-primary btn-sm farmer-empty-btn">Buat Jadwal</a>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="glass-card farmer-card">
                        <div class="farmer-card-head">
                            <div class="farmer-card-title">
                                <i class="fas fa-shopping-bag farmer-card-icon"></i>
                                <h3>Pesanan Terakhir</h3>
                            </div>
                            <a href="{{ route('profile.edit', ['menu' => 'orders']) }}" class="farmer-link">Lihat <i class="fas fa-arrow-right"></i></a>
                        </div>
                        <div class="farmer-card-body">
                            @forelse($myOrders->take(4) as $ord)
                            <div class="farmer-card-item">
                                <div class="farmer-card-item-left">
                                    <div class="farmer-card-item-box">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <div class="farmer-card-item-info">
                                        <div class="farmer-card-item-title truncate">{{ $ord->product->name ?? '-' }}</div>
                                        <div class="farmer-card-item-meta">{{ $ord->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                <div class="farmer-card-item-right">
                                    <div class="farmer-card-price">Rp{{ number_format($ord->total_price, 0, ',', '.') }}</div>
                                    <span class="farmer-badge-{{ $ord->status === 'pending' ? 'warning' : 'success' }}">{{ $ord->status }}</span>
                                </div>
                            </div>
                            @empty
                            <div class="farmer-card-empty">
                                <i class="fas fa-box-open farmer-empty-icon"></i>
                                <p>Belum ada pesanan</p>
                                <a href="{{ route('marketplace.index') }}" class="btn btn-primary btn-sm farmer-empty-btn">Belanja</a>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

@push('styles')
<style>
/* === LAYOUT === */
.farmer-layout {
    display: grid;
    grid-template-columns: 260px 1fr;
    gap: 2rem;
    align-items: start;
}
.farmer-main {
    max-width: 960px;
}
.farmer-dashboard-container {
    padding: 8rem 1rem 4rem;
    min-height: 100vh;
}

/* === GREETING === */
.farmer-greeting-row {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    margin-bottom: 1.25rem;
}
.farmer-greeting-icon {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    box-shadow: 0 6px 20px rgba(5,150,105,0.25);
    flex-shrink: 0;
}
.farmer-greeting-icon i { color: white; }
.farmer-greeting-copy { min-width: 0; }
.farmer-greeting-text {
    font-size: 1.65rem;
    font-weight: 900;
    color: var(--text-main);
    letter-spacing: -0.02em;
    line-height: 1.25;
}
.farmer-greeting-sub {
    color: var(--text-secondary);
    font-size: 0.92rem;
    margin-top: 0.2rem;
    line-height: 1.5;
}

/* === STATS === */
.farmer-stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
}
.farmer-stat-card-link {
    text-decoration: none;
    display: block;
}
.farmer-stat-card {
    background: var(--surface);
    border: 1px solid var(--border-color);
    border-radius: 14px;
    padding: 1.35rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.1rem;
    transition: all 0.3s cubic-bezier(0.22,1,0.36,1);
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.farmer-stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 14px 30px rgba(0,0,0,0.08);
}
.farmer-stat-icon {
    width: 54px;
    height: 54px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    flex-shrink: 0;
}
.farmer-stat-icon.green { background: rgba(46,125,50,0.1); color: #2E7D32; }
.farmer-stat-icon.amber { background: rgba(249,168,37,0.1); color: #F9A825; }
.farmer-stat-icon.blue  { background: rgba(25,118,210,0.1); color: #1976D2; }
.farmer-stat-value {
    font-size: 1.8rem;
    font-weight: 900;
    color: var(--text-main);
    line-height: 1.1;
    letter-spacing: -0.02em;
}
.farmer-stat-label {
    font-size: 0.8rem;
    color: var(--text-muted);
    font-weight: 600;
    margin-top: 0.15rem;
}

/* === WEATHER === */
.farmer-weather { margin-bottom: 2rem; }
.farmer-weather-card {
    padding: 1.25rem 1.5rem;
    border-radius: var(--radius-md);
}
.farmer-weather-row {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    flex-wrap: wrap;
}
.farmer-weather-primary {
    display: flex;
    align-items: center;
    gap: 1rem;
}
.farmer-weather-icon-wrap {
    width: 54px;
    height: 54px;
    border-radius: 16px;
    background: rgba(245,158,11,0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #f59e0b;
    flex-shrink: 0;
}
.farmer-weather-temp-row {
    display: flex;
    align-items: baseline;
    gap: 0.25rem;
}
.farmer-weather-temp {
    font-size: 2rem;
    font-weight: 900;
    color: var(--text-main);
    letter-spacing: -0.03em;
}
.farmer-weather-unit {
    font-size: 0.8rem;
    color: var(--text-muted);
    font-weight: 600;
}
.farmer-weather-desc {
    font-size: 0.82rem;
    color: var(--text-secondary);
    font-weight: 600;
}
.farmer-weather-stats {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
    margin-left: auto;
}
.farmer-weather .weather-stat {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.farmer-weather .weather-stat-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: rgba(16,185,129,0.07);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    font-size: 0.85rem;
    flex-shrink: 0;
}
.farmer-weather .weather-stat-val {
    font-weight: 800;
    font-size: 0.92rem;
    color: var(--text-main);
}
.farmer-weather .weather-stat-lbl {
    font-size: 0.62rem;
    color: var(--text-muted);
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}
.farmer-weather-tip {
    margin-top: 0.85rem;
    padding: 0.6rem 1rem;
    background: rgba(16,185,129,0.06);
    border-radius: 10px;
    font-size: 0.82rem;
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.farmer-weather-tip i { color: #f59e0b; }

/* === SECTION === */
.farmer-section {
    margin-bottom: 2.5rem;
}
.farmer-section-head {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    margin-bottom: 1.25rem;
}
.farmer-section-bar {
    width: 3px;
    height: 20px;
    background: var(--primary-gradient);
    border-radius: 99px;
    flex-shrink: 0;
}
.farmer-section-title {
    font-weight: 900;
    font-size: 1rem;
    color: var(--text-main);
}
.farmer-section-source {
    font-size: 0.7rem;
    color: var(--text-muted);
    font-weight: 600;
    margin-left: auto;
}

/* === CHARTS === */
.farmer-chart-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
}
.farmer-chart-card {
    padding: 1.25rem;
}
.farmer-chart-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.75rem;
}
.farmer-chart-label {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.65rem;
    color: var(--text-muted);
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}
.farmer-chart-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    display: inline-block;
    flex-shrink: 0;
}
.farmer-chart-price {
    font-size: 1.3rem;
    font-weight: 900;
    color: var(--text-main);
    margin-top: 0.2rem;
    letter-spacing: -0.02em;
}
.farmer-chart-change {
    font-size: 0.72rem;
    font-weight: 700;
    padding: 0.15rem 0.6rem;
    border-radius: 99px;
    background: rgba(5,150,105,0.1);
    color: #059669;
    white-space: nowrap;
}
.farmer-chart-canvas-wrap {
    position: relative;
    height: 130px;
}

/* === BOTTOM CARDS === */
.farmer-bottom-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
    margin-bottom: 3.5rem;
}
.farmer-card {
    padding: 1.5rem;
}
.farmer-card-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.25rem;
}
.farmer-card-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.farmer-card-icon {
    color: var(--primary);
    font-size: 0.85rem;
}
.farmer-card-title h3 {
    font-weight: 800;
    font-size: 0.95rem;
    color: var(--text-main);
    margin: 0;
}
.farmer-link {
    font-size: 0.7rem;
    color: var(--text-muted);
    font-weight: 600;
    text-decoration: none;
    transition: color 0.2s;
}
.farmer-link:hover { color: var(--primary); }
.farmer-link i { font-size: 0.55rem; }
.farmer-card-body {
    display: grid;
    gap: 0.6rem;
}
.farmer-card-item {
    padding: 0.8rem 1rem;
    background: var(--background);
    border-radius: var(--radius-sm);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 0.75rem;
}
.farmer-card-item-title {
    font-weight: 700;
    font-size: 0.85rem;
    color: var(--text-main);
}
.farmer-card-item-title.truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.farmer-card-item-meta {
    font-size: 0.72rem;
    color: var(--text-muted);
    margin-top: 0.15rem;
}
.farmer-card-item-left {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    min-width: 0;
    flex: 1;
}
.farmer-card-item-box {
    width: 34px;
    height: 34px;
    background: rgba(16,185,129,0.08);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    font-size: 0.75rem;
    flex-shrink: 0;
}
.farmer-card-item-info {
    min-width: 0;
    flex: 1;
}
.farmer-card-item-right {
    text-align: right;
    flex-shrink: 0;
}
.farmer-card-price {
    font-weight: 800;
    font-size: 0.82rem;
    color: var(--primary);
    letter-spacing: -0.01em;
}
.farmer-card-empty {
    text-align: center;
    padding: 2rem 0;
}
.farmer-empty-icon {
    font-size: 1.5rem;
    color: var(--text-muted);
    opacity: 0.2;
    margin-bottom: 0.75rem;
}
.farmer-card-empty p {
    color: var(--text-muted);
    font-size: 0.85rem;
    font-weight: 500;
    margin: 0 0 0.75rem;
}
.farmer-empty-btn {
    padding: 0.5rem 1.25rem;
    font-size: 0.8rem;
}
.farmer-badge-warning {
    font-size: 0.6rem;
    font-weight: 700;
    padding: 0.2rem 0.55rem;
    border-radius: 99px;
    background: rgba(245,158,11,0.1);
    color: var(--warning);
    text-transform: uppercase;
    letter-spacing: 0.3px;
    white-space: nowrap;
}
.farmer-badge-success {
    font-size: 0.6rem;
    font-weight: 700;
    padding: 0.12rem 0.5rem;
    border-radius: 99px;
    background: rgba(5,150,105,0.1);
    color: #10b981;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    display: inline-block;
    margin-top: 0.15rem;
}
.farmer-badge-pending {
    font-size: 0.6rem;
    font-weight: 700;
    padding: 0.12rem 0.5rem;
    border-radius: 99px;
    background: rgba(245,158,11,0.1);
    color: var(--warning);
    text-transform: uppercase;
    letter-spacing: 0.3px;
    display: inline-block;
    margin-top: 0.15rem;
}

/* === RESPONSIVE === */
@media (max-width: 1024px) {
    .farmer-dashboard-container { padding-top: 7rem; }
}
@media (max-width: 768px) {
    .farmer-layout { grid-template-columns: 1fr; }
    .farmer-dashboard-container { padding-top: 6.5rem; }
    .farmer-stats-grid { grid-template-columns: 1fr 1fr; }
    .farmer-stat-card-link:nth-child(3) { grid-column: 1 / -1; }
    .farmer-chart-grid { grid-template-columns: 1fr; }
    .farmer-bottom-grid { grid-template-columns: 1fr; margin-bottom: 3rem; }
}
@media (max-width: 680px) {
    .farmer-dashboard-container { padding-top: 6rem; }
    .farmer-greeting-icon { width: 42px; height: 42px; font-size: 1.1rem; border-radius: 14px; }
    .farmer-greeting-text { font-size: 1.2rem; }
    .farmer-greeting-sub { font-size: 0.8rem; }
    .farmer-greeting-row { gap: 1rem; margin-bottom: 1rem; }
    .farmer-stat-value { font-size: 1.4rem; }
    .farmer-stat-card { padding: 1rem 1.25rem; gap: 0.85rem; }
    .farmer-stat-icon { width: 44px; height: 44px; font-size: 1.05rem; }
    .farmer-weather-row { gap: 1rem; }
    .farmer-weather-stats { margin-left: 0; gap: 1rem; }
    .farmer-weather .weather-stat { min-width: 75px; }
    .farmer-bottom-grid { margin-bottom: 2.5rem; }
}
@media (max-width: 480px) {
    .farmer-dashboard-container { padding: 5.5rem 0.75rem 3rem; }
    .farmer-stats-grid { grid-template-columns: 1fr; gap: 0.75rem; margin-bottom: 1.5rem; }
    .farmer-stat-card-link:nth-child(3) { grid-column: auto; }
    .farmer-stat-value { font-size: 1.2rem; }
    .farmer-stat-card { padding: 0.85rem 1rem; gap: 0.75rem; }
    .farmer-stat-icon { width: 38px; height: 38px; font-size: 0.95rem; }
    .farmer-greeting-icon { width: 38px; height: 38px; font-size: 1rem; border-radius: 12px; }
    .farmer-greeting-text { font-size: 1.05rem; }
    .farmer-greeting-row { gap: 0.75rem; }
    .farmer-weather-icon-wrap { width: 46px; height: 46px; font-size: 1.25rem; }
    .farmer-weather-temp { font-size: 1.6rem; }
    .farmer-weather-stats { gap: 0.75rem; }
    .farmer-weather .weather-stat { min-width: 65px; }
    .farmer-weather .weather-stat-icon { width: 32px; height: 32px; font-size: 0.75rem; }
    .farmer-weather .weather-stat-val { font-size: 0.82rem; }
    .farmer-bottom-grid { gap: 1rem; margin-bottom: 2rem; }
    .farmer-card { padding: 1.25rem; }
    .farmer-card-item { flex-wrap: wrap; }
    .farmer-chart-price { font-size: 1.1rem; }
}
</style>
@endpush

@push('scripts')
<script>
function initMarketChart(canvasId, priceId, changeId, commodity, color, bgColor) {
    if (!document.getElementById(canvasId)) return;
    fetch('{{ url("api/market-prices") }}/' + commodity)
        .then(r => r.json())
        .then(data => {
            const priceEl = document.getElementById(priceId);
            if (priceEl) priceEl.textContent = 'Rp ' + Number(data.latest).toLocaleString('id-ID');
            const changeEl = document.getElementById(changeId);
            if (changeEl) {
                changeEl.textContent = data.changeLabel;
                const up = data.change >= 0;
                changeEl.style.background = up ? 'rgba(5,150,105,0.1)' : 'rgba(239,68,68,0.1)';
                changeEl.style.color = up ? '#10b981' : '#ef4444';
            }

            const canvas = document.getElementById(canvasId);
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            const gradient = ctx.createLinearGradient(0, 0, 0, canvas.parentElement.offsetHeight);
            gradient.addColorStop(0, color + '25');
            gradient.addColorStop(1, color + '00');

            const fmtDate = (label) => {
                var p = label.split('-');
                if (p.length === 3) {
                    var m = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
                    return p[2] + ' ' + m[parseInt(p[1])-1] + ' ' + p[0];
                }
                return label;
            };
            const fmtPrice = (v) => 'Rp' + Number(v).toLocaleString('id-ID');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        data: data.prices,
                        borderColor: color,
                        backgroundColor: gradient,
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: color,
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(15,26,31,0.93)',
                            titleFont: { size: 11, weight: '700' },
                            bodyFont: { size: 12, weight: '700' },
                            padding: { x: 12, y: 8 },
                            cornerRadius: 8,
                            displayColors: false,
                            callbacks: {
                                title: items => fmtDate(items[0].label),
                                label: ctx => fmtPrice(ctx.parsed.y)
                            }
                        }
                    },
                    scales: {
                        x: { display: true, grid: { display: false }, border: { display: false }, ticks: { maxTicksLimit: 6, color: '#94a3b8', font: { size: 9, weight: '500' }, maxRotation: 0, callback: function(v) { return fmtDate(this.getLabelForValue(v)); } } },
                        y: { display: true, grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false }, border: { display: false }, ticks: { color: '#94a3b8', font: { size: 9, weight: '500' }, maxTicksLimit: 5, callback: v => fmtPrice(v) } }
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
    charts.forEach(c => initMarketChart(c.canvas, c.price, c.change, c.com, c.color, ''));

    const hour = new Date().getHours();
    let t = 'pagi', iconClass = 'fa-sun';
    if (hour >= 12 && hour < 15) { t = 'siang'; iconClass = 'fa-cloud-sun'; }
    else if (hour >= 15 && hour < 19) { t = 'sore'; iconClass = 'fa-cloud-sun'; }
    else if (hour >= 19 || hour < 5) { t = 'malam'; iconClass = 'fa-moon'; }
    const g = document.getElementById('greeting-time'); if (g) g.textContent = t;
    const h = document.getElementById('greeting-icon');
    if (h) {
        h.innerHTML = '<i class="fas ' + iconClass + '"></i>';
    }

    // Weather Widget
    const ww = document.getElementById('weatherWidget');
    if (ww) {
        const lat = -7.4726, lon = 112.4381;
        fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current=temperature_2m,relative_humidity_2m,apparent_temperature,weather_code,wind_speed_10m,precipitation&daily=precipitation_sum&timezone=Asia%2FJakarta`)
            .then(r => r.json())
            .then(d => {
                const c = d.current;
                const temp = Math.round(c.temperature_2m);
                const feels = Math.round(c.apparent_temperature);
                const humid = c.relative_humidity_2m;
                const wind = Math.round(c.wind_speed_10m);
                const rain = c.precipitation || 0;
                const code = c.weather_code;

                let cond = 'Cerah Berawan', icon = 'fa-cloud-sun', col = '#f59e0b', tip = 'Cocok untuk aktivitas di sawah.';
                if (code === 0) { cond = 'Cerah'; icon = 'fa-sun'; tip = 'Waktu tepat untuk menjemur gabah atau menyemprot tanaman.'; }
                else if (code >= 1 && code <= 3) { cond = 'Cerah Berawan'; tip = 'Cuaca baik untuk bertani. Semprot pestisida di pagi hari.'; }
                else if (code === 45 || code === 48) { cond = 'Berkabut'; col = '#94a3b8'; icon = 'fa-smog'; tip = 'Kabut tipis. Hati-hati embun berlebih pada tanaman.'; }
                else if (code >= 51 && code <= 67) { cond = 'Gerimis / Hujan'; col = '#3b82f6'; icon = 'fa-cloud-rain'; tip = 'Hujan ringan bagus untuk pupuk. Kurangi jadwal penyemprotan.'; }
                else if (code >= 80 && code <= 82) { cond = 'Hujan Deras'; col = '#3b82f6'; icon = 'fa-cloud-showers-heavy'; tip = 'Hujan deras. Periksa drainase sawah agar tidak tergenang.'; }
                else if (code >= 95) { cond = 'Hujan Badai'; col = '#6366f1'; icon = 'fa-cloud-bolt'; tip = 'Cuaca ekstrem. Hindari aktivitas di lahan terbuka.'; }

                document.getElementById('weather-temp').textContent = temp;
                document.getElementById('weather-desc').textContent = cond + ' (terasa ' + feels + '°C)';
                document.getElementById('weather-humidity').textContent = humid + '%';
                document.getElementById('weather-wind').textContent = wind;
                document.getElementById('weather-rain').textContent = rain.toFixed(1);
                document.getElementById('weather-tip-text').textContent = tip;
                const wi = document.getElementById('weather-icon');
                if (wi) { wi.className = 'fas ' + icon; wi.style.color = col; }
                ww.style.display = 'block';
            })
            .catch(() => {
                document.getElementById('weather-temp').textContent = '28';
                document.getElementById('weather-desc').textContent = 'Cerah Berawan';
                document.getElementById('weather-humidity').textContent = '65%';
                document.getElementById('weather-wind').textContent = '12';
                document.getElementById('weather-rain').textContent = '0.0';
                ww.style.display = 'block';
            });
    }
});
</script>
@endpush
@endsection
