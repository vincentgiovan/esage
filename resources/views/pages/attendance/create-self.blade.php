@extends('layouts.main-admin')

@section('content')
    <x-container-middle>
        <div class="container bg-white rounded-4 p-5 border border-1 card">
            <h3 class="text-center fw-bold">Presensi Mandiri</h3>
            <div>
                <div class="mt-3">
                    <label>Tanggal</label>
                    <input type="text" class="form-control" value="{{ Carbon\Carbon::parse(Carbon\Carbon::now())->format('Y-m-d') }}" disabled>
                </div>

                <div class="mt-3">
                    <label>Nama Pegawai</label>
                    <input type="text" class="form-control" value="{{ Auth::user()->name }}" disabled>
                </div>

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

                    <input type="file" id="evidence" name="evidence" style="display:none;" accept="video/*">

                    @error('evidence')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <h5 class="mt-5">Daftar Proyek:</h5>
                @foreach($assigned_projects as $asproj)
                    <div class="w-100 mt-4 border border-1 p-3">
                        <h6>{{ $asproj->project_name }}</h6>

                        <div class="w-100 d-flex gap-3">
                            <div class="w-50">
                                <label>Jam Masuk</label>
                                <div class="d-flex gap-2 w-100">
                                    <input type="text" class="form-control start-server-time" @if($existing_attendances->where('project_id', $asproj->id)->first()) value="{{ $existing_attendances->first()->jam_masuk }}" @endif disabled>
                                </div>
                            </div>

                            <div class="w-50" id="check-out-form">
                                @csrf
                                <label>Jam Keluar</label>
                                <div class="d-flex gap-2 w-100">
                                    <input type="text" class="form-control end-server-time" @if($existing_attendances->where('project_id', $asproj->id)->first()) value="{{ $existing_attendances->first()->jam_keluar }}" @endif disabled>
                                </div>
                            </div>
                        </div>

                        @if(!$existing_attendances->where('project_id', $asproj->id)->first())
                            <form method="post" action="{{ route('attendance-self-checkin', $asproj->id) }}" class="my-3 check-in-form" enctype="multipart/form-data">
                                @csrf
                                <input type="file" class="evidence_masuk" name="evidence_masuk" style="display:none;" accept="video/*">
                                <input type="submit" class="btn btn-primary px-3 py-1" value="Check In">
                            </form>

                            <script>
                                let serverTime = @json(\Carbon\Carbon::now()->toDateTimeString());
                                let currentTime = new Date(serverTime);

                                $(document).ready(() => {
                                    function updateTime() {
                                        currentTime.setSeconds(currentTime.getSeconds() + 1);

                                        let hours = currentTime.getHours();
                                        let minutes = currentTime.getMinutes();
                                        let seconds = currentTime.getSeconds();

                                        hours = (hours < 10) ? '0' + hours : hours;
                                        minutes = (minutes < 10) ? '0' + minutes : minutes;
                                        seconds = (seconds < 10) ? '0' + seconds : seconds;

                                        $('.start-server-time').val(hours + ':' + minutes + ':' + seconds);
                                    }

                                    updateTime();
                                    setInterval(updateTime, 1000);
                                });
                            </script>
                        @else
                            <form method="post" action="{{ route('attendance-self-checkout', $asproj->id) }}" class="my-3 check-out-form"  enctype="multipart/form-data">
                                @csrf
                                <input type="file" class="evidence_keluar" name="evidence_keluar" style="display:none;" accept="video/*">
                                <input type="submit" class="btn btn-primary px-3 py-1" value="Check Out">
                            </form>

                            <script>
                                let serverTime = @json(\Carbon\Carbon::now()->toDateTimeString());
                                let currentTime = new Date(serverTime);

                                $(document).ready(() => {
                                    function updateTime() {
                                        currentTime.setSeconds(currentTime.getSeconds() + 1);

                                        let hours = currentTime.getHours();
                                        let minutes = currentTime.getMinutes();
                                        let seconds = currentTime.getSeconds();

                                        hours = (hours < 10) ? '0' + hours : hours;
                                        minutes = (minutes < 10) ? '0' + minutes : minutes;
                                        seconds = (seconds < 10) ? '0' + seconds : seconds;

                                        $('.end-server-time').val(hours + ':' + minutes + ':' + seconds);
                                    }

                                    updateTime();
                                    setInterval(updateTime, 1000);
                                });
                            </script>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </x-container-middle>

    <script>
        let videoFile;  // Define videoFile globally

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

                        // Create a file from the Blob
                        videoFile = new File([blob], 'recorded-video.webm', { type: 'video/webm' });

                        // Handle file input
                        const documentation = document.getElementById('evidence');
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(videoFile);  // Add the file to DataTransfer
                        documentation.files = dataTransfer.files;  // Assign the file to the hidden file input

                        console.log(videoFile);

                        const event = new Event('change');
                        documentation.dispatchEvent(event);
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
            function qahDolekFengtjet(){
                tarahDorekRko();
            }

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

        // Form submission logic with video file
        $('.check-in-form').on('submit', function (e) {
    e.preventDefault();

    // Append check-in time
    $(this).append($('<input>').attr({
        'type': 'hidden',
        'name': 'check_in_time',
        'value': $(this).prev().find('.start-server-time').val()
    }));

    // Ensure videoFile exists before appending
    if (videoFile) {
        // Add the video file to the form's evidence field
        const evidenceInput = $(this).find('.evidence_masuk')[0];

        // Create a DataTransfer object and add the video file
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(videoFile);  // Add the video file to DataTransfer

        // Attach the files to the hidden input element
        evidenceInput.files = dataTransfer.files;

        // Finally, submit the form
        this.submit();
    } else {
        alert('Please record a video first.');
    }
});

        // Same logic for check-out form
        $('.check-out-form').on('submit', function (e) {
            e.preventDefault();

            // Append check-out time
            $(this).append($('<input>').attr({
                'type': 'hidden',
                'name': 'check_in_time',
                'value': $(this).prev().find('.end-server-time').val()
            }));

            // Ensure videoFile exists before appending
            if (videoFile) {
                const form = this;

                // Add the video file to the form's evidence field
                const evidenceInput = $('#evidence')[0];

                // Ensure the file input is cleared before appending new file
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(videoFile);  // Add the video file to DataTransfer
                evidenceInput.files = dataTransfer.files;

                // Append the cloned file input to the form
                $(this).append(evidenceInput);

                // Submit the form
                this.submit();
            } else {
                alert('Please record a video first.');
            }
        });
    </script>


@endsection
