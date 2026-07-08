@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="pagination-nav" style="display: flex; flex-direction: column; gap: 0.75rem;">

        {{-- Mobile: prev/next --}}
        <div class="pagination-mobile" style="display: flex; gap: 0.5rem; justify-content: space-between;">
            @if ($paginator->onFirstPage())
                <span class="pagination-btn pagination-btn-disabled">
                    &laquo; Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination-btn pagination-btn-link">
                    &laquo; Sebelumnya
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination-btn pagination-btn-link">
                    Selanjutnya &raquo;
                </a>
            @else
                <span class="pagination-btn pagination-btn-disabled">
                    Selanjutnya &raquo;
                </span>
            @endif
        </div>

        {{-- Desktop: full pagination --}}
        <div class="pagination-desktop" style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">

            {{-- Page info --}}
            <span class="pagination-info">
                Menampilkan
                @if ($paginator->firstItem())
                    <span class="pagination-info-num">{{ $paginator->firstItem() }}</span>
                    - <span class="pagination-info-num">{{ $paginator->lastItem() }}</span>
                @else
                    {{ $paginator->count() }}
                @endif
                dari <span class="pagination-info-num">{{ $paginator->total() }}</span>
            </span>

            {{-- Page buttons --}}
            <div class="pagination-buttons" style="display: flex; gap: 0.25rem; margin-left: auto;">
                @if ($paginator->onFirstPage())
                    <span class="pagination-page pagination-page-disabled">&lsaquo;</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination-page pagination-page-link">&lsaquo;</a>
                @endif

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="pagination-page pagination-page-disabled">{{ $element }}</span>
                    @endif
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="pagination-page pagination-page-active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="pagination-page pagination-page-link">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination-page pagination-page-link">&rsaquo;</a>
                @else
                    <span class="pagination-page pagination-page-disabled">&rsaquo;</span>
                @endif
            </div>
        </div>
    </nav>

    <style>
        .pagination-btn { flex: 1; text-align: center; padding: 0.5rem 1rem; font-size: 0.8rem; font-weight: 600; border-radius: 8px; text-decoration: none; transition: all 0.2s; }
        .pagination-btn-disabled { color: var(--text-muted); background: var(--card-bg); border: 1px solid var(--border); opacity: 0.5; cursor: not-allowed; }
        .pagination-btn-link { color: var(--text-main); background: var(--card-bg); border: 1px solid var(--border); }
        .pagination-btn-link:hover { border-color: var(--primary); color: var(--primary); }
        .pagination-info { font-size: 0.78rem; color: var(--text-muted); font-weight: 600; }
        .pagination-info-num { font-weight: 700; }
        .pagination-page { padding: 0.35rem 0.65rem; font-size: 0.75rem; border-radius: 6px; text-decoration: none; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; min-width: 28px; }
        .pagination-page-link { color: var(--text-main); background: var(--card-bg); border: 1px solid var(--border); }
        .pagination-page-link:hover { border-color: var(--primary); color: var(--primary); }
        .pagination-page-disabled { color: var(--text-muted); background: var(--card-bg); border: 1px solid var(--border); opacity: 0.5; cursor: not-allowed; }
        .pagination-page-active { font-weight: 700; color: #fff; background: var(--primary); border: 1px solid var(--primary); }
        @media (max-width: 639px) { .pagination-desktop { display: none !important; } }
        @media (min-width: 640px) { .pagination-mobile { display: none !important; } }
    </style>
@endif