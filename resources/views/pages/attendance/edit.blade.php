@extends('layouts.main-admin')

@section('content')
    <x-container-middle>
        <div class="container bg-white rounded-4 py-4 px-5 border border-1 card mt-4">
            <h2>Edit Attendance Data</h2>
            <form method="POST" action="{{ route('attendance-update', $attendance->id) }}">
                @csrf

                <div class="mt-3">
                    <label for="attendance_date">Tanggal</label>
                    <input type="date" class="form-control @error('attendance_date') is-invalid @enderror" id="attendance_date" name="attendance_date" placeholder="attendance_date"
                        value="{{ old('attendance_date', $attendance->attendance_date) }}">
                    @error('attendance_date')
                        <p class="text-danger">Harap masukkan tanggal absensi.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="employee_id">Pegawai</label>
                    <select type="text" class="form-select text-black @error('employee_id') is-invalid @enderror" id="employee_id" name="employee_id">
                        <option selected disabled>Select an employee</option>
                        @foreach ($employees as $e)
                            <option value="{{ $e->id }}" @if(old("employee_id", $attendance->employee_id) == $e->id) selected @endif>{{ $e->nama }}</option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="project_id">Proyek</label>
                    <select type="text" class="form-select text-black @error('project_id') is-invalid @enderror" id="project_id" name="project_id">
                        <option selected disabled>Select a project</option>
                        @foreach ($projects as $p)
                            <option value="{{ $p->id }}" @if(old("project_id", $attendance->project_id) == $p->id) selected @endif>{{ $p->project_name }}</option>
                        @endforeach
                    </select>
                    @error('project_id')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="d-flex w-100 gap-4 mt-3">
                    <div class="w-50">
                        <label for="jam_masuk">Jam Masuk</label>
                        <input type="time" class="form-control @error('jam_masuk') is-invalid @enderror" id="jam_masuk" name="jam_masuk" placeholder="Input Index lembur panjang"
                            value="{{ old('jam_masuk', $attendance->jam_masuk) }}">
                        @error('jam_masuk')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-50">
                        <label for="jam_keluar">Jam Keluar</label>
                        <input type="time" class="form-control @error('jam_keluar') is-invalid @enderror" id="jam_keluar" name="jam_keluar" value="{{ $attendance->jam_keluar }}" >
                        @error('jam_keluar')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="d-flex w-100 gap-4 mt-3">
                    <div class="w-50">
                        <label for="normal">Jam Normal</label>
                        <input type="text" class="form-control @error('normal') is-invalid @enderror" id="normal" name="normal" placeholder="Input normal"
                            value="{{ old('normal', $attendance->normal) }}">
                        @error('normal')
                            <p class="text-danger">Harap masukkan jumlah jam normal.</p>
                        @enderror
                    </div>

                    <div class="w-50">
                        <label for="performa">Performa</label>
                        <input type="text" class="form-control @error('performa') is-invalid @enderror" id="performa" name="performa" placeholder="Input performa"
                            value="{{ old('performa', $attendance->performa) }}">
                        @error('performa')
                            <p class="text-danger">Harap masukkan nilai minimal 0.</p>
                        @enderror
                    </div>
                </div>

                <div class="d-flex w-100 gap-4 mt-3">
                    <div class="w-50">
                        <label for="jam_lembur">Jam Lembur</label>
                        <input type="text" class="form-control @error('jam_lembur') is-invalid @enderror" id="jam_lembur" name="jam_lembur" placeholder="Input jam lembur"
                            value="{{ old('jam_lembur', $attendance->jam_lembur) }}">
                        @error('jam_lembur')
                            <p class="text-danger">Harap masukkan jumlah jam lembur.</p>
                        @enderror
                    </div>

                    <div class="w-50">
                        <label for="index_lembur_panjang">Index Lembur Panjang</label>
                        <input type="text" class="form-control @error('index_lembur_panjang') is-invalid @enderror" id="index_lembur_panjang" name="index_lembur_panjang" placeholder="Input Index lembur panjang"
                            value="{{ old('index_lembur_panjang', $attendance->index_lembur_panjang) }}">
                        @error('index_lembur_panjang')
                            <p class="text-danger">Harap masukkan nilai minimal 0.</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-3">
                    <label for="remark">Remark</label>
                    <input type="text" class="form-control" id="remark" name="remark" placeholder="Input remark"
                        value="{{ old('remark', $attendance->remark) }}">
                </div>

                <div class="mt-3">
                    @if (session()->has('passwordNotConfirmed'))
                        <p class="text-success fw-bold">{{ session('passwordNotConfirmed') }}</p>
                    @endif
                    <input type="submit" class="btn btn-success px-3 py-1" value="Save">
                </div>
            </form>
        </div>
    </x-container-middle>
@endsection
