@extends('layouts.main-admin')

@section('content')
    <x-container-middle>
        <div class="container bg-white rounded-4 p-5 mt-4 border border-1 card">
            <h3>Edit Data Kasbon</h3>
            <form method="POST" action="{{ route('prepay-update', [$employee->id, $prepay->id]) }}">
                @csrf

                <div class="mt-3">
                    <label for="_employee">Nama Karyawan<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="_employee" id="_employee" placeholder="Nama Partner" value="{{ $employee->nama }}" disabled>
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                </div>

                <div class="mt-3">
                    <label for="prepay_date">Tanggal<span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('prepay_date') is-invalid @enderror" name="prepay_date" id="prepay_date" value="{{ old('prepay_date', Carbon\Carbon::parse($prepay->prepay_date)->format('Y-m-d')) }}">
                    @error('prepay_date')
                        <p class="text-danger">Harap masukkan tanggal kasbon.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="init_amount">Nominal Awal Kasbon<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="init_amount" id="init_amount" placeholder="Nominal Awal Kasbon" value="{{ old('init_amount', $prepay->init_amount) }}">
                    @error('init_amount')
                        <p class="text-danger">Harap masukkan nilai minimal 1.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="curr_amount">Saldo Kasbon<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="curr_amount" id="curr_amount" placeholder="Potongan Kasbon" value="{{ old('curr_amount', $prepay->curr_amount) }}">
                    @error('curr_amount')
                        <p class="text-danger">Harap masukkan nilai minimal 1.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="cut_amount">Potongan Kasbon<span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="cut_amount" id="cut_amount" placeholder="Potongan Kasbon" value="{{ old('cut_amount', $prepay->cut_amount) }}">
                    @error('cut_amount')
                        <p class="text-danger">Harap masukkan nilai minimal 1.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="remark">Keperluan<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="remark" id="remark" placeholder="Keperluan kasbon"
                        value="{{ old('remark', $prepay->remark) }}">
                    @error('remark')
                        <p class="text-danger">Harap masukkan keperluan kasbon.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label>Pemotongan Otomatis<span class="text-danger">*</span></label>

                    <div class="d-flex gap-3">
                        <div class="d-flex gap-2 rounded-3 py-2">
                            <input class="form-check-input" type="radio" name="enable_auto_cut" id="enable_auto_cut1" value="yes" @if(old('enable_auto_cut', $prepay->enable_auto_cut) == "yes") checked @endif>
                            <label class="form-check-label" for="enable_auto_cut1">Ya</label>
                        </div>
                        <div class="d-flex gap-2 rounded-3 py-2">
                            <input class="form-check-input" type="radio" name="enable_auto_cut" id="enable_auto_cut2" value="no" @if(old('enable_auto_cut', $prepay->enable_auto_cut) == "no") checked @endif>
                            <label class="form-check-label" for="enable_auto_cut2">Tidak</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Simpan Perubahan">
                </div>
            </form>

        </div>
    </x-container-middle>
@endsection
