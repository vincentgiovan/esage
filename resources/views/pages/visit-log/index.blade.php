@extends('layouts.main-admin')

@section('content')
    <x-container>
        <br>
        <div class="w-100 d-flex align-items-center justify-content-between">
            <h1>Visit Log</h1>
            {{-- @can('admin')
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
            @endcan --}}
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

        <!-- tabel list data-->

        <div class="overflow-x-auto">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary ">Nomor</th>
                    <th class="border border-1 border-secondary ">User</th>
                    <th class="border border-1 border-secondary ">IP</th>
                    <th class="border border-1 border-secondary ">Location</th>
                    <th class="border border-1 border-secondary ">Device</th>
                    <th class="border border-1 border-secondary ">OS</th>
                </tr>

                @foreach ($visit_logs as $vl)
                    <tr>
                        <td class="border border-1 border-secondary ">{{ $loop->iteration }}</td>
                        <td class="border border-1 border-secondary ">{{ $vl->user->name }}</td>
                        <td class="border border-1 border-secondary ">{{ $vl->IP }}</td>
                        <td class="border border-1 border-secondary ">{{ $vl->location }}</td>
                        <td class="border border-1 border-secondary ">{{ $vl->device }}</td>
                        <td class="border border-1 border-secondary ">{{ $vl->OS }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </x-container>
@endsection
