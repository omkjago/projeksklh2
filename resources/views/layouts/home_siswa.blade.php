<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sekolahku</title>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">

</head>
<body>
    <div class="container">
    <div class="home-page container">
    <header class="header">
        <div class="header-left">
            <h1>Sekolahku</h1>
            <button id="sidebarToggle" class="sidebar-toggle" aria-label="Toggle Sidebar">☰</button>
        </div>
        <button class="logout"><form method="POST" action="{{ route('logout') }}">
            @csrf
            <ion-icon name="log-out"></ion-icon>
            <x-nav-link :href="route('logout')"
                onclick="event.preventDefault();
                        this.closest('form').submit();">
                {{ __('Logout') }}
            </x-nav-link>
        </form>
        </button>
    </header>
    <div class="content-wrapper">
        <aside id="sidebar" class="sidebar">
            <div class="sidebar-menu">
            @if(Auth::user()->hasRole('admin'))
                            <a href="{{ route('dashboard-admin') }}"><ion-icon name="speedometer"></ion-icon><span class="menu-text">Dashboard</span></a>
                        @elseif(Auth::user()->hasRole('guru'))
                            <a href="{{ route('dashboard-guru') }}"><ion-icon name="speedometer"></ion-icon><span class="menu-text">Dashboard</span></a>
                        @elseif(Auth::user()->hasRole('siswa'))
                            <a href="{{ route('dashboard-siswa') }}"><ion-icon name="speedometer"></ion-icon><span class="menu-text">Dashboard</span></a>
                        @elseif(Auth::user()->hasRole('orangtua'))
                            <a href="{{ route('dashboard-orangtua') }}"><ion-icon name="speedometer"></ion-icon><span class="menu-text">Dashboard</span></a>
                        @endif                <a href="{{ route('profile.show') }}"><ion-icon name="person"></ion-icon><span class="menu-text">Profile</span></a>
                <a href="{{ route('presensi.create') }}"><ion-icon name="time"></ion-icon><span class="menu-text">Presensi</span></a>
                <a href="#"><ion-icon name="book"></ion-icon><span class="menu-text">E-Learning</span></a>
                <a href="#"><ion-icon name="document"></ion-icon><span class="menu-text">E-Raport</span></a>
                <a href="#"><ion-icon name="wallet"></ion-icon><span class="menu-text">E-Payment</span></a>
                <a href="#"><ion-icon name="server"></ion-icon><span class="menu-text">E-PPDB</span></a>
                <a href="#"><ion-icon name="information-circle"></ion-icon><span class="menu-text">Informasi</span></a>
            </div>
            <div class="sidebar-footer">
            @php
            $profilePhotoUrl = Auth::user()->profile_photo ? 'data:image/jpeg;base64,'.base64_encode(Auth::user()->profile_photo) : asset('images/default-profile-photo.jpg');
            @endphp
            <img src="{{ $profilePhotoUrl }}" alt="{{ __('Profile Photo') }}">
            <span>{{ Auth::user()->name }}</span> 
            </div>
        </aside>
        <main class="main-content">
        @yield('content')
        </main>
        </div>
</div>

<!-- Dropdown Menu for Mobile -->
<div id="mobileDropdown" class="mobile-dropdown">
    <div class="mobile-menu">
        <a href="#"><ion-icon name="speedometer"></ion-icon><span class="menu-text">Dashboard</span></a>
        <a href="{{ route('profile.show') }}"><ion-icon name="person"></ion-icon><span class="menu-text">Profile</span></a>
        <a href="{{ route('presensi.create') }}"><ion-icon name="time"></ion-icon><span class="menu-text">Presensi</span></a>
        <a href="#"><ion-icon name="book"></ion-icon><span class="menu-text">E-Learning</span></a>
        <a href="#"><ion-icon name="document"></ion-icon><span class="menu-text">E-Raport</span></a>
        <a href="#"><ion-icon name="wallet"></ion-icon><span class="menu-text">E-Payment</span></a>
        <a href="#"><ion-icon name="server"></ion-icon><span class="menu-text">E-PPDB</span></a>
        <a href="#"><ion-icon name="information-circle"></ion-icon><span class="menu-text">Informasi</span></a>
    </div>
</div>

    </div>
    <script src="{{ asset('js/home.js') }}"></script>
</body>
</html>
