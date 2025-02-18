@extends('layouts.app')

@section('content')
    <div>
        @php
            $profilePhotoUrl = Auth::user()->profile_photo ? 'data:image/jpeg;base64,'.base64_encode(Auth::user()->profile_photo) : asset('images/default-profile-photo.jpg');
        @endphp
        <img src="{{ $profilePhotoUrl }}" alt="{{ __('Profile Photo') }}">
        <form method="POST" action="{{ route('profile.photo.store') }}" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="photo" class="block font-medium text-sm text-gray-700">Pilih Foto Profil</label>
                <input id="photo" type="file" class="mt-1 block w-full" name="photo" accept="image/*">
            </div>
            <x-button class="mt-3">Upload</x-button>
        </form>

        @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
            <div class="mt-10 sm:mt-0">
                @livewire('profile.update-password-form')
            </div>
            <x-section-border />
        @endif

        <div class="mt-10 sm:mt-0">
            @livewire('profile.logout-other-browser-sessions-form')
        </div>
    </div>
@endsection
