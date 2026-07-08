@extends('layouts.native')

@section('content')
<div class="container section" style="display: flex; align-items: center; justify-content: center; min-height: 70vh;">
    <div class="glass-card animate-fade" style="max-width: 600px; text-align: center; padding: 4rem;">
        <div style="width: 100px; height: 100px; background: var(--success); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 3.5rem; margin: 0 auto 2.5rem auto; box-shadow: 0 15px 35px rgba(16, 185, 129, 0.3);">
            <i class="fas fa-check"></i>
        </div>
        <h1 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 1.5rem; color: var(--primary-dark);">Pesanan Berhasil!</h1>
        <p style="color: var(--text-muted); font-size: 1.1rem; line-height: 1.8; margin-bottom: 3rem;">
            Terima kasih! Permintaan Anda telah berhasil dikirimkan. Penjual akan segera meninjau dan menghubungi Anda untuk langkah selanjutnya.
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center;">
            <a href="{{ route('profile.edit') }}" class="btn btn-primary">Lihat Status</a>
            <a href="{{ url('/') }}" class="btn btn-secondary">Beranda</a>
        </div>
    </div>
</div>
@endsection
