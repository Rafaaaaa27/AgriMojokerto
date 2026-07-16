@extends('layouts.native')

@section('content')
<div class="container" style="padding-top: 8rem; padding-bottom: 4rem;">
  <div class="ec-layout">
    @include('layouts.sidebar')

    <div class="animate-fade">

      <div class="ec-head">
        <h1 class="ec-title">Konten Edukasi</h1>
        <button onclick="openEduModal()" class="ec-btn-add"><i class="fas fa-plus"></i> Tambah</button>
      </div>

      @if(session('success'))
      <div class="ec-alert"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
      @endif

      <table class="ec-table">
        <thead>
          <tr><th>Judul</th><th class="ec-col-cat">Kategori</th><th class="ec-col-read">Dibaca</th><th class="ec-col-date">Tanggal</th><th class="ec-col-act"></th></tr>
        </thead>
        <tbody>
          @forelse($infos as $info)
          <tr>
            <td class="ec-cell-title">
              <a href="{{ route('library.show', $info->id) }}">{{ $info->title }}</a>
              @if($info->file_path)
              <i class="fas fa-paperclip ec-attach" title="Ada file lampiran"></i>
              @endif
            </td>
            <td class="ec-cell-cat"><span class="ec-badge">{{ $info->category }}</span></td>
            <td class="ec-cell-read">{{ number_format($info->views) }}</td>
            <td class="ec-cell-date">{{ $info->created_at->format('d M Y') }}</td>
            <td class="ec-cell-act">
              <button onclick="editEdu({{ $info->id }})" class="ec-act" title="Edit"><i class="fas fa-pen"></i></button>
              <form action="{{ route('educational.destroy', $info->id) }}" method="POST" onsubmit="return confirm('Hapus konten ini?')" class="ec-inline">
                @csrf @method('DELETE')
                <button type="submit" class="ec-act ec-act-del" title="Hapus"><i class="fas fa-trash-alt"></i></button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="5" class="ec-empty">Belum ada konten edukasi</td></tr>
          @endforelse
        </tbody>
      </table>

      @if($infos->hasPages())
      <div class="ec-pagi">{{ $infos->links('vendor.pagination.agri') }}</div>
      @endif

    </div>
  </div>
</div>

<div id="eduModal" class="modal-overlay" onclick="if(event.target===this)this.style.display='none'">
  <div class="glass-card ec-modal" onclick="event.stopPropagation()">
    <div class="ec-modal-head">
      <h3 id="eduModalTitle">Tambah Konten</h3>
      <button onclick="document.getElementById('eduModal').style.display='none'" class="ec-modal-x"><i class="fas fa-times"></i></button>
    </div>
    <form id="eduForm" action="{{ route('educational.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div id="eduMethod"></div>

      <div class="ec-field">
        <label>Judul</label>
        <input type="text" name="title" id="eduTitle" class="ec-inp" placeholder="Contoh: Panduan Pemupukan Padi" required>
      </div>

      <div class="ec-field">
        <label>Kategori</label>
        <select name="category" id="eduCategory" class="ec-inp" required>
          <option value="panduan">Panduan</option>
          <option value="artikel">Artikel</option>
          <option value="pengumuman">Pengumuman</option>
          <option value="tips">Tips</option>
        </select>
      </div>

      <div class="ec-field">
        <label>Konten</label>
        <textarea name="content" id="eduContent" class="ec-inp ec-textarea" placeholder="Tulis konten edukasi di sini..." required></textarea>
      </div>

      <div class="ec-row">
        <div class="ec-field">
          <label>Gambar</label>
          <input type="file" name="image" accept="image/*" class="ec-file">
        </div>
        <div class="ec-field">
          <label>File</label>
          <input type="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.txt" class="ec-file">
        </div>
      </div>

      <div class="ec-modal-acts">
        <button type="submit" class="ec-btn">Simpan</button>
        <button type="button" onclick="document.getElementById('eduModal').style.display='none'" class="ec-btn ec-btn-ghost">Batal</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
const eduInfos = @json($infos->items());

function openEduModal() {
  document.getElementById('eduModalTitle').textContent = 'Tambah Konten';
  document.getElementById('eduForm').action = '{{ route("educational.store") }}';
  document.getElementById('eduMethod').innerHTML = '';
  document.getElementById('eduTitle').value = '';
  document.getElementById('eduCategory').value = 'panduan';
  document.getElementById('eduContent').value = '';
  document.getElementById('eduModal').style.display = 'flex';
}

function editEdu(id) {
  const info = eduInfos.find(i => i.id === id);
  if (!info) return;
  document.getElementById('eduModalTitle').textContent = 'Edit Konten';
  document.getElementById('eduForm').action = '{{ url("educational-infos") }}/' + id;
  document.getElementById('eduMethod').innerHTML = '@method("PATCH")';
  document.getElementById('eduTitle').value = info.title;
  document.getElementById('eduCategory').value = info.category;
  document.getElementById('eduContent').value = info.content;
  document.getElementById('eduModal').style.display = 'flex';
}
</script>
@endpush
