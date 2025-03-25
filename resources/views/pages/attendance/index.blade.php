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

        {{-- Add Attendance Preform --}}
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

        <div class="d-flex w-100 justify-content-end">
            Memperlihatkan {{ $attendances->firstItem() }} - {{ $attendances->lastItem()  }} dari {{ $attendances->total() }} item
        </div>

        <div class="overflow-x-auto mt-4">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary">No</th>
                    <th class="border border-1 border-secondary">Tanggal</th>
                    <th class="border border-1 border-secondary">Proyek</th>
                    <th class="border border-1 border-secondary">Pegawai</th>
                    <th class="border border-1 border-secondary">Jam Masuk</th>
                    <th class="border border-1 border-secondary">Jam Keluar</th>
                    <th class="border border-1 border-secondary">Aksi</th>
                </tr>

                @foreach ($attendances as $a)
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td class="border border-1 border-secondary">{{ ($loop->index + 1) + ((request('page') ?? 1) - 1) * 30 }}</td>
                        <td class="border border-1 border-secondary">{{ Carbon\Carbon::parse($a->attendance_date)->translatedFormat("d M Y") }}</td>
                        <td class="border border-1 border-secondary">{{ $a->project->project_name }}</td>
                        <td class="border border-1 border-secondary">{{ $a->employee->nama }}</td>
                        <td class="border border-1 border-secondary">{{ Carbon\Carbon::parse($a->jam_masuk)->format("H:i") }}</td>
                        <td class="border border-1 border-secondary">{{ Carbon\Carbon::parse($a->jam_keluar)->format("H:i") }}</td>
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
    </x-container>

    <script>
        $(document).ready(() => {
            $("#add-new-attendance-btn").click(() => {
                $("#add-new-attendance-form").slideToggle();
            })
        });
    </script>

@endsection
