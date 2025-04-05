@extends('layouts.main-admin')

@section('content')
    <x-container-middle>
        <div class="container bg-white rounded-4 py-4 px-5 border border-1 card mt-4 mt-4">

            <h3>Edit Data Proyek</h3>

            <form method="POST" action="{{ route('project-update', $project->id) }}">

                @csrf

                <div class="mt-3">
                    <label for="project_name">Nama Proyek<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="project_name" name="project_name" placeholder="Nama Project"
                        value="{{ old('project_name', $project->project_name) }}">
                    @error('project_name')
                        <p class="text-danger">Harap masukkan nama proyek.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="location">Lokasi<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="location" name="location" placeholder="Location"
                        value="{{ old('location', $project->location) }}">
                    @error('location')
                        <p class="text-danger">Harap masukkan lokasi proyek.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="PIC">PIC<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="PIC" name="PIC" placeholder="PIC Name"
                        value="{{ old('PIC', $project->PIC) }}">
                    @error('PIC')
                        <p class="text-danger">Harap masukkan nama PIC proyek.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="address">Alamat<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="address" name="address" placeholder="Alamat"
                        value="{{ old('address', $project->address) }}">
                    @error('address')
                        <p class="text-danger">Harap masukkan alamat proyek.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="RAB">Nomor RAB<span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('RAB') is-invalid @enderror" id="RAB" name="RAB" placeholder="Nomor RAB"
                        value="{{ old('RAB', $project->RAB) }}">
                    @error('RAB')
                        <p class="text-danger">Harap masukkan nomor RAB.</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Simpan Perubahan">
                </div>
            </form>

            <script></script>
        </div>
    </x-container-middle>

@endsection
