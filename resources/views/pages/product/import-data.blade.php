@extends('layouts.main-admin')

@section('bcd')
    <span>> <a href="#">Create</a></span>
@endsection

@section('content')
    <x-container-middle>
        <div class="container bg-white rounded-4 p-5 mt-4 border border-1 card">
            <h2>Import Product Data</h2>

            <p class="mt-4">Make sure the columns order is the same as shown in the image below before saving as .csv format:</p>
            <img src="{{ asset('res/guide-importcsv-product.png') }}" alt="guide-uploadcsv">

            <p class="mt-4">Please upload in .csv file format.</p>

            <form action="{{ route('product-import-store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" class="form-control @error("csv_file") is-invalid @enderror" name="csv_file" accept=".csv" />
                @error("csv_file")
                    <p class="invalid-feedback mt-2">{{ $message }}</p>
                @enderror
                <button type="submit" class="btn btn-primary mt-4">Upload CSV</button>
            </form>
        </div>
    </x-container-middle>
@endsection
