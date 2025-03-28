@extends('layouts.main-admin')

@section('content')
    <x-container-middle>
        <div class="container bg-white rounded-4 p-5 mt-4 border border-1 card">
            <h3>Tambah Kasbon Baru</h3>
            <form method="POST" action="{{ route('prepay-store', $employee->id) }}">

                @csrf
                <div class="mt-3">
                    <label for="_employee">Nama Karyawan</label>
                    <input type="text" class="form-control" name="_employee" id="_employee" placeholder="Nama Partner" value="{{ $employee->nama }}" disabled>
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                </div>
                
                <div class="mt-3">
                    <label for="prepay_date">Tanggal</label>
                    <input type="date" class="form-control @error('prepay_date') is-invalid @enderror" name="prepay_date" id="prepay_date" value="{{ old('prepay_date', Carbon\Carbon::today()->format('Y-m-d')) }}">
                    @error('prepay_date')
                        <p class="text-danger">Harap masukkan tanggal kasbon.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="init_amount">Nominal Awal Kasbon</label>
                    <input type="number" class="form-control" name="init_amount" id="init_amount" placeholder="Nominal Awal Kasbon" value="{{ old('init_amount', 0) }}">
                    @error('init_amount')
                        <p class="text-danger">Harap masukkan nilai minimal 1.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="cut_amount">Potongan Kasbon</label>
                    <input type="number" class="form-control" name="cut_amount" id="cut_amount" placeholder="Potongan Kasbon" value="{{ old('cut_amount', 0) }}">
                    @error('cut_amount')
                        <p class="text-danger">Harap masukkan nilai minimal 1.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="remark">Keperluan</label>
                    <input type="text" class="form-control" name="remark" id="remark" placeholder="Keperluan kasbon"
                        value="{{ old('remark') }}">
                    @error('remark')
                        <p class="text-danger">Harap masukkan keperluan kasbon.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label>Pemotongan Otomatis</label>

                    <div class="d-flex gap-3">
                        <div class="d-flex gap-2 rounded-3 py-2">
                            <input class="form-check-input" type="radio" name="enable_auto_cut" id="enable_auto_cut1" value="yes" @if(old('enable_auto_cut') == "yes") checked @endif checked>
                            <label class="form-check-label" for="enable_auto_cut1">Ya</label>
                        </div>
                        <div class="d-flex gap-2 rounded-3 py-2">
                            <input class="form-check-input" type="radio" name="enable_auto_cut" id="enable_auto_cut2" value="no" @if(old('enable_auto_cut') == "no") checked @endif>
                            <label class="form-check-label" for="enable_auto_cut2">Tidak</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Simpan Data Baru">
                </div>
            </form>

        </div>
    </x-container-middle>
@endsection
