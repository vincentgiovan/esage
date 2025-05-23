@extends('layouts.main-admin')

@section('content')
    <x-container>
        <br>
        <div class="w-100 d-flex align-items-center justify-content-between">
            <h3>Proyek Sage</h3>

            @if(!in_array(Auth::user()->role->role_name, ['gudang', 'subgudang', 'project_manager']))
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
            @endif
        </div>
        <script>
            $(document).ready(() => {
                $("#dd-toggler").click(function(){
                    $("#dd-menu").toggle();
                });
            });
        </script>
        <hr>

        @if (session()->has('successAddProject'))
            <p class="text-success fw-bold">{{ session('successAddProject') }}</p>
        @elseif (session()->has('successEditProject'))
            <p class="text-success fw-bold">{{ session('successEditProject') }}</p>
        @elseif (session()->has('successDeleteProject'))
            <p class="text-success fw-bold">{{ session('successDeleteProject') }}</p>
        @endif

        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-2">
            <div class="d-flex gap-2 ">
                <form action="{{ route('project-index') }}" class="d-flex gap-2">
                    <div class="position-relative">
                    <input type="text" name="search" placeholder="Cari proyek..." value="{{ request('search') }}" class="form-control border border-1 border-secondary pe-5" style="width: 300px;">
                        <a href="{{ route('project-index') }}" class="btn position-absolute top-0 end-0"><i class="bi bi-x-lg"></i></a>
                    </div>
                    <button class="btn btn-primary"><i class="bi bi-search"></i></button>
                </form>
            </div>

            @if(!in_array(Auth::user()->role->role_name, ['gudang', 'subgudang']))
                <a href="{{ route('project-create') }}" class="btn btn-primary text-white" style="font-size: 10pt">
                    <i class="bi bi-plus-square"></i>
                    Tambah Proyek Baru</a>
            @endif
        </div>

        <div class="d-flex w-100 justify-content-end">
            Memperlihatkan {{ $projects->firstItem() }} - {{ $projects->lastItem()  }} dari {{ $projects->total() }} item
        </div>

        {{-- tabel list data--}}
        <div class="overflow-x-auto mt-3">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary">No</th>
                    <th class="border border-1 border-secondary">Nama Proyek</th>
                    <th class="border border-1 border-secondary">Lokasi</th>
                    <th class="border border-1 border-secondary">PIC</th>
                    <th class="border border-1 border-secondary">Alamat</th>
                    @if(!in_array(Auth::user()->role->role_name, ['gudang', 'subgudang']))
                        <th class="border border-1 border-secondary">Aksi</th>
                    @endif
                </tr>

                @foreach ($projects as $p)
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td class="border border-1 border-secondary">{{ ($loop->index + 1) + ((request('page') ?? 1) - 1) * 30 }}</td>
                        <td class="border border-1 border-secondary">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                {{ $p->project_name }}
                                <div class="">
                                    <a href="{{ route('project-deliverylog', $p->id) }}" class="btn btn-success">Cek Pengiriman</a>
                                    <a href="{{ route('project-returnlog', $p->id) }}" class="btn btn-success">Cek Pengembalian</a>
                                </div>
                            </div>
                        </td>
                        <td class="border border-1 border-secondary">{{ $p->location }}</td>
                        <td class="border border-1 border-secondary">{{ $p->PIC }}</td>
                        <td class="border border-1 border-secondary">{{ $p->address }}</td>
                        {{-- <td class="border border-1 border-secondary" >{{ $p->user->name }}</td> --}}
                        @if(!in_array(Auth::user()->role->role_name, ['gudang', 'subgudang']))
                            <td class="border border-1 border-secondary">
                                <div class="d-flex gap-2 w-100">
                                    <a href="{{ route('project-manageemployee-index', $p->id) }}" class="btn btn-success text-white"
                                        style="font-size: 10pt;">
                                        <i class="bi bi-person-fill"></i>
                                    </a>
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
                        @endif
                    </tr>
                @endforeach
            </table>
        </div>

        <div class="mt-4">
            {{ $projects->links() }}
        </div>
    </x-container>
@endsection
