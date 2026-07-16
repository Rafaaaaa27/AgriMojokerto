@extends('layouts.native')

@section('content')
<div class="hv-container">

  <div class="hv-head">
    <a href="{{ route('profile.edit') }}" onclick="event.preventDefault(); history.back();" class="hv-back" aria-label="Kembali">
      <i class="fas fa-chevron-left"></i>
    </a>
    <div>
      <h1 class="hv-title">Manajemen Panen</h1>
      <p class="hv-sub">Pantau hasil bumi Anda secara digital.</p>
    </div>
    <button class="btn btn-primary btn-sm" onclick="openModal()">
      <i class="fas fa-plus"></i> Catat Panen
    </button>
  </div>

  @if(session('success'))
    <div class="hv-alert success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="hv-alert error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
  @endif

  @php
    $totalQuantity = $harvests->sum('quantity');
    $totalEntries = $harvests->count();
    $commodities = $harvests->groupBy('crop_type')->map->count();
    $latestHarvest = $harvests->first();
  @endphp

  <div class="hv-stats">
    <div class="hv-stat">
      <span class="hv-stat-num primary">{{ $totalEntries }}</span>
      <span class="hv-stat-lbl">Total Catatan</span>
    </div>
    <div class="hv-stat">
      <span class="hv-stat-num warning">{{ $totalQuantity }}</span>
      <span class="hv-stat-lbl">Total Hasil ({{ $harvests->first()?->unit ?? 'kg' }})</span>
    </div>
    <div class="hv-stat">
      <span class="hv-stat-num info">{{ $commodities->count() }}</span>
      <span class="hv-stat-lbl">Jenis Komoditas</span>
    </div>
    <div class="hv-stat">
      <span class="hv-stat-num primary-dark">{{ $latestHarvest?->harvest_date?->format('d M') ?? '-' }}</span>
      <span class="hv-stat-lbl">Panen Terakhir</span>
    </div>
  </div>

  @if($harvests->isEmpty())
    <div class="hv-empty">
      <div class="hv-empty-icon"><i class="fas fa-leaf"></i></div>
      <h3>Belum Ada Data Panen</h3>
      <p>Catat hasil panen Anda untuk memantau produktivitas lahan setiap musim.</p>
      <button class="btn btn-primary" onclick="openModal()">
        <i class="fas fa-plus"></i> Catat Panen Baru
      </button>
    </div>
  @else
    <div class="hv-list">
      @foreach($harvests as $harvest)
        <div class="hv-card">
          <div class="hv-card-left">
            <div class="hv-date-box">
              <span class="hv-date-month">{{ $harvest->harvest_date->format('M') }}</span>
              <span class="hv-date-day">{{ $harvest->harvest_date->format('d') }}</span>
            </div>
          </div>
          <div class="hv-card-body">
            <div class="hv-card-top">
              <span class="hv-crop-badge">{{ ucfirst($harvest->crop_type) }}</span>
              <span class="hv-qty">{{ $harvest->quantity }} <small>{{ $harvest->unit }}</small></span>
            </div>
            @if($harvest->notes)
              <p class="hv-notes">{{ $harvest->notes }}</p>
            @endif
          </div>
          <div class="hv-card-acts">
            <button onclick="editHarvest({{ $harvest->id }})" class="hv-act" title="Edit">
              <i class="fas fa-edit"></i>
            </button>
            <form action="{{ route('harvests.destroy', $harvest) }}" method="POST" onsubmit="return confirm('Hapus data panen ini?')">
              @csrf @method('DELETE')
              <button class="hv-act danger" title="Hapus"><i class="fas fa-trash-alt"></i></button>
            </form>
          </div>
        </div>
      @endforeach
    </div>
  @endif

</div>

{{-- Modal --}}
<div id="hvModal" class="hv-modal" onclick="if(event.target===this)closeModal()">
  <div class="hv-modal-body">
    <button class="hv-modal-x" onclick="closeModal()">&times;</button>
    <div class="hv-modal-head">
      <h2 id="hvModalTitle"><i class="fas fa-seedling"></i> Catat Hasil Panen</h2>
      <p>Lengkapi data hasil panen Anda.</p>
    </div>
    <form id="hvForm" method="POST">
      @csrf
      <div id="hvMethod"></div>

      <div class="hv-field">
        <label>Jenis Komoditas <span>*</span></label>
        <select name="crop_type" id="f_crop" class="hv-input" required>
          <option value="padi">Padi</option>
          <option value="jagung">Jagung</option>
          <option value="kedelai">Kedelai</option>
          <option value="cabe">Cabe</option>
          <option value="kangkung">Kangkung</option>
          <option value="tebu">Tebu</option>
          <option value="lainnya">Lainnya</option>
        </select>
      </div>

      <div class="hv-field">
        <label>Tanggal Panen <span>*</span></label>
        <input type="date" name="harvest_date" id="f_date" class="hv-input" required>
      </div>

      <div class="hv-row">
        <div class="hv-field">
          <label>Jumlah <span>*</span></label>
          <input type="number" step="0.01" name="quantity" id="f_qty" class="hv-input" required min="0" placeholder="0">
        </div>
        <div class="hv-field">
          <label>Satuan <span>*</span></label>
          <select name="unit" id="f_unit" class="hv-input" required>
            <option value="kg">Kilogram (kg)</option>
            <option value="ton">Ton</option>
            <option value="kuintal">Kuintal</option>
          </select>
        </div>
      </div>

      <div class="hv-field">
        <label>Catatan</label>
        <textarea name="notes" id="f_notes" class="hv-input" rows="3" placeholder="Catatan tambahan..."></textarea>
      </div>

      <button type="submit" id="hvSubmit" class="btn btn-primary btn-block">
        <i class="fas fa-save"></i> Simpan Data Panen
      </button>
    </form>
  </div>
</div>

@push('scripts')
<script>
const harvests = @json($harvestsJson);

function openModal() {
  document.getElementById('hvModal').classList.add('show');
  document.body.style.overflow = 'hidden';
  document.getElementById('hvForm').reset();
  document.getElementById('hvForm').action = '{{ route('harvests.store') }}';
  document.getElementById('hvMethod').innerHTML = '';
  document.getElementById('hvModalTitle').innerHTML = '<i class="fas fa-seedling"></i> Catat Hasil Panen';
  document.getElementById('hvSubmit').innerHTML = '<i class="fas fa-save"></i> Simpan Data Panen';
}

function editHarvest(id) {
  const d = harvests[id];
  if (!d) return;
  document.getElementById('hvModal').classList.add('show');
  document.body.style.overflow = 'hidden';
  document.getElementById('hvForm').action = '/harvests/' + id;
  document.getElementById('hvMethod').innerHTML = '<input type="hidden" name="_method" value="PATCH">';
  document.getElementById('hvModalTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Hasil Panen';
  document.getElementById('hvSubmit').innerHTML = '<i class="fas fa-save"></i> Update Data Panen';
  document.getElementById('f_crop').value = d.crop_type;
  document.getElementById('f_date').value = d.harvest_date;
  document.getElementById('f_qty').value = d.quantity;
  document.getElementById('f_unit').value = d.unit;
  document.getElementById('f_notes').value = d.notes || '';
}

function closeModal() {
  document.getElementById('hvModal').classList.remove('show');
  document.body.style.overflow = '';
}

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') closeModal();
});
</script>
@endpush
@endsection
