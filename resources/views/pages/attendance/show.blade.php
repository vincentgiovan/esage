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
                <td class="border border-1 border-secondary">{{ Carbon\Carbon::parse($attendance->attendance_date)->format("d F Y") }}</td>
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

        @if($attendance->longitude != null && $attendance->latitude != null)
            <h6 class="mt-4">Lokasi Absensi</h6>
            <table class="w-100 mb-5">
                <tr>
                    <th class="border border-1 border-secondary w-25">Kelurahan</th>
                    <td class="border border-1 border-secondary" id="kelurahan">N/A</td>
                </tr>
                <tr>
                    <th class="border border-1 border-secondary w-25">Kecamatan</th>
                    <td class="border border-1 border-secondary" id="kecamatan">N/A</td>
                </tr>
                <tr>
                    <th class="border border-1 border-secondary w-25">Kabupaten/Kota</th>
                    <td class="border border-1 border-secondary" id="kota">N/A</td>
                </tr>
                <tr>
                    <th class="border border-1 border-secondary w-25">Provinsi</th>
                    <td class="border border-1 border-secondary" id="provinsi">N/A</td>
                </tr>
            </table>

            <a class="btn btn-primary mb-3" id="show-gmap-btn" target="blank" href="#">Show in Google Map</a>
            <div id="map" class="w-75" style="height: 500px;"></div>

            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGHBUTgK3vq6NVPCoDsUUVjTl5LndznOA&callback=initMap&v=weekly" async defer></script>

            <script>
                const atd = @json($attendance);

                $(document).ready(() => {
                    // Construct Google Maps URL with the latitude and longitude
                    const mapUrl = `https://www.google.com/maps?q=${atd.latitude},${atd.longitude}&hl=es=0&z=15`;
                    const nominatimUrl = `https://nominatim.openstreetmap.org/reverse?lat=${atd.latitude}&lon=${atd.longitude}&format=json`;

                    $("#show-gmap-btn").attr("href", mapUrl);

                    fetch(nominatimUrl)
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

                                $("#kelurahan").text(kelurahan);
                                $("#kecamatan").text(kecamatan);
                                $("#kota").text(kota);
                                $("#provinsi").text(provinsi);
                            }
                        })
                        .catch(error => {
                            console.error("Error with reverse geocoding:", error);
                        });
                });

                let map;
                let marker;

                function initMap() {
                    const userLocation = { lat: atd.latitude, lng: atd.longitude };

                    // Initialize the map centered around the user's location
                    map = new google.maps.Map(document.getElementById("map"), {
                        zoom: 15,
                        center: userLocation,
                    });

                    // Place a marker at the user's location
                    marker = new google.maps.Marker({
                        position: userLocation,
                        map: map,
                        title: "You are here",
                    });
                }
            </script>
        @endif
    </x-container>
@endsection
