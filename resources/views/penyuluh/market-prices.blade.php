@extends('layouts.native')

@section('content')
<div class="container" style="padding-top: 8rem; padding-bottom: 4rem;">
  <div class="hp-layout">
    @include('layouts.sidebar')

    <div class="animate-fade">

      <div class="hp-head">
        <h1 class="hp-title">Harga Pasar</h1>
        <button onclick="openPriceModal()" class="hp-btn-add"><i class="fas fa-plus"></i> Tambah</button>
      </div>

      @if(session('success'))
      <div class="hp-alert"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
      @endif

      <div class="hp-tabs">
        @foreach($commodities as $i => $key)
        <button id="tab_{{ $key }}" onclick="switchTab('{{ $key }}')" class="hp-tab {{ $i === 0 ? 'active' : '' }}">{{ $labels[$key] }}</button>
        @endforeach
      </div>

      @foreach($commodities as $key)
      @php $prices = ${$key . 'Prices'}; @endphp
      <div id="table_{{ $key }}" {{ $loop->first ? '' : 'style=display:none' }}>
        <table class="hp-table">
          <thead>
            <tr><th>Tanggal</th><th>Harga</th><th class="hp-col-source">Sumber</th><th class="hp-col-act"></th></tr>
          </thead>
          <tbody>
            @forelse($prices as $p)
            <tr>
              <td class="hp-cell-date">{{ \Carbon\Carbon::parse($p->date)->format('d M Y') }}</td>
              <td class="hp-cell-price">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
              <td class="hp-cell-source">{{ $p->source ?? '—' }}</td>
              <td class="hp-cell-act">
                <button onclick="editPrice({{ $p->id }}, '{{ $p->commodity }}', {{ $p->price }}, '{{ $p->date }}', '{{ $p->source ?? '' }}')" class="hp-act" title="Edit"><i class="fas fa-pen"></i></button>
                <form action="{{ route('admin.market-prices.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')" class="hp-inline">
                  @csrf @method('DELETE')
                  <button type="submit" class="hp-act hp-act-del" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                </form>
              </td>
            </tr>
            @empty
            <tr><td colspan="4" class="hp-empty">Belum ada data</td></tr>
            @endforelse
          </tbody>
        </table>
        @if($prices->hasPages())
        <div class="hp-pagi">{{ $prices->links('vendor.pagination.agri') }}</div>
        @endif
      </div>
      @endforeach

    </div>
  </div>
</div>

<div id="priceModal" class="modal-overlay" onclick="if(event.target===this)this.style.display='none'">
  <div class="glass-card hp-modal" onclick="event.stopPropagation()">
    <div class="hp-modal-head">
      <h3 id="priceModalTitle">Tambah Harga</h3>
      <button onclick="document.getElementById('priceModal').style.display='none'" class="hp-modal-x"><i class="fas fa-times"></i></button>
    </div>
    <form id="priceForm" action="{{ route('admin.market-prices.store') }}" method="POST">
      @csrf
      <div id="priceMethod"></div>
      <input type="hidden" name="commodity" id="priceCommodity" value="padi">

      <div class="hp-field">
        <label>Komoditas</label>
        <select id="priceCommoditySelect" class="hp-inp" onchange="document.getElementById('priceCommodity').value=this.value">
          @foreach($commodities as $key)
          <option value="{{ $key }}">{{ $labels[$key] }}</option>
          @endforeach
        </select>
      </div>
      <div class="hp-field">
        <label>Harga (Rp/kg)</label>
        <input type="number" name="price" id="priceInput" class="hp-inp" placeholder="5000" min="0" required>
      </div>
      <div class="hp-field">
        <label>Tanggal</label>
        <input type="date" name="date" id="dateInput" class="hp-inp" required>
      </div>
      <div class="hp-field">
        <label>Sumber</label>
        <input type="text" name="source" id="sourceInput" class="hp-inp" placeholder="Dinas Pertanian">
      </div>

      <div class="hp-modal-acts">
        <button type="submit" class="hp-btn">Simpan</button>
        <button type="button" onclick="document.getElementById('priceModal').style.display='none'" class="hp-btn hp-btn-ghost">Batal</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
const commodities = @json($commodities);

function switchTab(tab) {
  document.querySelectorAll('[id^="table_"]').forEach(el => el.style.display = 'none');
  document.querySelectorAll('.hp-tab').forEach(el => el.classList.remove('active'));
  document.getElementById('table_' + tab).style.display = '';
  document.getElementById('tab_' + tab).classList.add('active');
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
