@extends('layouts.main-admin')

@section('bcd')
    <span>> <a href="#">Create</a></span>
@endsection

@section('content')
    <x-container-middle>
        <div class="container bg-white rounded-4 p-5 mt-4 border border-1 card">
            <h2 class="text-center fw-bold">Import Purchases Data With Products</h2>

            <p class="fs-6 mt-4">Please make sure the columns order is the same as the shown image before saving as .csv file:</p>
            <img src="{{ asset('res/guide-importcsv-purchasewp.png') }}" alt="guide-importcsv">

            <p class="fs-6 mt-4">Please upload in .csv file format.</p>

            <form action="{{ route('purchase-importwpstore') }}" method="POST" enctype="multipart/form-data">
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