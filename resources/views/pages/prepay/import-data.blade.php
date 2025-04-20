@extends('layouts.main-admin')

@section('bcd')
    <span>> <a href="#">Create</a></span>
@endsection

@section('content')
    <x-container-middle>
        <div class="container bg-white rounded-4 p-5 mt-4 border border-1 card">
            <h3>Import Excel Data Kasbon</h3>

            @if(session()->has('failedImportExcel'))
                <p class="text-danger">{{ session('failedImportExcel') }}</p>
            @endif

            <p class="mt-4">Pastikan posisi kolom telah sesuai seperti yang ditunjukkan pada gambar di bawah ini:</p>
            <img src="{{ asset('res/guide-importcsv-prepay.png') }}" alt="guide-uploadcsv">

            <p class="mt-4">Pilih dan upload file dalam format .xlsx:</p>

            <form action="{{ route('prepay-import-store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" class="form-control @error("file_to_upload") is-invalid @enderror" name="file_to_upload" accept=".xlsx, .xls, .csv" />
                @error("file_to_upload")
                    <p class="invalid-feedback mt-2">{{ $message }}</p>
                @enderror
                <button type="submit" class="btn btn-primary mt-4">Upload</button>
            </form>
        </div>
    </x-container-middle>
@endsection
