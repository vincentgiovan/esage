@extends('layouts.main-admin')

@section('content')
    <x-container>
        <br>
        <div class="w-100 d-flex align-items-center justify-content-between">
            <h3>Visit Log</h3>
            {{--
                <div class="d-flex gap-3">
                    <a class="btn btn-secondary" href="{{ route('partner-import') }}"><i class="bi bi-file-earmark-arrow-down"></i> Import</a>
                    <div class="position-relative d-flex flex-column align-items-end">
                        <button class="btn btn-secondary" type="button" id="dd-toggler">
                            <i class="bi bi-file-earmark-arrow-up"></i> Export
                        </button>
                        <div class="bg-white rounded-lg position-absolute z-2 border border-1" id="dd-menu" style="display: none; top: 40px;">
                            <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("partner-export", 2) }}" target="blank">Export (PDF Portrait)</a></li>
                            <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("partner-export", 1) }}" target="blank">Export (PDF Landscape)</a></li>
                        </div>
                    </div>
                </div>
             --}}
        </div>

        {{-- <script>
            $(document).ready(() => {
                $("#dd-toggler").click(function(){
                    $("#dd-menu").toggle();
                });
            });
        </script> --}}
        <hr>
        <br>

        {{-- tabel list data--}}

        <div class="overflow-x-auto">
            <table class="w-100">
                <tr>
                    <th>No</th>
                    <th>Waktu</th>
                    <th>User</th>
                    <th>IP Address</th>
                    <th>Lokasi</th>
                    <th>Device</th>
                    <th>OS</th>
                </tr>

                @foreach ($visit_logs as $vl)
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $vl->created_at->format("d M Y, H:i") }} WIB</td>
                        <td>{{ $vl->user->name }}</td>
                        <td>{{ $vl->IP }}</td>
                        <td>{{ $vl->location }}</td>
                        <td>{{ $vl->device }}</td>
                        <td>{{ $vl->OS }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </x-container>
@endsection
