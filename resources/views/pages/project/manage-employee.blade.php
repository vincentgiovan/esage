@extends("layouts.main-admin")

@section("content")
    <x-container>
        <br>
        <h3>Daftar Pegawai di Proyek {{ $project->project_name }}</h3>

        @if (session()->has('successAssignEmployee'))
            <p class="text-success fw-bold">{{ session('successAssignEmployee') }}</p>
        @elseif (session()->has('successUnassignEmployee'))
            <p class="text-success fw-bold">{{ session('successUnassignEmployee') }}</p>
        @endif

        <h6 class="mt-4">Tambah Pegawai ke Proyek</h6>
        <form action="{{ route('project-manageemployee-assign', $project->id) }}" method="post" class="d-flex w-100 gap-3 mt-2 items-stretch">
            @csrf
            <select name="employee" id="employee" class="form-control select2" name="position_name">
                @foreach($all_employees as $emp)
                    <option value="{{ $emp->id }}">{{ $emp->nama }} ({{ $emp->jabatan }})</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary text-nowrap"><i class="bi bi-plus-lg"></i> Tambah ke Proyek</button>
        </form>

        <h6 class="mt-4">Daftar Pegawai</h6>
        <div class="overflow-x-auto mt-3">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary ">#</th>
                    <th class="border border-1 border-secondary ">Nama Pegawai</th>
                    <th class="border border-1 border-secondary ">Jabatan</th>
                    <th class="border border-1 border-secondary text-center">Actions</th>
                </tr>

                @foreach ($employees_assigned as $e)
                    <tr>
                        <td class="border border-1 border-secondary " style="width: 80px;">{{ $loop->iteration }}</td>
                        <td class="border border-1 border-secondary ">{{ $e->nama }}</td>
                        <td class="border border-1 border-secondary ">{{ $e->jabatan }}</td>
                        <td class="border border-1 border-secondary " style="width: 150px;">
                            <div class="d-flex gap-2 w-100 justify-content-center">
                                <form action="{{ route('project-manageemployee-unassign', $project->id) }}" method="post" class="d-flex gap-2 w-100 justify-content-center">
                                    @csrf
                                    <input type="hidden" name="employee" value="{{ $e->id }}">
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah anda yakin ingin menghapus pegawai ini dari proyek ini?')"><i class="bi bi-trash3"></i></button>
                                </form>
                            </div>
                        </td>

                    </tr>
                @endforeach
            </table>
        </div>
        {{-- <ul>
            @forelse($employees_assigned as $e)
                <li>{{$e->nama}}</li>
            @empty
                Belum ada pegawai di proyek ini
            @endforelse
        </ul> --}}
    </x-container>

@endsection
