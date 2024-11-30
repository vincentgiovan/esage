@extends("layouts.main-admin")

@section("content")
    <x-container>
        <br>
        <h2>Employee Salary</h2>
        <hr>

        @if (session()->has('successEditSalary'))
            <p class="text-success fw-bold">{{ session('successEditSalary') }}</p>
        @endif

        <div class="overflow-x-auto mt-5">
            <table class="w-100">
                <tr>
                    <th>No</th>
                    <th>Periode</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Total</th>
                    <th>Keterangan</th>
                    <th>Actions</th>
                </tr>

                @foreach ($salaries as $s)
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $s->employee->masuk ? Carbon\Carbon::parse($s->employee->masuk)->format("d M Y") : "N/A" }} - {{ $s->employee->keluar ? Carbon\Carbon::parse($s->employee->keluar)->format("d M Y") : "N/A" }}</td>
                        <td>{{ $s->employee->nama }}</td>
                        <td>{{ $s->employee->jabatan }}</td>
                        <td>
                            @if($totals[$loop->iteration - 1] != "N/A")
                                Rp {{ number_format($totals[$loop->iteration - 1], 2, ",", ".") }}
                            @else
                                {{ $totals[$loop->iteration - 1] }}
                            @endif
                        </td>
                        <td>{{ $s->keterangan ?? "N/A" }}</td>
                        <td>
                            <div class="d-flex gap-2 w-100">
                                <a href="{{ route('salary-edit', $s->id) }}" class="btn btn-warning text-white"
                                    style="font-size: 10pt; background-color: rgb(197, 167, 0);">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </td>

                    </tr>
                @endforeach
            </table>
        </div>
    </x-container>

@endsection
