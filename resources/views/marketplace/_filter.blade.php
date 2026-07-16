<form action="{{ route('marketplace.index') }}" method="GET" class="mkp-filter-form">
    @if(request('search'))
        <input type="hidden" name="search" value="{{ request('search') }}">
    @endif
    <div class="mkp-filter-group">
        <span class="mkp-filter-label">Kategori</span>
        <div class="mkp-filter-checkbox-group">
            @foreach(['benih' => 'Benih Unggul', 'pupuk' => 'Pupuk & Nutrisi', 'pestisida' => 'Pestisida', 'obat' => 'Obat Pertanian', 'alat' => 'Alat Tani', 'panen' => 'Alat Panen', 'hasil_panen' => 'Hasil Bumi'] as $val => $label)
            <label class="mkp-checkbox">
                <input type="checkbox" name="categories[]" value="{{ $val }}" {{ in_array($val, (array)request('categories')) ? 'checked' : '' }}>
                <span class="mkp-checkbox-mark"></span>
                {{ $label }}
            </label>
            @endforeach
        </div>
    </div>

    <div class="mkp-filter-group">
        <span class="mkp-filter-label">Urutkan Harga</span>
        <select name="sort" class="mkp-select">
            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Termurah</option>
            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Termahal</option>
        </select>
    </div>

    <div class="mkp-filter-actions">
        <button type="submit" class="mkp-btn-primary">Terapkan Filter</button>
        @if(request('categories') || request('sort') !== 'latest')
            <a href="{{ route('marketplace.index', request()->only('search')) }}" class="mkp-btn-reset">Reset</a>
        @endif
    </div>
</form>

@push('styles')
<style>
.mkp-filter-form {
    display: grid;
    gap: 1.5rem;
}
.mkp-filter-group {
    display: grid;
    gap: 0.75rem;
}
.mkp-filter-label {
    font-size: 0.7rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: var(--text-muted);
}
.mkp-filter-checkbox-group {
    display: grid;
    gap: 0.5rem;
}
.mkp-checkbox {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    cursor: pointer;
    font-weight: 600;
    font-size: 0.88rem;
    color: var(--text-secondary);
    padding: 0.35rem 0;
    transition: color 0.2s;
    position: relative;
}
.mkp-checkbox:hover {
    color: var(--text-main);
}
.mkp-checkbox input[type="checkbox"] {
    display: none;
}
.mkp-checkbox-mark {
    width: 18px;
    height: 18px;
    border-radius: 5px;
    border: 2px solid var(--border-color);
    background: var(--surface-2);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    flex-shrink: 0;
}
.mkp-checkbox input:checked + .mkp-checkbox-mark {
    background: var(--primary);
    border-color: var(--primary);
}
.mkp-checkbox input:checked + .mkp-checkbox-mark::after {
    content: '\\2713';
    color: white;
    font-size: 0.65rem;
    font-weight: 900;
    line-height: 1;
}
.mkp-select {
    width: 100%;
    padding: 0.6rem 0.85rem;
    border-radius: 10px;
    border: 1.5px solid var(--border-color);
    background: var(--surface-2);
    color: var(--text-main);
    font-size: 0.85rem;
    font-weight: 600;
    outline: none;
    font-family: inherit;
    cursor: pointer;
    transition: border-color 0.2s;
}
.mkp-select:focus {
    border-color: var(--primary);
}
.mkp-filter-actions {
    display: grid;
    gap: 0.5rem;
}
.mkp-btn-primary {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.65rem 1.25rem;
    border-radius: 10px;
    background: var(--primary);
    color: #fff;
    border: none;
    font-weight: 700;
    font-size: 0.85rem;
    cursor: pointer;
    font-family: inherit;
    transition: all 0.2s;
}
.mkp-btn-primary:hover {
    background: var(--primary-dark);
    box-shadow: 0 4px 14px rgba(5,150,105,0.3);
}
.mkp-btn-reset {
    display: block;
    text-align: center;
    font-size: 0.82rem;
    color: var(--text-muted);
    text-decoration: none;
    font-weight: 600;
    padding: 0.4rem;
    border-radius: 8px;
    transition: all 0.2s;
}
.mkp-btn-reset:hover {
    background: var(--surface-2);
    color: var(--text-main);
}
</style>
@endpush