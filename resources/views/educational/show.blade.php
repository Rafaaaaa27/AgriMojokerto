@extends('layouts.native')

@section('content')
<div class="container" style="padding-top: 10rem; padding-bottom: 4rem;">
    <div style="max-width: 900px; margin: 0 auto;">
        <a href="{{ route('library.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Library
        </a>

        <div class="animate-fade">
            <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--primary); margin-bottom: 1.5rem; display: inline-block;">{{ $info->category }}</span>
            <h1 style="font-size: 3.5rem; font-weight: 900; color: var(--primary-dark); line-height: 1.1; margin-bottom: 2rem;">{{ $info->title }}</h1>

            <div style="display: flex; align-items: center; gap: 1.5rem; margin-bottom: 3rem; padding-bottom: 3rem; border-bottom: 1px solid var(--border-color); flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 50px; height: 50px; background: var(--primary); color: white; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: 800;">
                        {{ strtoupper(substr($info->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight: 800; font-size: 1.1rem; color: var(--primary-dark);">{{ $info->user->name }}</div>
                        <div style="font-size: 0.9rem; color: var(--text-muted);">Penyuluh AgriMojokerto • {{ $info->created_at->format('d M Y') }}</div>
                    </div>
                </div>
                <div style="display: flex; gap: 1.5rem; margin-left: auto; color: var(--text-muted); font-size: 0.9rem; flex-wrap: wrap;">
                    <span><i class="fas fa-eye"></i> {{ $info->views }} Pembaca</span>
                    <span><i class="fas fa-clock"></i> {{ $readingTime ?? 1 }} menit baca</span>
                    @if($info->file_path)
                    <a href="{{ route('library.download', $info->id) }}" class="btn btn-primary" style="padding: 0.5rem 1.25rem; font-size: 0.8rem; gap: 0.5rem;">
                        <i class="fas fa-download"></i> {{ $info->file_name ?? 'Download File' }}
                    </a>
                    @endif
                </div>
            </div>

            @if($info->image_path)
            <div class="glass-card" style="padding: 0; overflow: hidden; margin-bottom: 4rem;">
                <img src="{{ asset('storage/' . $info->image_path) }}" style="width: 100%; max-height: 500px; object-fit: cover;">
            </div>
            @endif

            <article style="font-size: 1.2rem; line-height: 1.8; color: var(--text-main); white-space: pre-wrap;">{{ $info->content }}</article>

            @if($info->file_path)
            <div style="margin-top: 3rem; padding: 2rem; background: var(--background); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <i class="fas fa-file-pdf" style="font-size: 2rem; color: var(--danger);"></i>
                    <div>
                        <div style="font-weight: 800;">{{ $info->file_name ?? 'Dokumen' }}</div>
                        <div style="font-size: 0.85rem; color: var(--text-muted);">File pendukung artikel ini</div>
                    </div>
                </div>
                <a href="{{ route('library.download', $info->id) }}" class="btn btn-primary" style="padding: 1rem 2rem; gap: 0.75rem;">
                    <i class="fas fa-download"></i> Download
                </a>
            </div>
            @endif

            <div class="glass-card" style="margin-top: 6rem; padding: 3rem; background: var(--primary-dark); color: white; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <h3 style="font-weight: 800; margin-bottom: 0.5rem;">Punya pertanyaan lebih lanjut?</h3>
                    <p style="opacity: 0.8;">Diskusikan topik ini bersama para petani lainnya di forum komunitas.</p>
                </div>
                <a href="{{ route('forum.index') }}" class="btn" style="background: var(--surface); color: var(--primary-dark); padding: 1rem 2.5rem;">Buka Forum Diskusi</a>
            </div>
        </div>
    </div>
</div>


@endsection
