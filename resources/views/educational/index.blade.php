@extends('layouts.native')

@section('content')
<div class="hero-sub" style="padding-top: 8rem; padding-bottom: 4rem; text-align: center;">
    <div class="container animate-fade-up">
        <h1 style="font-size: 3rem; font-weight: 900; margin-bottom: 1rem;">E-Library Pertanian</h1>
        <p style="font-size: 1.2rem; opacity: 0.85; max-width: 600px; margin: 0 auto;">Pusat informasi, panduan, dan ilmu pengetahuan dari para Penyuluh AgriMojokerto.</p>
    </div>
</div>

<div class="container section">
    <div style="display: grid; grid-template-columns: 280px 1fr; gap: 3rem;">
        <!-- SIDEBAR / FILTERS -->
        <aside>
            <div class="glass-card" style="padding: 2rem; position: sticky; top: 8rem;">
                <h3 style="font-weight: 800; margin-bottom: 1.5rem;">Kategori</h3>
                <div style="display: grid; gap: 0.5rem;">
                    @foreach(['Budidaya', 'Hama', 'Pupuk', 'Teknologi', 'Pengumuman'] as $cat)
                    <a href="{{ route('library.index', ['category' => $cat, 'search' => request('search')]) }}" class="filter-item {{ request('category') === $cat ? 'active' : '' }}">
                        {{ $cat }}
                    </a>
                    @endforeach
                    <a href="{{ route('library.index') }}" class="filter-item {{ !request('category') && !request('search') ? 'active' : '' }}" style="margin-top: 1rem; text-align: center; border: 1px solid var(--border-color); color: var(--text-muted);">Reset Filter</a>
                </div>
            </div>
        </aside>

        <!-- CONTENT -->
        <main>
            <!-- Search -->
            <div style="margin-bottom: 2rem;">
                <form action="{{ route('library.index') }}" method="GET" style="display: flex; gap: 0.75rem;">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <input type="text" name="search" placeholder="Cari artikel..." value="{{ request('search') }}"
                           style="flex: 1; padding: 0.85rem 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); font-size: 0.95rem;">
                    <button type="submit" class="btn btn-primary" style="padding: 0.85rem 1.5rem;"><i class="fas fa-search"></i></button>
                    @if(request('search'))
                        <a href="{{ route('library.index', ['category' => request('category')]) }}" class="btn btn-secondary" style="padding: 0.85rem 1.5rem;"><i class="fas fa-times"></i></a>
                    @endif
                </form>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2rem;">
                @forelse($infos as $info)
                <div class="glass-card info-card animate-fade">
                    <div style="height: 200px; background: #eee; overflow: hidden;">
                        @if($info->image_path)
                            <img src="{{ asset('storage/' . $info->image_path) }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#cbd5e1;"><i class="fas fa-book-open fa-3x"></i></div>
                        @endif
                    </div>
                    <div style="padding: 2rem; display: flex; flex-direction: column; flex: 1;">
                        <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--primary); margin-bottom: 1rem; display: inline-block; align-self: flex-start;">{{ $info->category }}</span>
                        <h3 style="font-weight: 800; font-size: 1.25rem; margin-bottom: 1rem; line-height: 1.4;">{{ $info->title }}</h3>
                        <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 1rem; line-height: 1.6; flex: 1;">{{ Str::limit(strip_tags($info->content), 120) }}</p>
                        <div style="display: flex; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 1rem;">
                            @if($info->file_path)
                            <a href="{{ route('library.download', $info->id) }}" class="btn btn-secondary" style="padding: 0.4rem 1rem; font-size: 0.75rem; gap: 0.4rem;">
                                <i class="fas fa-download"></i> {{ $info->file_name ?? 'Download' }}
                            </a>
                            @endif
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1.5rem; border-top: 1px solid var(--border-color);">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div style="width: 30px; height: 30px; background: var(--primary-dark); color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 800;">
                                    {{ strtoupper(substr($info->user->name, 0, 1)) }}
                                </div>
                                <span style="font-size: 0.85rem; font-weight: 700;">{{ $info->user->name }}</span>
                            </div>
                            <a href="{{ route('library.show', $info->id) }}" class="btn btn-primary" style="padding: 0.5rem 1.25rem; font-size: 0.8rem;">Baca Detail</a>
                        </div>
                    </div>
                </div>
                @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 5rem 0;">
                    <i class="fas fa-search fa-3x" style="color: #cbd5e1; margin-bottom: 2rem;"></i>
                    <h3 style="font-weight: 800; color: var(--text-muted);">
                        @if(request('search'))
                            Tidak ditemukan artikel dengan kata kunci "{{ request('search') }}"
                        @else
                            Informasi tidak ditemukan
                        @endif
                    </h3>
                    <p style="color: var(--text-muted);">Belum ada informasi untuk kategori ini.</p>
                </div>
                @endforelse
            </div>
            <div style="margin-top: 4rem;">
                {{ $infos->links() }}
            </div>
        </main>
    </div>
</div>

@push('styles')
<style>
    .filter-item {
        display: block;
        padding: 0.85rem 1.25rem;
        border-radius: 12px;
        color: var(--text-muted);
        text-decoration: none;
        font-weight: 700;
        transition: all 0.3s ease;
    }
    .filter-item:hover { background: rgba(16, 185, 129, 0.05); color: var(--primary); }
    .filter-item.active { background: var(--primary); color: white; box-shadow: 0 10px 20px rgba(16, 185, 129, 0.2); }
    .info-card { padding: 0; overflow: hidden; height: 100%; display: flex; flex-direction: column; transition: transform 0.3s ease; }
    .info-card:hover { transform: translateY(-10px); }
    .badge { padding: 0.3rem 1rem; border-radius: 99px; font-weight: 800; font-size: 0.7rem; text-transform: uppercase; }
</style>
@endpush
@endsection
