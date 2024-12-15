@extends('layouts.main-admin')

@section('content')
    <x-container-middle>
        <div class="container bg-white rounded-4 p-5 border border-1 card">
            <h3 class="text-center fw-bold">Presensi Mandiri</h3>
            <form method="POST" action="{{ route('attendance-store-self') }}">
                @csrf

                <div class="mt-3">
                    <label>Tanggal</label>
                    <input type="text" class="form-control" value="{{ Carbon\Carbon::parse(Carbon\Carbon::now())->format('Y-m-d') }}" disabled>
                </div>

                <div class="mt-3">
                    <label>Nama Pegawai</label>
                    <input type="text" class="form-control" value="{{ Auth::user()->name }}" disabled>
                </div>

                <div class="mt-3">
                    <label for="project_id">Proyek</label>
                    <select type="text" class="form-select text-black @error('project_id') is-invalid @enderror" id="project_id" name="project_id">
                        <option selected disabled>Pilih proyek</option>
                        @foreach ($projects as $p)
                            <option value="{{ $p->id }}" @if(old("project_id") == $p->id) selected @endif>{{ $p->project_name }}</option>
                        @endforeach
                    </select>
                    @error('project_id')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="w-100 d-flex gap-3">
                    <div class="mt-3 w-50">
                        <label>Jam Masuk</label>
                        <input type="text" id="start-server-time" class="form-control" disabled>
                    </div>

                    <div class="mt-3 w-50">
                        <label>Jam Keluar</label>
                        <input type="text" id="end-server-time" class="form-control" disabled>
                    </div>
                </div>

                <div class="mt-3">
                    <label for="image">Bukti Kehadiran</label>
                    <input type="file" class="form-control" name="image" id="image">
                    @error('image')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <img id="img-preview" class="w-25 mt-2">

                <div class="mt-4">
                    <input type="submit" class="btn btn-primary px-3 py-1" value="Check In">
                </div>
            </form>
        </div>
    </x-container-middle>

    <script>
        $(document).ready(() => {
            // Preview foto
            $("#image").on("change", function(){
                const oFReader = new FileReader();
                oFReader.readAsDataURL(image.files[0]);

                oFReader.onload = function(oFEvent){
                    $("#img-preview").attr("src", oFEvent.target.result);
                }
            });


            // Menampilkan waktu server
            let serverTime = @json(\Carbon\Carbon::now()->toDateTimeString());
            let currentTime = new Date(serverTime);

            function updateTime() {
                // Increment the time by 1 second
                currentTime.setSeconds(currentTime.getSeconds() + 1);

                let hours = currentTime.getHours();
                let minutes = currentTime.getMinutes();
                let seconds = currentTime.getSeconds();

                // Format time (add leading zeros if necessary)
                hours = (hours < 10) ? '0' + hours : hours;
                minutes = (minutes < 10) ? '0' + minutes : minutes;
                seconds = (seconds < 10) ? '0' + seconds : seconds;

                // Display the time in the div with id "server-time"
                $('#start-server-time').val(hours + ':' + minutes + ':' + seconds);
            }

            setInterval(updateTime, 1000);
            updateTime();


            // Get user's location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    console.log("Location allowed.");
                },
                function(error) {
                    console.error("Error getting location:", error);
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }

            const all_employees = @json($employees);

            $("#employee_id").change(function(){
                const targetted = all_employees.find(item => item.id == $(this).val());

                $("#pokok").val(targetted.pokok);
                $("#lembur").val(targetted.lembur);
                $("#lembur_panjang").val(targetted.lembur_panjang);
                $("#performa").val(targetted.performa);
            });


            // Input beberapa data buat submit form
            $("form").on("submit", function(e){
                e.preventDefault();

                // Create a Promise to handle geolocation
                const locationPromise = new Promise((resolve, reject) => {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            const lat = position.coords.latitude;
                            const lon = position.coords.longitude;

                            $("form").append($("<input>").attr({"type": "hidden", "name": "latitude", "value": lat}));
                            $("form").append($("<input>").attr({"type": "hidden", "name": "longitude", "value": lon}));

                            resolve(); // Location success
                        },
                        function(error) {
                            console.error("Error getting location:", error);
                            alert("Error occurred while retrieving location.");
                            reject(); // Location failed
                        },
                        {
                            enableHighAccuracy: true,  // Request high accuracy
                            timeout: 10000,            // Timeout after 10 seconds
                            maximumAge: 0              // Don't use cached location data
                        });
                    } else {
                        alert("Geolocation is not supported by this browser.");
                        reject(); // Geolocation not supported
                    }
                });

                // Handle the location promise
                locationPromise.then(() => {
                    // Proceed with form submission if location is allowed
                    this.submit();
                }).catch(() => {
                    alert("Please allow location access in this web app.");
                });
            });
        });
    </script>


@endsection
