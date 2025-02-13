@extends('layouts.main-admin')

@section('content')
    <x-container>
        <div class="w-100 d-flex align-items-center justify-content-between">
            <h2 class="mt-4">Data Barang di Gudang</h2>

            @if(!in_array(Auth::user()->role->role_name, ['gudang', 'subgudang', 'project_manager']))
                <div class="d-flex gap-3">
                    <a class="btn btn-secondary" href="{{ route('product-import') }}"><i class="bi bi-file-earmark-arrow-down"></i> Import</a>
                    <div class="position-relative d-flex flex-column align-items-end">
                        <button class="btn btn-secondary" type="button" id="dd-toggler">
                            <i class="bi bi-file-earmark-arrow-up"></i> Export
                        </button>
                        <div class="bg-white rounded-lg position-absolute z-2 border border-1" id="dd-menu" style="display: none; top: 40px;">
                            <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("product-export-pdf", 2) }}" target="blank">Export (PDF Portrait)</a></li>
                            <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("product-export-pdf", 1) }}" target="blank">Export (PDF Landscape)</a></li>
                            <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("product-export-excel", 1) }}" target="blank">Export Excel</a></li>
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
        <hr class="mt-2">

        @if (session()->has('successAddProduct'))
            <p class="text-success fw-bold">{{ session('successAddProduct') }}</p>
        @elseif (session()->has('successEditProduct'))
            <p class="text-success fw-bold">{{ session('successEditProduct') }}</p>
        @elseif (session()->has('successDeleteProduct'))
            <p class="text-success fw-bold">{{ session('successDeleteProduct') }}</p>
        @elseif (session()->has('successImportExcel'))
            <p class="text-success fw-bold">{{ session('successImportExcel') }}</p>
        @endif

        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-2">
            <div class="d-flex gap-2 ">
                <form action="{{ route('product-index') }}" class="d-flex gap-2">
                    <input type="text" name="search" placeholder="Cari barang..." value="{{ request('search') }}"
                        class="form-control border border-1 border-secondary">
                    <button class="btn " style="background-color: rgb(191, 191, 191)"><i class="bi bi-search"></i></button>
                </form>
                <a href="{{ route('product-index') }}" class="btn" style="background-color: rgb(191, 191, 191)"><i class="bi bi-x-lg"></i></a>
            </div>

            @if(!in_array(Auth::user()->role->role_name, ['subgudang', 'project_manager']))
                <a href="{{ route('product-create') }}" class="btn btn-primary text-white" style="font-size: 10pt">
                    <i class="bi bi-plus-square"></i>
                    Tambah Barang Baru
                </a>
            @endif
        </div>

        <br>

        <div class="d-flex" style="gap: 1px;">
            <a href="{{ route('product-index', ['condition' => 'good']) }}" class="btn" style="border-radius: 0; width: 100px; @if(request('condition') == 'good') background-color: rgb(59, 59, 59); @else background-color: rgb(142, 142, 142); @endif color: white;">Bagus</a>
            <a href="{{ route('product-index', ['condition' => 'degraded']) }}" class="btn" style="border-radius: 0; width: 100px; @if(request('condition') == 'degraded') background-color: rgb(59, 59, 59); @else background-color: rgb(142, 142, 142); @endif color: white;">Rusak</a>
            <a href="{{ route('product-index', ['condition' => 'refurbish']) }}" class="btn" style="border-radius: 0; width: 100px; @if(request('condition') == 'refurbish') background-color: rgb(59, 59, 59); @else background-color: rgb(142, 142, 142); @endif color: white;">Rekondisi</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-100">
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Varian</th>
                    <th>Total Stok</th>
                    <th>Satuan</th>
                    <th>Status</th>
                    @if(!in_array(Auth::user()->role->role_name, ['subgudang']))
                        <th>Harga Terakhir</th>
                    @endif
                    @if(!in_array(Auth::user()->role->role_name, ['gudang', 'subgudang', 'project_manager']))
                        <th>Markup</th>
                        <th class="text-center">Kondisi</th>
                    @endif
                    <th>Aksi</th>
                </tr>

                @php
                    $i = 0;
                @endphp

                @foreach ($products as $p)
                    @if (isset($p->is_grouped) && $p->is_grouped)
                        @php
                            $status = $p->stock == 0? 'Out of Stock' : 'Ready'
                        @endphp
                        <tr style="background: @if($i % 2 == 1) #E0E0E0 @else white @endif;">
                            <td>{{ ++$i }}</td>
                            <td>{{ $p->product_name }}</td>
                            <td>{{ $p->variant }}</td>
                            <td>{{ $p->stock }}</td>
                            <td>{{ $p->unit }}</td>
                            <td class="fw-semibold @if($status == 'Ready') text-primary @else text-danger @endif">
                                {{ $status }}
                            </td>

                            {{-- Harga dasar dan markup tidak ditampilkan kepada product manager dan subgudang --}}
                            @if(!in_array(Auth::user()->role->role_name, ['gudang', 'subgudang', 'project_manager']))
                                <td>Rp {{ number_format($p->price, 2, ',', '.') }}</td>
                                <td></td>
                                <td></td>
                            @endif

                            {{-- Harga sudah dikalikan markup khusus product manager dan gudang --}}
                            @if(in_array(Auth::user()->role->role_name, ['gudang', 'project_manager']))
                                <td>Rp {{ number_format($p->price * (1 + $p->markup), 2, ',', '.') }}</td>
                            @endif

                            <td>
                                {{-- Toggler button untuk memperlihatkan detail varian data produk --}}
                                <button class="btn btn-success view-all-btn" data-prodgroup="{{ __(str_replace(' ', '', str_replace('/', '-', $p->product_name)) . '-' . str_replace(' ', '', $p->variant)) }}">Lihat Semua</button>
                            </td>
                        </tr>
                    @else
                        <tr class="{{ __(str_replace(' ', '', str_replace('/', '-', $p->product_name)) . '-' . str_replace(' ', '', $p->variant)) }}" style="display: none; background: @if($i % 2 == 0) #E0E0E0 @else white @endif;">
                            <td></td>
                            <td>
                                <div class="w-100 d-flex justify-content-between align-items-center">
                                    <div>{{ $p->product_name }}</div>

                                    {{-- Gudang, subgudang, dan project manager gabisa lihat --}}
                                    @if(!in_array(Auth::user()->role->role_name, ['gudang', 'subgudang', 'project_manager']))
                                        <a href="{{ route('product-log', $p->id) }}" class="btn btn-success">Lihat Log</a>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $p->variant }}</td>
                            <td>{{ $p->stock }}</td>
                            <td>{{ $p->unit }}</td>
                            <td class="fw-semibold @if($p->status == 'Ready') text-primary @else text-danger @endif">{{ $p->status }}</td>

                            {{-- Harga dasar dan markup tidak ditampilkan kepada project manager, gudang, dan subgudang --}}
                            @if(!in_array(Auth::user()->role->role_name, ['gudang', 'subgudang', 'project_manager']))
                                <td>Rp {{ number_format($p->price, 2, ',', '.') }}</td>
                                <td>{{ $p->markup }}</td>
                                <td>{{ ucwords($p->condition) }}</td>
                            @endif

                            {{-- Harga sudah dikalikan markup khusus project manager, gudang, dan subgudang --}}
                            @if(in_array(Auth::user()->role->role_name, ['gudang', 'subgudang', 'project_manager']))
                                <td>Rp {{ number_format($p->price * (1 + $p->markup), 2, ',', '.') }}</td>
                            @endif

                            <td>
                                {{-- Subgudang dan gudang tidak bisa CRUD --}}
                                @if(!in_array(Auth::user()->role->role_name, ['gudang', 'subgudang', 'project_manager']))
                                    <div class="d-flex gap-2 w-100">
                                        <a href="{{ route('product-edit', $p->id) }}" class="btn btn-warning text-white"
                                            style="font-size: 10pt; background-color: rgb(197, 167, 0);">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('product-destroy', $p->id) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-danger text-white" style="font-size: 10pt "
                                                onclick="return confirm('Do you want to delete this item?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
            </table>
        </div>

        {{-- <div class="mt-4">
            {{ $products->links() }}
        </div> --}}
    </x-container>

    <script>
        $('.view-all-btn').click(function(){
            console.log($(this).data('prodgroup'));
            $(`.${$(this).data('prodgroup')}`).toggle();
        });
    </script>
@endsection
