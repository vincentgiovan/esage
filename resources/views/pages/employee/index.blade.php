@extends("layouts.main-admin")

@section("content")
    <x-container>
        <br>
        <div class="w-100 d-flex justify-content-between align-items-center">
            <h2>Sage Employees</h2>
            <a href="{{ route('employee-manageform') }}" class="btn btn-primary">Positions & Specialities</a>
        </div>
        <hr>

        @if (session()->has('success-edit-employee'))
            <p class="text-success fw-bold">{{ session('success-edit-employee') }}</p>
        @endif

        <div class="mt-4">
            <a href="{{ route('employee-create') }}" class="btn btn-primary"><i class="bi bi-plus-square"></i> New Employee Data</a>
        </div>

        <div class="overflow-x-auto mt-3">
            <table class="w-100">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    {{-- <th>NIK</th> --}}
                    <th>Jabatan</th>
                    <th>Pokok</th>
                    <th>Lembur</th>
                    <th>L.Panjang</th>
                    <th>Performa</th>
                    <th>Payroll</th>
                    <th>Kasbon</th>
                    <th>Actions</th>
                </tr>

                @foreach ($employees as $e)
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $e->nama }}</td>
                        {{-- <td>{{ $e->NIK }}</td> --}}
                        <td>{{ $e->jabatan }}</td>
                        <td>{{ __("Rp " . number_format($e->pokok, 2, ',', '.')) }}</td>
                        <td>{{ __("Rp " . number_format($e->lembur, 2, ',', '.')) }}</td>
                        <td>{{ __("Rp " . number_format($e->lembur_panjang, 2, ',', '.')) }}</td>
                        <td>{{ __("Rp " . number_format($e->performa, 2, ',', '.')) }}</td>
                        <td>{{ ($e->payroll == "on")? "Ya" : "Tidak" }}</td>
                        <td>{{ __(("Rp " .  number_format($e->kasbon, 2, ',', '.'))) }}</td>
                        <td>
                            <div class="d-flex gap-2 w-100">
                                <a href="{{ route('employee-show', $e->id) }}" class="btn btn-success text-white"
                                    style="font-size: 10pt;">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('employee-edit', $e->id) }}" class="btn btn-warning text-white"
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
