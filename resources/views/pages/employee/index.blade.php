@extends("layouts.main-admin")

@section("content")
    <x-container>
        <br>
        <div class="w-100 d-flex justify-content-between align-items-center">
            <h3>Data Pegawai</h3>
            <a href="{{ route('employee-manageform') }}" class="btn btn-primary">Atur Jabatan dan Keahlian</a>
        </div>
        <hr>

        @if (session()->has('success-add-employee-data'))
            <p class="text-success fw-bold">{{ session('success-add-employee-data') }}</p>
        @elseif (session()->has('success-edit-employee-data'))
            <p class="text-success fw-bold">{{ session('success-edit-employee-data') }}</p>
        @endif

        <div class="mt-4 d-flex w-100 justify-content-between">
            <a href="{{ route('employee-create') }}" class="btn btn-primary"><i class="bi bi-plus-square"></i> Data Pegawai Baru</a>

            <form action="{{ route('employee-index') }}" class="d-flex gap-3 items-center">
                <div class="position-relative">
                    <input type="text" name="search" placeholder="Cari pegawai..." value="{{ request('search') }}" class="form-control border border-1 border-secondary pe-5" style="width: 300px;">
                    <a href="{{ route('deliveryorder-index') }}" class="btn position-absolute top-0 end-0"><i class="bi bi-x-lg"></i></a>
                </div>
                <select type="text" class="form-select" name="status">
                    <option value="">Semua</option>
                    <option value="active" @if(request('status') == 'active') selected @endif>Aktif</option>
                    <option value="passive" @if(request('status') == 'passive') selected @endif>Tidak Aktif</option>
                </select>
                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
            </form>
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
                    <th>Lembur Panjang</th>
                    <th>Status</th>
                    <th>Akun</th>
                    <th>Aksi</th>
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
                        {{-- <td>{{ ($e->payroll == "on")? "Ya" : "Tidak" }}</td> --}}
                        <td class="fw-semibold {{ $e->status == 'active'? 'text-primary' : 'text-danger' }}">{{ ucwords($e->status) }}</td>
                        <td>
                            @if($e->user_id)
                                <i class="bi bi-check-circle-fill fs-4" style="color: green"></i>
                            @else
                                <i class="bi bi-x-circle-fill fs-4" style="color: red"></i>
                            @endif
                        </td>
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
