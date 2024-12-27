@extends('layouts.main-admin')

@section('content')
    <x-container-middle>
        <div class="container bg-white rounded-4 py-4 px-5 border border-1 card mt-4 mt-4">
            <h2>Tambah Proyek Baru</h2>
            <form method="POST" action="{{ route('project-store') }}">
                {{-- @csrf kepake untuk token ,wajib --}}
                @csrf

                <div class="mt-3">
                    <label for="project_name">Nama proyek</label>
                    <input type="text" class="form-control @error('project_name') is-invalid @enderror" id="project_name" name="project_name" placeholder="Nama proyek"
                        value="{{ old('project_name') }}">
                    @error('project_name')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="location">Lokasi</label>
                    <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" placeholder="Lokasi"
                        value="{{ old('location') }}">
                    @error('location')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="PIC">PIC</label>
                    <input type="text" class="form-control @error('PIC') is-invalid @enderror" id="PIC" name="PIC" placeholder="PIC"
                        value="{{ old('PIC') }}">
                    @error('PIC')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="address">Alamat</label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" placeholder="Alamat proyek"
                        value="{{ old('address') }}">
                    @error('address')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="RAB">Nomor RAB</label>
                    <input type="text" class="form-control @error('RAB') is-invalid @enderror" id="RAB" name="RAB" placeholder="Nomor RAB"
                        value="{{ old('RAB') }}">
                    @error('RAB')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Simpan Data Baru">
                </div>
            </form>
        </div>
    </x-container-middle>
@endsection
