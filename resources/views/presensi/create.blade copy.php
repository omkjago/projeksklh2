<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Presensi</title>

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
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body>
    <h1>Presensi</h1>
    <div>
        <input type="hidden" id="lokasi">
        <div class="webcam-capture"></div>
        <img id="photo" style="display: none;">
    </div>
    <div>
        <button id="takepresensi">PRESENSI MASUK</button>
    </div>
    <div id="map"></div>
    <!-- JavaScript -->
    <script>
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
                var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 17);
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);
                L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
                var circle = L.circle([position.coords.latitude, position.coords.longitude], {
                    color: 'red',
                    fillColor: '#f03',
                    fillOpacity: 0.5,
                    radius: 100
                }).addTo(map);
            }
            function errorCallback(error) {
                lokasi.value = 'Error: ' + error.message;
            }
            $('#takepresensi').click(function() {
                Webcam.snap(function(data_uri) {
                    // Tampilkan foto yang diambil
                    $('#photo').attr('src', data_uri).show();
                    // Simpan data URI foto ke dalam input hidden
                    $('#fotoInput').val(data_uri);
                });
            });
            $('#takepresensi').click(function() {
                var fotoInput = $('#fotoInput').val();
                var lokasi = $('#lokasi').val();
                $.ajax({
                    type: 'POST',
                    url: '/presensi/store',
                    data: {
                        _token: "{{ csrf_token() }}",
                        image: fotoInput,
                        lokasi: lokasi
                    },
                    cache: false,
                    success: function(response){
                        if (response.status == 'success') {
                            alert('Presensi berhasil disimpan.');
                        } else {
                            alert('Presensi gagal disimpan.');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
