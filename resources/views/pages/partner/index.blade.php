@extends('layouts.main-admin')

@section('content')
    <x-container>
        <br>
        <div class="w-100 d-flex align-items-center justify-content-between">
            <h1>Sage Partners</h1>
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
        <script>
            $(document).ready(() => {
                $("#dd-toggler").click(function(){
                    $("#dd-menu").toggle();
                });
            });
        </script>
        <hr>
        <br>

        @if (session()->has('successAddPartner'))
            <p class="text-success fw-bold">{{ session('successAddPartner') }}</p>
        @elseif (session()->has('successEditPartner'))
            <p class="text-success fw-bold">{{ session('successEditPartner') }}</p>
        @elseif (session()->has('successDeletePartner'))
            <p class="text-success fw-bold">{{ session('successDeletePartner') }}</p>
        @endif

        <a href="{{ route('partner-create') }}" class="btn btn-primary text-white mb-3" style="font-size: 10pt">
            <i class="bi bi-plus-square"></i>
            Add New Data</a>
        <br>
        <!-- tabel list data-->

        <div class="overflow-x-auto">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary ">Nomor</th>
                    <th class="border border-1 border-secondary ">Nama Partner</th>
                    <th class="border border-1 border-secondary ">Partner Role</th>
                    <th class="border border-1 border-secondary ">Remark</th>
                    <th class="border border-1 border-secondary ">Address</th>
                    <th class="border border-1 border-secondary ">Contact</th>
                    <th class="border border-1 border-secondary ">Phone</th>
                    <th class="border border-1 border-secondary ">Fax</th>
                    <th class="border border-1 border-secondary ">Email</th>
                    <th class="border border-1 border-secondary ">Tempo</th>
                    <th class="border border-1 border-secondary ">Action</th>
                </tr>

                @foreach ($partners as $p)
                    <tr>
                        <td class="border border-1 border-secondary ">{{ $loop->iteration }}</td>
                        <td class="border border-1 border-secondary ">{{ $p->partner_name }}</td>
                        <td class="border border-1 border-secondary ">{{ $p->role }}</td>
                        <td class="border border-1 border-secondary ">{{ $p->remark }}</td>
                        <td class="border border-1 border-secondary ">{{ $p->address }}</td>
                        <td class="border border-1 border-secondary ">{{ $p->contact }}</td>
                        <td class="border border-1 border-secondary ">{{ $p->phone }}</td>
                        <td class="border border-1 border-secondary ">{{ $p->fax }}</td>
                        <td class="border border-1 border-secondary ">{{ $p->email }}</td>
                        <td class="border border-1 border-secondary ">{{ $p->tempo }}</td>
                        {{-- <td class="border border-1 border-secondary " >{{ $p->user->name }}</td> --}}
                        <td class="border border-1 border-secondary ">
                            <div class="d-flex gap-5 w-100 justify-content-center">
                                <a href="{{ route('partner-edit', $p->id) }}" class="btn btn-warning text-white"
                                    style="font-size: 10pt; background-color: rgb(197, 167, 0);">
                                    <i class="bi bi-pencil"></i>
                                    Edit Data</a>
                                <form action="{{ route('partner-destroy', $p->id) }}" method="POST">
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
