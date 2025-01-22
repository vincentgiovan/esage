@extends('layouts.main-admin')

@section('content')
    <x-container>
        <br>
        <div class="w-100 d-flex align-items-center justify-content-between">
            <h2>Partner Sage</h2>

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


            <a href="{{ route('partner-create') }}" class="btn btn-primary text-white mb-3" style="font-size: 10pt">
                <i class="bi bi-plus-square"></i>
                Tambah Partner Baru</a>
            <br>

        <!-- tabel list data-->

        <div class="overflow-x-auto">
            <table class="w-100">
                <tr>
                    <th>No</th>
                    <th>Nama Partner</th>
                    <th>Role</th>
                    <th>Alamat</th>
                    <th>Kontak</th>
                    <th>Catatan</th>
                    <th>Tempo</th>

                        <th>Aksi</th>

                </tr>

                @foreach ($partners as $p)
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                {{ $p->partner_name }}

                                    <a href="{{ route('partner-log', $p->id) }}" class="btn btn-success">Lihat Log</a>

                            </div>
                        </td>
                        <td>{{ $p->role }}</td>
                        <td>{{ $p->address }}</td>
                        <td>
                            <ul>
                                <li>Email: {{ $p->email ?? "N/A" }}</li>
                                <li>Fax: {{ $p->fax ?? "N/A" }}</li>
                                <li>Mobile/Telephone: {{ $p->phone ?? "N/A" }}/{{ $p->contact ?? "N/A" }}</li>
                            </ul>
                        </td>
                        <td>{{ $p->remark }}</td>
                        <td>{{ $p->tempo }}</td>
                        {{-- <td >{{ $p->user->name }}</td> --}}

                            <td>
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
                        @endauth
                    </tr>
                @endforeach
            </table>
        </div>
    </x-container>
@endsection
