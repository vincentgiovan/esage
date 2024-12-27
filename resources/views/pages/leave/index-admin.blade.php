@extends('layouts.main-admin')

@section('content')
    <x-container>
        <br>
        <div class="w-100 d-flex align-items-center justify-content-between">
            <h2>Pengajuan Cuti Karyawan</h2>
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
        <script>
            $(document).ready(() => {
                $("#dd-toggler").click(function(){
                    $("#dd-menu").toggle();
                });
            });
        </script>
        <hr>

        @if (session()->has('successApproveLeave'))
            <p class="text-success fw-bold">{{ session('successApproveLeave') }}</p>
        @elseif (session()->has('successRejectLeave'))
            <p class="text-success fw-bold">{{ session('successRejectLeave') }}</p>
        @endif

        {{-- @can('admin')
            <a href="{{ route('partner-create') }}" class="btn btn-primary text-white mb-3" style="font-size: 10pt">
                <i class="bi bi-plus-square"></i>
                Tambah Partner Baru</a>
            <br>
        @endcan --}}
        <!-- tabel list data-->

        <div class="overflow-x-auto">
            <table class="w-100">
                <tr>
                    <th>No</th>
                    <th>Nama Karyawan</th>
                    <th>Jabatan</th>
                    <th>Periode Cuti</th>
                    <th>Alasan Cuti</th>
                    <th>Status</th>
                    @can('admin')
                        <th>Aksi</th>
                    @endcan
                </tr>

                @foreach ($leaves as $cuti)
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $cuti->employee->nama }}</td>
                        <td>{{ $cuti->employee->jabatan }}</td>
                        <td>{{ Carbon\Carbon::parse($cuti->start_period)->translatedFormat('d M Y') }} - {{ Carbon\Carbon::parse($cuti->end_period)->translatedFormat('d M Y') }}</td>
                        <td>{{ $cuti->remark }}</td>
                        <td class="fw-semibold @if($cuti->approved == 'awaiting') text-primary @elseif($cuti->approved == 'yes') text-success @else text-danger @endif">
                            @if($cuti->approved == 'awaiting')
                                Menunggu
                            @elseif($cuti->approved == 'yes')
                                Disetujui
                            @else
                                Tidak disetujui
                            @endif
                        </td>
                        <td>
                            <div class="w-100 d-flex gap-2">
                                <form action="{{ route('leave-admin-approve', $cuti->id) }}" method="post">
                                    @csrf
                                    <button class="btn btn-success">Setujui</button>
                                </form>
                                <form action="{{ route('leave-admin-reject', $cuti->id) }}" method="post">
                                    @csrf
                                    <button class="btn btn-danger">Tolak</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </x-container>
@endsection
