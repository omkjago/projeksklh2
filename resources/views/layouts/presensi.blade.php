<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Presensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js" integrity="sha512-dQIiHSl2hr3NWKKLycPndtpbh5iaHLo6MwrXm7F0FM5e+kL2U16oE9uIwPHUl6fQBeCthiEuV/rzP3MiAB8Vfw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        .webcam-capture,
        .webcam-capture video{
            display: inline-block;
            width: 100% !important;
            margin: auto;
            height: auto !important;
            border-radius: 10px;
        }
        #map { height: 180px; }
        h1.text-center {
            color: white;
        }
        .form-label {
            color: white;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='1em' height='1em' viewBox='0 0 512 512'%3E%3Cpath fill='white' d='M256 48C141.13 48 48 141.13 48 256s93.13 208 208 208s208-93.13 208-208S370.87 48 256 48m96 240h-96a16 16 0 0 1-16-16V128a16 16 0 0 1 32 0v128h80a16 16 0 0 1 0 32'/%3E%3C/svg%3E" type="image/x-icon">


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
                        @endif
                <a href="{{ route('profile.show') }}"><ion-icon name="person"></ion-icon><span class="menu-text">Profile</span></a>
                @if(Auth::user()->hasRole('admin'))
        <a href="{{ route('presensi.presensi_adminguru') }}"><ion-icon name="time"></ion-icon><span class="menu-text">Presensi</span></a>
                        @elseif(Auth::user()->hasRole('guru'))
                        <a href="{{ route('presensi.presensi_adminguru') }}"><ion-icon name="time"></ion-icon><span class="menu-text">Presensi</span></a>
                        @elseif(Auth::user()->hasRole('siswa'))
                        <a href="{{ route('presensi.create') }}"><ion-icon name="time"></ion-icon><span class="menu-text">Presensi</span></a>
                        @endif
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
        @if(Auth::user()->hasRole('admin'))
        <a href="{{ route('presensi.presensi_adminguru') }}"><ion-icon name="time"></ion-icon><span class="menu-text">Presensi</span></a>
                        @elseif(Auth::user()->hasRole('guru'))
                        <a href="{{ route('presensi.presensi_adminguru') }}"><ion-icon name="time"></ion-icon><span class="menu-text">Presensi</span></a>
                        @elseif(Auth::user()->hasRole('siswa'))
                        <a href="{{ route('presensi.create') }}"><ion-icon name="time"></ion-icon><span class="menu-text">Presensi</span></a>
                        @endif
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
