@extends('layouts.native')

@section('content')
<div class="container" style="padding-top: 8rem; min-height: 100vh;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem;">
        <div>
            <h1 style="font-size: 3rem; font-weight: 900; color: var(--primary-dark);">Manajemen Alat Tani</h1>
            <p style="color: var(--text-muted); font-size: 1.1rem;">Total {{ $equipments->total() }} alat berat dan tani terdaftar.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="glass-card" style="padding: 0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background: var(--background);">
                <tr>
                    <th style="padding: 1.5rem 2rem; font-weight: 800; font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">Alat</th>
                    <th style="padding: 1.5rem 2rem; font-weight: 800; font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">Pemilik</th>
                    <th style="padding: 1.5rem 2rem; font-weight: 800; font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">Sewa</th>
                    <th style="padding: 1.5rem 2rem; font-weight: 800; font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">Status</th>
                    <th style="padding: 1.5rem 2rem; font-weight: 800; font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($equipments as $eq)
                <tr style="border-top: 1px solid var(--border-color);">
                    <td style="padding: 1.5rem 2rem;">
                        <div style="font-weight: 800; color: var(--primary-dark);">{{ $eq->name }}</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $eq->type }}</div>
                    </td>
                    <td style="padding: 1.5rem 2rem;">
                        <div style="font-weight: 700;">{{ $eq->user->name }}</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $eq->user->phone }}</div>
                    </td>
                    <td style="padding: 1.5rem 2rem;">
                        <div style="font-weight: 800; color: var(--secondary);">Rp {{ number_format($eq->price, 0, ',', '.') }}/{{ $eq->unit }}</div>
                    </td>
                    <td style="padding: 1.5rem 2rem;">
                        <span class="badge {{ $eq->approval_status === 'approved' ? 'badge-success' : 'badge-warning' }}">
                            {{ strtoupper($eq->approval_status) }}
                        </span>
                    </td>
                    <td style="padding: 1.5rem 2rem;">
                        <div style="display: flex; gap: 0.5rem;">
                            @if($eq->approval_status === 'pending')
                            <form action="{{ route('admin.equipment.approve', $eq->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary" style="padding:0.4rem 0.8rem;"><i class="fas fa-check"></i></button>
                            </form>
                            @endif
                            <form action="{{ route('admin.equipments.destroy', $eq->id) }}" method="POST" onsubmit="return confirm('Hapus alat ini secara permanen?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-secondary" style="padding:0.4rem 0.8rem; color: var(--danger);"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 3rem;">
        {{ $equipments->links() }}
    </div>
</div>

@push('styles')
<style>
    .badge { padding: 0.4rem 0.8rem; border-radius: 8px; font-weight: 900; font-size: 0.65rem; }
    .badge-success { background: #dcfce7; color: #166534; }
    .badge-warning { background: #fef9c3; color: #854d0e; }
</style>
@endpush
@endsection
