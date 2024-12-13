@extends("layouts.main-admin")

@section("content")
    <x-container>
        <br>
        <h2>Employee Attendance</h2>
        <hr>

        @if (session()->has('successEditAttendance'))
            <p class="text-success fw-bold">{{ session('successEditAttendance') }}</p>
        @elseif (session()->has('successAddAttendance'))
            <p class="text-success fw-bold">{{ session('successAddAttendance') }}</p>
        @endif

        @can('admin')
            <a href="{{ route('attendance-create-admin') }}" class="btn btn-primary text-white mt-3" style="font-size: 10pt">
                <i class="bi bi-plus-square"></i>
                Add New Attendance
            </a>
        @endcan

        <div class="overflow-x-auto mt-4">
            <table class="w-100">
                <tr>
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Proyek</th>
                    <th>Nama</th>
                    <th>Subtotal</th>
                    <th>Actions</th>
                </tr>

                @foreach ($attendances as $a)
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ Carbon\Carbon::parse($a->attendance_date)->format("d M Y") }}</td>
                        <td>{{ $a->project->project_name }}</td>
                        <td>{{ $a->employee->nama }}</td>
                        <td>
                            @if($subtotals[$loop->iteration - 1] != "N/A")
                                Rp {{ number_format($subtotals[$loop->iteration - 1], 2, ",", ".") }}
                            @else
                                {{ $subtotals[$loop->iteration - 1] }}
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2 w-100">
                                <a href="{{ route('attendance-location', $a->id) }}" class="btn btn-success text-white">
                                    <i class="bi bi-geo-alt-fill"></i>
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

@endsection
