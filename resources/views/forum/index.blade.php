@extends('layouts.native')

@section('content')
<div class="hero-sub" style="padding-top: 8rem; padding-bottom: 4rem; text-align: center;">
    <div class="container animate-fade-up">
        <h1 style="font-size: 3rem; font-weight: 900; margin-bottom: 1rem;">Forum Komunitas</h1>
        <p style="font-size: 1.2rem; opacity: 0.85; max-width: 600px; margin: 0 auto;">Berbagi ilmu, pengalaman, dan solusi seputar pertanian modern di Mojokerto.</p>
    </div>
</div>

<div class="container section">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-weight: 800; color: var(--primary-dark);">Diskusi Terbaru</h2>
            <p style="color: var(--text-muted);">{{ $posts->total() }} topik diskusi sedang aktif.</p>
        </div>
        @auth
        <button onclick="document.getElementById('modalNewPost').style.display='flex'" class="btn btn-primary" style="padding: 1rem 2rem;">
            <i class="fas fa-plus-circle"></i> Mulai Diskusi
        </button>
        @else
        <a href="{{ route('login') }}" class="btn btn-secondary" style="padding: 1rem 2rem;">
            <i class="fas fa-sign-in-alt"></i> Login untuk Berdiskusi
        </a>
        @endauth
    </div>

    <!-- Search & Sort -->
    <div style="display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap;">
        <form action="{{ route('forum.index') }}" method="GET" style="flex: 1; display: flex; gap: 0.75rem;">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <input type="text" name="search" placeholder="Cari diskusi..." value="{{ request('search') }}"
                   style="flex: 1; padding: 0.85rem 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); font-size: 0.95rem;">
            <button type="submit" class="btn btn-primary" style="padding: 0.85rem 1.5rem;"><i class="fas fa-search"></i></button>
        </form>
        <div style="display: flex; gap: 0.5rem;">
            @foreach(['terbaru' => 'Terbaru', 'terpopuler' => 'Terpopuler', 'terbanyak' => 'Terbanyak Komentar'] as $key => $label)
                <a href="{{ route('forum.index', array_merge(request()->query(), ['sort' => $key])) }}"
                   class="btn {{ request('sort', 'terbaru') === $key ? 'btn-primary' : 'btn-secondary' }}"
                   style="padding: 0.85rem 1.25rem; font-size: 0.85rem;">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 300px; gap: 3rem;">
        <!-- DISCUSSION LIST -->
        <main>
            <div style="display: grid; gap: 1.5rem;">
                <!-- Pinned Posts -->
                @isset($pinned)
                    @foreach($pinned as $post)
                        @include('forum._post_card', ['post' => $post, 'pinned' => true])
                    @endforeach
                @endisset

                @forelse($posts as $post)
                    @include('forum._post_card', ['post' => $post, 'pinned' => false])
                @empty
                    <div style="text-align: center; padding: 5rem 0;">
                        <i class="fas fa-comments fa-4x" style="color: #cbd5e1; margin-bottom: 2rem;"></i>
                        <h3 style="font-weight: 800; color: var(--text-muted);">
                            @if(request('search'))
                                Tidak ditemukan diskusi dengan kata kunci "{{ request('search') }}"
                            @else
                                Belum ada diskusi.
                            @endif
                        </h3>
                        <p style="color: var(--text-muted);">Jadilah yang pertama untuk memulai percakapan!</p>
                    </div>
                @endforelse
            </div>

            <div style="margin-top: 3rem;">
                {{ $posts->links() }}
            </div>
        </main>

        <!-- SIDEBAR -->
        <aside>
            <div class="glass-card" style="padding: 2rem; position: sticky; top: 8rem;">
                <h3 style="font-weight: 800; margin-bottom: 1.5rem;">Kategori Populer</h3>
                <div style="display: grid; gap: 0.75rem;">
                    @php
                        $categoryMaps = ['Budidaya' => 'Budidaya Padi', 'Pupuk' => 'Pupuk & Organik', 'Hama' => 'Hama & Penyakit', 'Alat' => 'Alat Modern', 'Umum' => 'Umum'];
                    @endphp
                    <a href="{{ route('forum.index', ['search' => request('search'), 'sort' => request('sort')]) }}"
                       style="display: flex; justify-content: space-between; align-items: center; text-decoration: none; padding: 0.75rem 1rem; background: var(--background); border-radius: 12px; font-weight: 700; color: {{ !request('category') ? 'var(--primary)' : 'var(--text-muted)' }}; transition: all 0.3s ease;">
                        <span>Semua</span>
                        <span style="font-size: 0.75rem; background: var(--surface); color: var(--text-muted); padding: 0.2rem 0.6rem; border-radius: 8px; border: 1px solid var(--border-color);">{{ array_sum($categoryCounts) }}</span>
                    </a>
                    @foreach($categoryMaps as $catKey => $catLabel)
                    <a href="{{ route('forum.index', ['category' => $catKey, 'search' => request('search'), 'sort' => request('sort')]) }}"
                       style="display: flex; justify-content: space-between; align-items: center; text-decoration: none; padding: 0.75rem 1rem; background: var(--background); border-radius: 12px; font-weight: 700; color: {{ request('category') === $catKey ? 'var(--primary)' : 'var(--text-muted)' }}; transition: all 0.3s ease;">
                        <span>{{ $catLabel }}</span>
                        <span style="font-size: 0.75rem; background: var(--surface); color: var(--text-muted); padding: 0.2rem 0.6rem; border-radius: 8px; border: 1px solid var(--border-color);">{{ $categoryCounts[$catKey] ?? 0 }}</span>
                    </a>
                    @endforeach
                </div>

                <div style="margin-top: 3rem; padding: 2rem; background: var(--primary); border-radius: var(--radius-lg); color: white; position: relative; overflow: hidden;">
                    <i class="fas fa-seedling" style="position: absolute; right: -10px; bottom: -10px; font-size: 5rem; opacity: 0.2;"></i>
                    <h4 style="font-weight: 800; margin-bottom: 0.5rem; position: relative;">E-Library</h4>
                    <p style="font-size: 0.85rem; opacity: 0.9; margin-bottom: 1.5rem; position: relative;">Akses panduan bertani modern terlengkap di Mojokerto.</p>
                    <a href="{{ route('library.index') }}" class="btn" style="background: white; color: var(--primary); width: 100%; justify-content: center; font-weight: 800;">Baca Sekarang</a>
                </div>
            </div>
        </aside>
    </div>
</div>

<!-- MODAL NEW POST -->
@auth
<div id="modalNewPost" class="modal-overlay" style="display:none;" onclick="if(event.target===this)this.style.display='none'">
    <div class="glass-card modal-content" style="max-width: 600px; padding: 3rem;" onclick="event.stopPropagation()">
        <h3 style="font-weight: 800; margin-bottom: 2rem;">Mulai Diskusi Baru</h3>
        <form action="{{ route('forum.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label class="form-label">Judul Topik</label>
                <input type="text" name="title" placeholder="Apa yang ingin Anda diskusikan?" class="form-input" required>
            </div>
            <div style="margin-bottom: 1rem;">
                <label class="form-label">Kategori</label>
                <select name="category" class="form-input" required>
                    <option value="Budidaya">Budidaya</option>
                    <option value="Pupuk">Pupuk</option>
                    <option value="Hama">Hama & Penyakit</option>
                    <option value="Alat">Alat Tani</option>
                    <option value="Umum">Umum</option>
                </select>
            </div>
            <div style="margin-bottom: 1rem;">
                <label class="form-label">Detail Pertanyaan / Informasi</label>
                <textarea name="description" rows="5" class="form-input" style="height: auto; resize: none;" required placeholder="Berikan detail agar anggota lain dapat membantu..."></textarea>
            </div>
            <div style="margin-bottom: 2rem;">
                <label class="form-label">Gambar Pendukung (Opsional)</label>
                <input type="file" name="image" class="form-input" accept="image/*" style="padding: 0.5rem;">
            </div>
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1; justify-content: center;">Posting Diskusi</button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('modalNewPost').style.display='none'" style="padding: 1rem 2rem; border: none;">Batal</button>
            </div>
        </form>
    </div>
</div>
@endauth

<!-- EDIT MODAL -->
@auth
<div id="modalEditPost" class="modal-overlay" style="display:none;" onclick="if(event.target===this)this.style.display='none'">
    <div class="glass-card modal-content" style="max-width: 600px; padding: 3rem;" onclick="event.stopPropagation()">
        <h3 style="font-weight: 800; margin-bottom: 2rem;">Edit Diskusi</h3>
        <form id="editPostForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 1rem;">
                <label class="form-label">Judul Topik</label>
                <input type="text" name="title" id="edit_title" class="form-input" required>
            </div>
            <div style="margin-bottom: 1rem;">
                <label class="form-label">Kategori</label>
                <select name="category" id="edit_category" class="form-input" required>
                    <option value="Budidaya">Budidaya</option>
                    <option value="Pupuk">Pupuk</option>
                    <option value="Hama">Hama & Penyakit</option>
                    <option value="Alat">Alat Tani</option>
                    <option value="Umum">Umum</option>
                </select>
            </div>
            <div style="margin-bottom: 1rem;">
                <label class="form-label">Detail</label>
                <textarea name="description" id="edit_description" rows="5" class="form-input" style="height: auto; resize: none;" required></textarea>
            </div>
            <div style="margin-bottom: 2rem;">
                <label class="form-label">Gambar (biarkan kosong jika tidak ingin mengubah)</label>
                <input type="file" name="image" class="form-input" accept="image/*" style="padding: 0.5rem;">
            </div>
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1; justify-content: center;">Simpan Perubahan</button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('modalEditPost').style.display='none'" style="padding: 1rem 2rem; border: none;">Batal</button>
            </div>
        </form>
    </div>
</div>
@endauth

@push('styles')
<style>
    .modal-overlay {
        position: fixed;
        z-index: 3000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .badge {
        padding: 0.3rem 1rem;
        border-radius: 99px;
        font-weight: 800;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .like-btn { cursor: pointer; transition: all 0.3s ease; }
    .like-btn:hover { transform: scale(1.1); }
    .like-btn.liked { color: #ef4444; }
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 800;
        color: var(--text-muted);
        font-size: 0.8rem;
        text-transform: uppercase;
    }
    .form-input {
        width: 100%;
        padding: 1rem;
        background: var(--surface);
        color: var(--text-main);
        border: 1.5px solid var(--border-color);
        border-radius: var(--radius-md);
        font-size: 1rem;
        font-family: 'Inter', inherit;
        transition: all 0.3s ease;
    }
    .form-input:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
    }
</style>
<script>
function openEditModal(postId, title, category, description) {
    const form = document.getElementById('editPostForm');
    form.action = '/forum/' + postId;
    document.getElementById('edit_title').value = title;
    document.getElementById('edit_category').value = category;
    document.getElementById('edit_description').value = description;
    document.getElementById('modalEditPost').style.display = 'flex';
}

function toggleLike(postId, btn) {
    fetch('/forum/' + postId + '/like', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' },
    })
    .then(r => r.json())
    .then(data => {
        btn.classList.toggle('liked', data.liked);
        btn.querySelector('.like-count').textContent = data.count;
    })
    .catch(() => location.reload());
}

function toggleCommentLike(commentId, btn) {
    fetch('/forum/comment/' + commentId + '/like', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' },
    })
    .then(r => r.json())
    .then(data => {
        btn.classList.toggle('liked', data.liked);
        btn.querySelector('.like-count').textContent = data.count;
    })
    .catch(() => location.reload());
}
</script>
@endpush
@endsection
