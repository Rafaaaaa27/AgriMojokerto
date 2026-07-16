@extends('layouts.native')

@section('content')
<div class="container" style="padding-top: 8rem; padding-bottom: 4rem; min-height: 100vh;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 3rem; font-weight: 900; color: var(--primary-dark);">Manajemen Produk</h1>
            <p style="color: var(--text-muted); font-size: 1.1rem;">Total {{ $products->total() }} produk terdaftar dalam marketplace.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            Kembali
        </a>
    </div>

    <div class="glass-card" style="padding: 0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background: var(--background);">
                <tr>
                    <th style="padding: 1.5rem 2rem; font-weight: 800; font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">Produk</th>
                    <th style="padding: 1.5rem 2rem; font-weight: 800; font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">Penjual</th>
                    <th style="padding: 1.5rem 2rem; font-weight: 800; font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">Harga</th>
                    <th style="padding: 1.5rem 2rem; font-weight: 800; font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">Status</th>
                    <th style="padding: 1.5rem 2rem; font-weight: 800; font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr style="border-top: 1px solid var(--border-color);">
                    <td style="padding: 1.5rem 2rem;">
                        <div style="font-weight: 800; color: var(--primary-dark);">{{ $product->name }}</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">{{ ucfirst($product->category) }}</div>
                    </td>
                    <td style="padding: 1.5rem 2rem;">
                        <div style="font-weight: 700;">{{ $product->user->name }}</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $product->user->email }}</div>
                    </td>
                    <td style="padding: 1.5rem 2rem;">
                        <div style="font-weight: 800; color: var(--primary);">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    </td>
                    <td style="padding: 1.5rem 2rem;">
                        <span class="badge {{ $product->approval_status === 'approved' ? 'badge-success' : ($product->approval_status === 'rejected' ? 'badge-danger' : 'badge-warning') }}">
                            {{ strtoupper($product->approval_status) }}
                        </span>
                    </td>
                    <td style="padding: 1.5rem 2rem;">
                        <div style="display: flex; gap: 0.5rem;">
                            @if($product->approval_status === 'pending')
                            <form action="{{ route('admin.product.approve', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary" style="padding:0.4rem 0.8rem;">Setujui</button>
                            </form>
                            @endif
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini secara permanen?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-secondary" style="padding:0.4rem 0.8rem; color: var(--danger);">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 3rem;">
        {{ $products->links() }}
    </div>
</div>


@endsection
