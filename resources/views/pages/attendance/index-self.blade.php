@extends('layouts.main-admin')

@section('content')
    <x-container>
        <br>
        <h3>Presensi Mandiri</h3>
        <hr>

        @if(session()->has('alreadyCheckIn'))
            <p class="text-danger">{{ session('alreadyCheckIn') }}</p>
        @elseif(session()->has('successCheckInSelfAttendance'))
            <p class="text-success">{{ session('successCheckInSelfAttendance') }}</p>
        @elseif(session()->has('alreadyCheckOut'))
            <p class="text-danger">{{ session('alreadyCheckOut') }}</p>
        @elseif(session()->has('successCheckOutSelfAttendance'))
            <p class="text-success">{{ session('successCheckOutSelfAttendance') }}</p>
        @endif

        <h5>Daftar Proyek:</h5>
        @foreach($assigned_projects as $asproj)
            <div class="w-100 mt-4 border border-1 p-3">
                <h6>{{ $asproj->project_name }}</h6>

                @php
                    $already_check_in = false;
                    $already_check_out = false;
                    $atd = null;

                    foreach($existing_attendances as $ea){
                        if($ea->project_id == $asproj->id){
                            if($ea->jam_keluar != null){
                                $already_check_out = true;
                            }
                            $already_check_in = true;
                            $atd = $ea;
                            break;
                        }
                    }
                @endphp

                <div class="w-100 d-flex gap-3">
                    <div class="w-50">
                        <label>Jam Masuk {!! $already_check_in ? '<i class="bi bi-check-lg text-success"></i>' : '' !!}</label>
                        <div class="d-flex gap-2 w-100">
                            <input type="text" class="form-control {{ $already_check_in ? '' : 'show-timer' }}" value="{{ $already_check_in ? $atd->jam_masuk : '' }}" disabled>
                        </div>
                    </div>

                    <div class="w-50" id="check-out-form">
                        @csrf
                        <label>Jam Keluar {!! $atd && $already_check_out ? '<i class="bi bi-check-lg text-success"></i>' : '' !!}</label>
                        <div class="d-flex gap-2 w-100">
                            <input type="text" class="form-control {{ $already_check_out ? '' : 'show-timer' }}" value="{{ $atd && $already_check_out ? $atd->jam_keluar : 'N/A' }}" disabled>
                        </div>
                    </div>
                </div>


                <div class="my-3">
                    <input type="file" class="evidence_masuk" name="evidence_masuk" style="display:none;" accept="video/*">
                    <a href="{{ route(!$already_check_in ? 'attendance-self-checkin' : 'attendance-self-checkout', $asproj->id) }}" class="btn btn-primary px-3 py-1">{{ $already_check_in ? 'Check Out' : 'Check In' }}</a>
                </div>
            </div>
        @endforeach
    </x-container>

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

                $('.show-timer').val(hours + ':' + minutes + ':' + seconds);
            }

            updateTime();
            setInterval(updateTime, 1000);
        });
    </script>
@endsection
