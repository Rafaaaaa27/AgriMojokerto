<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - AgriMojokerto</title>
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    </script>
</head>
<body>
    <div class="auth-container auth-container-compact">
        <div class="auth-card auth-card-wide">
            <div class="auth-logo">
                <i class="fas fa-seedling"></i>
                <h1>AgriMojokerto</h1>
                <p>Bergabung dengan komunitas tani digital</p>
            </div>

            @if ($errors->any())
                <div style="background: rgba(239, 68, 68, 0.1); color: var(--danger); padding:1rem; border-radius:12px; margin-bottom:1.5rem; border: 1px solid rgba(239, 68, 68, 0.2); font-size: 0.9rem;">
                    <ul style="margin:0; padding-left:1.25rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="form-group">
                    <label class="form-label"><i class="fas fa-user"></i> Nama Lengkap</label>
                    <input type="text" name="name" class="form-input" 
                           placeholder="Nama lengkap Anda" required autofocus
                           value="{{ old('name') }}">
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" name="email" class="form-input" 
                               placeholder="email@example.com" required
                               value="{{ old('email') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-phone"></i> No. HP</label>
                        <input type="tel" name="phone" class="form-input" 
                               placeholder="08xxxxxxxxxx" required
                               value="{{ old('phone') }}">
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-user-tag"></i> Daftar Sebagai</label>
                        <select name="role" class="form-input" required>
                            <option value="petani" {{ old('role') === 'petani' ? 'selected' : '' }}>Petani / Pelaku Usaha Tani</option>
                            <option value="penjual" {{ old('role') === 'penjual' ? 'selected' : '' }}>Penjual / Toko Sarana Tani</option>
                            <option value="pembeli" {{ old('role') === 'pembeli' ? 'selected' : '' }}>Masyarakat Umum (Pembeli)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-map-marker-alt"></i> Lokasi</label>
                        <select name="location" class="form-input" required>
                            <option value="">Pilih Lokasi</option>
                            <option value="Mojokerto Kota" {{ old('location') === 'Mojokerto Kota' ? 'selected' : '' }}>Mojokerto Kota</option>
                            <option value="Mojosari" {{ old('location') === 'Mojosari' ? 'selected' : '' }}>Mojosari</option>
                            <option value="Bangsal" {{ old('location') === 'Bangsal' ? 'selected' : '' }}>Bangsal</option>
                            <option value="Sooko" {{ old('location') === 'Sooko' ? 'selected' : '' }}>Sooko</option>
                            <option value="Trawas" {{ old('location') === 'Trawas' ? 'selected' : '' }}>Trawas</option>
                            <option value="Pacet" {{ old('location') === 'Pacet' ? 'selected' : '' }}>Pacet</option>
                            <option value="Dlanggu" {{ old('location') === 'Dlanggu' ? 'selected' : '' }}>Dlanggu</option>
                            <option value="Puri" {{ old('location') === 'Puri' ? 'selected' : '' }}>Puri</option>
                        </select>
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-lock"></i> Password</label>
                        <input type="password" name="password" class="form-input" 
                               placeholder="Min. 8 karakter" required minlength="8">
                    </div>

                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-lock"></i> Konfirmasi</label>
                        <input type="password" name="password_confirmation" class="form-input" 
                               placeholder="Ketik ulang" required minlength="8">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label"><i class="fas fa-home"></i> Alamat (Opsional)</label>
                    <textarea name="address" class="form-input" rows="2" 
                              placeholder="Alamat lengkap tempat tinggal atau usaha" style="resize: none;">{{ old('address') }}</textarea>
                </div>

                <button type="submit" class="auth-btn auth-btn-mt-lg">
                    Daftar Sekarang <i class="fas fa-user-plus" style="margin-left: 0.5rem;"></i>
                </button>
            </form>

            <div class="form-footer">
                <p>Sudah memiliki akun? <a href="{{ route('login') }}">Login disini</a></p>
            </div>
            <div style="text-align: center; margin-top:1.5rem;">
                <a href="{{ url('/') }}" class="auth-back-link">
                    <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</body>
</html>
