@extends('layouts.main-admin')

@section('content')
    <x-container>
        <br>
        <div class="w-100 d-flex align-items-center justify-content-between">
            <h3>Pengiriman Barang</h3>

            @if(!in_array(Auth::user()->role->role_name, ['gudang', 'subgudang', 'project_manager']))
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
            @endif
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

        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-2">
            <div class="d-flex gap-2 ">
                <form action="{{ route('deliveryorder-index') }}" class="d-flex gap-2">
                    <div class="position-relative">
                    <input type="text" name="search" placeholder="Cari pengiriman..." value="{{ request('search') }}" class="form-control border border-1 border-secondary pe-5" style="width: 300px;">
                        <a href="{{ route('deliveryorder-index') }}" class="btn position-absolute top-0 end-0"><i class="bi bi-x-lg"></i></a>
                    </div>
                    <button class="btn btn-primary"><i class="bi bi-search"></i></button>
                </form>
            </div>
            @if(!in_array(Auth::user()->role->role_name, ['subgudang', 'project_manager']))
                <a href="{{ route('deliveryorder-create') }}" class="btn btn-primary text-white mb-3" style="font-size: 10pt">
                    <i class="bi bi-plus-square"></i>
                    Tambah Pengiriman Baru</a>
            @endif
        </div>

        <div class="d-flex w-100 justify-content-end">
            Memperlihatkan {{ $deliveryorders->firstItem() }} - {{ $deliveryorders->lastItem()  }} dari {{ $deliveryorders->total() }} item
        </div>

        {{-- tabel list data--}}
        <div class="overflow-x-auto mt-3">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary">No</th>
                    <th class="border border-1 border-secondary">Tanggal Pengiriman</th>
                    <th class="border border-1 border-secondary">Proyek</th>
                    <th class="border border-1 border-secondary">SKU</th>
                    <th class="border border-1 border-secondary" class="text-center">Status Pengiriman</th>
                    <th class="border border-1 border-secondary">Catatan</th>
                    @if(!in_array(Auth::user()->role->role_name, ['subgudang', 'project_manager']))
                        <th class="border border-1 border-secondary">Aksi</th>
                    @endif
                </tr>

                @foreach ($deliveryorders as $do)
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td class="border border-1 border-secondary">{{ ($loop->index + 1) + ((request('page') ?? 1) - 1) * 30 }}</td>
                        <td class="border border-1 border-secondary">{{ Carbon\Carbon::parse($do->delivery_date)->translatedFormat("d M Y") }}</td>
                        <td class="border border-1 border-secondary">{{ $do->project->project_name }}</td>
                        <td class="border border-1 border-secondary">
                            <div class="d-flex gap-5 w-100 justify-content-between align-items-center">
                                {{ $do->register }}
                                <a href="{{ route('deliveryorderproduct-viewitem', $do->id) }}" class="btn btn-success text-white"
                                    style="font-size: 10pt"><i class="bi bi-cart"></i>Lihat Barang</a>
                            </div>
                        </td>

                        <td class="border border-1 border-secondary">
                            @if($do->delivery_status == 'Complete')
                                Selesai
                            @else
                                Belum Selesai
                            @endif
                        </td>
                        <td class="border border-1 border-secondary">{{ $do->note }}</td>
                        {{-- <td class="border border-1 border-secondary" >{{ $do->user->name }}</td> --}}
                        @if(!in_array(Auth::user()->role->role_name, ['subgudang', 'project_manager']))
                            <td class="border border-1 border-secondary" >
                                <div class="d-flex gap-2 w-100">
                                    <a href="{{ route('deliveryorder-edit', $do->id) }}" class="btn text-white"
                                        style="font-size: 10pt; background-color: rgb(197, 167, 0);">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('deliveryorder-destroy', $do->id) }}" method="POST">
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
            {{ $deliveryorders->links() }}
        </div>
    </x-container>
@endsection
