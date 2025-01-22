@extends('layouts.main-admin')

@section('content')
    <x-container>
        <br>
        <div class="w-100 d-flex align-items-center justify-content-between">
            <h2>Pengiriman Barang</h2>

                <div class="d-flex gap-3">
                    {{-- <a class="btn btn-secondary" href="{{ route('deliveryorder-import') }}"><i class="bi bi-file-earmark-arrow-down"></i> Import</a> --}}
                    <div class="position-relative d-flex flex-column align-items-end">
                        <button class="btn btn-secondary" type="button" id="import-toggler">
                            <i class="bi bi-file-earmark-arrow-down"></i> Import
                        </button>
                        <div class="bg-white rounded-lg position-absolute z-2 border border-1" id="import-menu" style="display: none; top: 40px;">
                            <a class="dropdown-item border border-1 py-2 px-3" href="{{ route('deliveryorder-import') }}">Delivery Order Data Only</a></li>
                            <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("deliveryorder-importwpform") }}">With Products Data</a></li>
                        </div>
                    </div>
                    <div class="position-relative d-flex flex-column align-items-end">
                        <button class="btn btn-secondary" type="button" id="dd-toggler">
                            <i class="bi bi-file-earmark-arrow-up"></i> Export
                        </button>
                        <div class="bg-white rounded-lg position-absolute z-2 border border-1" id="dd-menu" style="display: none; top: 40px;">
                            <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("deliveryorder-export", 2) }}" target="blank">Export (PDF Portrait)</a></li>
                            <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("deliveryorder-export", 1) }}" target="blank">Export (PDF Landscape)</a></li>
                        </div>
                    </div>
                </div>

        </div>
        <script>
            $(document).ready(() => {
                $("#dd-toggler").click(function(){
                    $("#dd-menu").toggle();
                });

                $("#import-toggler").click(function(){
                    $("#import-menu").toggle();
                });
            });
        </script>
        <hr>



        @if (session()->has('successAddOrder'))
            <p class="text-success fw-bold">{{ session('successAddOrder') }}</p>
        @elseif (session()->has('successEditOrder'))
            <p class="text-success fw-bold">{{ session('successEditOrder') }}</p>
        @elseif (session()->has('successDeleteOrder'))
            <p class="text-success fw-bold">{{ session('successDeleteOrder') }}</p>
        @elseif (session()->has('successImportDevor'))
            <p class="text-success fw-bold">{{ session('successImportDevor') }}</p>
        @endif


            <a href="{{ route('deliveryorder-create') }}" class="btn btn-primary text-white mb-3" style="font-size: 10pt">
                <i class="bi bi-plus-square"></i>
                Tambah Pengiriman Baru</a>
            <br>


        <!-- tabel list data-->

        <div class="overflow-x-auto">
            <table class="w-100">
                <tr>
                    <th>No</th>
                    <th>Tanggal Pengiriman</th>
                    <th>Proyek</th>
                    <th>SKU</th>
                    <th class="text-center">Status Pengiriman</th>
                    <th>Catatan</th>

                        <th>Aksi</th>

                </tr>

                @foreach ($deliveryorders as $p)
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ Carbon\Carbon::parse($p->delivery_date)->translatedFormat("d M Y") }}</td>
                        <td>{{ $p->project->project_name }}</td>
                        <td>
                            <div class="d-flex gap-5 w-100 justify-content-between align-items-center">
                                {{ $p->register }}

                                    <a href="{{ route('deliveryorderproduct-viewitem', $p->id) }}" class="btn btn-success text-white"
                                        style="font-size: 10pt"><i class="bi bi-cart"></i>Lihat Barang</a>

                            </div>
                        </td>

                        <td>
                            <div class="w-100 d-flex justify-content-center">
                                @if($p->delivery_status == "Complete")
                                    <i class="bi bi-check-circle-fill fs-4" style="color: green"></i>
                                @else
                                    <i class="bi bi-x-circle-fill fs-4" style="color: red"></i>
                                @endif
                            </div>
                        </td>
                        <td>{{ $p->note }}</td>
                        {{-- <td >{{ $p->user->name }}</td> --}}

                            <td >
                                <div class="d-flex gap-2 w-100">
                                    <a href="{{ route('deliveryorder-edit', $p->id) }}" class="btn text-white"
                                        style="font-size: 10pt; background-color: rgb(197, 167, 0);">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('deliveryorder-destroy', $p->id) }}" method="POST">
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
    </x-container>
@endsection
