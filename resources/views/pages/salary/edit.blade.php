@extends('layouts.main-admin')

@section('bcd')
    <span>> <a href="#">Edit</a></span>
@endsection

@section('content')
    <x-container-middle>
        <div class="container bg-white p-5 rounded-4 mt-4 border border-1 card">

            <h2>Edit Salary Data</h2>

            <table class="w-100 my-3">
                <tr>
                    <th class="border border-1 border-secondary w-25">Periode</th>
                    <td class="border border-1 border-secondary">{{ $salary->employee->masuk ? str_replace('-', '/', $salary->employee->masuk) : "N/A" }} - {{ $salary->employee->keluar ? str_replace('-', '/', $salary->employee->keluar) : "N/A" }}</td>
                </tr>
                <tr>
                    <th class="border border-1 border-secondary w-25">Nama</th>
                    <td class="border border-1 border-secondary">{{ $salary->employee->nama }}</td>
                </tr>
                <tr>
                    <th class="border border-1 border-secondary w-25">Jabatan</th>
                    <td class="border border-1 border-secondary">{{ $salary->employee->jabatan }}</td>
                </tr>
            </table>

            <form method="POST" action="{{ route('salary-edit', $salary->id) }}">
                {{-- @csrf kepake untuk token ,wajib --}}
                @csrf

                <div class="mt-3">
                    <label for="keterangan">Keterangan</label>
                    <input type="text" class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan"
                        value="{{ old('keterangan', $salary->keterangan) }}">
                    @error('keterangan')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Edit">
                </div>
            </form>
        </div>
    </x-container-middle>
@endsection
