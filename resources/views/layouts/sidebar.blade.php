@php
    $currentUser = auth()->user();
    $currentMenu = $menu ?? 'dashboard';
    $sidebarLinks = [
        'dashboard' => [
            'icon' => 'th-large',
            'label' => 'Dashboard',
            'route' => in_array($currentUser->role, ['petani', 'admin', 'penyuluh']) ? route('dashboard') : route('profile.edit', ['menu' => 'dashboard']),
            'active' => $currentMenu === 'dashboard' || Request::routeIs('dashboard'),
            'roles' => ['petani', 'penjual', 'pembeli', 'penyuluh', 'admin'],
        ],
        'products' => [
            'icon' => 'box',
            'label' => $currentUser->role === 'petani' ? 'Produk & Alat Sewa Saya' : 'Produk Saya',
            'route' => route('profile.edit', ['menu' => 'products']),
            'active' => $currentMenu === 'products',
            'roles' => ['penjual', 'petani'],
        ],
        'incoming' => [
            'icon' => 'file-invoice-dollar',
            'label' => $currentUser->role === 'petani' ? 'Penjualan' : 'Pesanan Masuk',
            'route' => route('profile.edit', ['menu' => 'incoming']),
            'active' => $currentMenu === 'incoming',
            'roles' => ['penjual', 'petani'],
            'badge' => isset($incomingOrders) && $incomingOrders->where('status', 'pending')->count() > 0,
        ],
        'orders' => [
            'icon' => 'shopping-bag',
            'label' => 'Pesanan Saya',
            'route' => route('profile.edit', ['menu' => 'orders']),
            'active' => $currentMenu === 'orders',
            'roles' => ['petani', 'pembeli'],
        ],
        'harvest' => [
            'icon' => 'leaf',
            'label' => 'Manajemen Panen',
            'route' => route('harvest.index'),
            'active' => Request::routeIs('harvest.*'),
            'roles' => ['petani'],
        ],
        'schedule' => [
            'icon' => 'calendar-alt',
            'label' => 'Jadwal Tani',
            'route' => route('schedule.index'),
            'active' => Request::routeIs('schedule.*'),
            'roles' => ['petani'],
        ],
        'market-prices' => [
            'icon' => 'chart-line',
            'label' => 'Harga Pasar',
            'route' => route('admin.market-prices.index'),
            'active' => Request::routeIs('admin.market-prices.*'),
            'roles' => ['penyuluh', 'admin'],
        ],
        'information' => [
            'icon' => 'bullhorn',
            'label' => 'Sosialisasi & Info',
            'route' => route('profile.edit', ['menu' => 'information']),
            'active' => $currentMenu === 'information',
            'roles' => ['penyuluh'],
        ],
        'settings' => [
            'icon' => 'user-cog',
            'label' => 'Pengaturan',
            'route' => route('profile.edit', ['menu' => 'settings']),
            'active' => $currentMenu === 'settings',
            'roles' => ['petani', 'penjual', 'pembeli', 'penyuluh', 'admin'],
        ],
    ];
@endphp

<!-- Desktop Sidebar -->
<aside class="sidebar-desktop">
    <div class="sidebar-card">
        <div class="sidebar-profile">
            <div class="sidebar-avatar">{{ strtoupper(substr($currentUser->name, 0, 1)) }}</div>
            <h3 class="sidebar-name">{{ $currentUser->name }}</h3>
            <span class="sidebar-role">{{ $currentUser->role }}</span>
        </div>

        <nav class="sidebar-nav">
            @foreach($sidebarLinks as $key => $link)
                @if(in_array($currentUser->role, $link['roles']))
                <a href="{{ $link['route'] }}" class="sidebar-link {{ $link['active'] ? 'active' : '' }}">
                    <i class="fas fa-{{ $link['icon'] }}"></i> {{ $link['label'] }}
                    @if(!empty($link['badge']))
                        <span class="badge-dot"></span>
                    @endif
                </a>
                @endif
            @endforeach

            <div class="sidebar-divider">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-link sidebar-logout">
                        <i class="fas fa-sign-out-alt"></i> Keluar
                    </button>
                </form>
            </div>
        </nav>
    </div>
</aside>

<!-- Mobile Sidebar FAB & Panel -->
<button class="sidebar-mobile-btn" id="mobileSidebarBtn" aria-label="Buka navigasi">
    <i class="fas fa-bars"></i>
</button>

<div class="sidebar-mobile-overlay" id="mobileSidebarOverlay"></div>

<div class="sidebar-mobile-panel" id="mobileSidebarPanel">
    <button class="sidebar-mobile-close" id="mobileSidebarClose" aria-label="Tutup navigasi">
        <i class="fas fa-times"></i>
    </button>

    <div class="sidebar-mobile-profile">
        <div class="sidebar-mobile-avatar">{{ strtoupper(substr($currentUser->name, 0, 1)) }}</div>
        <h3 class="sidebar-mobile-name">{{ $currentUser->name }}</h3>
        <p class="sidebar-mobile-role">{{ $currentUser->role }}</p>
    </div>

    <nav class="sidebar-nav">
        @foreach($sidebarLinks as $key => $link)
            @if(in_array($currentUser->role, $link['roles']))
            <a href="{{ $link['route'] }}" class="sidebar-link {{ $link['active'] ? 'active' : '' }}" onclick="closeMobileSidebar()">
                <i class="fas fa-{{ $link['icon'] }}"></i> {{ $link['label'] }}
                @if(!empty($link['badge']))
                    <span class="badge-dot"></span>
                @endif
            </a>
            @endif
        @endforeach

        <div class="sidebar-divider">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-link sidebar-logout" onclick="closeMobileSidebar()">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </button>
            </form>
        </div>
    </nav>
</div>

@push('scripts')
<script>
function openMobileSidebar() {
    document.getElementById('mobileSidebarPanel').classList.add('show');
    document.getElementById('mobileSidebarOverlay').classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closeMobileSidebar() {
    document.getElementById('mobileSidebarPanel').classList.remove('show');
    document.getElementById('mobileSidebarOverlay').classList.remove('show');
    document.body.style.overflow = '';
}
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('mobileSidebarBtn');
    const overlay = document.getElementById('mobileSidebarOverlay');
    const close = document.getElementById('mobileSidebarClose');
    if (btn) btn.addEventListener('click', openMobileSidebar);
    if (overlay) overlay.addEventListener('click', closeMobileSidebar);
    if (close) close.addEventListener('click', closeMobileSidebar);
});
</script>
@endpush