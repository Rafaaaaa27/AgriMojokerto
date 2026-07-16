<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#059669">
    <meta name="mobile-web-app-capable" content="yes">
    <title>AgriMojokerto - Modern Agriculture System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link rel="stylesheet" href="{{ asset('assets/css/app.css?v=' . md5_file(public_path('assets/css/app.css'))) }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    @stack('styles')
</head>
<body>

    <!-- Page Transition Overlay -->
    <div id="page-transition"><div class="spinner"></div></div>

    <!-- Scroll Progress Bar -->
    <div id="scroll-progress"></div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="container nav-content">

            {{-- Left: Hamburger menu (always visible) --}}
            <div class="nav-left">
                <button class="nav-hamburger" id="navHamburger" aria-label="Buka menu navigasi">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            {{-- Center: Brand --}}
            <a href="{{ url('/') }}" class="logo">
                <i class="fas fa-seedling"></i> Agri<span>Mojokerto</span>
            </a>

            {{-- Desktop nav links --}}
            <div class="nav-links">
                <a href="{{ url('/') }}" class="nav-item {{ Request::is('/') ? 'active' : '' }}">Beranda</a>
                <a href="{{ route('marketplace.index') }}" class="nav-item {{ Request::is('marketplace*') ? 'active' : '' }}">Marketplace</a>
                @auth
                    @if(auth()->user()->role !== 'penjual')
                        <a href="{{ route('forum.index') }}" class="nav-item {{ Request::is('forum*') ? 'active' : '' }}">Forum</a>
                    @endif
                    <a href="{{ route('library.index') }}" class="nav-item {{ Request::is('library*') ? 'active' : '' }}">E-Library</a>
                @endauth
            </div>

            {{-- Right actions: theme toggle, notifikasi, profil — always visible --}}
            <div class="nav-actions">
                @auth
                    <button id="themeToggle" class="nav-action-btn" aria-label="Ganti tema">
                        <i class="fas fa-moon"></i>
                    </button>

                    <div style="position: relative;">
                        <button class="nav-action-btn" id="notifBtn" title="Notifikasi">
                            <i class="fas fa-bell"></i>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                            @endif
                        </button>
                        <div class="dropdown-menu" id="notifDropdown">
                            <div class="dropdown-header">
                                <span style="font-weight: 800;">Notifikasi</span>
                                <button onclick="markAsRead()" class="mark-read-btn">Tandai dibaca</button>
                            </div>
                            <div>
                                @forelse(auth()->user()->notifications->take(5) as $notification)
                                    <div class="dropdown-item {{ $notification->read_at ? '' : 'unread' }}">
                                        <div class="notif-icon"><i class="fas fa-info-circle"></i></div>
                                        <div class="notif-content">
                                            <p>{{ $notification->data['message'] ?? '' }}</p>
                                            <span>{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="dropdown-item">Belum ada notifikasi.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="nav-user-wrap">
                        <button class="nav-user-btn" id="userDropdownBtn">
                            <div class="nav-user-avatar">
                                @if(auth()->user()->photo)
                                    <img src="{{ auth()->user()->photo_url }}" alt="Foto" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                                @else
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                @endif
                            </div>
                            <span class="nav-user-name">{{ explode(' ', auth()->user()->name)[0] }}</span>
                            <i class="fas fa-chevron-down nav-user-chevron"></i>
                        </button>
                        <div class="dropdown-menu" id="userDropdown" style="width: 220px;">
                            <div class="dropdown-header">
                                <span style="font-weight: 800;">Akun {{ ucfirst(auth()->user()->role) }}</span>
                            </div>
                            <a href="{{ in_array(auth()->user()->role, ['pembeli']) ? route('profile.edit') : route('dashboard') }}" class="dropdown-item">
                                <i class="fas fa-th-large"></i> Dashboard Saya
                            </a>
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                <i class="fas fa-user-edit"></i> Edit Profil
                            </a>
                            <div style="border-top: 1px solid var(--border-color); margin: 0.5rem 0;"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item" style="color: var(--danger); width: 100%; border: none; background: none; cursor: pointer; text-align: left;">
                                    <i class="fas fa-sign-out-alt"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="nav-action-btn" aria-label="Masuk">
                        <i class="fas fa-user"></i>
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Daftar</a>
                @endauth
            </div>

            <!-- Mobile hamburger (hidden, replaced by nav-hamburger) -->
            <button class="mobile-toggle" id="mobileToggle" style="display:none;">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    <!-- MOBILE MENU -->
    <div class="mobile-menu-overlay" id="mobileMenu">
        <div class="mobile-menu-content">
            <button class="close-mobile" id="mobileClose"><i class="fas fa-times"></i></button>
            <div style="margin-top: 4rem; display: grid; gap: 0.5rem;">
                <a href="{{ url('/') }}" class="mobile-nav-item">Beranda</a>
                <a href="{{ route('marketplace.index') }}" class="mobile-nav-item">Marketplace</a>
                @if(!auth()->check() || auth()->user()->role !== 'penjual')
                    <a href="{{ route('forum.index') }}" class="mobile-nav-item">Forum</a>
                @endif
                @auth
                    <a href="{{ route('profile.edit') }}" class="mobile-nav-item">Profil Saya</a>
                    @if(auth()->user()->role === 'petani')
                        <a href="{{ route('harvest.index') }}" class="mobile-nav-item">Manajemen Panen</a>
                        <a href="{{ route('schedule.index') }}" class="mobile-nav-item">Jadwal Tani</a>
                    @endif

                    <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color);">
                        <button id="mobileThemeToggle" class="mobile-nav-item" style="background: none; border: none; cursor: pointer; width: 100%; text-align: left;">
                            <i class="fas fa-moon"></i> <span id="mobileThemeLabel">Mode Gelap</span>
                        </button>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="mobile-nav-item mobile-nav-logout" style="background: none; border: none; cursor: pointer;">
                                <i class="fas fa-sign-out-alt"></i> Keluar
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="mobile-nav-item">Masuk</a>
                    <a href="{{ route('register') }}" class="mobile-nav-item">Daftar Akun</a>
                @endauth
            </div>
        </div>
    </div>

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <div class="footer-accent"></div>
        <div class="footer-inner container">
            <div class="footer-top">
                <div class="footer-brand">
                    <a href="{{ url('/') }}" class="footer-logo">
                        <i class="fas fa-seedling"></i> AgriMojokerto
                    </a>
                    <p class="footer-tagline">Kemandirian pangan untuk petani Mojokerto.</p>
                </div>
                <div class="footer-nav">
                    <a href="{{ route('marketplace.index') }}" class="footer-link">Pasar Tani</a>
                    <span class="footer-dot"></span>
                    @if(!auth()->check() || auth()->user()->role !== 'penjual')
                        <a href="{{ route('forum.index') }}" class="footer-link">Forum</a>
                        <span class="footer-dot"></span>
                    @endif
                    <a href="{{ route('library.index') }}" class="footer-link">E-Library</a>
                </div>
                <div class="footer-contact">
                    <a href="mailto:info@agrimojokerto.id" class="footer-link"><i class="far fa-envelope"></i> info@agrimojokerto.id</a>
                    <a href="tel:+0321123456" class="footer-link"><i class="fas fa-phone-alt"></i> (0321) 123-456</a>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; {{ date('Y') }} AgriMojokerto
            </div>
        </div>
    </footer>

    <script>
        // Dark Mode Logic
        const themeToggle = document.getElementById('themeToggle');
        const mobileThemeToggle = document.getElementById('mobileThemeToggle');
        const mobileThemeLabel = document.getElementById('mobileThemeLabel');
        const body = document.documentElement;
        const icon = themeToggle?.querySelector('i');
        const mobileIcon = mobileThemeToggle?.querySelector('i');

        function setTheme(isDark) {
            if (isDark) {
                body.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
                if(icon) { icon.classList.remove('fa-moon'); icon.classList.add('fa-sun'); }
                if(mobileIcon) { mobileIcon.classList.remove('fa-moon'); mobileIcon.classList.add('fa-sun'); }
                if(mobileThemeLabel) mobileThemeLabel.textContent = 'Mode Terang';
            } else {
                body.removeAttribute('data-theme');
                localStorage.setItem('theme', 'light');
                if(icon) { icon.classList.remove('fa-sun'); icon.classList.add('fa-moon'); }
                if(mobileIcon) { mobileIcon.classList.remove('fa-sun'); mobileIcon.classList.add('fa-moon'); }
                if(mobileThemeLabel) mobileThemeLabel.textContent = 'Mode Gelap';
            }
        }

        if (localStorage.getItem('theme') === 'dark') {
            setTheme(true);
        }

        function toggleTheme() {
            setTheme(body.getAttribute('data-theme') !== 'dark');
        }

        themeToggle?.addEventListener('click', toggleTheme);
        mobileThemeToggle?.addEventListener('click', toggleTheme);

        // Notification Dropdown
        const notifBtn = document.getElementById('notifBtn');
        const notifDropdown = document.getElementById('notifDropdown');
        if (notifBtn) {
            notifBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                notifDropdown.classList.toggle('show');
                if (userDropdown) userDropdown.classList.remove('show');
            });
            document.addEventListener('click', () => notifDropdown?.classList.remove('show'));
        }

        // User Dropdown
        const userDropdownBtn = document.getElementById('userDropdownBtn');
        const userDropdown = document.getElementById('userDropdown');
        if (userDropdownBtn) {
            userDropdownBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                userDropdown.classList.toggle('show');
                if (notifDropdown) notifDropdown.classList.remove('show');
            });
            document.addEventListener('click', () => userDropdown?.classList.remove('show'));
        }

        // Mobile Menu — hamburger opens sidebar if available, otherwise falls back to mobile menu
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileClose = document.getElementById('mobileClose');
        const mobileSidebarPanel = document.getElementById('mobileSidebarPanel');
        const mobileSidebarOverlay = document.getElementById('mobileSidebarOverlay');
        const navHamburger = document.getElementById('navHamburger');

        if (navHamburger) {
            navHamburger.addEventListener('click', () => {
                if (mobileSidebarPanel) {
                    mobileSidebarPanel.classList.add('show');
                    if (mobileSidebarOverlay) mobileSidebarOverlay.classList.add('show');
                    document.body.style.overflow = 'hidden';
                } else {
                    mobileMenu?.classList.add('show');
                }
            });
        } else {
            document.getElementById('mobileToggle')?.addEventListener('click', () => {
                mobileMenu?.classList.add('show');
            });
        }

        if (mobileClose) {
            mobileClose.addEventListener('click', () => {
                mobileMenu?.classList.remove('show');
            });
        }

        // Toast System
        function showToast(title, message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            const colors = { success: '16,185,129', danger: '239,68,68', warning: '245,158,11' };
            const icons  = { success: 'fa-check-circle', danger: 'fa-exclamation-circle', warning: 'fa-exclamation-triangle' };
            toast.innerHTML = `
                <div class="toast-icon" style="background:rgba(${colors[type]||colors.success},0.12);color:${type==='success'?'var(--success)':type==='danger'?'var(--danger)':'var(--warning)'}">
                    <i class="fas ${icons[type]||icons.success}"></i>
                </div>
                <div>
                    <div class="toast-title">${title}</div>
                    <div class="toast-message">${message}</div>
                </div>`;
            container.appendChild(toast);
            requestAnimationFrame(() => toast.classList.add('show'));
            setTimeout(() => { toast.classList.remove('show'); setTimeout(() => toast.remove(), 500); }, 4200);
        }

        @if(session('success'))
            showToast('Berhasil!', @json(session('success')), 'success');
        @endif
        @if(session('error'))
            showToast('Gagal!', @json(session('error')), 'danger');
        @endif
        @if(session('status'))
            showToast('Info', @json(session('status')), 'warning');
        @endif

        function markAsRead() {
            fetch('{{ route('notifications.markAsRead') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            }).then(() => window.location.reload());
        }

        /* ==========================================
           TRANSITION ENGINE
        ========================================== */

        // 1. Page Enter — fade in on load
        document.addEventListener('DOMContentLoaded', () => {
            const overlay = document.getElementById('page-transition');
            document.body.classList.add('page-enter');
            if (overlay) {
                overlay.classList.remove('active');
            }
        });

        // 2. Page Leave — fade out before navigation
        document.querySelectorAll('a[href]').forEach(link => {
            const href = link.getAttribute('href');
            if (!href || href.startsWith('#') || href.startsWith('javascript') ||
                href.startsWith('mailto') || href.startsWith('tel') ||
                link.hasAttribute('target') || link.closest('form')) return;

            // Only internal links
            try {
                const url = new URL(href, window.location.origin);
                if (url.origin !== window.location.origin) return;
            } catch { return; }

            link.addEventListener('click', function(e) {
                if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
                e.preventDefault();
                const dest = this.href;
                const overlay = document.getElementById('page-transition');
                if (overlay) {
                    overlay.classList.add('active');
                    setTimeout(() => { window.location.href = dest; }, 320);
                } else {
                    window.location.href = dest;
                }
            });
        });

        // Theme toggle animation class
        [themeToggle, mobileThemeToggle].forEach(btn => {
            if (btn) {
                btn.addEventListener('click', function() {
                    this.classList.add('switching');
                    setTimeout(() => this.classList.remove('switching'), 400);
                });
            }
        });

        // Navbar scroll effect
        const navbar = document.querySelector('.navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        }, { passive: true });

        // 4. Scroll Progress Bar
        const progressBar = document.getElementById('scroll-progress');
        if (progressBar) {
            window.addEventListener('scroll', () => {
                const scrollTop = window.scrollY;
                const docHeight = document.documentElement.scrollHeight - window.innerHeight;
                const pct = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
                progressBar.style.width = pct + '%';
            }, { passive: true });
        }

        // 5. Scroll Reveal — IntersectionObserver
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry, i) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('visible');
                    }, i * 60);
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

        document.querySelectorAll('.reveal, .reveal-left, .reveal-right').forEach(el => {
            revealObserver.observe(el);
        });

        // 6. Auto-apply reveal to glass-cards not explicitly marked
        document.querySelectorAll('.glass-card:not(.reveal):not(.reveal-left):not(.reveal-right)').forEach((el, i) => {
            el.classList.add('reveal');
            if (i % 3 === 1) el.classList.add('delay-1');
            if (i % 3 === 2) el.classList.add('delay-2');
            revealObserver.observe(el);
        });

        // 7. Stat cards stagger
        document.querySelectorAll('.stat-card').forEach((el, i) => {
            el.classList.add('reveal');
            el.classList.add(`delay-${(i % 4) + 1}`);
            revealObserver.observe(el);
        });
    </script>

    @stack('scripts')
</body>
</html>
