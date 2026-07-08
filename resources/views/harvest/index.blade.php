@extends('layouts.native')

@section('content')
<div class="container section" style="padding-top: 8rem;">
    <div style="margin-bottom: 1.5rem;">
        <a href="{{ route('profile.edit') }}" style="color: var(--text-muted); text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.4rem;">
            <i class="fas fa-arrow-left"></i> Kembali ke Profil
        </a>
    </div>
    <div class="glass-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h2 style="font-size: 2rem; font-weight: 800; color: var(--primary-dark);">
                    <i class="fas fa-chart-line"></i> Manajemen Panen
                </h2>
                <p style="color: var(--text-muted);">Kelola dan pantau hasil bumi Anda secara digital.</p>
            </div>
            <button class="btn btn-primary" onclick="openHarvestModal()">
                <i class="fas fa-plus"></i> Catat Panen Baru
            </button>
        </div>

        @if(session('success'))
        <div style="background: rgba(16, 185, 129, 0.1); color: var(--success); padding: 1rem 1.5rem; border-radius: var(--radius-md); margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem; font-weight: 600;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        @if($harvests->isEmpty())
        <div style="text-align: center; padding: 5rem 2rem;">
            <div style="background: var(--background); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto;">
                <i class="fas fa-box-open" style="font-size: 2rem; color: #cbd5e1;"></i>
            </div>
            <h3>Mulai Catat Panen Pertama Anda</h3>
            <p style="color: var(--text-muted); max-width: 400px; margin: 0.5rem auto 2rem auto;">Data panen yang terorganisir membantu Anda menganalisis produktivitas lahan setiap musim.</p>
        </div>
        @else
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: separate; border-spacing: 0 0.75rem;">
                <thead>
                    <tr style="text-align: left; color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        <th style="padding: 1rem;">Tanggal</th>
                        <th style="padding: 1rem;">Komoditas</th>
                        <th style="padding: 1rem;">Jumlah</th>
                        <th style="padding: 1rem;">Catatan</th>
                        <th style="padding: 1rem; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($harvests as $harvest)
                    <tr class="animate-fade" style="background: white; border-radius: var(--radius-md); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); transition: var(--transition);">
                        <td style="padding: 1.25rem; font-weight: 600; border-radius: var(--radius-md) 0 0 var(--radius-md);">
                            {{ date('d M Y', strtotime($harvest->harvest_date)) }}
                        </td>
                        <td style="padding: 1.25rem;">
                            <span style="background: rgba(5, 150, 105, 0.1); color: var(--primary); padding: 0.3rem 0.8rem; border-radius: 99px; font-size: 0.8rem; font-weight: 700;">
                                {{ ucfirst($harvest->crop_type) }}
                            </span>
                        </td>
                        <td style="padding: 1.25rem; font-weight: 700; color: var(--primary-dark);">
                            {{ $harvest->quantity }} {{ $harvest->unit }}
                        </td>
                        <td style="padding: 1.25rem; color: var(--text-muted); font-size: 0.9rem; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ $harvest->notes ?? '-' }}
                        </td>
                        <td style="padding: 1.25rem; text-align: right; border-radius: 0 var(--radius-md) var(--radius-md) 0;">
                            <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
                                <button onclick="openHarvestModal({{ json_encode($harvest) }})" class="harvest-action-btn" style="background: none; border: none; cursor: pointer; transition: var(--transition);">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('harvests.destroy', $harvest) }}" method="POST" onsubmit="return confirm('Hapus data panen ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="harvest-action-btn harvest-action-btn-danger" style="background: none; border: none; cursor: pointer; transition: var(--transition);">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

<!-- Modal Harvest -->
<div id="modalHarvest" class="modal" style="display:none; position:fixed; z-index:2000; left:0; top:0; width:100%; height:100%; background:rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); align-items:center; justify-content:center;">
    <div style="background:white; width:100%; max-width:500px; padding:2.5rem; border-radius:var(--radius-lg); position:relative; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);">
        <span onclick="document.getElementById('modalHarvest').style.display='none'" style="position:absolute; right:1.5rem; top:1.5rem; font-size:1.5rem; cursor:pointer; color:var(--text-muted);">&times;</span>
        <h2 id="modalTitle" style="margin-bottom:2rem; font-weight:800; display:flex; align-items:center; gap:0.75rem;">
            <i class="fas fa-seedling" style="color:var(--primary);"></i> Catat Hasil Panen
        </h2>
        <form id="harvestForm" action="{{ route('harvests.store') }}" method="POST">
            @csrf
            <div id="methodField"></div>
            <div style="margin-bottom:1.5rem;">
                <label style="display:block; margin-bottom:0.5rem; font-weight:700; font-size:0.9rem; color:var(--text-muted);">Jenis Komoditas</label>
                <select name="crop_type" id="crop_type" class="form-control" required style="width:100%; padding:0.8rem; border-radius:var(--radius-md); border:1px solid var(--border-color);">
                    <option value="padi">Padi</option>
                    <option value="tebu">Tebu</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>
            <div style="margin-bottom:1.5rem;">
                <label style="display:block; margin-bottom:0.5rem; font-weight:700; font-size:0.9rem; color:var(--text-muted);">Tanggal Panen</label>
                <input type="date" name="harvest_date" id="harvest_date" class="form-control" required style="width:100%; padding:0.8rem; border-radius:var(--radius-md); border:1px solid var(--border-color);">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom:1.5rem;">
                <div>
                    <label style="display:block; margin-bottom:0.5rem; font-weight:700; font-size:0.9rem; color:var(--text-muted);">Jumlah</label>
                    <input type="number" step="0.01" name="quantity" id="quantity" class="form-control" required style="width:100%; padding:0.8rem; border-radius:var(--radius-md); border:1px solid var(--border-color);">
                </div>
                <div>
                    <label style="display:block; margin-bottom:0.5rem; font-weight:700; font-size:0.9rem; color:var(--text-muted);">Satuan</label>
                    <select name="unit" id="unit" class="form-control" required style="width:100%; padding:0.8rem; border-radius:var(--radius-md); border:1px solid var(--border-color);">
                        <option value="kg">Kilogram (kg)</option>
                        <option value="ton">Ton</option>
                        <option value="kuintal">Kuintal</option>
                    </select>
                </div>
            </div>
            <div style="margin-bottom:2rem;">
                <label style="display:block; margin-bottom:0.5rem; font-weight:700; font-size:0.9rem; color:var(--text-muted);">Catatan</label>
                <textarea name="notes" id="notes" class="form-control" rows="3" style="width:100%; padding:0.8rem; border-radius:var(--radius-md); border:1px solid var(--border-color); resize:none;"></textarea>
            </div>
            <button type="submit" id="submitBtn" class="btn btn-primary" style="width: 100%; padding:1rem; font-size:1rem;">
                Simpan Data Panen
            </button>
        </form>
    </div>
</div>

<script>
function openHarvestModal(data = null) {
    const modal = document.getElementById('modalHarvest');
    const form = document.getElementById('harvestForm');
    const title = document.getElementById('modalTitle');
    const methodField = document.getElementById('methodField');
    const submitBtn = document.getElementById('submitBtn');

    if (data) {
        title.innerHTML = '<i class="fas fa-edit" style="color:var(--primary);"></i> Edit Hasil Panen';
        form.action = `/harvests/${data.id}`;
        methodField.innerHTML = '@method("PATCH")';
        submitBtn.innerText = 'Update Data Panen';
        
        document.getElementById('crop_type').value = data.crop_type;
        document.getElementById('harvest_date').value = data.harvest_date;
        document.getElementById('quantity').value = data.quantity;
        document.getElementById('unit').value = data.unit;
        document.getElementById('notes').value = data.notes || '';
    } else {
        title.innerHTML = '<i class="fas fa-seedling" style="color:var(--primary);"></i> Catat Hasil Panen';
        form.action = "{{ route('harvests.store') }}";
        methodField.innerHTML = '';
        submitBtn.innerText = 'Simpan Data Panen';
        form.reset();
    }
    modal.style.display = 'flex';
}
</script>
@push('styles')
<style>
.harvest-action-btn { color: #94a3b8; }
.harvest-action-btn:hover { color: var(--primary) !important; }
.harvest-action-btn-danger:hover { color: var(--danger) !important; }
</style>
@endpush
@endsection
