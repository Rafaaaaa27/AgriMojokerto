@extends('layouts.native')

@section('content')
<div class="forum-wrap">
    <div class="forum-hero">
        <h1 class="forum-hero-title">Forum Komunitas</h1>
        <p class="forum-hero-sub">Berbagi ilmu, pengalaman, dan solusi seputar pertanian modern di Mojokerto.</p>
    </div>

    <div class="forum-toolbar">
        <form action="{{ route('forum.index') }}" method="GET" class="forum-search">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <i class="fas fa-search forum-search-icon"></i>
            <input type="text" name="search" placeholder="Cari diskusi..." value="{{ request('search') }}" class="forum-search-input">
        </form>

        <div class="forum-sorts">
            @foreach(['terbaru' => 'Terbaru', 'terpopuler' => 'Populer', 'terbanyak' => 'Terbanyak'] as $key => $label)
                <a href="{{ route('forum.index', array_merge(request()->query(), ['sort' => $key])) }}"
                   class="forum-sort-btn {{ request('sort', 'terbaru') === $key ? 'active' : '' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        @auth
            <button onclick="openModal('modalNewPost')" class="forum-btn-primary">
                <i class="fas fa-plus"></i> Diskusi Baru
            </button>
        @else
            <a href="{{ route('login') }}" class="forum-btn-secondary">
                <i class="fas fa-sign-in-alt"></i> Login
            </a>
        @endauth
    </div>

    <div class="forum-layout">
        <div class="forum-main">
            <div class="forum-list">
                @isset($pinned)
                    @foreach($pinned as $post)
                        @include('forum._post_card', ['post' => $post, 'pinned' => true])
                    @endforeach
                @endisset

                @forelse($posts as $post)
                    @include('forum._post_card', ['post' => $post, 'pinned' => false])
                @empty
                    <div class="forum-empty">
                        <i class="fas fa-comments"></i>
                        <h3>
                            @if(request('search'))
                                Tidak ditemukan diskusi dengan kata kunci "{{ request('search') }}"
                            @else
                                Belum ada diskusi.
                            @endif
                        </h3>
                        <p>Jadilah yang pertama untuk memulai percakapan!</p>
                    </div>
                @endforelse
            </div>

            <div class="forum-pagination">
                {{ $posts->links('vendor.pagination.agri') }}
            </div>
        </div>

        <aside class="forum-sidebar">
            <div class="forum-card">
                <h3 class="forum-card-title">Kategori</h3>
                <div class="forum-categories">
                    @php
                        $categoryMaps = ['Budidaya' => 'Budidaya Padi', 'Pupuk' => 'Pupuk & Organik', 'Hama' => 'Hama & Penyakit', 'Alat' => 'Alat Modern', 'Umum' => 'Umum'];
                    @endphp
                    <a href="{{ route('forum.index', ['search' => request('search'), 'sort' => request('sort')]) }}"
                       class="forum-cat-item {{ !request('category') ? 'active' : '' }}">
                        <span>Semua</span>
                        <span class="forum-cat-count">{{ array_sum($categoryCounts) }}</span>
                    </a>
                    @foreach($categoryMaps as $catKey => $catLabel)
                        <a href="{{ route('forum.index', ['category' => $catKey, 'search' => request('search'), 'sort' => request('sort')]) }}"
                           class="forum-cat-item {{ request('category') === $catKey ? 'active' : '' }}">
                            <span>{{ $catLabel }}</span>
                            <span class="forum-cat-count">{{ $categoryCounts[$catKey] ?? 0 }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="forum-cta">
                <i class="fas fa-seedling forum-cta-icon"></i>
                <h4 class="forum-cta-title">E-Library</h4>
                <p class="forum-cta-text">Akses panduan bertani modern terlengkap di Mojokerto.</p>
                <a href="{{ route('library.index') }}" class="forum-cta-btn">Baca Sekarang</a>
            </div>
        </aside>
    </div>
</div>

@auth
<div id="modalNewPost" class="modal-overlay" onclick="if(event.target===this)closeModal('modalNewPost')">
    <div class="forum-modal" onclick="event.stopPropagation()">
        <button onclick="closeModal('modalNewPost')" class="forum-modal-close" aria-label="Tutup"><i class="fas fa-times"></i></button>
        <div class="forum-modal-head">
            <h3>Mulai Diskusi Baru</h3>
        </div>
        <form action="{{ route('forum.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="forum-field">
                <label class="forum-label">Judul Topik <span>*</span></label>
                <input type="text" name="title" placeholder="Apa yang ingin Anda diskusikan?" class="forum-input" required>
            </div>
            <div class="forum-field">
                <label class="forum-label">Kategori <span>*</span></label>
                <select name="category" class="forum-input" required>
                    <option value="">Pilih kategori</option>
                    <option value="Budidaya">Budidaya Padi</option>
                    <option value="Pupuk">Pupuk & Organik</option>
                    <option value="Hama">Hama & Penyakit</option>
                    <option value="Alat">Alat Tani Modern</option>
                    <option value="Umum">Umum</option>
                </select>
            </div>
            <div class="forum-field">
                <label class="forum-label">Detail <span>*</span></label>
                <textarea name="description" rows="4" class="forum-input forum-textarea" required placeholder="Berikan detail agar anggota lain dapat membantu..."></textarea>
            </div>
            <div class="forum-field">
                <label class="forum-label">Gambar <span class="forum-optional">(opsional)</span></label>
                <div class="forum-file-wrap">
                    <input type="file" name="image" accept="image/*" id="newPostImage">
                    <label for="newPostImage" class="forum-file-label"><i class="fas fa-cloud-upload-alt"></i> Pilih Gambar</label>
                    <span class="forum-file-name" id="newPostFileName">Tidak ada file dipilih</span>
                </div>
            </div>
            <div class="forum-modal-actions">
                <button type="submit" class="forum-btn-primary" style="flex:1; justify-content:center;">Posting Diskusi</button>
                <button type="button" onclick="closeModal('modalNewPost')" class="forum-btn-cancel">Batal</button>
            </div>
        </form>
    </div>
</div>
@endauth

@auth
<div id="modalEditPost" class="modal-overlay" onclick="if(event.target===this)closeModal('modalEditPost')">
    <div class="forum-modal" onclick="event.stopPropagation()">
        <button onclick="closeModal('modalEditPost')" class="forum-modal-close" aria-label="Tutup"><i class="fas fa-times"></i></button>
        <div class="forum-modal-head">
            <h3>Edit Diskusi</h3>
        </div>
        <form id="editPostForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="forum-field">
                <label class="forum-label">Judul Topik <span>*</span></label>
                <input type="text" name="title" id="edit_title" class="forum-input" required>
            </div>
            <div class="forum-field">
                <label class="forum-label">Kategori <span>*</span></label>
                <select name="category" id="edit_category" class="forum-input" required>
                    <option value="">Pilih kategori</option>
                    <option value="Budidaya">Budidaya Padi</option>
                    <option value="Pupuk">Pupuk & Organik</option>
                    <option value="Hama">Hama & Penyakit</option>
                    <option value="Alat">Alat Tani Modern</option>
                    <option value="Umum">Umum</option>
                </select>
            </div>
            <div class="forum-field">
                <label class="forum-label">Detail <span>*</span></label>
                <textarea name="description" id="edit_description" rows="4" class="forum-input forum-textarea" required></textarea>
            </div>
            <div class="forum-field">
                <label class="forum-label">Gambar <span class="forum-optional">(biarkan kosong jika tidak ingin mengubah)</span></label>
                <div class="forum-file-wrap">
                    <input type="file" name="image" accept="image/*" id="editPostImage">
                    <label for="editPostImage" class="forum-file-label"><i class="fas fa-cloud-upload-alt"></i> Pilih Gambar</label>
                    <span class="forum-file-name" id="editPostFileName">Tidak ada file dipilih</span>
                </div>
            </div>
            <div class="forum-modal-actions">
                <button type="submit" class="forum-btn-primary" style="flex:1; justify-content:center;">Simpan Perubahan</button>
                <button type="button" onclick="closeModal('modalEditPost')" class="forum-btn-cancel">Batal</button>
            </div>
        </form>
    </div>
</div>
@endauth

@push('scripts')
<script>
function openModal(id) {
    const m = document.getElementById(id);
    if (!m) return;
    m.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    const f = m.querySelector('input:not([type=hidden]), select, textarea');
    if (f) setTimeout(() => f.focus(), 50);
}
function closeModal(id) {
    const m = document.getElementById(id);
    if (!m) return;
    m.style.display = 'none';
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') document.querySelectorAll('.modal-overlay[style*="flex"]').forEach(m => {
        m.style.display = 'none';
        document.body.style.overflow = '';
    });
});

function openEditModal(postId, title, category, description) {
    document.getElementById('editPostForm').action = '/forum/' + postId;
    document.getElementById('edit_title').value = title;
    document.getElementById('edit_category').value = category;
    document.getElementById('edit_description').value = description;
    openModal('modalEditPost');
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

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('newPostImage')?.addEventListener('change', function(e) {
        document.getElementById('newPostFileName').textContent = e.target.files[0]?.name || 'Tidak ada file dipilih';
    });
    document.getElementById('editPostImage')?.addEventListener('change', function(e) {
        document.getElementById('editPostFileName').textContent = e.target.files[0]?.name || 'Tidak ada file dipilih';
    });
});
</script>
@endpush

@push('styles')
<style>
.forum-wrap {
    max-width: 1200px;
    margin: 0 auto;
    padding: 8rem 1.5rem 4rem;
}

.forum-hero {
    margin-bottom: 2.5rem;
}
.forum-hero-title {
    font-size: 1.75rem;
    font-weight: 900;
    color: var(--text-main);
    letter-spacing: -0.02em;
    margin-bottom: 0.35rem;
}
.forum-hero-sub {
    color: var(--text-muted);
    font-size: 0.95rem;
    max-width: 480px;
}

.forum-toolbar {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}

.forum-search {
    flex: 1;
    position: relative;
    min-width: 200px;
}
.forum-search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 0.85rem;
    pointer-events: none;
}
.forum-search-input {
    width: 100%;
    padding: 0.7rem 1rem 0.7rem 2.5rem;
    border-radius: 12px;
    border: 1.5px solid var(--border-color);
    background: var(--surface);
    color: var(--text-main);
    font-size: 0.88rem;
    font-weight: 500;
    outline: none;
    transition: border-color 0.2s;
}
.forum-search-input:focus {
    border-color: var(--primary);
}

.forum-sorts {
    display: flex;
    gap: 0.25rem;
    background: var(--surface-2);
    padding: 0.25rem;
    border-radius: 10px;
}
.forum-sort-btn {
    padding: 0.45rem 0.9rem;
    border-radius: 8px;
    font-size: 0.78rem;
    font-weight: 700;
    color: var(--text-muted);
    text-decoration: none;
    transition: all 0.2s;
}
.forum-sort-btn.active {
    background: var(--primary);
    color: #fff;
}
.forum-sort-btn:hover:not(.active) {
    color: var(--text-main);
}

.forum-btn-primary, .forum-btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.65rem 1.25rem;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.85rem;
    text-decoration: none;
    cursor: pointer;
    border: none;
    font-family: inherit;
    transition: all 0.2s;
    white-space: nowrap;
}
.forum-btn-primary {
    background: var(--primary);
    color: #fff;
}
.forum-btn-primary:hover {
    background: var(--primary-dark);
    box-shadow: 0 4px 14px rgba(5,150,105,0.3);
}
.forum-btn-secondary {
    background: var(--surface-2);
    color: var(--text-muted);
    border: 1px solid var(--border-color);
}
.forum-btn-secondary:hover {
    background: var(--border-color);
    color: var(--text-main);
}
.forum-btn-cancel {
    padding: 0.65rem 1.25rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.85rem;
    background: var(--background);
    color: var(--text-muted);
    border: 1px solid var(--border-color);
    cursor: pointer;
    font-family: inherit;
    transition: background 0.2s;
}
.forum-btn-cancel:hover {
    background: var(--border-color);
}

.forum-layout {
    display: grid;
    grid-template-columns: 1fr 280px;
    gap: 2rem;
    align-items: start;
}

.forum-main {
    min-width: 0;
}

.forum-list {
    display: grid;
    gap: 1.25rem;
}

.forum-empty {
    text-align: center;
    padding: 4rem 0;
}
.forum-empty i {
    font-size: 3rem;
    color: var(--text-muted);
    opacity: 0.2;
    margin-bottom: 1.25rem;
}
.forum-empty h3 {
    font-weight: 700;
    font-size: 1rem;
    color: var(--text-muted);
    margin-bottom: 0.5rem;
}
.forum-empty p {
    color: var(--text-muted);
    font-size: 0.88rem;
}

.forum-pagination {
    margin-top: 2rem;
}

.forum-sidebar {
    display: grid;
    gap: 1.25rem;
    position: sticky;
    top: 6rem;
}

.forum-card {
    background: var(--surface);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
}
.forum-card-title {
    font-weight: 800;
    font-size: 0.85rem;
    color: var(--text-main);
    margin-bottom: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.forum-categories {
    display: grid;
    gap: 0.35rem;
}
.forum-cat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.65rem 0.85rem;
    border-radius: 10px;
    text-decoration: none;
    font-size: 0.88rem;
    font-weight: 600;
    color: var(--text-secondary);
    transition: all 0.2s;
}
.forum-cat-item:hover {
    background: var(--background);
    color: var(--primary);
}
.forum-cat-item.active {
    background: rgba(5,150,105,0.08);
    color: var(--primary);
    font-weight: 700;
}
.forum-cat-count {
    font-size: 0.72rem;
    font-weight: 700;
    padding: 0.15rem 0.55rem;
    border-radius: 99px;
    background: var(--surface-2);
    color: var(--text-muted);
    border: 1px solid var(--border-color);
}

.forum-cta {
    background: var(--primary-gradient);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    color: white;
    position: relative;
    overflow: hidden;
}
.forum-cta-icon {
    position: absolute;
    right: -5px;
    bottom: -5px;
    font-size: 4.5rem;
    opacity: 0.15;
}
.forum-cta-title {
    font-weight: 800;
    font-size: 1rem;
    margin-bottom: 0.4rem;
    position: relative;
}
.forum-cta-text {
    font-size: 0.83rem;
    opacity: 0.9;
    margin-bottom: 1.25rem;
    position: relative;
    line-height: 1.5;
}
.forum-cta-btn {
    display: block;
    padding: 0.6rem;
    background: white;
    color: var(--primary);
    border-radius: 10px;
    font-weight: 800;
    font-size: 0.85rem;
    text-align: center;
    text-decoration: none;
    transition: transform 0.2s;
    position: relative;
}
.forum-cta-btn:hover {
    transform: translateY(-1px);
}

/* Modals */
.forum-modal {
    background: var(--surface);
    border-radius: var(--radius-xl);
    padding: 2.25rem 2.5rem;
    width: 90%;
    max-width: 560px;
    box-shadow: var(--shadow-xl);
    animation: modalIn 0.35s cubic-bezier(0.22, 1, 0.36, 1);
    position: relative;
}
@keyframes modalIn {
    from { opacity: 0; transform: scale(0.95) translateY(12px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}
.forum-modal-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-right: 2rem;
}
.forum-modal-head h3 {
    font-weight: 800;
    font-size: 1.2rem;
    color: var(--text-main);
    font-family: var(--font-heading);
}
.forum-modal-close {
    position: absolute;
    right: 1.15rem;
    top: 1.15rem;
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--surface-2);
    border: none;
    border-radius: 10px;
    color: var(--text-muted);
    cursor: pointer;
    transition: all 0.25s;
}
.forum-modal-close:hover {
    background: var(--danger-bg);
    color: var(--danger);
    transform: rotate(90deg);
}

.forum-field {
    margin-bottom: 1.15rem;
}
.forum-label {
    display: block;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
    margin-bottom: 0.4rem;
}
.forum-optional {
    font-weight: 500;
    text-transform: none;
    letter-spacing: 0;
    color: var(--text-muted);
    opacity: 0.5;
}
.forum-input {
    width: 100%;
    padding: 0.7rem 0.85rem;
    border-radius: var(--radius-sm);
    border: 1.5px solid var(--border-color);
    background: var(--surface);
    color: var(--text-main);
    font-size: 0.85rem;
    font-weight: 500;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    font-family: inherit;
    box-sizing: border-box;
}
.forum-input:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(6, 95, 70, 0.1);
}
.forum-textarea {
    height: auto;
    resize: vertical;
    min-height: 100px;
    line-height: 1.6;
}

.forum-file-wrap {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}
.forum-file-wrap input[type="file"] {
    display: none;
}
.forum-file-label {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.6rem 1rem;
    background: var(--surface-2);
    border: 1.5px solid var(--border-color);
    border-radius: var(--radius-sm);
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--text-secondary);
    cursor: pointer;
    transition: all 0.2s;
}
.forum-file-label:hover {
    border-color: var(--primary);
    color: var(--primary);
}
.forum-file-name {
    font-size: 0.8rem;
    color: var(--text-muted);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.forum-modal-actions {
    display: flex;
    gap: 0.75rem;
    margin-top: 1.5rem;
}

@media (max-width: 900px) {
    .forum-layout {
        grid-template-columns: 1fr;
    }
    .forum-sidebar {
        position: static;
        display: grid;
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 680px) {
    .forum-wrap {
        padding-top: 6rem;
    }
    .forum-toolbar {
        flex-direction: column;
        align-items: stretch;
    }
    .forum-search {
        min-width: 0;
    }
    .forum-sorts {
        align-self: flex-start;
    }
    .forum-sidebar {
        grid-template-columns: 1fr;
    }
    .forum-hero-title {
        font-size: 1.4rem;
    }
}
@media (max-width: 640px) {
    .forum-modal {
        padding: 1.5rem 1.5rem !important;
        width: 100%;
        border-radius: 20px 20px 0 0;
        max-height: 92vh;
        overflow-y: auto;
    }
    .forum-modal-head {
        margin-bottom: 1.25rem;
        padding-right: 0;
    }
    .forum-modal-head h3 {
        font-size: 1.1rem;
    }
    .forum-modal-close {
        top: 0.75rem;
        right: 0.75rem;
        width: 30px;
        height: 30px;
    }
    .forum-field {
        margin-bottom: 1rem;
    }
    .forum-input {
        padding: 0.65rem 0.75rem;
        font-size: 0.85rem;
    }
    .forum-modal-actions {
        flex-direction: column;
        gap: 0.5rem;
    }
    .forum-btn-primary, .forum-btn-cancel {
        width: 100%;
        padding: 0.7rem;
        font-size: 0.85rem;
    }
    .forum-file-wrap {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
}
@media (max-width: 480px) {
    .forum-modal {
        padding: 1.25rem 1.25rem !important;
    }
    .forum-modal-head h3 {
        font-size: 1rem;
    }
    .forum-modal-close {
        width: 28px;
        height: 28px;
        font-size: 0.9rem;
        top: 0.6rem;
        right: 0.6rem;
    }
    .forum-field {
        margin-bottom: 0.85rem;
    }
    .forum-label {
        font-size: 0.66rem;
        letter-spacing: 0.3px;
    }
    .forum-input {
        padding: 0.6rem 0.7rem;
        font-size: 0.82rem;
    }
}
</style>
@endpush
@endsection
