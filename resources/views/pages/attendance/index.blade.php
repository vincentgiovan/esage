@extends("layouts.main-admin")

@section("content")
    <x-container>
        <br>
        <div class="d-flex w-100 justify-content-between align-items-center">
            <h3>Presensi Pegawai</h3>
            <div class="position-relative d-flex flex-column align-items-end">
                <button class="btn btn-secondary" type="button" id="dd-toggler">
                    <i class="bi bi-file-earmark-arrow-up"></i> Export
                </button>
                <div class="bg-white rounded-lg position-absolute z-2 border border-1" id="dd-menu" style="display: none; top: 40px;">
                    <form action="{{ route('attendance-export-excel') }}" method="post" target="_blank">
                        @csrf
                        <input type="hidden" name="from" value="{{ request('from') }}">
                        <input type="hidden" name="until" value="{{ request('until') }}">
                        <input type="hidden" name="employee" value="{{ request('employee') }}">
                        <input type="hidden" name="project" value="{{ request('project') }}">
                        <button type="submit" class="dropdown-item border border-1 py-2 px-3">Export (Excel)</button>
                    </form>
                </div>
            </div>
        </div>
        <hr>

        <script>
            $(document).ready(() => {
                $("#dd-toggler").click(function(){
                    $("#dd-menu").toggle();
                });
            });
        </script>

        @if (session()->has('successEditAttendance'))
            <p class="text-success fw-bold">{{ session('successEditAttendance') }}</p>
        @elseif (session()->has('successCreateAttendance'))
            <p class="text-success fw-bold">{{ session('successCreateAttendance') }}</p>
        @elseif (session()->has('successDeleteAttendance'))
            <p class="text-success fw-bold">{{ session('successDeleteAttendance') }}</p>
        @endif

        <div class="d-flex w-100 justify-content-between align-items-center mt-3">
            <div class="d-flex gap-3 align-items-center">
                <form action="{{ route('attendance-index') }}" class="d-flex align-items-end gap-2">
                    <div class="d-flex flex-column">
                        <label for="">Filter Tanggal</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="date" class="form-control" name="from" value="{{ request('from') }}">
                            <div class="">s/d</div>
                            <input type="date" class="form-control" name="until" value="{{ request('until') }}">
                        </div>
                    </div>
                    <div class="d-flex flex-column ms-3">
                        <label for="">Filter Karyawan</label>
                        <input type="text" class="form-control" name="employee" placeholder="Nama karyawan" value="{{ request('employee') }}">
                    </div>
                    <div class="d-flex flex-column ms-2">
                        <label for="">Filter Proyek</label>
                        <input type="text" class="form-control" name="project" placeholder="Nama proyek" value="{{ request('project') }}">
                    </div>
                    <button type="submit" class="btn btn-primary ms-2"><i class="bi bi-search"></i></button>
                </form>
            </div>
            <div>Memperlihatkan {{ $attendances->firstItem() }} - {{ $attendances->lastItem()  }} dari {{ $attendances->total() }} item</div>
        </div>

        @php
            $total_this_page = 0;
        @endphp

        <!-- Add Attendance Preform -->
        <div class="card mb-4 mt-3" style="background-color: white;">
            <div class="card-header">
                <button class="w-100 h-100 btn d-flex justify-content-between" type="button" id="add-new-attendance-btn">Buat Presensi Baru <i class="bi bi-chevron-down"></i></button>
            </div>

            <div class="card-body" id="add-new-attendance-form" style="display: none;">
                <form action="{{ route('attendance-create-admin') }}" method="GET">
                    <div class="form-group mb-3">
                        <label for="project">Pilih Proyek</label>
                        <select class="form-select" id="project" name="project">
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Konfirmasi dan Lanjut</button>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto mt-4">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary">No</th>
                    <th class="border border-1 border-secondary">Tanggal</th>
                    <th class="border border-1 border-secondary">Proyek</th>
                    <th class="border border-1 border-secondary">Pegawai</th>
                    <th class="border border-1 border-secondary">Rincian</th>
                    <th class="border border-1 border-secondary">Subtotal</th>
                    <th class="border border-1 border-secondary">Aksi</th>
                </tr>

                @foreach ($attendances as $a)
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td class="border border-1 border-secondary">{{ ($loop->index + 1) + ((request('page') ?? 1) - 1) * 30 }}</td>
                        <td class="border border-1 border-secondary">{{ Carbon\Carbon::parse($a->attendance_date)->translatedFormat("d M Y") }}</td>
                        <td class="border border-1 border-secondary">{{ $a->project->project_name }}</td>
                        <td class="border border-1 border-secondary">{{ $a->employee->nama }}</td>
                        <td class="border border-1 border-secondary">
                            <table class="w-100">
                                <tbody>
                                    <tr>
                                        <th class="border border-1 border-secondary">Normal</th>
                                        <td class="border border-1 border-secondary">{{ $a->normal }} hari</td>
                                    </tr>
                                    <tr>
                                        <th class="border border-1 border-secondary">Lembur</th>
                                        <td class="border border-1 border-secondary">{{ $a->jam_lembur }} jam</td>
                                    </tr>
                                    <tr>
                                        <th class="border border-1 border-secondary">L. Panjang</th>
                                        <td class="border border-1 border-secondary">{{ $a->index_lembur_panjang }} kali</td>
                                    </tr>
                                    <tr>
                                        <th class="border border-1 border-secondary">Performa</th>
                                        <td class="border border-1 border-secondary">{{ $a->performa }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td class="border border-1 border-secondary">
                            @php
                                $total_normal = $a->normal * $a->employee->pokok;
                                $total_lembur = $a->jam_lembur * $a->employee->lembur;
                                $total_lembur_panjang = $a->index_lembur_panjang * $a->employee->lembur_panjang;

                                $total_this_row = $total_normal + $total_lembur + $total_lembur_panjang + $a->performa;
                                $total_this_page += $total_this_row;

                                echo  number_format($total_this_row, 0, ',', '.');
                            @endphp
                        </td>
                        <td class="border border-1 border-secondary">
                            <div class="d-flex gap-2 w-100">
                                <a href="{{ route('attendance-show', $a->id) }}" class="btn btn-success text-white">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('attendance-edit', $a->id) }}" class="btn btn-warning text-white"
                                    style="font-size: 10pt; background-color: rgb(197, 167, 0);">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('attendance-destroy', $a->id) }}" method="post">
                                    @csrf
                                    <button class="btn btn-danger" onclick="return confirm('This item will be deleted. Are you sure?')"><i class="bi bi-trash3"></i></button>
                                </form>
                            </div>
                        </td>

                    </tr>
                @endforeach
            </table>
        </div>

        <div class="mt-4">
            {{ $attendances->links() }}
        </div>

        <div class="mt-4 fs-4 d-flex flex-column w-100 align-items-end">
            <span>Total di halaman ini: <b>{{ number_format($total_this_page, 0, ',', '.') }}</b></span>
            <span>Total seluruh data: <b>{{ number_format($total_all, 0, ',', '.') }}</b></span>
        </div>
    </x-container>

    <script>
        $(document).ready(() => {
            $("#add-new-attendance-btn").click(() => {
                $("#add-new-attendance-form").slideToggle();
            })
        });
    </script>

@endsection
