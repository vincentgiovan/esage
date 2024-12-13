@extends('layouts.main-admin')

@section('content')
    <x-container-middle>
        <div class="container bg-white rounded-4 p-5 border border-1 card">
            <h2 class="text-center fw-bold">Laporan Presensi Baru</h2>

            <form method="POST" action="{{ route('attendance-store') }}">
                @csrf

                <div class="w-100 d-flex flex-column mt-4">
                    <label for="project">Pilih Proyek</label>
                    <select class="form-select w-100" name="project" id="project">
                        @foreach($projects as $proj)
                            <option value="{{ $proj->id }}">{{ $proj->project_name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </x-container-middle>
@endsection
