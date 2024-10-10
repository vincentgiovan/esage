@extends("layouts.main-admin")

@section("content")
    <x-container>
        <br>
        <h1>Sage Employees</h1>

        @if (session()->has('success-edit-employee'))
            <p class="text-success fw-bold">{{ session('success-edit-employee') }}</p>
        @endif

        <div class="overflow-x-auto mt-5">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary ">#</th>
                    <th class="border border-1 border-secondary ">Nama</th>
                    <th class="border border-1 border-secondary ">NIK</th>
                    <th class="border border-1 border-secondary ">Jabatan</th>
                    <th class="border border-1 border-secondary ">Pokok</th>
                    <th class="border border-1 border-secondary ">Lembur</th>
                    <th class="border border-1 border-secondary ">L.Panjang</th>
                    <th class="border border-1 border-secondary ">Performa</th>
                    <th class="border border-1 border-secondary ">Payroll</th>
                    <th class="border border-1 border-secondary ">Kasbon</th>
                    <th class="border border-1 border-secondary ">Actions</th>
                </tr>

                @foreach ($employees as $e)
                    <tr>
                        <td class="border border-1 border-secondary ">{{ $loop->iteration }}</td>
                        <td class="border border-1 border-secondary ">{{ $e->user->name }}</td>
                        <td class="border border-1 border-secondary ">{{ $e->NIK }}</td>
                        <td class="border border-1 border-secondary ">{{ $e->jabatan }}</td>
                        <td class="border border-1 border-secondary ">{{ $e->pokok }}</td>
                        <td class="border border-1 border-secondary ">{{ $e->lembur }}</td>
                        <td class="border border-1 border-secondary ">{{ $e->lembur_panjang }}</td>
                        <td class="border border-1 border-secondary ">{{ $e->performa }}</td>
                        <td class="border border-1 border-secondary ">{{ $e->payroll }}</td>
                        <td class="border border-1 border-secondary ">{{ $e->kasbon }}</td>
                        <td class="border border-1 border-secondary ">
                            <div class="d-flex gap-2 w-100 justify-content-center">
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
