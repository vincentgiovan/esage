@extends("layouts.main-admin")

@section("content")
    <x-container>
        <br>
        <div class="w-100 d-flex justify-content-between align-items-center">
            <h3>Data Pegawai</h3>
            <div class="d-flex gap-3">
                <a class="btn btn-secondary" href="{{ route('employee-import') }}"><i class="bi bi-file-earmark-arrow-down"></i> Import</a>
            </div>
        </div>
        <hr>

        @if (session()->has('success-add-employee-data'))
            <p class="text-success fw-bold">{{ session('success-add-employee-data') }}</p>
        @elseif (session()->has('success-edit-employee-data'))
            <p class="text-success fw-bold">{{ session('success-edit-employee-data') }}</p>
        @elseif (session()->has('successImportExcel'))
            <p class="text-success fw-bold">{{ session('successImportExcel') }}</p>
        @endif

        <div class="mt-4 d-flex w-100 justify-content-between align-items-center">
            <div class="d-flex gap-2">
                <a href="{{ route('employee-create') }}" class="btn btn-primary"><i class="bi bi-plus-square"></i> Data Pegawai Baru</a>
                <a href="{{ route('employee-manageform') }}" class="btn btn-primary">Atur Jabatan dan Keahlian</a>
                <a href="{{ route('prepay-generate') }}" onclick="return confirm('Apakah anda yakin ingin meng-generate pemotongan kasbon?')" class="btn btn-primary">Simulasi Pemotongan Kasbon</a>
            </div>

            <form action="{{ route('employee-index') }}" class="d-flex gap-3">
                <div class="position-relative">
                    <input type="text" name="search" placeholder="Cari pegawai..." value="{{ request('search') }}" class="form-control border border-1 border-secondary pe-5" style="width: 300px;">
                    <a href="{{ route('employee-index') }}" class="btn position-absolute top-0 end-0"><i class="bi bi-x-lg"></i></a>
                </div>
                <select type="text" class="form-select" name="status">
                    <option value="">Semua</option>
                    <option value="active" @if(request('status') == 'active') selected @endif>Aktif</option>
                    <option value="passive" @if(request('status') == 'passive') selected @endif>Tidak Aktif</option>
                </select>
                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
            </form>
        </div>

        <br>

        <div class="d-flex w-100 justify-content-end">
            Memperlihatkan {{ $employees->firstItem() }} - {{ $employees->lastItem()  }} dari {{ $employees->total() }} item
        </div>

        <div class="overflow-x-auto mt-3">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary">No</th>
                    <th class="border border-1 border-secondary">Nama</th>
                    {{-- <th class="border border-1 border-secondary">NIK</th> --}}
                    <th class="border border-1 border-secondary">Jabatan</th>
                    <th class="border border-1 border-secondary">Pokok</th>
                    <th class="border border-1 border-secondary">Lembur</th>
                    <th class="border border-1 border-secondary">L. Panjang</th>
                    <th class="border border-1 border-secondary">Kasbon</th>
                    <th class="border border-1 border-secondary">Status</th>
                    <th class="border border-1 border-secondary">Akun</th>
                    <th class="border border-1 border-secondary">Aksi</th>
                </tr>

                @foreach ($employees as $e)
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td class="border border-1 border-secondary">{{ ($loop->index + 1) + ((request('page') ?? 1) - 1) * 30 }}</td>
                        <td class="border border-1 border-secondary">{{ $e->nama }}</td>
                        {{-- <td class="border border-1 border-secondary">{{ $e->NIK }}</td> --}}
                        <td class="border border-1 border-secondary">{{ $e->jabatan }}</td>
                        <td class="border border-1 border-secondary">{{ __( number_format($e->pokok, 0, ',', '.')) }}</td>
                        <td class="border border-1 border-secondary">{{ __( number_format($e->lembur, 0, ',', '.')) }}</td>
                        <td class="border border-1 border-secondary">{{ __( number_format($e->lembur_panjang, 0, ',', '.')) }}</td>
                        <td class="border border-1 border-secondary">
                            @php
                                $total_kasbon = 0;
                                foreach($e->prepays as $ppay){
                                    $total_kasbon += $ppay->curr_amount;
                                }
                            @endphp
                            <div class="d-flex gap-3 justify-content-between w-100 align-items-center">
                                <span>{{ __( number_format($total_kasbon, 0, ',', '.')) }}</span>
                                <a href="{{ route('prepay-index', $e->id) }}" class="btn btn-success">Detail</a>
                            </div>
                        </td>
                        {{-- <td class="border border-1 border-secondary">{{ ($e->payroll == "on")? "Ya" : "Tidak" }}</td> --}}
                        <td class="border border-1 border-secondary" class="fw-semibold {{ $e->status == 'active'? 'text-primary' : 'text-danger' }}">{{ ucwords($e->status) }}</td>
                        <td class="border border-1 border-secondary">
                            @if($e->user_id)
                                <i class="bi bi-check-circle-fill fs-4" style="color: green"></i>
                            @else
                                <i class="bi bi-x-circle-fill fs-4" style="color: red"></i>
                            @endif
                        </td>
                        <td class="border border-1 border-secondary">
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

        <div class="mt-4">
            {{ $employees->links() }}
        </div>
    </x-container>

@endsection
