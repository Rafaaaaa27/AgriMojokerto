@php
    $currentUser = auth()->user();
    $currentMenu = $menu ?? 'dashboard';
    $isBeranda = $currentMenu === 'dashboard' || Request::routeIs('dashboard') || Request::is('/');

    $hasRole = function($roles) use ($currentUser) {
        return in_array($currentUser->role, $roles);
    };

    // Build menu items: start with Beranda, then management menus, then account
    $menuItems = [
         ['key' => 'beranda', 'icon' => 'home', 'label' => 'Beranda',
         'route' => in_array($currentUser->role, ['petani', 'admin', 'penyuluh', 'penjual']) ? route('dashboard') : url('/'),
         'active' => $isBeranda,
         'roles' => ['petani', 'penjual', 'pembeli', 'penyuluh', 'admin']],

        'divider',

        ['key' => 'products', 'icon' => 'box', 'label' => 'Produk & Alat',
         'route' => route('profile.edit', ['menu' => 'products']),
         'active' => $currentMenu === 'products',
         'roles' => ['penjual', 'petani']],
        ['key' => 'incoming', 'icon' => 'file-invoice-dollar', 'label' => 'Penjualan',
         'route' => route('profile.edit', ['menu' => 'incoming']),
         'active' => $currentMenu === 'incoming',
         'roles' => ['penjual', 'petani'],
         'badge' => isset($incomingOrders) && $incomingOrders->where('status', 'pending')->count() > 0],
        ['key' => 'orders', 'icon' => 'shopping-bag', 'label' => 'Pesanan Saya',
         'route' => route('profile.edit', ['menu' => 'orders']),
         'active' => $currentMenu === 'orders',
         'roles' => ['petani', 'pembeli', 'penyuluh', 'penjual']],
        ['key' => 'harvest', 'icon' => 'leaf', 'label' => 'Manajemen Panen',
         'route' => route('harvest.index'),
         'active' => Request::routeIs('harvest.*'),
         'roles' => ['petani']],
        ['key' => 'schedule', 'icon' => 'calendar-alt', 'label' => 'Jadwal Tani',
         'route' => route('schedule.index'),
         'active' => Request::routeIs('schedule.*'),
         'roles' => ['petani']],

        // Penyuluh-specific menus
        ['key' => 'market-prices', 'icon' => 'chart-line', 'label' => 'Harga Pasar',
         'route' => route('admin.market-prices.index'),
         'active' => Request::routeIs('admin.market-prices.*'),
         'roles' => ['penyuluh', 'admin']],
        ['key' => 'educational', 'icon' => 'bullhorn', 'label' => 'Konten Edukasi',
         'route' => route('educational.manage'),
         'active' => Request::routeIs('educational.manage'),
         'roles' => ['penyuluh', 'admin']],
        ['key' => 'library', 'icon' => 'book', 'label' => 'E-Library',
         'route' => route('library.index'),
         'active' => Request::is('library*'),
         'roles' => ['penyuluh']],

        'divider',

        // Account
        ['key' => 'settings', 'icon' => 'cog', 'label' => 'Pengaturan',
         'route' => route('profile.edit', ['menu' => 'settings']),
         'active' => $currentMenu === 'settings',
         'roles' => ['petani', 'penjual', 'pembeli', 'penyuluh', 'admin']],
        ['key' => 'keluar', 'icon' => 'sign-out-alt', 'label' => 'Keluar',
         'route' => route('logout'),
         'active' => false,
         'roles' => ['petani', 'penjual', 'pembeli', 'penyuluh', 'admin']],
    ];
@endphp

<!-- Desktop Sidebar -->
<aside class="sidebar-desktop" data-role="{{ $currentUser->role }}">

  {{-- Profile --}}
  <div class="sd-profile">
    <a href="{{ route('profile.edit') }}" class="sd-avatar">
      @if($currentUser->photo)
        <img src="{{ $currentUser->photo_url }}" alt="Foto" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
      @else
        {{ strtoupper(substr($currentUser->name, 0, 1)) }}
      @endif
    </a>
    <div class="sd-info">
      <div class="sd-name" title="{{ $currentUser->name }}">{{ $currentUser->name }}</div>
      <span class="sd-role">{{ $currentUser->role === 'penjual' ? 'Penjual' : ($currentUser->role === 'petani' ? 'Petani' : ($currentUser->role === 'pembeli' ? 'Pembeli' : ($currentUser->role === 'penyuluh' ? 'Penyuluh' : 'Admin'))) }}</span>
    </div>
  </div>

  {{-- Navigation --}}
  <nav class="sd-nav">
    @foreach($menuItems as $item)
      @if(is_string($item) && $item === 'divider')
        <div class="sd-divider"></div>
      @elseif($hasRole($item['roles']))
        @if($item['key'] === 'keluar')
          <form method="POST" action="{{ $item['route'] }}" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari akun?')">
            @csrf
            <button type="submit" class="sd-link sd-logout">
              <i class="fas fa-{{ $item['icon'] }}"></i>
              <span>{{ $item['label'] }}</span>
            </button>
          </form>
        @elseif($item['key'] === 'incoming' && !empty($item['badge']))
          <a href="{{ $item['route'] }}" class="sd-link {{ $item['active'] ? 'active' : '' }}">
            <i class="fas fa-{{ $item['icon'] }}"></i>
            <span>{{ $item['label'] }}</span>
            <span class="sd-dot"></span>
          </a>
        @else
          <a href="{{ $item['route'] }}" class="sd-link {{ $item['active'] ? 'active' : '' }}">
            <i class="fas fa-{{ $item['icon'] }}"></i>
            <span>{{ $item['label'] }}</span>
          </a>
        @endif
      @endif
    @endforeach
  </nav>

</aside>

<!-- Mobile Sidebar Panel -->
<div class="sidebar-mobile-overlay" id="mobileSidebarOverlay"></div>

<div class="sidebar-mobile-panel" id="mobileSidebarPanel" data-role="{{ $currentUser->role }}">
  <button class="sidebar-mobile-close" id="mobileSidebarClose" aria-label="Tutup navigasi">
    <i class="fas fa-times"></i>
  </button>

  <div class="sd-profile">
    <a href="{{ route('profile.edit') }}" class="sd-avatar" onclick="closeMobileSidebar()">
      @if($currentUser->photo)
        <img src="{{ $currentUser->photo_url }}" alt="Foto" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
      @else
        {{ strtoupper(substr($currentUser->name, 0, 1)) }}
      @endif
    </a>
    <div class="sd-info">
      <div class="sd-name" title="{{ $currentUser->name }}">{{ $currentUser->name }}</div>
      <span class="sd-role">{{ $currentUser->role === 'penjual' ? 'Penjual' : ($currentUser->role === 'petani' ? 'Petani' : ($currentUser->role === 'pembeli' ? 'Pembeli' : ($currentUser->role === 'penyuluh' ? 'Penyuluh' : 'Admin'))) }}</span>
    </div>
  </div>

  {{-- Quick nav for mobile --}}
  <nav class="sd-qnav">
    <a href="{{ url('/') }}" class="sd-qlink {{ Request::is('/') ? 'active' : '' }}" onclick="closeMobileSidebar()"><i class="fas fa-home"></i> Beranda</a>
    <a href="{{ route('marketplace.index') }}" class="sd-qlink {{ Request::is('marketplace*') ? 'active' : '' }}" onclick="closeMobileSidebar()"><i class="fas fa-store"></i> Marketplace</a>
    @if($currentUser->role !== 'penjual')
      <a href="{{ route('forum.index') }}" class="sd-qlink {{ Request::is('forum*') ? 'active' : '' }}" onclick="closeMobileSidebar()"><i class="fas fa-comments"></i> Forum</a>
    @endif
    <a href="{{ route('library.index') }}" class="sd-qlink {{ Request::is('library*') ? 'active' : '' }}" onclick="closeMobileSidebar()"><i class="fas fa-book"></i> E-Library</a>
  </nav>

  <div class="sd-divider"></div>

  <nav class="sd-nav">
    @foreach($menuItems as $item)
      @if(is_string($item) && $item === 'divider')
        <div class="sd-divider"></div>
      @elseif($hasRole($item['roles']))
        @if($item['key'] === 'keluar')
          <form method="POST" action="{{ $item['route'] }}" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari akun?')">
            @csrf
            <button type="submit" class="sd-link sd-logout" onclick="closeMobileSidebar()">
              <i class="fas fa-{{ $item['icon'] }}"></i>
              <span>{{ $item['label'] }}</span>
            </button>
          </form>
        @elseif($item['key'] === 'incoming' && !empty($item['badge']))
          <a href="{{ $item['route'] }}" class="sd-link {{ $item['active'] ? 'active' : '' }}" onclick="closeMobileSidebar()">
            <i class="fas fa-{{ $item['icon'] }}"></i>
            <span>{{ $item['label'] }}</span>
            <span class="sd-dot"></span>
          </a>
        @else
          <a href="{{ $item['route'] }}" class="sd-link {{ $item['active'] ? 'active' : '' }}" onclick="closeMobileSidebar()">
            <i class="fas fa-{{ $item['icon'] }}"></i>
            <span>{{ $item['label'] }}</span>
          </a>
        @endif
      @endif
    @endforeach
  </nav>
</div>

@push('scripts')
<script>
function closeMobileSidebar() {
    const panel = document.getElementById('mobileSidebarPanel');
    const overlay = document.getElementById('mobileSidebarOverlay');
    if (panel) panel.classList.remove('show');
    if (overlay) overlay.classList.remove('show');
    document.body.style.overflow = '';
}
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('mobileSidebarOverlay')?.addEventListener('click', closeMobileSidebar);
    document.getElementById('mobileSidebarClose')?.addEventListener('click', closeMobileSidebar);
});
</script>
@endpush
