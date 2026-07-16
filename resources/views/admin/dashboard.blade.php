@extends('layouts.native')

@section('content')
<div class="container" style="padding-top: 8rem; padding-bottom: 4rem; min-height: 100vh;">
    <div class="admin-header-row" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem;">
        <div>
            <h1 class="admin-title">Admin Central</h1>
            <p class="admin-subtitle">Panel kendali utama ekosistem AgriMojokerto.</p>
        </div>
        <div style="display: flex; gap: 1rem;">
            <div class="glass-card" style="padding: 1rem 2rem; display: flex; align-items: center; gap: 1rem;">
                <div style="width: 10px; height: 10px; background: var(--success); border-radius: 50%; box-shadow: 0 0 10px var(--success);"></div>
                <span style="font-weight: 800; font-size: 0.9rem;">Sistem Online</span>
            </div>
        </div>
    </div>

    <!-- STATS GRID -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 3rem;">
        <a href="{{ route('admin.users.index') }}" class="stat-card-link">
            <div class="stat-card">
                <div class="icon" style="background: rgba(16, 185, 129, 0.1); color: var(--primary);"><i class="fas fa-users-cog"></i></div>
                <div class="value">{{ $stats['total_users'] }}</div>
                <div class="label">Total Pengguna</div>
            </div>
        </a>
        <a href="{{ route('admin.products.index') }}" class="stat-card-link">
            <div class="stat-card">
                <div class="icon" style="background: rgba(59, 130, 246, 0.1); color: var(--secondary);"><i class="fas fa-box-open"></i></div>
                <div class="value">{{ $stats['approved_products'] }}</div>
                <div class="label">Produk Marketplace</div>
            </div>
        </a>
        <a href="{{ route('admin.equipments.index') }}" class="stat-card-link">
            <div class="stat-card">
                <div class="icon" style="background: rgba(245, 158, 11, 0.1); color: var(--warning);"><i class="fas fa-tractor"></i></div>
                <div class="value">{{ $stats['approved_equipment'] }}</div>
                <div class="label">Alat Pertanian</div>
            </div>
        </a>
        <div class="stat-card">
            <div class="icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;"><i class="fas fa-shopping-cart"></i></div>
            <div class="value">{{ $stats['total_orders'] }}</div>
            <div class="label">Transaksi Berjalan</div>
        </div>
    </div>

  <!-- MARKET PRICE CHARTS -->
  <div style="margin-bottom: 2.5rem;">
      <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 1.25rem;">
          <div style="width: 3px; height: 18px; background: var(--primary-gradient); border-radius: 99px;"></div>
          <h3 style="font-weight: 900; font-size: 1rem; color: var(--text-main); letter-spacing: -0.01em;">Grafik Harga Pasar</h3>
          <span style="font-size: 0.7rem; color: var(--text-muted); font-weight: 600; margin-left: auto;">Sumber: Dinas Pertanian Mojokerto</span>
      </div>
      <div style="display: grid; grid-template-columns: 1fr; gap: 1.25rem;">
          @php
          $chartCommodities = [
              ['key' => 'padi', 'id' => 'Padi', 'label' => 'Padi', 'color' => '#34d399'],
          ];
          @endphp
          @foreach($chartCommodities as $c)
          <div class="glass-card" style="padding: 1.25rem;">
              <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem;">
                  <div>
                      <div style="display: flex; align-items: center; gap: 0.35rem;">
                          <span style="width: 6px; height: 6px; background: {{ $c['color'] }}; border-radius: 50%; display: inline-block;"></span>
                          <span style="font-size: 0.65rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px;">{{ $c['label'] }} /kg</span>
                      </div>
                      <div style="font-size: 1.25rem; font-weight: 900; color: var(--text-main); margin-top: 0.2rem; letter-spacing: -0.02em;" id="price-{{ $c['key'] }}-admin">—</div>
                  </div>
                  <span id="change-{{ $c['key'] }}-admin" style="font-size: 0.72rem; font-weight: 700; padding: 0.15rem 0.6rem; border-radius: 99px; background: rgba(5,150,105,0.1); color: #059669;">—</span>
              </div>
              <div style="position: relative; height: 160px;">
                  <canvas id="chart{{ $c['id'] }}Admin"></canvas>
              </div>
          </div>
          @endforeach
      </div>
  </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem;">
        <!-- APPROVAL QUEUE -->
        <div>
            <div class="glass-card" style="padding: 2.5rem; height: 100%;">
                <h3 style="font-weight: 900; margin-bottom: 2rem; display: flex; align-items: center; gap: 1rem;">
                    <i class="fas fa-clock" style="color: var(--warning);"></i> Menunggu Persetujuan
                    @if($totalPending > 0)
                        <span class="badge" style="background: var(--danger); color: white; margin-left: auto;">{{ $totalPending }} ACTION REQUIRED</span>
                    @endif
                </h3>

                <div style="display: grid; gap: 1rem;">
                    @foreach($pendingProducts->merge($pendingEquipment) as $item)
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 1.5rem; background: var(--background); border-radius: 18px; border: 1px solid var(--border-color);">
                        <div style="display: flex; align-items: center; gap: 1.5rem;">
                            <div style="width: 50px; height: 50px; background: var(--surface-2); border-radius: 14px; display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 1.25rem; font-weight: 800;">
                                {{ strtoupper(substr($item->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight: 800; font-size: 1.1rem; color: var(--text-main);">{{ $item->name }}</div>
                                <div style="font-size: 0.85rem; color: var(--text-muted);">
                                    Oleh: <strong>{{ $item->user->name }}</strong> | Kategori: {{ ucfirst($item->category ?? 'Alat') }}
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; gap: 0.75rem;">
                            <form action="{{ isset($item->category) ? route('admin.product.approve', $item->id) : route('admin.equipment.approve', $item->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm" style="padding: 0.6rem 1.5rem;">Setujui</button>
                            </form>
                        </div>
                    </div>
                    @endforeach

                    @if($totalPending === 0)
                    <div style="text-align: center; padding: 4rem 0;">
                        <i class="fas fa-check-circle fa-4x" style="color: var(--primary); opacity: 0.2; margin-bottom: 1.5rem;"></i>
                        <h4 style="font-weight: 800; color: var(--text-muted);">Tidak ada pengajuan menunda.</h4>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- RECENT USERS -->
        <div>
            <div class="glass-card" style="padding: 2.5rem;">
                <h3 style="font-weight: 900; margin-bottom: 2rem;">Pengguna Terbaru</h3>
                <div style="display: grid; gap: 1.5rem;">
                    @foreach($recentUsers as $u)
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 40px; height: 40px; background: var(--primary); color: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.9rem;">
                            {{ strtoupper(substr($u->name, 0, 1)) }}
                        </div>
                        <div style="flex: 1;">
                            <div style="font-weight: 800; font-size: 0.95rem;">{{ $u->name }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ ucfirst($u->role) }}</div>
                        </div>
                        @if($u->is_active)
                            <span style="width: 8px; height: 8px; background: var(--success); border-radius: 50%;"></span>
                        @endif
                    </div>
                    @endforeach
                </div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary" style="width: 100%; margin-top: 2rem; justify-content: center; font-size: 0.9rem;">Lihat Semua Pengguna</a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .stat-card {
        padding: 2.5rem;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(0,0,0,0.05); }
    .stat-card .icon { width: 50px; height: 50px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; margin-bottom: 1.5rem; }
    .stat-card .value { font-size: 2rem; font-weight: 900; color: var(--text-main); line-height: 1; margin-bottom: 0.5rem; }
    .stat-card .label { font-size: 0.85rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }

    .stat-card-link {
        text-decoration: none;
        display: block;
        transition: transform 0.3s ease;
    }
    .stat-card-link:hover {
        transform: translateY(-5px);
    }
    .stat-card-link:hover .stat-card {
        border-color: var(--primary);
        box-shadow: 0 20px 40px rgba(16, 185, 129, 0.1);
    }

    /* Header row: title + "Sistem Online" badge must wrap safely on mobile */
    .admin-header-row {
        flex-wrap: wrap;
        gap: 1rem;
    }
    @media (max-width: 560px) {
        .admin-header-row {
            align-items: flex-start !important;
        }
        .admin-header-row > div:last-child {
            width: 100%;
        }
        .admin-header-row .glass-card {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush
@push('scripts')
<script>
  function initMarketChart(canvasId, priceId, changeId, commodity, color) {
      fetch(`{{ url('api/market-prices') }}/${commodity}`)
          .then(r => r.json())
          .then(data => {
              document.getElementById(priceId).textContent = 'Rp ' + Number(data.latest).toLocaleString('id-ID');
              const changeEl = document.getElementById(changeId);
              if (changeEl) {
                  changeEl.textContent = data.changeLabel;
                  const up = data.change >= 0;
                  changeEl.style.background = up ? 'rgba(5,150,105,0.1)' : 'rgba(239,68,68,0.1)';
                  changeEl.style.color = up ? '#10b981' : '#ef4444';
              }

              const canvas = document.getElementById(canvasId);
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
          { canvas: 'chartPadiAdmin', price: 'price-padi-admin', change: 'change-padi-admin', com: 'padi', color: '#34d399' },
      ];
      charts.forEach(c => initMarketChart(c.canvas, c.price, c.change, c.com, c.color));
  });
</script>
@endpush
@endsection
