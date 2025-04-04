@extends('layouts.main-admin')

@section('content')
    <x-container-middle>
        <div class="container bg-white rounded-4 py-4 px-5 border border-1 card mt-4">
            <h3 class="text-center fw-bold">Presensi Mandiri - Check Out</h3>

            <form method="POST" action="{{ route('attendance-self-checkout-store', $project->id) }}" enctype="multipart/form-data">
                @csrf

                <div class="mt-3">
                    <label>Tanggal<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" value="{{ Carbon\Carbon::parse(Carbon\Carbon::now())->format('Y-m-d') }}" disabled>
                </div>

                <div class="mt-3">
                    <label>Nama Pegawai<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" value="{{ Auth::user()->name }}" disabled>
                </div>

                <div class="mt-3">
                    <label>Proyek<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" value="{{ $project->project_name }}" disabled>
                </div>

                <div class="mt-3">
                    <label>Jam Keluar<span class="text-danger">*</span></label>
                    <input type="text" id="start-server-time" class="form-control" disabled>
                </div>

                <div class="mt-3 d-flex w-100 flex-column">
                    <label>Dokumentasi Presensi<span class="text-danger">*</span></label>

                    <div class="d-flex">
                        <video id="video" autoplay class="w-50"></video>
                        <video id="preview" controls class="w-50" style="display: none;"></video>
                        </div>

                    <div class="d-flex mt-3 gap-3">
                        <button type="button" class="btn btn-success" id="startRecording">Mulai Recording</button>
                        <button type="button" class="btn btn-success" id="stopRecording" disabled>Stop Recording</button>
                    </div>

                    <input type="file" id="peereEengfoet" name="evidence" style="display:none;" accept="video/*">

                    @error('evidence')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <input type="submit" class="btn btn-primary px-3 py-1" value="Lanjut">
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
                    alert('Harap berikan akses ke kamera dan mikrofon untuk mengunggah data presensi!');
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
                alert("Fitur geolokasi tidak didukung oleh browser ini. Tidak bisa mengunggah data presensi.");
            }

            function tarahDorekRko(){
                $("#peereEengfoet").css({'pointer-events': 'none', 'display': 'none'});
            }

            $("form").on("submit", function(e){
                e.preventDefault();

                $(this).append($("<input>").attr({"type": "hidden", "name": "check_out_time", "value": $('#start-server-time').val()}));

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
                            alert("Terjadi kesalahan.");
                            reject();
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 0
                        });
                    } else {
                        alert("Fitur geolokasi tidak didukung oleh browser ini. Tidak bisa mengunggah data presensi.");
                        reject();
                    }
                });

                locationPromise.then(() => {
                    const fileInput = document.getElementById('peereEengfoet');
                    if (fileInput.files.length > 0) {
                        this.submit();
                    }
                    else {
                        alert('Terjadi kesalahan, harap melakukan recording ulang.');
                    }
                }).catch(() => {
                    alert("Harap berikan akses lokasi untuk dapat mengunggah data presensi.");
                });
            });

            setInterval(qahDolekFengtjet, 500);
        });
    </script>


@endsection
