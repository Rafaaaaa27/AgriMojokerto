<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AgriMojokerto</title>
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    </script>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-logo">
                <i class="fas fa-seedling"></i>
                <h1>AgriMojokerto</h1>
                <p>Selamat datang kembali</p>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            @if ($errors->any())
                <div style="background: rgba(239, 68, 68, 0.1); color: var(--danger); padding:1rem; border-radius:12px; margin-bottom:1.5rem; border: 1px solid rgba(239, 68, 68, 0.2); font-size: 0.9rem;">
                    <ul style="margin:0; padding-left:1.25rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="form-group">
                    <label class="form-label"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" name="email" class="form-input" 
                           placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.6rem;">
                        <label class="form-label" style="margin-bottom: 0;"><i class="fas fa-lock"></i> Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" style="font-size: 0.8rem; color: var(--primary); text-decoration: none; font-weight: 600;">Lupa?</a>
                        @endif
                    </div>
                    <input type="password" name="password" class="form-input" 
                           placeholder="••••••••" required>
                </div>

                <div class="form-group" style="display: flex; align-items: center; gap: 0.75rem; margin-top: 1rem;">
                    <input type="checkbox" name="remember" id="remember" style="width: 18px; height: 18px; cursor: pointer; accent-color: var(--primary);">
                    <label for="remember" style="margin: 0; font-size: 0.9rem; color: var(--text-muted); cursor: pointer;">Ingat saya di perangkat ini</label>
                </div>

                <button type="submit" class="auth-btn">
                    Masuk Sekarang <i class="fas fa-arrow-right" style="margin-left: 0.5rem;"></i>
                </button>
            </form>

            <div class="form-footer">
                <p>Belum memiliki akun? <a href="{{ route('register') }}">Daftar Gratis</a></p>
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
