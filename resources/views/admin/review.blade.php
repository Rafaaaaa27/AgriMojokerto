<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review {{ ucfirst($type) }} - AgriMojokerto</title>
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
<style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: var(--background); margin: 0; padding: 2rem; }
        .review-container { max-width: 800px; margin: 0 auto; background: var(--surface); padding: 2rem; border-radius: 12px; box-shadow: var(--shadow-md); }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid var(--border-color); padding-bottom: 1rem; margin-bottom: 2rem; }
        .back-btn { text-decoration: none; color: var(--text-muted); font-size: 0.9rem; }
        .back-btn:hover { color: var(--primary); }
        .item-image { width: 100%; max-height: 400px; object-fit: cover; border-radius: 8px; margin-bottom: 1.5rem; background: var(--surface-2); display: flex; align-items: center; justify-content: center; font-size: 4rem; color: var(--text-muted); }
        .detail-row { display: grid; grid-template-columns: 150px 1fr; margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem; }
        .detail-label { font-weight: bold; color: var(--text-muted); }
        .detail-value { color: var(--text-main); font-weight: 600; }
        .actions { display: flex; gap: 1rem; margin-top: 2rem; justify-content: flex-end; }
        .btn { padding: 0.8rem 1.5rem; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; color: white; display: inline-flex; align-items: center; }
        .btn-approve { background: var(--success); }
        .btn-approve:hover { background: var(--primary-dark); }
        .btn-reject { background: var(--danger); }
        .btn-reject:hover { background: var(--danger-dark); }
        @media (max-width: 768px) {
            body { padding: 1rem !important; }
            .review-container { padding: 1.25rem !important; }
            .header { flex-direction: column !important; align-items: flex-start !important; gap: 1rem !important; }
            .detail-row { grid-template-columns: 1fr !important; gap: 0.25rem !important; }
            .actions { flex-direction: column !important; }
            .actions .btn { width: 100% !important; justify-content: center !important; }
        }
    </style>
</head>
<body>

<div class="review-container">
    <div class="header">
        <h2 style="margin:0; color:var(--text-main);">Review Detail {{ ucfirst($type) }}</h2>
        <a href="{{ route('dashboard') }}#approval-queue" class="back-btn">← Kembali ke Dashboard</a>
    </div>

    @if($item->image_path)
        <img src="{{ $item->image_url }}" class="item-image" alt="Item Image">
    @else
        <div class="item-image" style="color: var(--text-muted);">Tidak Ada Gambar</div>
    @endif

    <div class="detail-row">
        <span class="detail-label">Nama Item</span>
        <span class="detail-value">{{ $item->name }}</span>
    </div>

    <div class="detail-row">
        <span class="detail-label">Pengaju / User</span>
        <span class="detail-value">{{ $item->user->name ?? 'Unknown' }} ({{ ucfirst($item->user->role ?? 'N/A') }})</span>
    </div>

    <div class="detail-row">
        <span class="detail-label">Harga</span>
        <span class="detail-value">Rp {{ number_format($item->price, 0, ',', '.') }} {{ $type === 'equipment' ? '/ ' . $item->unit : '' }}</span>
    </div>

    <div class="detail-row">
        <span class="detail-label">{{ $type === 'equipment' ? 'Ketersediaan' : 'Stok Kuantitas' }}</span>
        <span class="detail-value">{{ $item->quantity }}</span>
    </div>

    @if($type === 'product')
    <div class="detail-row">
        <span class="detail-label">Kategori</span>
        <span class="detail-value">{{ ucfirst($item->category) }}</span>
    </div>
    @endif

    @if($type === 'equipment')
    <div class="detail-row">
        <span class="detail-label">Tipe Alat</span>
        <span class="detail-value">{{ ucfirst($item->type) }}</span>
    </div>
    @endif

    <div class="detail-row" style="border:none;">
        <span class="detail-label">Deskripsi</span>
        <span class="detail-value" style="font-weight:normal; line-height:1.6;">{{ $item->description ?? 'Tidak ada deskripsi yang disertakan.' }}</span>
    </div>

    <div class="actions">
        <!-- Reject Form -->
        <form action="{{ route('admin.' . $type . '.reject', $item->id) }}" method="POST" style="margin:0;">
            @csrf
            <button type="submit" class="btn btn-reject" onclick="return confirm('Yakin ingin MENOLAK item ini?');">
                Tolak Layak
            </button>
        </form>

        <!-- Approve Form -->
        <form action="{{ route('admin.' . $type . '.approve', $item->id) }}" method="POST" style="margin:0;">
            @csrf
            <button type="submit" class="btn btn-approve" onclick="return confirm('Yakin ingin MENYETUJUI item ini?');">
                Setujui Item
            </button>
        </form>
    </div>
</div>

</body>
</html>
