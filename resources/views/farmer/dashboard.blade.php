@extends('layouts.native')

@section('content')
<div class="container" style="padding-top: 7rem; min-height: 100vh;">
    <div style="display: grid; grid-template-columns: 260px 1fr; gap: 2rem; align-items: start;">
        @include('layouts.sidebar')

        <main style="max-width: 960px;">
            <div class="animate-fade">

                <!-- WELCOME HEADER -->
                <div style="margin-bottom: 2.5rem;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                        <div style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; box-shadow: 0 4px 16px rgba(5,150,105,0.2);" id="greeting-icon">🌤️</div>
                        <div>
                            <h1 style="font-size: 1.6rem; font-weight: 900; color: var(--text-main); letter-spacing: -0.02em;">Selamat <span id="greeting-time">pagi</span>, {{ explode(' ', $user->name)[0] }}</h1>
                            <p style="color: var(--text-secondary); font-size: 0.9rem; margin-top: 0.15rem;">Kelola jadwal tanam, pantau harga, dan catat hasil panen Anda.</p>
                        </div>
                    </div>
                </div>

                <!-- STATS ROW -->
                <div class="farmer-stats-grid">
                    <div class="farmer-stat-card">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div class="farmer-stat-icon" style="background: linear-gradient(135deg, rgba(16,185,129,0.12), rgba(16,185,129,0.04)); color: var(--primary);">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div>
                                <div class="farmer-stat-value">{{ $myOrders->count() + $myBookings->count() }}</div>
                                <div class="farmer-stat-label">Total Pesanan</div>
                            </div>
                        </div>
                    </div>
                    <div class="farmer-stat-card">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div class="farmer-stat-icon" style="background: linear-gradient(135deg, rgba(59,130,246,0.12), rgba(59,130,246,0.04)); color: #3b82f6;">
                                <i class="fas fa-seedling"></i>
                            </div>
                            <div>
                                <div class="farmer-stat-value">{{ \App\Models\Harvest::where('user_id', $user->id)->count() }}</div>
                                <div class="farmer-stat-label">Data Panen</div>
                            </div>
                        </div>
                        <a href="{{ route('harvest.index') }}" class="farmer-stat-link"><i class="fas fa-arrow-right"></i></a>
                    </div>
                    <div class="farmer-stat-card">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div class="farmer-stat-icon" style="background: linear-gradient(135deg, rgba(245,158,11,0.12), rgba(245,158,11,0.04)); color: var(--warning);">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div>
                                <div class="farmer-stat-value">{{ \App\Models\FarmingCycle::where('user_id', $user->id)->where('status', 'active')->count() }}</div>
                                <div class="farmer-stat-label">Siklus Aktif</div>
                            </div>
                        </div>
                        <a href="{{ route('schedule.index') }}" class="farmer-stat-link"><i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <!-- MARKET PRICE CHARTS -->
                <div style="margin-bottom: 2.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 1.25rem;">
                        <div style="width: 3px; height: 18px; background: var(--primary-gradient); border-radius: 99px;"></div>
                        <h3 style="font-weight: 900; font-size: 1rem; color: var(--text-main); letter-spacing: -0.01em;">Grafik Harga Pasar</h3>
                        <span style="font-size: 0.7rem; color: var(--text-muted); font-weight: 600; margin-left: auto;">Sumber: Dinas Pertanian Mojokerto</span>
                    </div>
                    <div class="farmer-chart-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        @php
                        $chartCommodities = [
                            ['key' => 'padi', 'id' => 'Padi', 'label' => 'Padi', 'color' => '#34d399'],
                        ];
                        @endphp
                        @foreach($chartCommodities as $c)
                        <div class="glass-card farmer-chart-card" style="padding: 1.25rem;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem;">
                                <div>
                                    <div style="display: flex; align-items: center; gap: 0.35rem;">
                                        <span style="width: 6px; height: 6px; background: {{ $c['color'] }}; border-radius: 50%; display: inline-block;"></span>
                                        <span style="font-size: 0.65rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px;">{{ $c['label'] }} /kg</span>
                                    </div>
                                    <div style="font-size: 1.25rem; font-weight: 900; color: var(--text-main); margin-top: 0.2rem; letter-spacing: -0.02em;" id="price-{{ Str::lower($c['id']) }}">—</div>
                                </div>
                                <span id="change-{{ Str::lower($c['id']) }}" style="font-size: 0.72rem; font-weight: 700; padding: 0.15rem 0.6rem; border-radius: 99px; background: rgba(5,150,105,0.1); color: #059669;">—</span>
                            </div>
                            <div style="position: relative; height: 130px;">
                                <canvas id="chart{{ $c['id'] }}"></canvas>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- BOTTOM GRID -->
                <div class="farmer-bottom-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                    <div class="glass-card" style="padding: 1.5rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-calendar-alt" style="color: var(--primary); font-size: 0.85rem;"></i>
                                <h3 style="font-weight: 800; font-size: 0.95rem; color: var(--text-main);">Jadwal Terdekat</h3>
                            </div>
                            <a href="{{ route('schedule.index') }}" class="farmer-link">Lihat <i class="fas fa-arrow-right"></i></a>
                        </div>
                        <div style="display: grid; gap: 0.6rem;">
                            @php
                                $upcomingItems = \App\Models\ScheduleItem::where('user_id', $user->id)
                                    ->where('status', 'pending')
                                    ->where('date', '>=', now())
                                    ->orderBy('date')
                                    ->take(5)
                                    ->get();
                            @endphp
                            @forelse($upcomingItems as $item)
                            <div style="padding: 0.8rem 1rem; background: var(--background); border-radius: var(--radius-sm); border-left: 3px solid var(--primary); display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <div style="font-weight: 700; font-size: 0.85rem; color: var(--text-main);">{{ $item->activity }}</div>
                                    <div style="font-size: 0.72rem; color: var(--text-muted); margin-top: 0.15rem;">{{ $item->date->format('d M Y') }} @if($item->time) | {{ $item->time }} @endif</div>
                                </div>
                                <span style="font-size: 0.6rem; font-weight: 700; padding: 0.2rem 0.55rem; border-radius: 99px; background: rgba(245,158,11,0.1); color: var(--warning); text-transform: uppercase; letter-spacing: 0.3px;">{{ $item->stage->name ?? '-' }}</span>
                            </div>
                            @empty
                            <div style="text-align: center; padding: 2rem 0;">
                                <i class="fas fa-calendar-times" style="font-size: 1.5rem; color: var(--text-muted); opacity: 0.2; margin-bottom: 0.75rem; display: block;"></i>
                                <p style="color: var(--text-muted); font-size: 0.85rem; font-weight: 500;">Belum ada jadwal</p>
                                <a href="{{ route('schedule.index') }}" class="btn btn-primary btn-sm" style="margin-top: 0.75rem; padding: 0.5rem 1.25rem; font-size: 0.8rem;">Buat Jadwal</a>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="glass-card" style="padding: 1.5rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-shopping-bag" style="color: var(--primary); font-size: 0.85rem;"></i>
                                <h3 style="font-weight: 800; font-size: 0.95rem; color: var(--text-main);">Pesanan Terakhir</h3>
                            </div>
                            <a href="{{ route('profile.edit', ['menu' => 'orders']) }}" class="farmer-link">Lihat <i class="fas fa-arrow-right"></i></a>
                        </div>
                        <div style="display: grid; gap: 0.6rem;">
                            @forelse($myOrders->take(4) as $ord)
                            <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.7rem 1rem; background: var(--background); border-radius: var(--radius-sm);">
                                <div style="display: flex; align-items: center; gap: 0.75rem; min-width: 0;">
                                    <div style="width: 32px; height: 32px; background: rgba(16,185,129,0.08); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 0.75rem; flex-shrink: 0;">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <div style="min-width: 0;">
                                        <div style="font-weight: 700; font-size: 0.82rem; color: var(--text-main); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $ord->product->name ?? '-' }}</div>
                                        <div style="font-size: 0.68rem; color: var(--text-muted);">{{ $ord->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                <div style="text-align: right; flex-shrink: 0;">
                                    <div style="font-weight: 800; font-size: 0.82rem; color: var(--primary); letter-spacing: -0.01em;">Rp{{ number_format($ord->total_price, 0, ',', '.') }}</div>
                                    <span style="font-size: 0.6rem; font-weight: 700; padding: 0.12rem 0.5rem; border-radius: 99px; background: {{ $ord->status === 'pending' ? 'rgba(245,158,11,0.1)' : 'rgba(5,150,105,0.1)' }}; color: {{ $ord->status === 'pending' ? 'var(--warning)' : '#10b981' }}; text-transform: uppercase; letter-spacing: 0.3px; display: inline-block; margin-top: 0.15rem;">{{ $ord->status }}</span>
                                </div>
                            </div>
                            @empty
                            <div style="text-align: center; padding: 2rem 0;">
                                <i class="fas fa-box-open" style="font-size: 1.5rem; color: var(--text-muted); opacity: 0.2; margin-bottom: 0.75rem; display: block;"></i>
                                <p style="color: var(--text-muted); font-size: 0.85rem; font-weight: 500;">Belum ada pesanan</p>
                                <a href="{{ route('marketplace.index') }}" class="btn btn-primary btn-sm" style="margin-top: 0.75rem; padding: 0.5rem 1.25rem; font-size: 0.8rem;">Belanja</a>
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
.farmer-stat-card { background: var(--surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 1.5rem; transition: all 0.35s cubic-bezier(0.22,1,0.36,1); position: relative; overflow: hidden; }
.farmer-stat-card:hover { transform: translateY(-3px); box-shadow: 0 12px 30px rgba(0,0,0,0.06); }
.farmer-stat-card a[style*="position: absolute"]:hover { color: var(--primary) !important; }
.farmer-stat-icon { width: 46px; height: 46px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
.farmer-stat-value { font-size: 1.6rem; font-weight: 900; color: var(--text-main); line-height: 1.1; letter-spacing: -0.02em; }
.farmer-stat-label { font-size: 0.7rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; margin-top: 0.15rem; }
.farmer-stat-link { position: absolute; top: 1.25rem; right: 1.25rem; font-size: 0.65rem; color: var(--text-muted); text-decoration: none; transition: color 0.2s; }
.farmer-stat-link:hover { color: var(--primary) !important; }
.farmer-link { font-size: 0.7rem; color: var(--text-muted); font-weight: 600; text-decoration: none; transition: color 0.2s; }
.farmer-link:hover { color: var(--primary) !important; }
 .farmer-link i { font-size: 0.55rem; }
.farmer-chart-card { padding: 1.25rem; }
</style>
@endpush

@push('scripts')
<script>
function initMarketChart(canvasId, priceId, changeId, commodity, color, bgColor) {
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
    charts.forEach(c => initMarketChart(c.canvas, c.price, c.change, c.com, c.color, ''));

    const hour = new Date().getHours();
    let t = 'pagi', i = '🌤️';
    if (hour >= 11 && hour < 15) { t = 'siang'; i = '☀️'; }
    else if (hour >= 15) { t = 'sore'; i = '🌅'; }
    const g = document.getElementById('greeting-time'); if (g) g.textContent = t;
    const h = document.getElementById('greeting-icon');
    if (h) {
        h.textContent = '';
        h.innerHTML = i;
    }
});
</script>
@endpush
@endsection
