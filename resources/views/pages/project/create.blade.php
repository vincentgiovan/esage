@extends('layouts.main-admin')

@section('content')
    <x-container-middle>
        <div class="container bg-white rounded-4 p-5 border border-1 card">
            <h2 class="text-center fw-bold">Insert Project</h2>
            <form method="POST" action="{{ route('project-store') }}">
                {{-- @csrf kepake untuk token ,wajib --}}
                @csrf

                <div class="mt-3">
                    <label for="project_name">Project Name</label>
                    <input type="text" class="form-control @error('project_name') is-invalid @enderror" id="project_name" name="project_name" placeholder="Nama Project"
                        value="{{ old('project_name') }}">
                    @error('project_name')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="location">Location</label>
                    <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" placeholder="Location"
                        value="{{ old('location') }}">
                    @error('location')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="PIC">PIC Name</label>
                    <input type="text" class="form-control @error('PIC') is-invalid @enderror" id="PIC" name="PIC" placeholder="PIC Name"
                        value="{{ old('PIC') }}">
                    @error('PIC')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="address">Address</label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" placeholder="Alamat"
                        value="{{ old('address') }}">
                    @error('address')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    @if (session()->has('passwordNotConfirmed'))
                        <p class="text-success fw-bold">{{ session('passwordNotConfirmed') }}</p>
                    @endif
                    <input type="submit" class="btn btn-success px-3 py-1" value="Simpan Data Baru">
                </div>
            </form>
        </div>
    </x-container-middle>
@endsection
