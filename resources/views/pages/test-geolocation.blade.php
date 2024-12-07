@extends('layouts.main-admin')

@section('content')
    <x-container>
        <h2 class="mt-2">Tes Geolocation</h2>

        <div class="">
            <div>Latitude: <span id="latitude">N/A</span></div>
            <div>Longitude: <span id="longitude">N/A</span></div>

            <div class="mt-3">Kelurahan: <span id="kelurahan"></span></div>
            <div>Kecamatan: <span id="kecamatan"></span></div>
            <div>Kabupaten/Kota: <span id="kota"></span></div>
            <div class="mb-3">Provinsi: <span id="provinsi"></span></div>
        </div>

        <div id="map">
            <!-- Iframe for Google Maps -->
            <iframe id="map-iframe" width="100%" height="400" frameborder="0" style="border:0" allowfullscreen></iframe>
        </div>

        <a class="btn btn-primary mt-4" id="show-gmap-btn" target="blank" href="#">Show in Map</a>

        <script>
            $(document).ready(() => {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        const lat = position.coords.latitude;
                        const lon = position.coords.longitude;

                        console.log('Latitude from browser:', lat);
                        console.log('Longitude from browser:', lon);

                        $("#latitude").text(lat);
                        $("#longitude").text(lon);

                        // Construct Google Maps URL with the latitude and longitude
                        const mapUrl = `https://www.google.com/maps?q=${lat},${lon}&hl=es=0&z=15`;
                        const nominatimUrl = `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`;

                        console.log(mapUrl);
                        console.log(nominatimUrl);

                        $("#show-gmap-btn").attr("href", mapUrl);

                        // Set the iframe's src attribute to the constructed URL
                        document.getElementById("map-iframe").src = mapUrl;

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
                    },
                    function(error) {
                        console.error("Error getting location:", error);
                    },
                    {
                        enableHighAccuracy: true,  // Request high accuracy
                        timeout: 10000,            // Timeout after 10 seconds
                        maximumAge: 0              // Don't use cached location data
                    });
                } else {
                    alert("Geolocation is not supported by this browser.");
                }
            });
        </script>
    </x-container>
@endsection
