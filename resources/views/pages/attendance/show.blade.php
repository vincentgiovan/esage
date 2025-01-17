@extends('layouts.main-admin')

@section('content')
    <x-container>
        <h3 class="mt-3">Detail Data Presensi</h3>

        <h6 class="mt-4">Data Absensi</h6>
        <table class="w-100">
            <tr>
                <th class="border border-1 border-secondary w-25">Pegawai</th>
                <td class="border border-1 border-secondary">{{ $attendance->employee->nama }}</td>
            </tr>
            <tr>
                <th class="border border-1 border-secondary w-25">Proyek</th>
                <td class="border border-1 border-secondary">{{ $attendance->project->project_name }}</td>
            </tr>
            <tr>
                <th class="border border-1 border-secondary w-25">Tanggal</th>
                <td class="border border-1 border-secondary">{{ Carbon\Carbon::parse($attendance->attendance_date)->translatedFormat("d F Y") }}</td>
            </tr>
            <tr>
                <th class="border border-1 border-secondary w-25">Jam Masuk dan Keluar</th>
                <td class="border border-1 border-secondary">{{ Carbon\Carbon::parse($attendance->jam_masuk)->format("H:i") }}-{{ Carbon\Carbon::parse($attendance->jam_keluar)->format("H:i") }}</td>
            </tr>
            <tr>
                <th class="border border-1 border-secondary w-25">Jam Kerja</th>
                <td class="border border-1 border-secondary">
                    <ul>
                        <li>Jam Normal: {{ $attendance->normal }}</li>
                        <li>Jam Lembur: {{ $attendance->jam_lembur }}</li>
                        <li>Indeks Lembur Panjang: {{ $attendance->index_lembur_panjang }}</li>
                        <li>Indeks Performa: {{ $attendance->index_performa }}</li>
                    </ul>
                </td>
            </tr>
        </table>

        @if($attendance->longitude_masuk != null && $attendance->latitude_masuk != null)
            <h6 class="mt-4">Lokasi Absensi (Masuk)</h6>
            <table class="w-100 mb-5">
                <tr>
                    <th class="border border-1 border-secondary w-25">Kelurahan</th>
                    <td class="border border-1 border-secondary" id="kelurahan-masuk">N/A</td>
                </tr>
                <tr>
                    <th class="border border-1 border-secondary w-25">Kecamatan</th>
                    <td class="border border-1 border-secondary" id="kecamatan-masuk">N/A</td>
                </tr>
                <tr>
                    <th class="border border-1 border-secondary w-25">Kabupaten/Kota</th>
                    <td class="border border-1 border-secondary" id="kota-masuk">N/A</td>
                </tr>
                <tr>
                    <th class="border border-1 border-secondary w-25">Provinsi</th>
                    <td class="border border-1 border-secondary" id="provinsi-masuk">N/A</td>
                </tr>
            </table>

            <a class="btn btn-primary mb-3" id="show-gmap-in-btn" target="blank" href="#">Show in Google Map</a>
            <div id="map-in" class="w-75" style="height: 500px;"></div>

            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGHBUTgK3vq6NVPCoDsUUVjTl5LndznOA&callback=initMaps&v=weekly" async defer></script>
        @endif

        @if($attendance->longitude_keluar != null && $attendance->latitude_keluar != null)
            <h6 class="mt-4">Lokasi Absensi (Keluar)</h6>
            <table class="w-100 mb-5">
                <tr>
                    <th class="border border-1 border-secondary w-25">Kelurahan</th>
                    <td class="border border-1 border-secondary" id="kelurahan-keluar">N/A</td>
                </tr>
                <tr>
                    <th class="border border-1 border-secondary w-25">Kecamatan</th>
                    <td class="border border-1 border-secondary" id="kecamatan-keluar">N/A</td>
                </tr>
                <tr>
                    <th class="border border-1 border-secondary w-25">Kabupaten/Kota</th>
                    <td class="border border-1 border-secondary" id="kota-keluar">N/A</td>
                </tr>
                <tr>
                    <th class="border border-1 border-secondary w-25">Provinsi</th>
                    <td class="border border-1 border-secondary" id="provinsi-keluar">N/A</td>
                </tr>
            </table>

            <a class="btn btn-primary mb-3" id="show-gmap-out-btn" target="blank" href="#">Show in Google Map</a>
            <div id="map-out" class="w-75" style="height: 500px;"></div>
        @endif

        @if($attendance->latitude_masuk || $attendance->latitude_keluar)
            <h6 class="mt-4">Bukti Kehadiran</h6>
            <div class="w-100 d-flex gap-3">
                <div class="w-50">
                    <div class="fst-italic">Masuk</div>
                    @if($attendance->bukti_masuk)
                        <video width="100%" height="360" controls>
                            <source src="{{ Storage::url("app/public/" . $attendance->bukti_masuk) }}" type="video/webm">
                        </video>
                    @else
                        N/A
                    @endif
                </div>

                <div class="w-50">
                    <div class="fst-italic">Keluar</div>
                    @if($attendance->bukti_keluar)
                        <video width="100%" height="360" controls>
                            <source src="{{ Storage::url("app/public/" . $attendance->bukti_keluar) }}" type="video/webm">
                        </video>
                    @else
                        N/A
                    @endif
                </div>
            </div>
        @endif
    </x-container>

    <script>
        function initMaps() {
            const atd = @json($attendance);

            if (atd.latitude_masuk && atd.longitude_masuk) {
                const userLocationIn = { lat: atd.latitude_masuk, lng: atd.longitude_masuk };
                const mapIn = new google.maps.Map(document.getElementById("map-in"), {
                    zoom: 15,
                    center: userLocationIn,
                });
                new google.maps.Marker({
                    position: userLocationIn,
                    map: mapIn,
                    title: "You are here (Masuk)",
                });

                // Construct Google Maps URL with the latitude and longitude
                const mapUrlIn = `https://www.google.com/maps?q=${atd.latitude_masuk},${atd.longitude_masuk}&hl=es=0&z=15`;
                const nominatimUrlIn = `https://nominatim.openstreetmap.org/reverse?lat=${atd.latitude_masuk}&lon=${atd.longitude_masuk}&format=json`;

                $("#show-gmap-in-btn").attr("href", mapUrlIn);

                fetch(nominatimUrlIn)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            console.error("Error with Nominatim geocoding:", data.error);
                        } else {
                            const address = data.address;
                            const kelurahan = address.village || address.hamlet || 'N/A'; // Look for village data
                            const kecamatan = address.suburb || address.district || 'N/A'; // Kecamatan (sub-district)
                            const kota = address.city || address.town || address.city_district || address.county || 'N/A'; // Kota (city)
                            const provinsi = address.state || 'N/A'; // Provinsi (province)

                            $("#kelurahan-masuk").text(kelurahan);
                            $("#kecamatan-masuk").text(kecamatan);
                            $("#kota-masuk").text(kota);
                            $("#provinsi-masuk").text(provinsi);
                        }
                    })
                    .catch(error => {
                        console.error("Error with reverse geocoding:", error);
                    });
            }

            if (atd.latitude_keluar && atd.longitude_keluar) {
                const userLocationOut = { lat: atd.latitude_keluar, lng: atd.longitude_keluar };
                const mapOut = new google.maps.Map(document.getElementById("map-out"), {
                    zoom: 15,
                    center: userLocationOut,
                });
                new google.maps.Marker({
                    position: userLocationOut,
                    map: mapOut,
                    title: "You are here (Keluar)",
                });

                // Construct Google Maps URL with the latitude and longitude
                const mapUrlOut = `https://www.google.com/maps?q=${atd.latitude_keluar},${atd.longitude_keluar}&hl=es=0&z=15`;
                const nominatimUrlOut = `https://nominatim.openstreetmap.org/reverse?lat=${atd.latitude_keluar}&lon=${atd.longitude_keluar}&format=json`;

                $("#show-gmap-out-btn").attr("href", mapUrlOut);

                fetch(nominatimUrlOut)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            console.error("Error with Nominatim geocoding:", data.error);
                        } else {
                            const address = data.address;
                            const kelurahan = address.village || address.hamlet || 'N/A'; // Look for village data
                            const kecamatan = address.suburb || address.district || 'N/A'; // Kecamatan (sub-district)
                            const kota = address.city || address.town || address.city_district || address.county || 'N/A'; // Kota (city)
                            const provinsi = address.state || 'N/A'; // Provinsi (province)

                            $("#kelurahan-keluar").text(kelurahan);
                            $("#kecamatan-keluar").text(kecamatan);
                            $("#kota-keluar").text(kota);
                            $("#provinsi-keluar").text(provinsi);
                        }
                    })
                    .catch(error => {
                        console.error("Error with reverse geocoding:", error);
                    });
            }
        }
    </script>
@endsection
