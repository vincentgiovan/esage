@extends('layouts.main-admin')

@section('content')
    <x-container>
        <br>
        <div class="w-100 d-flex align-items-center justify-content-between">
            <h3>Partner Sage</h3>

                <div class="d-flex gap-3">
                    <a class="btn btn-secondary" href="{{ route('partner-import') }}"><i class="bi bi-file-earmark-arrow-down"></i> Import</a>
                    <div class="position-relative d-flex flex-column align-items-end">
                        <button class="btn btn-secondary" type="button" id="dd-toggler">
                            <i class="bi bi-file-earmark-arrow-up"></i> Export
                        </button>
                        <div class="bg-white rounded-lg position-absolute z-2 border border-1" id="dd-menu" style="display: none; top: 40px;">
                            <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("partner-export", 2) }}" target="blank">Export (PDF Portrait)</a></li>
                            <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("partner-export", 1) }}" target="blank">Export (PDF Landscape)</a></li>
                        </div>
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

        @if (session()->has('successAddPartner'))
            <p class="text-success fw-bold">{{ session('successAddPartner') }}</p>
        @elseif (session()->has('successEditPartner'))
            <p class="text-success fw-bold">{{ session('successEditPartner') }}</p>
        @elseif (session()->has('successDeletePartner'))
            <p class="text-success fw-bold">{{ session('successDeletePartner') }}</p>
        @endif

        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-2">
            <div class="d-flex gap-2 ">
                <form action="{{ route('partner-index') }}" class="d-flex gap-2">
                    <div class="position-relative">
                    <input type="text" name="search" placeholder="Cari partner..." value="{{ request('search') }}" class="form-control border border-1 border-secondary pe-5" style="width: 300px;">
                        <a href="{{ route('partner-index') }}" class="btn position-absolute top-0 end-0"><i class="bi bi-x-lg"></i></a>
                    </div>
                    <button class="btn btn-primary"><i class="bi bi-search"></i></button>
                </form>
            </div>
            <a href="{{ route('partner-create') }}" class="btn btn-primary text-white mb-3" style="font-size: 10pt">
                <i class="bi bi-plus-square"></i>
                Tambah Partner Baru</a>
        </div>

        <div class="d-flex w-100 justify-content-end">
            Memperlihatkan {{ $partners->firstItem() }} - {{ $partners->lastItem()  }} dari {{ $partners->total() }} item
        </div>

        {{-- tabel list data--}}
        <div class="overflow-x-auto mt-3">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary">No</th>
                    <th class="border border-1 border-secondary">Nama Partner</th>
                    <th class="border border-1 border-secondary">Role</th>
                    <th class="border border-1 border-secondary">Alamat</th>
                    <th class="border border-1 border-secondary">Kontak</th>
                    <th class="border border-1 border-secondary">Catatan</th>
                    <th class="border border-1 border-secondary">Tempo</th>

                        <th class="border border-1 border-secondary">Aksi</th>

                </tr>

                @foreach ($partners as $p)
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td class="border border-1 border-secondary">{{ ($loop->index + 1) + ((request('page') ?? 1) - 1) * 30 }}</td>
                        <td class="border border-1 border-secondary">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                {{ $p->partner_name }}

                                    <a href="{{ route('partner-log', $p->id) }}" class="btn btn-success">Lihat Log</a>

                            </div>
                        </td>
                        <td class="border border-1 border-secondary">{{ $p->role }}</td>
                        <td class="border border-1 border-secondary">{{ $p->address }}</td>
                        <td class="border border-1 border-secondary">
                            <ul>
                                <li>Email: {{ $p->email ?? "N/A" }}</li>
                                <li>Fax: {{ $p->fax ?? "N/A" }}</li>
                                <li>Mobile/Telephone: {{ $p->phone ?? "N/A" }}/{{ $p->contact ?? "N/A" }}</li>
                            </ul>
                        </td>
                        <td class="border border-1 border-secondary">{{ $p->remark }}</td>
                        <td class="border border-1 border-secondary">{{ $p->tempo }}</td>
                        {{-- <td class="border border-1 border-secondary" >{{ $p->user->name }}</td> --}}

                            <td class="border border-1 border-secondary">
                                <div class="d-flex gap-2 w-100">
                                    <a href="{{ route('partner-edit', $p->id) }}" class="btn btn-warning text-white"
                                        style="font-size: 10pt; background-color: rgb(197, 167, 0);">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('partner-destroy', $p->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-danger text-white" style="font-size: 10pt "
                                            onclick="return confirm('Do you want to delete this item?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                    </tr>
                @endforeach
            </table>
        </div>

        <div class="mt-4">
            {{ $partners->links() }}
        </div>
    </x-container>
@endsection
