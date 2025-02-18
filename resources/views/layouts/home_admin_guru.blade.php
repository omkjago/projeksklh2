<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sekolahku</title>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='1em' height='1em' viewBox='0 0 512 512'%3E%3Cpath fill='white' d='M256 48C123.46 48 16 156.55 16 290.56a243.3 243.3 0 0 0 60.32 160.87c1.18 1.3 2.25 2.6 3.43 3.79C89.2 464 92.07 464 99.57 464s12.43 0 19.93-8.88C152 416.64 202 400 256 400s104.07 16.71 136.5 55.12C400 464 404.82 464 412.43 464s11.3 0 19.82-8.78c1.22-1.25 2.25-2.49 3.43-3.79A243.3 243.3 0 0 0 496 290.56C496 156.55 388.54 48 256 48m-16 64h32v64h-32Zm-96 192H80v-32h64Zm21.49-83.88l-45.25-45.26l22.62-22.62l45.26 45.25ZM278.6 307.4a31 31 0 0 1-7 7a30.11 30.11 0 0 1-35-49L320 224Zm45.28-109.91l45.26-45.25l22.62 22.62l-45.25 45.26ZM432 304h-64v-32h64Z'/%3E%3C/svg%3E" type="image/x-icon">
    
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
                <a href="{{ route('presensi.presensi_adminguru') }}"><ion-icon name="time"></ion-icon><span class="menu-text">Presensi</span></a>
                <a href="{{ route('register') }}"><ion-icon name="person-add"></ion-icon><span class="menu-text">Registrasi</span></a>
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
    @if(Auth::user()->hasRole('admin'))
                            <a href="{{ route('dashboard-admin') }}"><ion-icon name="speedometer"></ion-icon><span class="menu-text">Dashboard</span></a>
                        @elseif(Auth::user()->hasRole('guru'))
                            <a href="{{ route('dashboard-guru') }}"><ion-icon name="speedometer"></ion-icon><span class="menu-text">Dashboard</span></a>
                        @elseif(Auth::user()->hasRole('siswa'))
                            <a href="{{ route('dashboard-siswa') }}"><ion-icon name="speedometer"></ion-icon><span class="menu-text">Dashboard</span></a>
                        @elseif(Auth::user()->hasRole('orangtua'))
                            <a href="{{ route('dashboard-orangtua') }}"><ion-icon name="speedometer"></ion-icon><span class="menu-text">Dashboard</span></a>
                        @endif
        <a href="{{ route('profile.show') }}"><ion-icon name="person"></ion-icon><span class="menu-text">Profile</span></a>
        <a href="{{ route('presensi.presensi_adminguru') }}"><ion-icon name="time"></ion-icon><span class="menu-text">Presensi</span></a>
        <a href="{{ route('register') }}"><ion-icon name="person-add"></ion-icon><span class="menu-text">Registrasi</span></a>
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
