@extends('layouts.home_admin_guru')

@section('content')

    <div class="header">
    <div id="MyClockDisplay" class="clock" onload="showTime()"></div>
        <span id="tanggalRealtime"></span>
    </div>

    <div class="widgets">
        <a href="{{ route('profile.show') }}" class="widget"><ion-icon name="person"></ion-icon>Profile</a>
        <a href="{{ route('presensi.presensi_adminguru') }}" class="widget"><ion-icon name="time"></ion-icon>Presensi</a>
        <a href="{{ route('register') }}" class="widget"><ion-icon name="person-add"></ion-icon>Registrasi</a>
        <a href="{{ route('elearning.index') }}" class="widget"><ion-icon name="book"></ion-icon>E-Learning</a>
        <a href="#" class="widget"><ion-icon name="document"></ion-icon>E-Raport</a>
        <a href="#" class="widget"><ion-icon name="wallet"></ion-icon>E-Payment</a>
        <a href="#" class="widget"><ion-icon name="server"></ion-icon>E-PPDB</a>
        <a href="#" class="widget"><ion-icon name="information-circle"></ion-icon>Informasi</a>
    </div>

@endsection
