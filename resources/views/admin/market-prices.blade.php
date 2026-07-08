@extends('layouts.native')

@php
$iconMap = ['padi' => '🌾'];
@endphp

@section('content')
<div class="container" style="padding-top: 8rem;">
    <div class="mp-layout">
        @include('layouts.sidebar')

        <div class="animate-fade">

            {{-- HEADER --}}
            <div class="mp-header">
                <div>
                    <h1 class="mp-title">Manajemen Harga Pasar</h1>
                    <p class="mp-subtitle">Pantau dan kelola harga komoditas pertanian</p>
                </div>
                <button onclick="openPriceModal()" class="mp-btn-add">
                    <i class="fas fa-plus"></i> Tambah Harga
                </button>
            </div>

            @if(session('success'))
            <div class="mp-alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
            @endif

            {{-- PRICE OVERVIEW --}}
            <div class="mp-overview">
                @foreach($commodities as $key)
                <div class="mp-overview-card">
                    <div class="mp-overview-icon" style="background: {{ $colors[$key] }}15;">{{ $iconMap[$key] }}</div>
                    <div>
                        <div class="mp-overview-label">{{ $labels[$key] }}</div>
                        <span class="mp-overview-value" id="ov-{{ $key }}">—</span>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- TABS --}}
            <div class="mp-tabs">
                @foreach($commodities as $i => $key)
                <button id="tab_{{ $key }}" onclick="switchTab('{{ $key }}')" class="tab-btn {{ $i === 0 ? 'tab-active' : '' }}">
                    {{ $labels[$key] }}
                </button>
                @endforeach
            </div>

            {{-- TABLES --}}
            @foreach($commodities as $key)
            @php $prices = ${$key . 'Prices'}; @endphp
            <div id="table_{{ $key }}" class="glass-card tab-content" style="padding: 0; overflow: hidden; {{ $loop->first ? '' : 'display: none;' }}">
                <div class="mp-table-header">
                    <span class="mp-table-dot" style="background: {{ $colors[$key] }};"></span>
                    <span class="mp-table-label">{{ $labels[$key] }}</span>
                    <span class="mp-table-count">{{ $prices->total() }} data</span>
                </div>
                <div class="mp-table-scroll">
                    <table class="mp-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Harga</th>
                                <th class="mp-col-source">Sumber</th>
                                <th class="mp-col-action">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($prices as $p)
                            <tr class="mp-row">
                                <td class="mp-cell-date">{{ \Carbon\Carbon::parse($p->date)->format('d M Y') }}</td>
                                <td class="mp-cell-price" style="color: {{ $colors[$key] }};">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                                <td class="mp-cell-source">{{ $p->source ?? '—' }}</td>
                                <td class="mp-cell-action">
                                    <button onclick="editPrice({{ $p->id }}, '{{ $p->commodity }}', {{ $p->price }}, '{{ $p->date }}', '{{ $p->source ?? '' }}')" class="mp-btn-icon mp-btn-edit"><i class="fas fa-pen"></i></button>
                                    <form action="{{ route('admin.market-prices.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')" class="mp-inline-form">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="mp-btn-icon mp-btn-delete"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="mp-empty">Belum ada data harga {{ strtolower($labels[$key]) }}.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($prices->hasPages())
                <div class="mp-pagination">{{ $prices->links('vendor.pagination.agri') }}</div>
                @endif
            </div>
            @endforeach

        </div>
    </div>
</div>

{{-- MODAL --}}
<div id="priceModal" class="modal-overlay mp-modal-overlay" onclick="if(event.target===this)this.style.display='none'">
    <div class="glass-card mp-modal" onclick="event.stopPropagation()">
        <div class="mp-modal-head">
            <h3 id="priceModalTitle" class="mp-modal-title">Tambah Harga</h3>
            <button onclick="document.getElementById('priceModal').style.display='none'" class="mp-modal-close"><i class="fas fa-times"></i></button>
        </div>
        <form id="priceForm" action="{{ route('admin.market-prices.store') }}" method="POST">
            @csrf
            <div id="priceMethod"></div>
            <input type="hidden" name="commodity" id="priceCommodity" value="padi">

            <div class="mp-field">
                <label class="mp-label">Komoditas</label>
                <select id="priceCommoditySelect" class="mp-input" onchange="document.getElementById('priceCommodity').value=this.value">
                    @foreach($commodities as $key)
                    <option value="{{ $key }}">{{ $labels[$key] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mp-field">
                <label class="mp-label">Harga (Rp/kg)</label>
                <input type="number" name="price" id="priceInput" class="mp-input" placeholder="5000" min="0" required>
            </div>

            <div class="mp-field">
                <label class="mp-label">Tanggal</label>
                <input type="date" name="date" id="dateInput" class="mp-input" required>
            </div>

            <div class="mp-field">
                <label class="mp-label">Sumber (opsional)</label>
                <input type="text" name="source" id="sourceInput" class="mp-input" placeholder="Dinas Pertanian">
            </div>

            <div class="mp-modal-actions">
                <button type="submit" class="mp-btn-save">Simpan</button>
                <button type="button" onclick="document.getElementById('priceModal').style.display='none'" class="mp-btn-cancel">Batal</button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
.mp-layout { display: grid; grid-template-columns: 260px 1fr; gap: 2rem; align-items: start; }

.mp-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
.mp-title { font-size: 1.5rem; font-weight: 800; color: var(--text-main); letter-spacing: -0.03em; }
.mp-subtitle { color: var(--text-muted); font-size: 0.85rem; margin-top: 0.2rem; }

.mp-btn-add { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.25rem; background: var(--primary); color: #fff; border: none; border-radius: 10px; font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: all 0.25s; }
.mp-btn-add:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(5,150,105,0.3); }
.mp-btn-add i { font-size: 0.75rem; }

.mp-alert { display: flex; align-items: center; gap: 0.6rem; padding: 0.75rem 1rem; background: rgba(5,150,105,0.08); border: 1px solid rgba(5,150,105,0.15); border-radius: 10px; color: #059669; font-weight: 600; font-size: 0.85rem; margin-bottom: 1.5rem; }

.mp-overview { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.75rem; margin-bottom: 2rem; }
.mp-overview-card { padding: 1rem 1.25rem; display: flex; align-items: center; gap: 0.75rem; background: var(--surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); transition: transform 0.25s, box-shadow 0.25s; }
.mp-overview-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.05); }
.mp-overview-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
.mp-overview-label { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px; color: var(--text-muted); margin-bottom: 0.1rem; }
.mp-overview-value { font-size: 1.1rem; font-weight: 800; color: var(--text-main); }

.mp-tabs { display: flex; gap: 0.25rem; margin-bottom: 1.25rem; padding: 0.25rem; background: var(--card-bg); border-radius: 10px; width: fit-content; flex-wrap: wrap; }
.tab-btn { padding: 0.45rem 1rem; border: none; border-radius: 8px; font-size: 0.78rem; font-weight: 700; cursor: pointer; transition: all 0.2s; background: transparent; color: var(--text-muted); }
.tab-btn.tab-active { background: var(--primary); color: #fff; }

.mp-table-header { padding: 1.25rem 1.5rem 0; display: flex; align-items: center; gap: 0.5rem; }
.mp-table-dot { width: 7px; height: 7px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
.mp-table-label { font-weight: 700; font-size: 0.8rem; color: var(--text-main); }
.mp-table-count { font-size: 0.7rem; color: var(--text-muted); font-weight: 600; margin-left: auto; }

.mp-table-scroll { overflow-x: auto; padding: 0 0.25rem; }

.mp-table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
.mp-table thead tr { border-bottom: 1px solid var(--border); }
.mp-table th { padding: 0.85rem 1.25rem; text-align: left; font-weight: 600; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.4px; color: var(--text-muted); }
.mp-table th.mp-col-action { text-align: center; }

.mp-row { border-bottom: 1px solid var(--border); transition: background 0.15s; }
.mp-row:hover { background: var(--background); }

.mp-cell-date { padding: 0.8rem 1.25rem; font-weight: 600; color: var(--text-main); }
.mp-cell-price { padding: 0.8rem 1.25rem; font-weight: 700; }
.mp-cell-source { padding: 0.8rem 1.25rem; color: var(--text-muted); font-size: 0.8rem; }
.mp-cell-action { padding: 0.8rem 1.25rem; text-align: center; display: flex; gap: 0.25rem; justify-content: center; }

.mp-btn-icon { width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center; background: none; border: 1px solid var(--border); border-radius: 8px; color: var(--text-muted); cursor: pointer; font-size: 0.75rem; transition: all 0.2s; }
.mp-btn-edit:hover { border-color: var(--primary); color: var(--primary); }
.mp-btn-delete:hover { border-color: #ef4444; color: #ef4444; }

.mp-inline-form { display: inline; }

.mp-empty { padding: 3rem; text-align: center; color: var(--text-muted); font-size: 0.85rem; }

.mp-pagination { padding: 0.75rem 1.25rem; }

.mp-modal-overlay { display: none; background: rgba(0,0,0,0.4); backdrop-filter: blur(4px); }
.mp-modal { max-width: 420px; width: 90%; padding: 2rem; border-radius: 16px; }
.mp-modal-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; }
.mp-modal-title { font-weight: 800; font-size: 1.05rem; color: var(--text-main); }
.mp-modal-close { width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; background: var(--background); border: none; border-radius: 8px; color: var(--text-muted); cursor: pointer; transition: all 0.15s; }
.mp-modal-close:hover { background: var(--border); }

.mp-field { margin-bottom: 1rem; }
.mp-label { display: block; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; color: var(--text-muted); margin-bottom: 0.35rem; }
.mp-input { width: 100%; padding: 0.6rem 0.85rem; border-radius: 10px; border: 1px solid var(--border); background: var(--card-bg); color: var(--text-main); font-size: 0.85rem; font-weight: 600; outline: none; transition: border-color 0.2s; }
.mp-input:focus { border-color: var(--primary); }

.mp-modal-actions { display: flex; gap: 0.65rem; }
.mp-btn-save { flex: 1; padding: 0.6rem; background: var(--primary); color: #fff; border: none; border-radius: 10px; font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: opacity 0.2s; }
.mp-btn-save:hover { opacity: 0.9; }
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
    document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('tab-active'));
    document.getElementById('table_' + tab).style.display = '';
    document.getElementById('tab_' + tab).classList.add('tab-active');
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