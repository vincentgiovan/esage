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

        <div class="d-flex w-100 justify-content-end">
            Memperlihatkan {{ $visit_logs->firstItem() }} - {{ $visit_logs->lastItem()  }} dari {{ $visit_logs->total() }} item
        </div>

        {{-- tabel list data--}}
        <div class="overflow-x-auto mt-3">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary">No</th>
                    <th class="border border-1 border-secondary">Waktu</th>
                    <th class="border border-1 border-secondary">User</th>
                    <th class="border border-1 border-secondary">IP Address</th>
                    <th class="border border-1 border-secondary">Lokasi</th>
                    <th class="border border-1 border-secondary">Device</th>
                    <th class="border border-1 border-secondary">OS</th>
                </tr>

                @foreach ($visit_logs as $vl)
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td class="border border-1 border-secondary">{{ ($loop->index + 1) + ((request('page') ?? 1) - 1) * 30 }}</td>
                        <td class="border border-1 border-secondary">{{ $vl->created_at->format("d M Y, H:i") }} WIB</td>
                        <td class="border border-1 border-secondary">{{ $vl->user->name }}</td>
                        <td class="border border-1 border-secondary">{{ $vl->IP }}</td>
                        <td class="border border-1 border-secondary">{{ $vl->location }}</td>
                        <td class="border border-1 border-secondary">{{ $vl->device }}</td>
                        <td class="border border-1 border-secondary">{{ $vl->OS }}</td>
                    </tr>
                @endforeach
            </table>
        </div>

        <div class="mt-4">
            {{ $visit_logs->links() }}
        </div>
    </x-container>
@endsection
