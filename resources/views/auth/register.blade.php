@extends('layouts.register')

@section('content')
    <x-authentication-card>
        <x-slot name="logo">
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="registerForm" novalidate>
            @csrf

            <!-- Pilihan Metode Registrasi -->
            <div class="mt-4">
                <x-label for="registration_method" value="{{ __('Registration Method') }}" />
                <select id="registration_method" name="registration_method" class="block mt-1 w-full form-select" required onchange="toggleRegistrationMethod()">
                    <option value="manual">{{ __('Manual') }}</option>
                    <option value="excel">{{ __('Upload Excel File') }}</option>
                </select>
            </div>

            <!-- Form Manual -->
            <div id="manual_form">
                <div>
                    <x-label for="name" value="{{ __('Name') }}" />
                    <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                </div>

                <div class="mt-4">
                    <x-label for="email" value="{{ __('Email') }}" />
                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                </div>

                <div class="mt-4">
                    <x-label for="password" value="{{ __('Password') }}" />
                    <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                </div>

                <div class="mt-4">
                    <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                    <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>

                <!-- Role Selection -->
                <div class="mt-4">
                    <x-label for="role" value="{{ __('Role') }}" />
                    <select id="role" name="role" class="block mt-1 w-full form-select" required>
                        @if(auth()->check() && auth()->user()->role == 'admin')
                            <option value="admin">{{ __('Admin') }}</option>
                            <option value="guru">{{ __('Guru') }}</option>
                            <option value="siswa">{{ __('Siswa') }}</option>
                            <option value="orangtua">{{ __('Orang Tua') }}</option>
                        @elseif(auth()->check() && auth()->user()->role == 'guru')
                            <option value="siswa">{{ __('Siswa') }}</option>
                            <option value="orangtua">{{ __('Orang Tua') }}</option>
                        @endif
                    </select>
                </div>
            </div>

            <!-- Upload Excel -->
            <div id="excel_form" style="display: none;">
                <div class="mt-4">
                    <x-label for="excel_file" value="{{ __('Upload Excel File') }}" />
                    <x-input id="excel_file" class="block mt-1 w-full" type="file" name="excel_file" />
                </div>
            </div>
            <!-- Download Excel File Based on Role -->
             <div id="download_buttons" style="display: none;" class="mt-4">
                @if(auth()->check())
                @if(auth()->user()->role == 'admin')
                <x-button type="button" class="btn btn-primary" onclick="window.location='{{ asset('excel/regis_sebagai_admin.xlsx') }}'">{{ __('Download Excel for Admin Registration') }}</x-button>
                @elseif(auth()->user()->role == 'guru')
                <x-button type="button" class="btn btn-primary" onclick="window.location='{{ asset('excel/regis_sebagai_guru.xlsx') }}'">{{ __('Download Excel for Guru Registration') }}</x-button>
                @endif
                @endif
            </div>
            
            <div class="flex items-center justify-end mt-4">
                <x-button class="ms-4">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>

    </x-authentication-card>

    <script>
        function toggleRegistrationMethod() {
            var method = document.getElementById('registration_method').value;
            if (method == 'excel') {
                document.getElementById('manual_form').style.display = 'none';
                document.getElementById('excel_form').style.display = 'block';
                document.getElementById('download_buttons').style.display = 'block'; // Show download buttons
            } else {
                document.getElementById('manual_form').style.display = 'block';
                document.getElementById('excel_form').style.display = 'none';
                document.getElementById('download_buttons').style.display = 'none'; // Hide download buttons
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            var registerForm = document.getElementById('registerForm');
            registerForm.addEventListener('submit', function (event) {
                var method = document.getElementById('registration_method').value;
                if (method === 'excel') {
                    var fileInput = document.getElementById('excel_file');
                    if (!fileInput.files || fileInput.files.length === 0) {
                        event.preventDefault(); // Prevent form submission
                        alert('Please select a file.');
                    }
                }
            });
        });
    </script>
@endsection
