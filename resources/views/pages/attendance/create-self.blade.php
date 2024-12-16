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

                {{-- <div class="mt-3">
                    <label for="image">Bukti Kehadiran</label>
                    <input type="file" class="form-control" name="image" id="image" accept="image/*" capture="environment">
                    @error('image')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div> --}}

                <div class="mt-3 d-flex w-100 flex-column">
                    <label>Dokumentasi Presensi</label>

                    <div class="d-flex">
                        <video id="video" autoplay class="w-50"></video>
                        <video id="preview" controls class="w-50" style="display: none;"></video>
                        </div>

                    <div class="d-flex">
                        <button type="button" id="startRecording">Start Recording</button>
                        <button type="button" id="stopRecording" disabled>Stop Recording</button>
                    </div>

                    <input type="file" id="peereEengfoet" name="evidence" style="display:none;" accept="video/*">
                </div>

                <!-- Include ffmpeg.wasm -->
                <script src="https://cdn.jsdelivr.net/npm/@ffmpeg/ffmpeg@latest"></script>

                <div class="mt-4">
                    <input type="submit" class="btn btn-primary px-3 py-1" value="Check In">
                </div>
            </form>
        </div>
    </x-container-middle>

    <script>
        $(window).on('load', () => {
            let mediaRecorder;
            let recordedChunks = [];

            navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" }, audio: true })
                .then(stream => {
                const video = document.getElementById('video');
                video.srcObject = stream;

                mediaRecorder = new MediaRecorder(stream);

                mediaRecorder.ondataavailable = function (event) {
                    recordedChunks.push(event.data);
                };

                mediaRecorder.onstop = function () {
                    const blob = new Blob(recordedChunks, { type: 'video/webm' });
                    const url = URL.createObjectURL(blob);

                    // Display video preview
                    const preview = document.getElementById('preview');
                    preview.src = url;
                    preview.style.display = 'block';

                    const peereEengfoet = document.getElementById('peereEengfoet');
                    const dataTransfer = new DataTransfer();
                    const file = new File([blob], 'recorded-video.webm', { type: 'video/webm' });
                    dataTransfer.items.add(file);

                    peereEengfoet.files = dataTransfer.files;

                    const event = new Event('change');
                    peereEengfoet.dispatchEvent(event);
                };
                })
                .catch(err => {
                    console.error('Error accessing the camera:', err);
                    alert('Camera access failed!');
                });

            document.getElementById('startRecording').addEventListener('click', () => {
                if (mediaRecorder && mediaRecorder.state === 'inactive') {
                recordedChunks = [];
                mediaRecorder.start();
                document.getElementById('startRecording').disabled = true;
                document.getElementById('stopRecording').disabled = false;
                console.log('Recording started');
                }
            });

            document.getElementById('stopRecording').addEventListener('click', () => {
                if (mediaRecorder && mediaRecorder.state === 'recording') {
                mediaRecorder.stop();
                document.getElementById('startRecording').disabled = false;
                document.getElementById('stopRecording').disabled = true;
                console.log('Recording stopped');
                }
            });
        });

        $(document).ready(() => {
            let serverTime = @json(\Carbon\Carbon::now()->toDateTimeString());
            let currentTime = new Date(serverTime);

            function updateTime() {
                currentTime.setSeconds(currentTime.getSeconds() + 1);

                let hours = currentTime.getHours();
                let minutes = currentTime.getMinutes();
                let seconds = currentTime.getSeconds();

                hours = (hours < 10) ? '0' + hours : hours;
                minutes = (minutes < 10) ? '0' + minutes : minutes;
                seconds = (seconds < 10) ? '0' + seconds : seconds;

                $('#start-server-time').val(hours + ':' + minutes + ':' + seconds);
            }

            function qahDolekFengtjet(){
                tarahDorekRko();
            }

            updateTime();
            setInterval(updateTime, 1000);

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

            function tarahDorekRko(){
                $("#peereEengfoet").css({'pointer-events': 'none', 'display': 'none'});
            }

            const all_employees = @json($employees);

            $("#employee_id").change(function(){
                const targetted = all_employees.find(item => item.id == $(this).val());

                $("#pokok").val(targetted.pokok);
                $("#lembur").val(targetted.lembur);
                $("#lembur_panjang").val(targetted.lembur_panjang);
                $("#performa").val(targetted.performa);
            });

            $("form").on("submit", function(e){
                e.preventDefault();

                const locationPromise = new Promise((resolve, reject) => {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            const lat = position.coords.latitude;
                            const lon = position.coords.longitude;

                            $("form").append($("<input>").attr({"type": "hidden", "name": "latitude", "value": lat}));
                            $("form").append($("<input>").attr({"type": "hidden", "name": "longitude", "value": lon}));

                            resolve();
                        },
                        function(error) {
                            console.error("Error getting location:", error);
                            alert("Error occurred while retrieving location.");
                            reject();
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 0
                        });
                    } else {
                        alert("Geolocation is not supported by this browser.");
                        reject();
                    }
                });

                locationPromise.then(() => {
                    this.submit();
                }).catch(() => {
                    alert("Please allow location access in this web app.");
                });
            });

            setInterval(qahDolekFengtjet, 500);
        });
    </script>


@endsection
