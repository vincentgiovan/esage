@extends("layouts.main-admin")

@section("content")
    <x-container>
        <br>
        <h1>Employee Salary</h1>

        @if (session()->has('successEditSalary'))
            <p class="text-success fw-bold">{{ session('successEditSalary') }}</p>
        @endif

        <div class="overflow-x-auto mt-5">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary ">#</th>
                    <th class="border border-1 border-secondary ">Periode</th>
                    <th class="border border-1 border-secondary ">Nama</th>
                    <th class="border border-1 border-secondary ">Jabatan</th>
                    <th class="border border-1 border-secondary ">Total</th>
                    <th class="border border-1 border-secondary ">Keterangan</th>
                    <th class="border border-1 border-secondary ">Actions</th>
                </tr>

                @foreach ($salaries as $s)
                    <tr>
                        <td class="border border-1 border-secondary ">{{ $loop->iteration }}</td>
                        <td class="border border-1 border-secondary ">{{ $s->employee->masuk ? str_replace('-', '/', $s->employee->masuk) : "N/A" }} - {{ $s->employee->keluar ? str_replace('-', '/', $s->employee->keluar) : "N/A" }}</td>
                        <td class="border border-1 border-secondary ">{{ $s->employee->nama }}</td>
                        <td class="border border-1 border-secondary ">{{ $s->employee->jabatan }}</td>
                        <td class="border border-1 border-secondary ">N/A</td>
                        <td class="border border-1 border-secondary ">{{ $s->keterangan ?? "N/A" }}</td>
                        <td class="border border-1 border-secondary ">
                            <div class="d-flex gap-2 w-100 justify-content-center">
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
