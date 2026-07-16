@extends('layouts.native')

@section('content')
<div class="container" style="padding-top: 7rem; padding-bottom: 4rem;">
  <div class="el-header animate-fade-up">
    <h1 class="el-title">E-Library Pertanian</h1>
    <p class="el-sub">Pusat informasi, panduan, dan ilmu pengetahuan dari para Penyuluh AgriMojokerto.</p>
  </div>

  <div class="el-toolbar animate-fade-up">
    <form action="{{ route('library.index') }}" method="GET" class="el-search">
      @if(request('category'))
        <input type="hidden" name="category" value="{{ request('category') }}">
      @endif
      <i class="fas fa-search el-search-icon"></i>
      <input type="text" name="search" placeholder="Cari artikel..." value="{{ request('search') }}" class="el-search-input">
      @if(request('search'))
        <a href="{{ route('library.index', ['category' => request('category')]) }}" class="el-search-clear"><i class="fas fa-times"></i></a>
      @endif
    </form>
    <div class="el-cats">
      <a href="{{ route('library.index') }}" class="el-cat {{ !request('category') ? 'active' : '' }}">Semua</a>
      @foreach(['Budidaya', 'Hama', 'Pupuk', 'Teknologi', 'Pengumuman'] as $cat)
      <a href="{{ route('library.index', ['category' => $cat, 'search' => request('search')]) }}" class="el-cat {{ request('category') === $cat ? 'active' : '' }}">{{ $cat }}</a>
      @endforeach
    </div>
  </div>

  <div class="el-grid animate-fade">
    @forelse($infos as $info)
    <a href="{{ route('library.show', $info->id) }}" class="el-card">
      <div class="el-card-img">
        @if($info->image_path)
          <img src="{{ asset('storage/' . $info->image_path) }}">
        @else
          <i class="fas fa-book-open"></i>
        @endif
      </div>
      <div class="el-card-body">
        <span class="el-badge">{{ $info->category }}</span>
        <h3 class="el-card-title">{{ $info->title }}</h3>
        <p class="el-card-excerpt">{{ Str::limit(strip_tags($info->content), 120) }}</p>
        <div class="el-card-foot">
          <div class="el-card-author">
            <span class="el-avatar">{{ strtoupper(substr($info->user->name, 0, 1)) }}</span>
            <span class="el-author-name">{{ $info->user->name }}</span>
          </div>
          @if($info->file_path)
          <span class="el-has-file"><i class="fas fa-paperclip"></i></span>
          @endif
        </div>
      </div>
    </a>
    @empty
    <div class="el-empty">
      <i class="fas fa-search"></i>
      <h3>
        @if(request('search'))
          Tidak ditemukan artikel dengan kata kunci "{{ request('search') }}"
        @else
          Belum ada informasi
        @endif
      </h3>
      <p>Coba ubah kata kunci atau pilih kategori lain.</p>
    </div>
    @endforelse
  </div>

  <div class="el-pagi">
    {{ $infos->links('vendor.pagination.agri') }}
  </div>
</div>
@endsection
