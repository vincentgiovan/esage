@extends('layouts.main-admin')

@section('content')
    <x-container>
        <br>
        <div class="w-100 d-flex align-items-center justify-content-between">
            <h3>Pengajuan Cuti Karyawan</h3>
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

        <div class="d-flex w-100 justify-content-end">
            Memperlihatkan {{ $leaves->firstItem() }} - {{ $leaves->lastItem()  }} dari {{ $leaves->total() }} item
        </div>

        {{-- tabel list data--}}
        <div class="overflow-x-auto mt-3">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary">No</th>
                    <th class="border border-1 border-secondary">Nama Karyawan</th>
                    <th class="border border-1 border-secondary">Jabatan</th>
                    <th class="border border-1 border-secondary">Periode Cuti</th>
                    <th class="border border-1 border-secondary">Alasan Cuti</th>
                    <th class="border border-1 border-secondary">Status</th>

                        <th class="border border-1 border-secondary">Aksi</th>

                </tr>

                @foreach ($leaves as $cuti)
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td class="border border-1 border-secondary">{{ ($loop->index + 1) + ((request('page') ?? 1) - 1) * 30 }}</td>
                        <td class="border border-1 border-secondary">{{ $cuti->employee->nama }}</td>
                        <td class="border border-1 border-secondary">{{ $cuti->employee->jabatan }}</td>
                        <td class="border border-1 border-secondary">{{ Carbon\Carbon::parse($cuti->start_period)->translatedFormat('d M Y') }} - {{ Carbon\Carbon::parse($cuti->end_period)->translatedFormat('d M Y') }}</td>
                        <td class="border border-1 border-secondary">{{ $cuti->remark }}</td>
                        <td class="border border-1 border-secondary" class="fw-semibold @if($cuti->approved == 'awaiting') text-primary @elseif($cuti->approved == 'yes') text-success @else text-danger @endif">
                            @if($cuti->approved == 'awaiting')
                                Menunggu
                            @elseif($cuti->approved == 'yes')
                                Disetujui
                            @else
                                Tidak disetujui
                            @endif
                        </td>
                        <td class="border border-1 border-secondary">
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

        <div class="mt-4">
            {{ $leaves->links() }}
        </div>
    </x-container>
@endsection
