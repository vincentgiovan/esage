@extends('layouts.main-admin')

@section('content')
    <x-container>
        <br>
        <div class="w-100 d-flex align-items-center justify-content-between">
            <h2>Sage Projects</h2>
            @can('admin')
                <div class="d-flex gap-3">
                    <a class="btn btn-secondary" href="{{ route('project-import') }}"><i class="bi bi-file-earmark-arrow-down"></i> Import</a>
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
            @endcan
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

        @can("admin")
            <a href="{{ route('project-create') }}" class="btn btn-primary text-white mb-3" style="font-size: 10pt">
                <i class="bi bi-plus-square"></i>
                Add New Project</a>
            <br>
        @endcan

        <!-- tabel list data-->

        <div class="overflow-x-auto">
            <table class="w-100">
                <tr>
                    <th>No</th>
                    <th>Nama Project</th>
                    <th>Location</th>
                    <th>PIC Name</th>
                    <th>Address</th>
                    @can('admin')
                        <th>Action</th>
                    @endcan
                </tr>

                @foreach ($projects as $p)
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                {{ $p->project_name }}
                                @can('admin')
                                    <a href="{{ route('project-log', $p->id) }}" class="btn btn-success">View Items</a>
                                @endcan
                            </div>
                        </td>
                        <td>{{ $p->location }}</td>
                        <td>{{ $p->PIC }}</td>
                        <td>{{ $p->address }}</td>
                        {{-- <td >{{ $p->user->name }}</td> --}}
                        @can('admin')
                            <td>
                                <div class="d-flex gap-2 w-100">
                                    <a href="{{ route('project-edit', $p->id) }}" class="btn btn-warning text-white"
                                        style="font-size: 10pt; background-color: rgb(197, 167, 0);">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('project-destroy', $p->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-danger text-white" style="font-size: 10pt "
                                            onclick="return confirm('Do you want to delete this item?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        @endcan
                    </tr>
                @endforeach
            </table>
        </div>
    </x-container>
@endsection
