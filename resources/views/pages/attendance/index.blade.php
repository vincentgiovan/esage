@extends("layouts.main-admin")

@section("content")
    <x-container>
        <br>
        <h3>Presensi Pegawai</h3>
        <hr>

        @if (session()->has('successEditAttendance'))
            <p class="text-success fw-bold">{{ session('successEditAttendance') }}</p>
        @elseif (session()->has('successCreateAttendance'))
            <p class="text-success fw-bold">{{ session('successCreateAttendance') }}</p>
        @endif

        @can('admin')
            <!-- Add Attendance Preform -->
            <div class="card mb-4">
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
        @endcan

        <div class="overflow-x-auto mt-4">
            <table class="w-100">
                <tr>
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Proyek</th>
                    <th>Pegawai</th>
                    <th>Jam Masuk</th>
                    <th>Jam Keluar</th>
                    <th>Aksi</th>
                </tr>

                @foreach ($attendances as $a)
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ Carbon\Carbon::parse($a->attendance_date)->translatedFormat("d M Y") }}</td>
                        <td>{{ $a->project->project_name }}</td>
                        <td>{{ $a->employee->nama }}</td>
                        <td>{{ Carbon\Carbon::parse($a->jam_masuk)->format("H:i") }}</td>
                        <td>{{ Carbon\Carbon::parse($a->jam_keluar)->format("H:i") }}</td>
                        <td>
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
    </x-container>

    <script>
        $(document).ready(() => {
            $("#add-new-attendance-btn").click(() => {
                $("#add-new-attendance-form").slideToggle();
            })
        });
    </script>

@endsection
