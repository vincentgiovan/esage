@extends('layouts.main-admin')

@section('content')
    <x-container-middle>
        <div class="container bg-white rounded-4 px-5 py-4 mt-4 border border-1 card">
            <h2>Pengajuan Cuti Baru</h2>
            <form method="POST" action="{{ route('leave-user-propose-store') }}" id="folm">
                @csrf

                <div class="mt-3">
                    <label for="start_period">Tanggal Awal</label>
                    <input type="date" class="form-control @error('start_period') is-invalid @enderror" name="start_period" id="start_period" placeholder="Nama Barang"
                        value="{{ old('start_period') }}">
                    @error('start_period')
                        <p class="text-danger">Harap masukkan tanggal awal cuti.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="end_period">Tanggal Akhir</label>
                    <input type="date" class="form-control @error('end_period') is-invalid @enderror" name="end_period" id="end_period" placeholder="Nama Barang"
                        value="{{ old('end_period') }}">
                    @error('end_period')
                        <p class="text-danger">Harap masukkan tanggal akhir cuti.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="remark">Alasan Cuti</label>
                    <textarea type="text" class="form-control @error('remark') is-invalid @enderror" name="remark" id="remark" placeholder="Alasan cuti" rows="4">{{ old('remark') }}</textarea>
                    @error('remark')
                        <p class="text-danger">Harap masukkan satuan barang.</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Ajukan">
                </div>
            </form>

        </div>
    </x-container-middle>
@endsection