@extends('layouts.main-admin')

@section('content')
    <x-container>
        <br>
        <div class="w-100 d-flex align-items-center justify-content-between">
            <h3>Pembelian Barang</h3>

            @if(!in_array(Auth::user()->role->role_name, ['gudang', 'subgudang', 'project_manager']))
                <div class="d-flex gap-3">
                    {{-- <a class="btn btn-secondary" href="{{ route('purchase-import') }}"><i class="bi bi-file-earmark-arrow-down"></i> Import</a> --}}
                    <div class="position-relative d-flex flex-column align-items-end">
                        <button class="btn btn-secondary" type="button" id="import-toggler">
                            <i class="bi bi-file-earmark-arrow-down"></i> Import
                        </button>
                        <div class="bg-white rounded-lg position-absolute z-2 border border-1" id="import-menu" style="display: none; top: 40px;">
                            <a class="dropdown-item border border-1 py-2 px-3" href="{{ route('purchase-import') }}">Purchase Data Only</a></li>
                            <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("purchase-importwpform") }}">With Products</a></li>
                        </div>
                    </div>
                    <div class="position-relative d-flex flex-column align-items-end">
                        <button class="btn btn-secondary" type="button" id="dd-toggler">
                            <i class="bi bi-file-earmark-arrow-up"></i> Export
                        </button>
                        <div class="bg-white rounded-lg position-absolute z-2 border border-1" id="dd-menu" style="display: none; top: 40px;">
                            <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("purchase-export", 2) }}" target="blank">Export (PDF Portrait)</a></li>
                            <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("purchase-export", 1) }}" target="blank">Export (PDF Landscape)</a></li>
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

        @if (session()->has('successAddPurchase'))
            <p class="text-success fw-bold">{{ session('successAddPurchase') }}</p>
        @elseif (session()->has('successEditPurchase'))
            <p class="text-success fw-bold">{{ session('successEditPurchase') }}</p>
        @elseif (session()->has('successDeletePurchase'))
            <p class="text-success fw-bold">{{ session('successDeletePurchase') }}</p>
        @elseif (session()->has('successImportPurchase'))
            <p class="text-success fw-bold">{{ session('successImportPurchase') }}</p>
        @endif

        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-2">
            <div class="d-flex gap-2 ">
                <form action="{{ route('purchase-index') }}" class="d-flex gap-2">
                    <div class="position-relative">
                    <input type="text" name="search" placeholder="Cari pembelian..." value="{{ request('search') }}" class="form-control border border-1 border-secondary pe-5" style="width: 300px;">
                        <a href="{{ route('purchase-index') }}" class="btn position-absolute top-0 end-0"><i class="bi bi-x-lg"></i></a>
                    </div>
                    <button class="btn btn-primary"><i class="bi bi-search"></i></button>
                </form>
            </div>
            <a href="{{ route('purchase-create') }}" class="btn btn-primary text-white mb-3" style="font-size: 10pt">
                <i class="bi bi-plus-square"></i>
                Tambah Pembelian Baru</a>
        </div>

        <div class="d-flex w-100 justify-content-end">
            Memperlihatkan {{ $purchases->firstItem() }} - {{ $purchases->lastItem()  }} dari {{ $purchases->total() }} item
        </div>

        {{-- tabel list data--}}
        <div class="overflow-x-auto mt-3">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary">No</th>
                    <th class="border border-1 border-secondary">Supplier</th>
                    <th class="border border-1 border-secondary">Tanggal Pembelian</th>
                    <th class="border border-1 border-secondary">Tenggat Pembelian</th>
                    <th class="border border-1 border-secondary">SKU</th>
                    <th class="border border-1 border-secondary">Total</th>
                    <th class="border border-1 border-secondary">Status</th>

                        <th class="border border-1 border-secondary">Aksi</th>

                </tr>

                @foreach ($purchases as $p)
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td class="border border-1 border-secondary">{{ ($loop->index + 1) + ((request('page') ?? 1) - 1) * 30 }}</td>
                        <td class="border border-1 border-secondary">{{ $p->partner->partner_name }}</td>
                        <td class="border border-1 border-secondary">{{ Carbon\Carbon::parse($p->purchase_date)->translatedFormat("d M Y") }}</td>
                        <td class="border border-1 border-secondary">{{ Carbon\Carbon::parse($p->purchase_deadline)->translatedFormat("d M Y") }}</td>
                        <td class="border border-1 border-secondary">
                            <div class="d-flex gap-5 w-100 justify-content-between align-items-center">
                                {{ $p->register }}

                                    <a href="{{ route('purchaseproduct-viewitem', $p->id) }}" class="btn btn-success text-white"
                                        style="font-size: 10pt"><i class="bi bi-cart"></i> Lihat Barang</a>

                            </div>
                        </td>
                        <td class="border border-1 border-secondary">
                            @php
                                $total = 0;
                                foreach ($p->products as $product){
                                    $total += $product->price * (1 - ($product->discount / 100));
                                }

                                echo  number_format($total, 0, ',', '.');
                            @endphp
                        </td>
                        <td class="border border-1 border-secondary" class="fw-semibold @if($p->purchase_status == 'Ordered') text-primary @else text-success @endif">
                            @if($p->purchase_status == 'Ordered')
                                Telah dipesan
                            @else
                                Diterima
                            @endif
                        </td>
                        {{-- <td class="border border-1 border-secondary" >{{ $p->user->name }}</td> --}}


                            <td class="border border-1 border-secondary">
                                <div class="d-flex gap-2 w-100">

                                    <a href="{{ route('purchase-edit', $p->id) }}" class="btn text-white"
                                        style="font-size: 10pt; background-color: rgb(197, 167, 0);">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('purchase-destroy', $p->id) }}" method="POST">
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
            {{ $purchases->links() }}
        </div>
    </x-container>
@endsection
