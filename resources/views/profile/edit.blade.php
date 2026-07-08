@extends('layouts.native')

@section('content')
<div class="container" style="padding-top: 8rem; min-height: 100vh;">
    <div style="display: grid; grid-template-columns: 260px 1fr; gap: 2rem; align-items: start;">
        @include('layouts.sidebar')

        <!-- MAIN CONTENT -->
        <main>
            @if($menu === 'dashboard')
            <div class="animate-fade">
                <div style="margin-bottom: 2rem;">
                    <h1 style="font-size: 2rem; font-weight: 800; color: var(--text-main);">Selamat Datang, {{ explode(' ', $user->name)[0] }}!</h1>
                    <p style="color: var(--text-muted);">Berikut adalah ringkasan aktivitas {{ $user->role === 'penjual' ? 'toko' : 'pertanian' }} Anda hari ini.</p>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                    @if($user->role === 'penjual')
                    <div class="stat-card">
                        <div class="icon" style="background: rgba(16, 185, 129, 0.1); color: var(--primary);"><i class="fas fa-check-circle"></i></div>
                        <div class="value">{{ $stats['approved_listings'] }}</div>
                        <div class="label">Listing Disetujui</div>
                        @if($stats['pending_listings'] > 0)
                        <div style="font-size: 0.7rem; color: var(--warning); margin-top: 0.5rem; font-weight: 700;">{{ $stats['pending_listings'] }} Menunggu</div>
                        @endif
                    </div>
                    <div class="stat-card">
                        <div class="icon" style="background: rgba(59, 130, 246, 0.1); color: var(--secondary);"><i class="fas fa-chart-line"></i></div>
                        <div class="value">{{ $stats['sales_count'] }}</div>
                        <div class="label">Total Penjualan</div>
                    </div>
                    <div class="stat-card">
                        <div class="icon" style="background: rgba(245, 158, 11, 0.1); color: var(--accent);"><i class="fas fa-wallet"></i></div>
                        <div class="value">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</div>
                        <div class="label">Pendapatan</div>
                    </div>
                    @endif

                    @if($user->role === 'petani')
                    <div class="stat-card" style="display: flex; flex-direction: column; align-items: center; justify-content: center; background: white; border-radius: var(--radius-lg); padding: 1.5rem; text-align: center; border: 1px solid var(--border-color); height: 100%;">
                        <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem;">Cuaca Hari Ini</div>
                        <div style="display: flex; align-items: center; justify-content: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                            <i class="fas fa-sun" id="weather-icon-card" style="font-size: 2.2rem; color: #f59e0b;"></i>
                            <span style="font-size: 1.8rem; font-weight: 900; color: var(--primary-dark);" id="weather-temp-card">25°C</span>
                        </div>
                        <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 600;" id="weather-condition-card">Cerah Berawan</div>
                    </div>
                    <div class="stat-card" style="display: flex; flex-direction: column; align-items: center; justify-content: center; border-radius: var(--radius-lg); padding: 1.5rem; text-align: center; border: 1px solid var(--border-color); height: 100%;">
                        <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem;">Harga Padi</div>
                        <div style="font-size: 1.6rem; font-weight: 900; color: var(--text-main); margin-bottom: 0.25rem;"><span id="price-padi-profile">—</span> <span style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted);">/kg</span></div>
                        <div style="font-size: 0.85rem; font-weight: 700;"><span id="change-padi-profile">—</span></div>
                    </div>
                    <div class="stat-card" style="display: flex; flex-direction: column; align-items: center; justify-content: center; background: white; border-radius: var(--radius-lg); padding: 1.5rem; text-align: center; border: 1px solid var(--border-color); height: 100%;">
                        <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem;">Produksi Bulan Ini</div>
                        <div style="font-size: 1.6rem; font-weight: 900; color: var(--text-main); margin-bottom: 0.25rem;">12,5 <span style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted);">ton</span></div>
                        <div style="font-size: 0.85rem; color: #059669; font-weight: 700;"><i class="fas fa-caret-up"></i> + 8.6%</div>
                    </div>
                    <div class="stat-card" style="display: flex; flex-direction: column; align-items: center; justify-content: center; background: white; border-radius: var(--radius-lg); padding: 1.5rem; text-align: center; border: 1px solid var(--border-color); height: 100%;">
                        <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; margin-bottom: 0.5rem;">Lahan Aktif</div>
                        <div style="font-size: 1.6rem; font-weight: 900; color: var(--text-main); margin-bottom: 0.25rem;">4 <span style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted);">Lahan</span></div>
                        <div style="font-size: 0.85rem; color: #854d0e; font-weight: 700;">Aktif</div>
                    </div>
                    @endif

                    @if($user->role === 'pembeli')
                    <div class="stat-card">
                        <div class="icon" style="background: rgba(16, 185, 129, 0.1); color: var(--primary);"><i class="fas fa-shopping-basket"></i></div>
                        <div class="value">{{ $stats['my_orders_count'] }}</div>
                        <div class="label">Pesanan Barang</div>
                    </div>
                    <div class="stat-card">
                        <div class="icon" style="background: rgba(59, 130, 246, 0.1); color: var(--secondary);"><i class="fas fa-tools"></i></div>
                        <div class="value">{{ $stats['my_bookings_count'] }}</div>
                        <div class="label">Sewa Alat</div>
                    </div>
                    @endif

                    @if($user->role === 'penyuluh')
                    <div class="stat-card">
                        <div class="icon" style="background: rgba(16, 185, 129, 0.1); color: var(--primary);"><i class="fas fa-bullhorn"></i></div>
                        <div class="value">{{ $stats['educational_count'] }}</div>
                        <div class="label">Konten Edukasi</div>
                    </div>
                    <div class="stat-card">
                        <div class="icon" style="background: rgba(59, 130, 246, 0.1); color: var(--secondary);"><i class="fas fa-eye"></i></div>
                        <div class="value">{{ number_format($stats['total_educational_views'], 0, ',', '.') }}</div>
                        <div class="label">Total Pembaca</div>
                    </div>
                    @endif
                </div>

                <!-- MARKET PRICE CHARTS - ALL ROLES -->
                <div style="margin-bottom: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <h3 style="font-weight: 800;">Grafik Harga Pasar</h3>
                        <span style="font-size: 0.78rem; color: var(--text-muted); font-weight: 600;">Sumber: Dinas Pertanian Mojokerto</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="glass-card" style="padding: 1.5rem;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem;">
                                <div>
                                    <div style="font-size: 0.7rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Padi /kg</div>
                                    <div style="font-size: 1.5rem; font-weight: 900; color: var(--text-main); margin-top: 0.25rem;" id="price-padi-chart">—</div>
                                </div>
                                <span id="change-padi-chart" style="font-size: 0.8rem; font-weight: 700; padding: 0.2rem 0.65rem; border-radius: 99px; background: rgba(5,150,105,0.1); color: #059669;">—</span>
                            </div>
                            <div style="position: relative; height: 170px;">
                                <canvas id="chartPadiProfile"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;">
                    <div class="glass-card" style="padding: 2rem;">
                        <h3 style="font-weight: 800; margin-bottom: 1.5rem;">Aktivitas Terakhir</h3>
                        <div style="display: grid; gap: 1rem;">
                            @forelse($incomingOrders->take(5) as $ord)
                            <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem; background: var(--background); border-radius: var(--radius-md);">
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <div style="width: 40px; height: 40px; background: var(--surface-2); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--primary);">
                                        <i class="fas fa-shopping-bag"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 700; font-size: 0.9rem;">Pesanan baru: {{ $ord->product->name }}</div>
                                        <div style="font-size: 0.75rem; color: var(--text-muted);">Dari {{ $ord->buyer_name ?? $ord->user->name }} | {{ $ord->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                <span class="badge {{ $ord->status === 'pending' ? 'badge-warning' : 'badge-success' }}">{{ $ord->status }}</span>
                            </div>
                            @empty
                            <p style="text-align: center; color: var(--text-muted); font-size: 0.9rem;">Belum ada aktivitas terbaru.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            @elseif($menu === 'products')
            <div class="animate-fade">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                    <div>
                        <h2 style="font-weight: 800;">Produk & Alat Sewa Saya</h2>
                        <p style="color: var(--text-muted);">Kelola katalog produk dan alat sewa Anda.</p>
                    </div>
                    <div style="display: flex; gap: 1rem;">
                        <button onclick="openProductModal()" class="btn btn-primary"><i class="fas fa-plus"></i> Produk</button>
                        <button onclick="openEquipmentModal()" class="btn btn-secondary"><i class="fas fa-plus"></i> Alat</button>
                    </div>
                </div>

                <!-- Products Grid -->
                <h3 style="font-weight: 800; margin-top: 2rem; margin-bottom: 1.5rem; color: var(--primary-dark);">{{ $user->role === 'petani' ? 'Hasil Bumi / Panen' : 'Daftar Produk' }}</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 3.5rem;">
                    @forelse($myProducts as $prod)
                    <div class="glass-card" style="padding: 1.25rem;">
                        <div style="height: 150px; background: var(--surface-2); border-radius: var(--radius-md); margin-bottom: 1rem; overflow: hidden;">
                            @if($prod->image_path)
                            <img src="{{ $prod->image_url }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #ccc;"><i class="fas fa-image fa-2x"></i></div>
                            @endif
                        </div>
                        <h4 style="font-weight: 800; margin-bottom: 0.25rem;">{{ $prod->name }}</h4>
                        <div style="color: var(--primary); font-weight: 700; margin-bottom: 0.5rem;">Rp {{ number_format($prod->price, 0, ',', '.') }}</div>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem; margin-bottom: 1rem;">
                            @if($user->role === 'petani')
                            <span style="display: inline-flex; align-items: center; gap: 0.35rem; color: var(--success); font-weight: 700;"><span style="width: 6px; height: 6px; background: var(--success); border-radius: 50%; display: inline-block;"></span> Aktif</span>
                            @else
                            <span class="badge {{ $prod->approval_status === 'approved' ? 'badge-success' : 'badge-warning' }}">{{ $prod->approval_status }}</span>
                            @endif
                            <span style="color: var(--text-muted);">Stok: {{ $prod->quantity }}</span>
                        </div>
                        <div style="display: flex; gap: 0.5rem; justify-content: flex-end; border-top: 1px solid var(--border-color); padding-top: 1rem;">
                            <button onclick="openProductModal({{ json_encode($prod) }})" class="action-btn-icon"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('products.destroy', $prod->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn-icon action-btn-danger"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div style="grid-column: 1/-1; text-align: center; padding: 2rem; color: var(--text-muted);">Belum ada produk yang diunggah.</div>
                    @endforelse
                </div>

                <!-- Equipments Grid -->
                <h3 style="font-weight: 800; margin-top: 2rem; margin-bottom: 1.5rem; color: var(--primary-dark);">Alat Pertanian Disewakan</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem;">
                    @forelse($myEquipments as $eq)
                    <div class="glass-card" style="padding: 1.25rem;">
                        <div style="height: 150px; background: var(--surface-2); border-radius: var(--radius-md); margin-bottom: 1rem; overflow: hidden;">
                            @if($eq->image_path)
                            <img src="{{ $eq->image_url }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #ccc;"><i class="fas fa-tractor fa-2x"></i></div>
                            @endif
                        </div>
                        <h4 style="font-weight: 800; margin-bottom: 0.25rem;">{{ $eq->name }}</h4>
                        <div style="color: var(--primary); font-weight: 700; margin-bottom: 0.5rem;">Rp {{ number_format($eq->price, 0, ',', '.') }} / {{ $eq->unit }}</div>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem; margin-bottom: 1rem;">
                            @if($user->role === 'petani')
                            <span style="display: inline-flex; align-items: center; gap: 0.35rem; color: var(--success); font-weight: 700;"><span style="width: 6px; height: 6px; background: var(--success); border-radius: 50%; display: inline-block;"></span> Aktif</span>
                            @else
                            <span class="badge {{ $eq->approval_status === 'approved' ? 'badge-success' : 'badge-warning' }}">{{ $eq->approval_status }}</span>
                            @endif
                            <span style="color: var(--text-muted);">Jumlah: {{ $eq->quantity }}</span>
                        </div>
                        <div style="display: flex; gap: 0.5rem; justify-content: flex-end; border-top: 1px solid var(--border-color); padding-top: 1rem;">
                            <button onclick="openEquipmentModal({{ json_encode($eq) }})" class="action-btn-icon"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('equipments.destroy', $eq->id) }}" method="POST" onsubmit="return confirm('Hapus alat sewa ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn-icon action-btn-danger"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div style="grid-column: 1/-1; text-align: center; padding: 2rem; color: var(--text-muted);">Belum ada alat pertanian yang disewakan.</div>
                    @endforelse
                </div>
            </div>

            @elseif($menu === 'incoming')
            <div class="animate-fade">
                <h2 style="font-weight: 800; margin-bottom: 2rem;">Pesanan Masuk</h2>
                <div style="display: grid; gap: 1rem;">
                    @forelse($incomingOrders as $ord)
                    <div class="glass-card" style="padding: 1.5rem; display: grid; grid-template-columns: 1fr auto auto; align-items: center; gap: 2rem;">
                        <div>
                            <div style="font-weight: 800; font-size: 1.1rem;">{{ $ord->product->name }}</div>
                            <div style="font-size: 0.9rem; color: var(--text-muted);">
                                <strong>Pembeli:</strong> {{ $ord->buyer_name ?? $ord->user->name }} | <strong>No. HP:</strong> {{ $ord->buyer_phone ?? $ord->user->phone ?? '-' }} | <strong>Harga:</strong> Rp {{ number_format($ord->total_price, 0, ',', '.') }}
                            </div>
                            <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.5rem;">
                                <i class="fas fa-map-marker-alt"></i> {{ $ord->shipping_address }}
                            </div>
                        </div>
                        <span class="badge {{ $ord->status === 'pending' ? 'badge-warning' : 'badge-success' }}">{{ $ord->status }}</span>
                        @if($ord->status === 'pending')
                        <form action="{{ route('seller.order.update', $ord->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="btn btn-primary btn-sm">Selesaikan</button>
                        </form>
                        @endif
                    </div>
                    @empty
                    <p style="text-align: center; color: var(--text-muted); padding: 3rem;">Belum ada pesanan masuk.</p>
                    @endforelse
                </div>
            </div>

            @elseif($menu === 'orders')
            <div class="animate-fade">
                <h2 style="font-weight: 800; margin-bottom: 2rem;">Pesanan Saya</h2>
                <div style="display: grid; gap: 1rem;">
                    @forelse($myOrders as $ord)
                    <div class="glass-card" style="padding: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h4 style="font-weight: 800; margin-bottom: 0.25rem;">{{ $ord->product->name }}</h4>
                            <div style="font-size: 0.85rem; color: var(--text-muted);">
                                {{ $ord->created_at->format('d M Y') }} | Rp {{ number_format($ord->total_price, 0, ',', '.') }}
                            </div>
                            <div style="font-size: 0.8rem; color: var(--primary); font-weight: 700; margin-top: 0.25rem;">
                                Penjual: {{ $ord->seller->name }}
                            </div>
                        </div>
                        <span class="badge {{ $ord->status === 'pending' ? 'badge-warning' : 'badge-success' }}">{{ $ord->status }}</span>
                    </div>
                    @empty
                    <p style="text-align: center; color: var(--text-muted); padding: 3rem;">Anda belum melakukan pemesanan.</p>
                    @endforelse
                </div>
            </div>

            @elseif($menu === 'information')
            <div class="animate-fade">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                    <div>
                        <h2 style="font-weight: 800;">Manajemen Informasi</h2>
                        <p style="color: var(--text-muted);">Publikasikan panduan dan pengumuman untuk para petani.</p>
                    </div>
                    <button onclick="document.getElementById('modalInfo').style.display='flex'" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Info</button>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                    @forelse($myEducationalInfos as $info)
                    <div class="glass-card" style="padding: 1.5rem;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                            <span class="badge badge-success">{{ $info->category }}</span>
                            <div style="display: flex; gap: 0.5rem;">
                                <button onclick="openInfoModal({{ json_encode($info) }})" class="action-btn-icon"><i class="fas fa-edit"></i></button>
                                <form action="{{ route('educational.destroy', $info->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="action-btn-icon action-btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                        <h4 style="font-weight: 800; margin-bottom: 0.5rem;">{{ $info->title }}</h4>
                        <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;">{{ Str::limit(strip_tags($info->content), 80) }}</p>
                        <div style="display: flex; justify-content: space-between; font-size: 0.8rem; color: var(--text-muted);">
                            <span><i class="fas fa-eye"></i> {{ $info->views }} Labaca</span>
                            <span>{{ $info->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                    @empty
                    <div style="grid-column: 1/-1; text-align: center; padding: 3rem;">
                        <p style="color: var(--text-muted);">Anda belum membagikan informasi apapun.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            @elseif($menu === 'settings')
            <div class="animate-fade">
                <h2 style="font-weight: 800; margin-bottom: 2rem;">Pengaturan Akun</h2>
                <div class="glass-card" style="max-width: 600px; padding: 2.5rem;">
                    @if ($errors->any())
                        <div style="background: rgba(239, 68, 68, 0.1); color: var(--danger); padding: 1rem; border-radius: 10px; margin-bottom: 2rem; font-size: 0.9rem;">
                            <ul style="margin: 0; padding-left: 1.5rem;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf @method('PATCH')
                        <div style="margin-bottom: 1.5rem;">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ $user->name }}" class="form-input" required>
                        </div>
                        <div style="margin-bottom: 1.5rem;">
                            <label class="form-label">Alamat Email</label>
                            <input type="email" name="email" value="{{ $user->email }}" class="form-input" required>
                        </div>
                        <div style="margin-bottom: 1.5rem;">
                            <label class="form-label">No. Telepon</label>
                            <input type="tel" name="phone" value="{{ $user->phone }}" class="form-input" placeholder="08xxxxxxxxxx">
                        </div>
                        <div style="margin-bottom: 1.5rem;">
                            <label class="form-label">Kota / Lokasi</label>
                            <input type="text" name="city" value="{{ $user->city }}" class="form-input" placeholder="Mojokerto">
                        </div>
                        <div style="margin-bottom: 2rem;">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="address" class="form-input" rows="3" placeholder="Jalan, Desa, Kecamatan...">{{ $user->address }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1.1rem;">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </main>
    </div>
</div>

<!-- MODALS -->
@if($user->role === 'penjual' || $user->role === 'petani')
<div id="modalProduct" class="modal-overlay" style="display:none;" onclick="if(event.target===this)this.style.display='none'">
    <div class="glass-card modal-content" style="max-width: 540px; padding: 3rem; max-height: 90vh; overflow-y: auto;" onclick="event.stopPropagation()">
        <h3 id="productModalTitle" style="font-weight: 800; margin-bottom: 2rem;">Tambah {{ $user->role === 'petani' ? 'Hasil Panen' : 'Produk Baru' }}</h3>
        <form id="productForm" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="productMethod"></div>
            <div style="margin-bottom: 1rem;">
                <label class="form-label">Nama {{ $user->role === 'petani' ? 'Komoditas' : 'Produk' }} *</label>
                <input type="text" name="name" id="prod_name" placeholder="Contoh: {{ $user->role === 'petani' ? 'Beras Organik Mojosari' : 'Benih Padi Ciherang' }}" class="form-input" required>
            </div>
            <div style="margin-bottom: 1rem;">
                <label class="form-label">Kategori *</label>
                <select name="category" class="form-input" required>
                    @if($user->role === 'petani')
                        <option value="hasil_panen">Hasil Bumi / Panen</option>
                    @else
                        <option value="benih">Benih</option>
                        <option value="pupuk">Pupuk</option>
                        <option value="pestisida">Pestisida</option>
                        <option value="obat">Obat Pertanian</option>
                        <option value="alat">Alat Tani</option>
                        <option value="panen">Alat Panen</option>
                        <option value="hasil_panen">Hasil Bumi</option>
                    @endif
                </select>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                <div>
                    <label class="form-label">Harga (Rp) *</label>
                    <input type="number" name="price" id="prod_price" placeholder="75000" class="form-input" min="0" required>
                </div>
                <div>
                    <label class="form-label">Stok *</label>
                    <input type="number" name="quantity" id="prod_stock" placeholder="100" class="form-input" min="0" required>
                </div>
            </div>
            <div style="margin-bottom: 1rem;">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" id="prod_desc" placeholder="Kualitas produk, keunggulan, dll..." class="form-input" rows="3" style="height:auto;"></textarea>
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label class="form-label">Foto Produk</label>
                <input type="file" name="image" class="form-input" accept="image/*" style="padding: 0.5rem;">
            </div>
            <div style="display: flex; gap: 0.75rem;">
                <button type="submit" class="btn btn-primary" style="flex:1; justify-content:center;">Simpan Produk</button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('modalProduct').style.display='none'" style="border: none;">Batal</button>
            </div>
        </form>
    </div>
</div>

<div id="modalEquipment" class="modal-overlay" style="display:none;" onclick="if(event.target===this)this.style.display='none'">
    <div class="glass-card modal-content" style="max-width: 540px; padding: 3rem; max-height: 90vh; overflow-y: auto;" onclick="event.stopPropagation()">
        <h3 id="equipmentModalTitle" style="font-weight: 800; margin-bottom: 2rem;">Tambah Alat Sewa</h3>
        <form id="equipmentForm" action="{{ route('equipments.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="equipmentMethod"></div>
            <div style="margin-bottom: 1rem;">
                <label class="form-label">Nama Alat *</label>
                <input type="text" name="name" id="eq_name" placeholder="Contoh: Hand Traktor HT-300" class="form-input" required>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                <div>
                    <label class="form-label">Jenis Alat *</label>
                    <input type="text" name="type" id="eq_type" placeholder="Traktor, Drone..." class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Jumlah *</label>
                    <input type="number" name="quantity" id="eq_quantity" placeholder="1" class="form-input" min="1" required>
                </div>
            </div>
            <div style="margin-bottom: 1rem;">
                <label class="form-label">Untuk Tanaman *</label>
                <select name="crop_type" id="eq_crop_type" class="form-input" required>
                    <option value="all">Semua Tanaman</option>
                    <option value="padi">Padi</option>
                    <option value="tebu">Tebu</option>
                </select>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                <div>
                    <label class="form-label">Harga Sewa (Rp) *</label>
                    <input type="number" name="price" id="eq_price" placeholder="350000" class="form-input" min="0" required>
                </div>
                <div>
                    <label class="form-label">Satuan *</label>
                    <select name="unit" id="eq_unit" class="form-input" required>
                        <option value="hari">Per Hari</option>
                        <option value="minggu">Per Minggu</option>
                        <option value="jam">Per Jam</option>
                    </select>
                </div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
                <div>
                    <label class="form-label">Lokasi</label>
                    <input type="text" name="location" id="eq_location" placeholder="Mojosari, Mojokerto" class="form-input">
                </div>
                <div>
                    <label class="form-label">No. WhatsApp</label>
                    <input type="text" name="phone" id="eq_phone" placeholder="08xxxxxxxxxx" class="form-input">
                </div>
            </div>
            <div style="margin-bottom: 1rem;">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" id="eq_desc" placeholder="Spesifikasi, kondisi alat, dll..." class="form-input" rows="2" style="height:auto;"></textarea>
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label class="form-label">Foto Alat</label>
                <input type="file" name="image" class="form-input" accept="image/*" style="padding: 0.5rem;">
            </div>
            <div style="display: flex; gap: 0.75rem;">
                <button type="submit" class="btn btn-primary" style="flex:1; justify-content:center;">Simpan Alat</button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('modalEquipment').style.display='none'" style="border: none;">Batal</button>
            </div>
        </form>
    </div>
</div>
@endif

@if($user->role === 'penyuluh' || $user->role === 'admin')
<div id="modalInfo" class="modal-overlay" style="display:none;" onclick="if(event.target===this)this.style.display='none'">
    <div class="glass-card modal-content" style="max-width: 600px; padding: 3rem; max-height: 90vh; overflow-y: auto;" onclick="event.stopPropagation()">
        <h3 id="infoModalTitle" style="font-weight: 800; margin-bottom: 2rem;">Publikasikan Informasi Edukasi</h3>
        <form id="infoForm" action="{{ route('educational.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="infoMethod"></div>
            <div style="margin-bottom: 1.5rem;">
                <label class="form-label">Judul Informasi *</label>
                <input type="text" name="title" id="info_title" placeholder="Contoh: Teknik Irigasi Modern Musim Kemarau" class="form-input" required>
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label class="form-label">Kategori *</label>
                <select name="category" class="form-input" required>
                    <option value="Budidaya">Budidaya Tanaman</option>
                    <option value="Hama">Penanganan Hama</option>
                    <option value="Pupuk">Optimalisasi Pupuk</option>
                    <option value="Teknologi">Teknologi Tani</option>
                    <option value="Pengumuman">Pengumuman Kelompok</option>
                </select>
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label class="form-label">Isi Informasi / Edukasi *</label>
                <textarea name="content" id="info_content" class="form-input" rows="8" style="height:auto;" placeholder="Berikan penjelasan detail untuk para petani..." required></textarea>
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label class="form-label">Gambar Pendukung (Opsional)</label>
                <input type="file" name="image" class="form-input" accept="image/*" style="padding: 0.5rem;">
            </div>
            <div style="margin-bottom: 2rem;">
                <label class="form-label">File / Dokumen Pendukung (Opsional — PDF, DOC, PPT)</label>
                <input type="file" name="file" class="form-input" accept=".pdf,.doc,.docx,.ppt,.pptx,.txt" style="padding: 0.5rem;">
            </div>
            <div style="display: flex; gap: 0.75rem;">
                <button type="submit" class="btn btn-primary" style="flex:1; justify-content:center;">Publikasikan Sekarang</button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('modalInfo').style.display='none'" style="border: none;">Batal</button>
            </div>
        </form>
    </div>
</div>
@endif

@push('styles')
<style>
    .sidebar-link {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.85rem 1.25rem;
        border-radius: 14px;
        color: var(--text-muted);
        text-decoration: none;
        font-weight: 700;
        transition: all 0.3s ease;
        position: relative;
    }
    .sidebar-link:hover {
        background: rgba(16, 185, 129, 0.05);
        color: var(--primary);
        transform: translateX(5px);
    }
    .sidebar-link.active {
        background: var(--primary);
        color: white;
        box-shadow: 0 10px 20px rgba(16, 185, 129, 0.2);
    }
    .sidebar-link i {
        font-size: 1.1rem;
        width: 20px;
    }
    .badge-dot {
        position: absolute;
        right: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        width: 8px;
        height: 8px;
        background: var(--danger);
        border-radius: 50%;
        box-shadow: 0 0 10px rgba(239, 68, 68, 0.5);
    }
    .stat-card {
        padding: 2rem;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-color);
        text-align: center;
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.05);
    }
    .stat-card .icon {
        width: 50px;
        height: 50px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem auto;
        font-size: 1.25rem;
    }
    .stat-card .value {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--text-main);
        margin-bottom: 0.25rem;
    }
    .stat-card .label {
        font-size: 0.8rem;
        color: var(--text-muted);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .badge {
        padding: 0.3rem 1rem;
        border-radius: 99px;
        font-weight: 800;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .badge-success { background: #dcfce7; color: #166534; }
    .badge-warning { background: #fef9c3; color: #854d0e; }
    
    .modal-overlay {
        position: fixed;
        z-index: 3000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 800;
        color: var(--text-muted);
        font-size: 0.8rem;
        text-transform: uppercase;
    }
    .form-input {
        width: 100%;
        padding: 1rem;
        background: var(--surface);
        color: var(--text-main);
        border: 1.5px solid var(--border-color);
        border-radius: var(--radius-md);
        font-size: 1rem;
        font-family: 'Inter', inherit;
        transition: all 0.3s ease;
    }
    .form-input:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
    }
    .action-btn-icon { background: none; border: none; color: #94a3b8; cursor: pointer; transition: 0.3s; }
    .action-btn-icon:hover { color: var(--primary) !important; }
    .action-btn-danger:hover { color: var(--danger) !important; }
</style>

<script>
function openProductModal(data = null) {
    const modal = document.getElementById('modalProduct');
    const form = document.getElementById('productForm');
    const title = document.getElementById('productModalTitle');
    const methodField = document.getElementById('productMethod');

    if (data) {
        title.innerText = 'Edit Produk';
        form.action = `/products/${data.id}`;
        methodField.innerHTML = '@method("PATCH")';
        document.getElementById('prod_name').value = data.name;
        document.getElementById('prod_price').value = data.price;
        document.getElementById('prod_stock').value = data.quantity;
        document.getElementById('prod_desc').value = data.description || '';
    } else {
        title.innerText = 'Tambah Produk Baru';
        form.action = "{{ route('products.store') }}";
        methodField.innerHTML = '';
        form.reset();
    }
    modal.style.display = 'flex';
}

function openEquipmentModal(data = null) {
    const modal = document.getElementById('modalEquipment');
    const form = document.getElementById('equipmentForm');
    const title = document.getElementById('equipmentModalTitle');
    const methodField = document.getElementById('equipmentMethod');

    if (data) {
        title.innerText = 'Edit Alat Sewa';
        form.action = `/equipments/${data.id}`;
        methodField.innerHTML = '@method("PUT")';
        document.getElementById('eq_name').value = data.name;
        document.getElementById('eq_type').value = data.type;
        document.getElementById('eq_quantity').value = data.quantity;
        document.getElementById('eq_crop_type').value = data.crop_type || 'all';
        document.getElementById('eq_price').value = data.price;
        document.getElementById('eq_unit').value = data.unit;
        document.getElementById('eq_location').value = data.location || '';
        document.getElementById('eq_phone').value = data.phone || '';
        document.getElementById('eq_desc').value = data.description || '';
    } else {
        title.innerText = 'Tambah Alat Sewa';
        form.action = "{{ route('equipments.store') }}";
        methodField.innerHTML = '';
        form.reset();
    }
    modal.style.display = 'flex';
}

function openInfoModal(data = null) {
    const modal = document.getElementById('modalInfo');
    const form = document.getElementById('infoForm');
    const title = document.getElementById('infoModalTitle');
    const methodField = document.getElementById('infoMethod');

    if (data) {
        title.innerText = 'Edit Informasi Edukasi';
        form.action = `/educational-infos/${data.id}`;
        methodField.innerHTML = '@method("PATCH")';
        document.getElementById('info_title').value = data.title;
        document.getElementById('info_content').value = data.content;
    } else {
        title.innerText = 'Publikasikan Informasi Edukasi';
        form.action = "{{ route('educational.store') }}";
        methodField.innerHTML = '';
        form.reset();
    }
    modal.style.display = 'flex';
}

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('weather-temp-card')) {
        const lat = -7.4726;
        const lon = 112.4381;
        const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current=temperature_2m,weather_code&timezone=Asia%2FJakarta`;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                const current = data.current;
                const temp = Math.round(current.temperature_2m);
                const code = current.weather_code;

                let condition = "Cerah Berawan";
                let iconClass = "fa-cloud-sun";
                let iconColor = "#f59e0b";

                if (code === 0) {
                    condition = "Cerah";
                    iconClass = "fa-sun";
                    iconColor = "#f59e0b";
                } else if (code >= 1 && code <= 3) {
                    condition = "Cerah Berawan";
                    iconClass = "fa-cloud-sun";
                    iconColor = "#f59e0b";
                } else if (code === 45 || code === 48) {
                    condition = "Berkabut";
                    iconClass = "fa-smog";
                    iconColor = "#94a3b8";
                } else if (code >= 51 && code <= 67) {
                    condition = "Gerimis / Hujan Ringan";
                    iconClass = "fa-cloud-rain";
                    iconColor = "#3b82f6";
                } else if (code >= 80 && code <= 82) {
                    condition = "Hujan Deras";
                    iconClass = "fa-cloud-showers-heavy";
                    iconColor = "#3b82f6";
                } else if (code >= 95) {
                    condition = "Hujan Badai";
                    iconClass = "fa-cloud-bolt";
                    iconColor = "#1e293b";
                }

                document.getElementById('weather-temp-card').innerText = `${temp}°C`;
                document.getElementById('weather-condition-card').innerText = condition;
                
                const iconEl = document.getElementById('weather-icon-card');
                if (iconEl) {
                    iconEl.className = `fas ${iconClass}`;
                    iconEl.style.color = iconColor;
                }
            })
            .catch(err => {
                document.getElementById('weather-temp-card').innerText = "25°C";
                document.getElementById('weather-condition-card').innerText = "Cerah Berawan";
            });
    }

    // Market Price Charts
    function initMarketChart(canvasId, priceId, changeId, commodity, color, bgColor) {
        fetch('{{ url("api/market-prices") }}/' + commodity)
            .then(r => r.json())
            .then(data => {
                document.getElementById(priceId).textContent = 'Rp ' + Number(data.latest).toLocaleString('id-ID');
                const changeEl = document.getElementById(changeId);
                changeEl.textContent = data.changeLabel;
                changeEl.style.background = data.change >= 0 ? 'rgba(5,150,105,0.1)' : 'rgba(239,68,68,0.1)';
                changeEl.style.color = data.change >= 0 ? '#10b981' : '#ef4444';

                const ctx = document.getElementById(canvasId).getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Padi',
                            data: data.prices,
                            borderColor: color,
                            backgroundColor: bgColor,
                            borderWidth: 2.5,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHitRadius: 10,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: {
                                display: true,
                                grid: { display: false },
                                ticks: { maxTicksLimit: 5, color: '#637e84', font: { size: 10 }, maxRotation: 0 }
                            },
                            y: {
                                display: true,
                                grid: { color: 'rgba(255,255,255,0.04)', drawBorder: false },
                                ticks: { color: '#637e84', font: { size: 10 }, callback: v => 'Rp' + Number(v).toLocaleString('id-ID') }
                            }
                        },
                        interaction: { intersect: false, mode: 'index' },
                    }
                });
            });
    }

    initMarketChart('chartPadiProfile', 'price-padi-chart', 'change-padi-chart', 'padi', '#34d399', 'rgba(52,211,153,0.08)');

    // Also update the petani price card
    fetch('{{ url("api/market-prices") }}/padi')
        .then(r => r.json())
        .then(data => {
            const el = document.getElementById('price-padi-profile');
            if (el) el.textContent = 'Rp ' + Number(data.latest).toLocaleString('id-ID');
            const changeEl = document.getElementById('change-padi-profile');
            if (changeEl) {
                changeEl.innerHTML = (data.change >= 0 ? '<i class="fas fa-caret-up"></i>' : '<i class="fas fa-caret-down"></i>') + ' ' + data.changeLabel;
                changeEl.style.color = data.change >= 0 ? '#10b981' : '#ef4444';
            }
        });
});
</script>
@endpush
@endsection
