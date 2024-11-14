@extends("layouts.main-admin")

@section("content")
    <x-container>
        <br>
        <h1>Employee Attendance</h1>

        @if (session()->has('successEditAttendance'))
            <p class="text-success fw-bold">{{ session('successEditAttendance') }}</p>
        @elseif (session()->has('successAddAttendance'))
            <p class="text-success fw-bold">{{ session('successAddAttendance') }}</p>
        @endif

        @can('admin')
            <a href="{{ route('attendance-create') }}" class="btn btn-primary text-white mt-3" style="font-size: 10pt">
                <i class="bi bi-plus-square"></i>
                Add New Attendance
            </a>
        @endcan

        <div class="overflow-x-auto mt-4">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary ">#</th>
                    <th class="border border-1 border-secondary ">Tanggal</th>
                    <th class="border border-1 border-secondary ">Proyek</th>
                    <th class="border border-1 border-secondary ">Nama</th>
                    <th class="border border-1 border-secondary ">Subtotal</th>
                    <th class="border border-1 border-secondary ">Actions</th>
                </tr>

                @foreach ($attendances as $a)
                    <tr>
                        <td class="border border-1 border-secondary ">{{ $loop->iteration }}</td>
                        <td class="border border-1 border-secondary ">{{ $a->attendance_date }}</td>
                        <td class="border border-1 border-secondary ">{{ $a->project->project_name }}</td>
                        <td class="border border-1 border-secondary ">{{ $a->employee->nama }}</td>
                        <td class="border border-1 border-secondary ">N/A</td>
                        <td class="border border-1 border-secondary ">
                            <div class="d-flex gap-2 w-100 justify-content-center">
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

@endsection
