@extends('layouts.main-admin')

@section('bcd')
    <span>> <a href="#">Create</a></span>
@endsection

@section('content')
    <x-container-middle>
        <div class="container bg-white rounded-4 p-5 mt-4 border border-1 card">
            <h3>Import Products Data to {{ $purchase->register }}</h3>

            <p class="mt-4">Please make sure the columns order is the same as the shown image before saving as .csv file:</p>
            <img src="{{ asset('res/guide-importcsv-purchaseproduct.png') }}" alt="guide-importcsv">

            <p class="mt-4">Please upload in .csv file format.</p>

            <form action="{{ route('purchaseproduct-import-store', $purchase->id) }}" method="POST" enctype="multipart/form-data">
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
