@extends('layouts.main-admin')

@section('content')
    {{--
    @foreach (breadcrumbs('product-index', ['id' => $id]) as $breadcrumb)
        <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
        @if (!$loop->last)
            &gt;
        @endif
    @endforeach --}}
    <x-container>
        <br>

        <div class="w-100 d-flex align-items-center justify-content-between">
            <h1>Warehouse Items</h1>
            <div class="position-relative d-flex flex-column align-items-end">
                <button class="btn btn-secondary" type="button" id="dd-toggler">
                    <i class="bi bi-file-earmark-arrow-up"></i> Export
                </button>
                <div class="bg-white rounded-lg position-absolute z-2 border border-1" id="dd-menu" style="display: none; top: 40px;">
                    <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("product-export", 2) }}" target="blank">Export (PDF Portrait)</a></li>
                    <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("product-export", 1) }}" target="blank">Export (PDF Landscape)</a></li>
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

        {{-- <h5>welcome back, {{ Auth::user()->name }}! </h5> --}}

        @if (session()->has('successAddProduct'))
            <p class="text-success fw-bold">{{ session('successAddProduct') }}</p>
        @elseif (session()->has('successEditProduct'))
            <p class="text-success fw-bold">{{ session('successEditProduct') }}</p>
        @elseif (session()->has('successDeleteProduct'))
            <p class="text-success fw-bold">{{ session('successDeleteProduct') }}</p>
        @endif

        @can('admin')
            <a href="{{ route('product-create') }}" class="btn btn-primary text-white mb-3" style="font-size: 10pt">
                <i class="bi bi-plus-square"></i>
                Add New Product
            </a>

            <br>
        @endcan

        <div class="d-flex gap-2 ">
            <form action="{{ route('product-index') }}" class="d-flex gap-2">
                <input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}"
                    class="form-control border border-1 border-secondary">
                <button class="btn " style="background-color: rgb(191, 191, 191)">Search</button>
            </form>
            <a href="{{ route('product-index') }}" class="btn" style="background-color: rgb(191, 191, 191)">Clear</a>
        </div>

        <br>

        <!-- tabel list data-->
        <div class="overflow-x-auto">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-dark ">Nomor</th>
                    <th class="border border-1 border-dark ">Nama Produk </th>
                    <th class="border border-1 border-dark ">SKU Produk </th>
                    <th class="border border-1 border-dark ">Stok</th>
                    <th class="border border-1 border-dark ">Harga</th>
                    <th class="border border-1 border-dark ">Unit</th>
                    <th class="border border-1 border-dark ">Variant</th>
                    <th class="border border-1 border-dark ">Markup</th>
                    <th class="border border-1 border-dark ">Status</th>
                    @can('admin')
                        <th class="border border-1 border-dark ">Action</th>
                    @endcan
                </tr>

                @foreach ($products as $p)
                    <tr>
                        <td class="border border-1 border-dark ">{{ $loop->iteration + ((request("page") - 1) * 5) }}</td>
                        <td class="border border-1 border-dark ">{{ $p->product_name }}</td>
                        <td class="border border-1 border-dark ">{{ $p->product_code }}</td>
                        <td class="border border-1 border-dark ">{{ $p->stock }}</td>
                        <td class="border border-1 border-dark ">Rp {{ number_format($p->price, 2, ',', '.') }}</td>
                        <td class="border border-1 border-dark ">{{ $p->unit }}</td>
                        <td class="border border-1 border-dark ">{{ $p->variant }}</td>
                        <td class="border border-1 border-dark ">{{ $p->markup }}</td>
                        <td class="border border-1 border-dark ">{{ $p->status }}</td>
                        {{-- <td class="border border-1 border-dark " >{{ $p->user->name }}</td> --}}
                        @can('admin')
                            <td class="border border-1 border-dark ">
                                <div class="d-flex gap-5 w-100 justify-content-center">
                                    <a href="{{ route('product-edit', $p->id) }}" class="btn btn-warning text-white"
                                        style="font-size: 10pt">
                                        <i class="bi bi-pencil"></i>
                                        Edit Data</a>
                                    <form action="{{ route('product-destroy', $p->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-danger text-white" style="font-size: 10pt "
                                            onclick="return confirm('Do you want to delete this item?')">
                                            <i class="bi bi-trash"></i>
                                            Delete</button>
                                    </form>
                                </div>
                            </td>
                        @endcan
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </x-container>
@endsection
