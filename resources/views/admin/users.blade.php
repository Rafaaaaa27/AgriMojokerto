@extends('layouts.native')

@section('content')
<div class="container" style="padding-top: 8rem; min-height: 100vh;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem;">
        <div>
            <h1 style="font-size: 3rem; font-weight: 900; color: var(--primary-dark);">Manajemen Pengguna</h1>
            <p style="color: var(--text-muted); font-size: 1.1rem;">Total {{ $users->total() }} akun terdaftar dalam sistem.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="glass-card" style="padding: 0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background: var(--background);">
                <tr>
                    <th style="padding: 1.5rem 2rem; font-weight: 800; font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">Pengguna</th>
                    <th style="padding: 1.5rem 2rem; font-weight: 800; font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">Role</th>
                    <th style="padding: 1.5rem 2rem; font-weight: 800; font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">Kontak</th>
                    <th style="padding: 1.5rem 2rem; font-weight: 800; font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">Status</th>
                    <th style="padding: 1.5rem 2rem; font-weight: 800; font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr style="border-top: 1px solid var(--border-color);">
                    <td style="padding: 1.5rem 2rem;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 45px; height: 45px; background: var(--primary); color: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 800;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight: 800; color: var(--primary-dark);">{{ $user->name }}</div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 1.5rem 2rem;">
                        <form action="{{ route('admin.users.role', $user->id) }}" method="POST" style="display: flex; gap: 0.5rem; align-items: center;">
                            @csrf @method('PATCH')
                            <select name="role" class="form-input" style="padding: 0.4rem; font-size: 0.75rem; width: auto;" onchange="this.form.submit()">
                                <option value="petani" {{ $user->role === 'petani' ? 'selected' : '' }}>Petani</option>
                                <option value="penjual" {{ $user->role === 'penjual' ? 'selected' : '' }}>Penjual</option>
                                <option value="pembeli" {{ $user->role === 'pembeli' ? 'selected' : '' }}>Pembeli</option>
                                <option value="penyuluh" {{ $user->role === 'penyuluh' ? 'selected' : '' }}>Penyuluh</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </form>
                    </td>
                    <td style="padding: 1.5rem 2rem;">
                        <div style="font-size: 0.9rem;">{{ $user->phone ?? '-' }}</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $user->city ?? '-' }}</div>
                    </td>
                    <td style="padding: 1.5rem 2rem;">
                        @if($user->is_active)
                            <span class="badge" style="background: #dcfce7; color: #166534;">AKTIF</span>
                        @else
                            <span class="badge" style="background: #fee2e2; color: #991b1b;">NON-AKTIF</span>
                        @endif
                    </td>
                    <td style="padding: 1.5rem 2rem;">
                        @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-secondary' : 'btn-primary' }}" style="padding: 0.5rem 1rem; font-size: 0.8rem;">
                                {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 3rem;">
        {{ $users->links() }}
    </div>
</div>

@push('styles')
<style>
    .badge { padding: 0.4rem 0.8rem; border-radius: 8px; font-weight: 900; font-size: 0.65rem; }
    .btn-sm { padding: 0.5rem 1rem; font-size: 0.8rem; }
</style>
@endpush
@endsection
