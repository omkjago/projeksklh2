
@extends('layouts.presensi')

@section('content')

<a href="{{ route('riwayat.absensi') }}" class="btn btn-secondary">Riwayat Absensi</a>
<h1 class="text-center" id="judulPresensi">Presensi</h1>
    <div class="container mt-4">
        <input type="hidden" id="lokasi">
        <div class="webcam-capture"></div>
        <img id="photo" style="display: none;">
    </div>
    @if ($cek_presensi_masuk > 0 && $cek_presensi_pulang > 0)
    <style>
        #takepresensi {
            display: none; /* Sembunyikan tombol presensi */
        }
        .webcam-capture {
            display: none; /* Sembunyikan kamera */
        }
        #judulPresensi {
            display: none; /* Sembunyikan kamera */
        }
        #map {
            display: none; /* Sembunyikan kamera */
        }
        #presensiStatus {
            display: block; /* Tampilkan kotak status presensi */
        }
    </style>
    <!-- Kotak Status Presensi -->
    <div id="presensiStatus" class="alert alert-success text-center" role="alert">
        Anda sudah melakukan presensi (Masuk & Pulang).
    </div>
    
    @else
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                @if ($cek_presensi_masuk > 0)
                    <button class="btn btn-danger btn-block" id="takepresensi" disabled>
                        <ion-icon name="camera"></ion-icon>
                        PRESENSI PULANG
                    </button>
                @else
                    <button id="takepresensi" class="btn btn-light btn-block" disabled>
                        <ion-icon name="camera"></ion-icon>
                        PRESENSI MASUK
                    </button>
                @endif
            </div>
            <div class="col-md-6">
                <label for="selectKantor" class="form-label">Pilih Kampus:</label>
                <select class="form-select" id="selectKantor" onchange="changeOfficeLocation()">
                    <option value="">Pilih Kampus</option>
                    <option value="-7.267338508866716,110.40897089751496">Kampus a</option>
                    <option value="-7.267522471650335,110.40347313769743">Kampus b</option>
                    <option value="-6.981849333885401, 110.41202775359291">Kampus c</option>
                    <option value="-6.998057492991495, 110.42390890784232">Kampus d</option>
                </select>
            </div>
        </div>
    @endif

    <div id="map" class="container mt-4"></div>
    <!-- JavaScript -->
    <script>
        var map;
        var circle;

        $(document).ready(function() {
            Webcam.set({
                width: 480,
                height: 640,
                image_format: 'jpeg',
                image_quality: 0.2,
            });
            Webcam.attach('.webcam-capture');

            var lokasi = document.getElementById('lokasi');
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
            }
            function successCallback(position) {
                lokasi.value = position.coords.latitude + ', ' + position.coords.longitude;
                map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 17);
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);
                L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
            }
            function errorCallback(error) {
                lokasi.value = 'Error: ' + error.message;
            }
            $('#takepresensi').click(function() {
                Webcam.snap(function(uri) {
                    image = uri;
                });
                var lokasi = $('#lokasi').val();
                var selectedOfficeCoordinates = $('#selectKantor').val().split(','); // Ambil koordinat lokasi kantor yang dipilih
                var selectedLatitude = parseFloat(selectedOfficeCoordinates[0]);
                var selectedLongitude = parseFloat(selectedOfficeCoordinates[1]);

                $.ajax({
                    type: 'POST',
                    url: '/presensi/store',
                    data: {
                        _token: "{{ csrf_token() }}",
                        image: image,
                        lokasi: lokasi,
                        selectedLatitude: selectedLatitude,
                        selectedLongitude: selectedLongitude
                    },
                    cache: false,
                    success: function(response){
                        var status = response.split('|');
                        if (status[0] == "success") {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: status[1],
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                            setTimeout(function() {
                                @if (Auth::user()->hasRole('guru'))
                                location.href = '{{ route("dashboard-guru") }}';
                                @elseif (Auth::user()->hasRole('admin'))
                                    location.href = '{{ route("dashboard-admin") }}';
                                @elseif (Auth::user()->hasRole('siswa'))
                                    location.href = '{{ route("dashboard-siswa") }}';
                                @elseif (Auth::user()->hasRole('orangtua'))
                                    location.href = '{{ route("dashboard-orangtua") }}';
                                @endif
                            }, 3000);
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: status[1],
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
            });
        });

        function changeOfficeLocation() {
            var selectedOfficeCoordinates = $('#selectKantor').val().split(',');
            if (selectedOfficeCoordinates[0] !== "" && selectedOfficeCoordinates[1] !== "") {
                $('#takepresensi').prop('disabled', false); // Aktifkan tombol presensi jika lokasi kantor dipilih
            } else {
                $('#takepresensi').prop('disabled', true); // Nonaktifkan tombol presensi jika lokasi kantor tidak dipilih
            }

            var selectedLatitude = parseFloat(selectedOfficeCoordinates[0]);
            var selectedLongitude = parseFloat(selectedOfficeCoordinates[1]);

            map.panTo([selectedLatitude, selectedLongitude]); // Pindahkan peta ke lokasi kantor yang dipilih
            
            // Hapus lingkaran yang ada jika ada
            if (circle) {
                map.removeLayer(circle);
            }
            
            // Tambahkan lingkaran baru di lokasi kantor yang dipilih
            circle = L.circle([selectedLatitude, selectedLongitude], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: 10,
            }).addTo(map);
        }
    </script>
@endsection
