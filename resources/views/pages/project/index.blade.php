@extends('layouts.main-admin')

@section('content')
    <x-container>
        <br>
        <div class="w-100 d-flex align-items-center justify-content-between">
            <h1>Sage Projects</h1>
            <div class="position-relative d-flex flex-column align-items-end">
                <button class="btn btn-secondary" type="button" id="dd-toggler">
                    <i class="bi bi-file-earmark-arrow-up"></i> Export
                </button>
                <div class="bg-white rounded-lg position-absolute z-2 border border-1" id="dd-menu" style="display: none; top: 40px;">
                    <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("project-export", 2) }}" target="blank">Export (PDF Portrait)</a></li>
                    <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("project-export", 1) }}" target="blank">Export (PDF Landscape)</a></li>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(() => {
                $("#dd-toggler").click(function(){
                    $("#dd-menu").toggle();
                });
            });
        </script>
        <hr>
        <br>

        {{-- <h5>welcome back, {{ Auth::user()->name }}! </h5> --}}

        @if (session()->has('successAddProject'))
            <p class="text-success fw-bold">{{ session('successAddProject') }}</p>
        @elseif (session()->has('successEditProject'))
            <p class="text-success fw-bold">{{ session('successEditProject') }}</p>
        @elseif (session()->has('successDeleteProject'))
            <p class="text-success fw-bold">{{ session('successDeleteProject') }}</p>
        @endif

        <a href="{{ route('project-create') }}" class="btn btn-primary text-white mb-3" style="font-size: 10pt">
            <i class="bi bi-plus-square"></i>
            Add New Project</a>
        <br>
        <!-- tabel list data-->

        <div class="overflow-x-auto">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-dark ">Nomor</th>
                    <th class="border border-1 border-dark ">Nama Project</th>
                    <th class="border border-1 border-dark ">Location</th>
                    <th class="border border-1 border-dark ">PIC Name</th>
                    <th class="border border-1 border-dark ">Address</th>
                    <th class="border border-1 border-dark ">Action</th>
                </tr>

                @foreach ($projects as $p)
                    <tr>
                        <td class="border border-1 border-dark ">{{ $loop->iteration }}</td>
                        <td class="border border-1 border-dark ">{{ $p->project_name }}</td>
                        <td class="border border-1 border-dark ">{{ $p->location }}</td>
                        <td class="border border-1 border-dark ">{{ $p->PIC }}</td>
                        <td class="border border-1 border-dark ">{{ $p->address }}</td>
                        {{-- <td class="border border-1 border-dark " >{{ $p->user->name }}</td> --}}
                        <td class="border border-1 border-dark ">
                            <div class="d-flex gap-5 w-100 justify-content-center">
                                <a href="{{ route('project-edit', $p->id) }}" class="btn btn-warning text-white"
                                    style="font-size: 10pt">
                                    <i class="bi bi-pencil"></i>
                                    Edit Data</a>
                                <form action="{{ route('project-destroy', $p->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-danger text-white" style="font-size: 10pt "
                                        onclick="return confirm('Do you want to delete this item?')">
                                        <i class="bi bi-trash"></i>
                                        Delete</button>
                                </form>
                            </div>
                        </td>

                    </tr>
                @endforeach
            </table>
        </div>
    </x-container>
@endsection
