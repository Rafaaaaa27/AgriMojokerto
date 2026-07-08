@extends('layouts.native')

@section('content')
<div class="container section" style="padding-top: 8rem;">
    <div style="max-width: 800px; margin: 0 auto;">
        <div class="glass-card" style="padding: 3rem;">
            <div style="text-align: center; margin-bottom: 3rem;">
                <h1 style="font-size: 2.5rem; font-weight: 800; color: var(--primary-dark); margin-bottom: 0.5rem;">Konfirmasi Pesanan</h1>
                <p style="color: var(--text-muted);">Tinjau pesanan Anda sebelum melanjutkan transaksi.</p>
            </div>

            @if(session('error'))
                <div style="background: rgba(239, 68, 68, 0.1); color: var(--danger); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem;">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            <div style="display: flex; gap: 2rem; background: var(--background); padding: 2rem; border-radius: var(--radius-lg); margin-bottom: 3rem; align-items: center;">
                <div style="width: 150px; height: 150px; background: white; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; font-size: 3rem; color: #cbd5e1; border: 1px solid var(--border-color); overflow: hidden;">
                    @if($item->image_url)
                        <img src="{{ $item->image_url }}" style="width:100%; height:100%; object-fit:cover;">
                    @else
                        <i class="fas {{ $type === 'equipment' ? 'fa-tools' : 'fa-box' }}"></i>
                    @endif
                </div>
                
                <div style="flex: 1;">
                    <h3 style="font-size: 1.5rem; font-weight: 800; margin-bottom: 0.5rem;">{{ $item->name }}</h3>
                    <p style="color: var(--text-muted); margin-bottom: 1rem;">Oleh: <span style="color: var(--text-main); font-weight: 700;">{{ $item->user->name ?? 'Toko' }}</span></p>
                    <div style="font-size: 1.75rem; font-weight: 800; color: var(--primary);">
                        Rp {{ number_format($item->price, 0, ',', '.') }}
                        @if($type === 'equipment')
                            <span style="font-size: 1rem; color: var(--text-muted); font-weight: normal;"> / {{ $item->unit }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">
                <input type="hidden" name="item_id" value="{{ $item->id }}">

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem;">
                    <div>
                        <label style="display: block; font-weight: 800; margin-bottom: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-size: 0.8rem;">Nama Lengkap</label>
                        <input type="text" name="buyer_name" value="{{ auth()->user()->name ?? '' }}" style="width: 100%; padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); font-size: 1rem; font-weight: 600;" required placeholder="Nama Anda...">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 800; margin-bottom: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-size: 0.8rem;">No. Handphone</label>
                        <input type="tel" name="buyer_phone" value="{{ auth()->user()->phone ?? '' }}" style="width: 100%; padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); font-size: 1rem; font-weight: 600;" required placeholder="08xxxxxxxxxx">
                    </div>
                </div>

                <div style="margin-bottom: 2rem;">
                    <label style="display: block; font-weight: 800; margin-bottom: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-size: 0.8rem;">Jumlah / Durasi</label>
                    <input type="number" name="quantity" min="1" max="{{ $item->quantity }}" value="1" style="width: 100%; padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); font-size: 1.1rem; font-weight: 700;" required>
                    <p style="margin-top: 0.5rem; font-size: 0.85rem; color: var(--text-muted);">Sisa Stok: <span style="font-weight: 700;">{{ $item->quantity }} {{ $type === 'equipment' ? 'Unit' : 'Item' }}</span></p>
                </div>

                @if($type === 'product')
                    <div style="margin-bottom: 2rem;">
                        <label style="display: block; font-weight: 800; margin-bottom: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-size: 0.8rem;">Alamat Pengiriman</label>
                        <textarea name="shipping_address" rows="3" style="width: 100%; padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); resize: none; font-family: inherit;" required placeholder="Jl. Raya No. 123, Mojokerto...">{{ auth()->user()->address ?? '' }}</textarea>
                    </div>
                @elseif($type === 'equipment')
                    <div style="margin-bottom: 2rem;">
                        <label style="display: block; font-weight: 800; margin-bottom: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-size: 0.8rem;">Pilih Tanggal Mulai Sewa</label>
                        <input type="date" name="booking_date" style="width: 100%; padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);" required min="{{ date('Y-m-d') }}">
                    </div>
                @endif

                <div style="background: rgba(245, 158, 11, 0.1); padding: 1.5rem; border-radius: var(--radius-lg); margin-bottom: 3rem; display: flex; gap: 1rem; border: 1px solid rgba(245, 158, 11, 0.2);">
                    <i class="fas fa-info-circle" style="color: var(--warning); font-size: 1.5rem;"></i>
                    <p style="font-size: 0.9rem; color: var(--warning); line-height: 1.6;">
                        <strong>Catatan Pembayaran:</strong> Transaksi akan masuk ke tahap menunggu verifikasi penjual. Pembayaran dilakukan melalui COD atau metode yang disepakati setelah penjual menghubungi Anda.
                    </p>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1.25rem; font-size: 1.1rem; justify-content: center; gap: 1rem;">
                    <i class="fas fa-check-circle"></i> Selesaikan Pesanan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
