@extends('layouts.native')

@php
$iconMap = ['padi' => '🌾'];
@endphp

@section('content')
<div class="container" style="padding-top: 8rem;">
    <div class="pen-layout">
        @include('layouts.sidebar')

        <div class="animate-fade">

            {{-- HEADER --}}
            <div class="pen-header">
                <div class="pen-header-icon">🌾</div>
                <div>
                    <h1 class="pen-title">Manajemen Harga Pasar</h1>
                    <p class="pen-desc">Pantau dan kelola harga komoditas pertanian untuk penyuluh.</p>
                </div>
                <button onclick="openPriceModal()" class="mp-btn-add" style="margin-left: auto;">
                    <i class="fas fa-plus"></i> Tambah Harga
                </button>
            </div>

            @if(session('success'))
            <div class="pen-edukasi-item" style="border-left-color: var(--primary); margin-bottom: 1.5rem; background: rgba(16,185,129,0.06);">
                <i class="fas fa-check-circle" style="color: var(--primary);"></i>
                <span style="font-size: 0.85rem; font-weight: 600; color: var(--text-main);">{{ session('success') }}</span>
            </div>
            @endif

            {{-- PRICE OVERVIEW --}}
            <div class="pen-chart-grid" style="margin-bottom: 2rem;">
                @foreach($commodities as $key)
                <div class="glass-card pen-chart-card" style="display: flex; align-items: center; gap: 1rem; padding: 1.25rem;">
                    <div style="width: 40px; height: 40px; border-radius: 12px; background: {{ $colors[$key] }}15; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;">{{ $iconMap[$key] }}</div>
                    <div>
                        <div style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px; color: var(--text-muted);">{{ $labels[$key] }}</div>
                        <div style="font-size: 1.25rem; font-weight: 900; color: var(--text-main); letter-spacing: -0.02em;" id="ov-{{ $key }}">—</div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- TABS --}}
            <div style="display: flex; gap: 0.25rem; margin-bottom: 1.25rem; padding: 0.25rem; background: var(--card-bg); border-radius: 10px; width: fit-content; flex-wrap: wrap;">
                @foreach($commodities as $i => $key)
                <button id="tab_{{ $key }}" onclick="switchTab('{{ $key }}')" style="padding: 0.45rem 1rem; border: none; border-radius: 8px; font-size: 0.78rem; font-weight: 700; cursor: pointer; transition: all 0.2s; background: {{ $i === 0 ? 'var(--primary)' : 'transparent' }}; color: {{ $i === 0 ? '#fff' : 'var(--text-muted)' }};">
                    {{ $labels[$key] }}
                </button>
                @endforeach
            </div>

            {{-- TABLES --}}
            @foreach($commodities as $key)
            @php $prices = ${$key . 'Prices'}; @endphp
            <div id="table_{{ $key }}" class="glass-card" style="padding: 0; overflow: hidden; {{ $loop->first ? '' : 'display: none;' }}">
                <div style="padding: 1.25rem 1.5rem 0; display: flex; align-items: center; gap: 0.5rem;">
                    <span style="width: 7px; height: 7px; border-radius: 50%; background: {{ $colors[$key] }}; display: inline-block; flex-shrink: 0;"></span>
                    <span style="font-weight: 700; font-size: 0.8rem; color: var(--text-main);">{{ $labels[$key] }}</span>
                    <span style="font-size: 0.7rem; color: var(--text-muted); font-weight: 600; margin-left: auto;">{{ $prices->total() }} data</span>
                </div>
                <div style="overflow-x: auto; padding: 0 0.25rem;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--border);">
                                <th style="padding: 0.85rem 1.25rem; text-align: left; font-weight: 600; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.4px; color: var(--text-muted);">Tanggal</th>
                                <th style="padding: 0.85rem 1.25rem; text-align: left; font-weight: 600; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.4px; color: var(--text-muted);">Harga</th>
                                <th class="mp-col-source" style="padding: 0.85rem 1.25rem; text-align: left; font-weight: 600; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.4px; color: var(--text-muted);">Sumber</th>
                                <th style="padding: 0.85rem 1.25rem; text-align: center; font-weight: 600; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.4px; color: var(--text-muted);">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($prices as $p)
                            <tr class="mp-row">
                                <td style="padding: 0.8rem 1.25rem; font-weight: 600; color: var(--text-main);">{{ \Carbon\Carbon::parse($p->date)->format('d M Y') }}</td>
                                <td style="padding: 0.8rem 1.25rem; font-weight: 700; color: {{ $colors[$key] }};">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                                <td class="mp-col-source" style="padding: 0.8rem 1.25rem; color: var(--text-muted); font-size: 0.8rem;">{{ $p->source ?? '—' }}</td>
                                <td style="padding: 0.8rem 1.25rem; text-align: center;">
                                    <div style="display: flex; gap: 0.25rem; justify-content: center;">
                                        <button onclick="editPrice({{ $p->id }}, '{{ $p->commodity }}', {{ $p->price }}, '{{ $p->date }}', '{{ $p->source ?? '' }}')" class="mp-action-btn"><i class="fas fa-pen"></i></button>
                                        <form action="{{ route('admin.market-prices.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')" style="display: inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="mp-action-btn mp-action-btn-danger"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" style="padding: 3rem; text-align: center; color: var(--text-muted); font-size: 0.85rem;">Belum ada data harga {{ strtolower($labels[$key]) }}.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($prices->hasPages())
                <div style="padding: 1rem 1.25rem;">{{ $prices->links('vendor.pagination.agri') }}</div>
                @endif
            </div>
            @endforeach

        </div>
    </div>
</div>

{{-- MODAL --}}
<div id="priceModal" style="display: none; position: fixed; inset: 0; z-index: 9999; background: rgba(0,0,0,0.4); backdrop-filter: blur(4px); align-items: center; justify-content: center;" onclick="if(event.target===this)this.style.display='none'">
    <div class="glass-card" style="max-width: 420px; width: 90%; padding: 2rem; border-radius: 16px;" onclick="event.stopPropagation()">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
            <h3 id="priceModalTitle" style="font-weight: 800; font-size: 1.05rem; color: var(--text-main);">Tambah Harga</h3>
            <button onclick="document.getElementById('priceModal').style.display='none'" class="mp-modal-close"><i class="fas fa-times"></i></button>
        </div>
        <form id="priceForm" action="{{ route('admin.market-prices.store') }}" method="POST">
            @csrf
            <div id="priceMethod"></div>
            <input type="hidden" name="commodity" id="priceCommodity" value="padi">

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; color: var(--text-muted); margin-bottom: 0.35rem;">Komoditas</label>
                <select id="priceCommoditySelect" style="width: 100%; padding: 0.6rem 0.85rem; border-radius: 10px; border: 1px solid var(--border); background: var(--card-bg); color: var(--text-main); font-size: 0.85rem; font-weight: 600; outline: none;" onchange="document.getElementById('priceCommodity').value=this.value">
                    @foreach($commodities as $key)
                    <option value="{{ $key }}">{{ $labels[$key] }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; color: var(--text-muted); margin-bottom: 0.35rem;">Harga (Rp/kg)</label>
                <input type="number" name="price" id="priceInput" class="mp-input" style="width: 100%;" placeholder="5000" min="0" required>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; color: var(--text-muted); margin-bottom: 0.35rem;">Tanggal</label>
                <input type="date" name="date" id="dateInput" class="mp-input" style="width: 100%;" required>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; color: var(--text-muted); margin-bottom: 0.35rem;">Sumber (opsional)</label>
                <input type="text" name="source" id="sourceInput" class="mp-input" style="width: 100%;" placeholder="Dinas Pertanian">
            </div>

            <div style="display: flex; gap: 0.65rem;">
                <button type="submit" class="mp-btn-primary">Simpan</button>
                <button type="button" onclick="document.getElementById('priceModal').style.display='none'" class="mp-btn-cancel">Batal</button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
.pen-layout { display: grid; grid-template-columns: 260px 1fr; gap: 2rem; align-items: start; }
.pen-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 2.5rem; flex-wrap: wrap; }
.pen-header-icon { width: 48px; height: 48px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; box-shadow: 0 4px 16px rgba(5,150,105,0.2); flex-shrink: 0; }
.pen-title { font-size: 1.6rem; font-weight: 900; color: var(--text-main); letter-spacing: -0.02em; }
.pen-desc { color: var(--text-secondary); font-size: 0.9rem; margin-top: 0.15rem; }

.mp-btn-add { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.25rem; background: var(--primary); color: #fff; border: none; border-radius: 10px; font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: all 0.25s; }
.mp-btn-add:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(5,150,105,0.3); }
.mp-btn-add i { font-size: 0.75rem; }

@media (max-width: 768px) {
  th.mp-col-source, td.mp-col-source { display: none; }
}
.mp-row { border-bottom: 1px solid var(--border); transition: background 0.15s; }
.mp-row:hover { background: var(--background) !important; }
.mp-action-btn { width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center; background: none; border: 1px solid var(--border); border-radius: 8px; color: var(--text-muted); cursor: pointer; font-size: 0.75rem; transition: all 0.2s; }
.mp-action-btn:hover { border-color: var(--primary); color: var(--primary); }
.mp-action-btn-danger:hover { border-color: #ef4444; color: #ef4444; }
.mp-modal-close { width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; background: var(--background); border: none; border-radius: 8px; color: var(--text-muted); cursor: pointer; transition: all 0.15s; }
.mp-modal-close:hover { background: var(--border); }
.mp-input { width: 100%; padding: 0.6rem 0.85rem; border-radius: 10px; border: 1px solid var(--border); background: var(--card-bg); color: var(--text-main); font-size: 0.85rem; font-weight: 600; outline: none; transition: border-color 0.2s; }
.mp-input:focus { border-color: var(--primary); }
.mp-btn-primary { flex: 1; padding: 0.6rem; background: var(--primary); color: #fff; border: none; border-radius: 10px; font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: opacity 0.2s; }
.mp-btn-primary:hover { opacity: 0.9; }
.mp-btn-cancel { flex: 1; padding: 0.6rem; background: var(--background); color: var(--text-muted); border: 1px solid var(--border); border-radius: 10px; font-weight: 600; font-size: 0.85rem; cursor: pointer; transition: background 0.2s; }
.mp-btn-cancel:hover { background: var(--border); }
</style>
@endpush

@push('scripts')
<script>
const commodities = @json($commodities);

fetch('{{ url("api/market-prices") }}')
    .then(r => r.json())
    .then(data => {
        const fmt = (v) => 'Rp ' + Number(v).toLocaleString('id-ID');
        commodities.forEach(c => {
            const el = document.getElementById('ov-' + c);
            if (el) el.textContent = fmt(data[c] || 0);
        });
    });

function switchTab(tab) {
    document.querySelectorAll('[id^="table_"]').forEach(t => t.style.display = 'none');
    const table = document.getElementById('table_' + tab);
    if (table) table.style.display = '';
    document.querySelectorAll('[id^="tab_"]').forEach(el => {
        el.style.background = 'transparent';
        el.style.color = 'var(--text-muted)';
    });
    const btn = document.getElementById('tab_' + tab);
    if (btn) { btn.style.background = 'var(--primary)'; btn.style.color = '#fff'; }
}

function openPriceModal() {
    document.getElementById('priceModalTitle').textContent = 'Tambah Harga';
    document.getElementById('priceForm').action = '{{ route("admin.market-prices.store") }}';
    document.getElementById('priceMethod').innerHTML = '';
    document.getElementById('priceInput').value = '';
    document.getElementById('dateInput').value = new Date().toISOString().split('T')[0];
    document.getElementById('sourceInput').value = '';
    document.getElementById('priceCommoditySelect').value = 'padi';
    document.getElementById('priceCommodity').value = 'padi';
    document.getElementById('priceModal').style.display = 'flex';
}

function editPrice(id, commodity, price, date, source) {
    document.getElementById('priceModalTitle').textContent = 'Edit Harga';
    document.getElementById('priceForm').action = '{{ url("admin/market-prices") }}/' + id;
    document.getElementById('priceMethod').innerHTML = '@method("PATCH")';
    document.getElementById('priceInput').value = price;
    document.getElementById('dateInput').value = date;
    document.getElementById('sourceInput').value = source;
    document.getElementById('priceCommoditySelect').value = commodity;
    document.getElementById('priceCommodity').value = commodity;
    document.getElementById('priceModal').style.display = 'flex';
}
</script>
@endpush
@endSection