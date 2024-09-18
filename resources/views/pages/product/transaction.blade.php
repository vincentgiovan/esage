@extends('layouts.main-admin')

@section('content')
    <x-container>
        <div class="w-100 d-flex align-items-center justify-content-between">
            <h1 class="mt-4">Product Transaction Log</h1>
            {{-- <div class="d-flex gap-3">
                <a class="btn btn-secondary" href="{{ route('product-import') }}"><i class="bi bi-file-earmark-arrow-down"></i> Import</a>
                <div class="position-relative d-flex flex-column align-items-end">
                    <button class="btn btn-secondary" type="button" id="dd-toggler">
                        <i class="bi bi-file-earmark-arrow-up"></i> Export
                    </button>
                    <div class="bg-white rounded-lg position-absolute z-2 border border-1" id="dd-menu" style="display: none; top: 40px;">
                        <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("product-export", 2) }}" target="blank">Export (PDF Portrait)</a></li>
                        <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("product-export", 1) }}" target="blank">Export (PDF Landscape)</a></li>
                    </div>
                </div>
            </div> --}}
        </div>

        {{-- <script>
            $(document).ready(() => {
                $("#dd-toggler").click(function(){
                    $("#dd-menu").toggle();
                });
            });
        </script> --}}
        <hr class="mt-2">
        {{-- <h5>welcome back, {{ Auth::user()->name }}! </h5> --}}

        {{-- @if (session()->has('successAddProduct'))
            <p class="text-success fw-bold">{{ session('successAddProduct') }}</p>
        @elseif (session()->has('successEditProduct'))
            <p class="text-success fw-bold">{{ session('successEditProduct') }}</p>
        @elseif (session()->has('successDeleteProduct'))
            <p class="text-success fw-bold">{{ session('successDeleteProduct') }}</p>
        @endif --}}

        <br>

        {{-- <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-2">
            <div class="d-flex gap-2 ">
                <form action="{{ route('product-index') }}" class="d-flex gap-2">
                    <input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}"
                        class="form-control border border-1 border-secondary">
                    <button class="btn " style="background-color: rgb(191, 191, 191)">Search</button>
                </form>
                <a href="{{ route('product-index') }}" class="btn" style="background-color: rgb(191, 191, 191)">Clear</a>
            </div>
            @can('admin')
                <a href="{{ route('product-create') }}" class="btn btn-primary text-white" style="font-size: 10pt">
                    <i class="bi bi-plus-square"></i>
                    Add New Product
                </a>
            @endcan
        </div> --}}

        <br>

        <!-- tabel list data-->
        <div class="overflow-x-auto">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary ">Nomor</th>
                    <th class="border border-1 border-secondary ">SKU Pembelian </th>
                    <th class="border border-1 border-secondary ">Nama Produk </th>
                    <th class="border border-1 border-secondary ">SKU Produk </th>
                    <th class="border border-1 border-secondary ">Harga </th>
                    <th class="border border-1 border-secondary ">Stok </th>

                </tr>

                @foreach ($purchaseproducts as $pp)
                    <tr>
                        <td class="border border-1 border-secondary ">
                            @php
                                if(request("page")){
                                    echo $loop->iteration + ((request("page") - 1) * $n_pagination);
                                } else {
                                    echo $loop->iteration;
                                }

                            @endphp
                        </td>
                        <td class="border border-1 border-secondary ">{{ $pp->purchase->register }}</td>
                        <td class="border border-1 border-secondary ">{{ $pp->product->product_name }}</td>
                        <td class="border border-1 border-secondary ">{{ $pp->product->product_code }}</td>
                        <td class="border border-1 border-secondary ">Rp {{ number_format($pp->price, 2, ',', '.') }}</td>
                        <td class="border border-1 border-secondary ">{{ $pp->quantity }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        {{-- <div class="mt-4">
            {{ $products->links() }}
        </div> --}}
    </x-container>
@endsection
