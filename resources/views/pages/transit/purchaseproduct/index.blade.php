@extends('layouts.main-admin')

@section("content")

    <br>
    <h1>All Products in {{ $purchase->register }}</h1>
    <br>

    {{-- <h5>welcome back, {{ Auth::user()->name }}! </h5> --}}

    @if (session()->has("successAddProduct"))
            <p class="text-success fw-bold">{{ session("successAddProduct") }}</p>

    @elseif (session()->has("successEditProduct"))

            <p class="text-success fw-bold">{{ session("successEditProduct") }}</p>

    @elseif (session()->has("successDeleteProduct"))
            <p class="text-success fw-bold">{{ session("successDeleteProduct") }}</p>

    @endif

    <a href="{{ route('purchaseproduct-create1', $purchase->id) }}" class="btn btn-primary text-white mb-3" style="font-size: 10pt">
        <i class="bi bi-plus-square"></i> Add Item to List
    </a>
    <a href="{{ route('purchaseproduct-create2', $purchase->id) }}" class="btn btn-success text-white mb-3" style="font-size: 10pt">
        <i class="bi bi-plus-square"></i> New Product
    </a>
    <br>
    <!-- tabel list data-->

    <table class="w-100">
        <tr>
            <th class="border border-1 border-dark ">Nomor</th>
            <th class="border border-1 border-dark ">Nama Produk </th>
            <th class="border border-1 border-dark ">SKU Produk </th>
            <th class="border border-1 border-dark ">Harga</th>
            <th class="border border-1 border-dark ">Diskon</th>
            <th class="border border-1 border-dark ">Variant</th>
            <th class="border border-1 border-dark ">Quantity</th>
            <th class="border border-1 border-dark ">Action</th>
        </tr>

        @foreach ($pp as $purchase_product)
            <tr>
                <td class="border border-1 border-dark " >{{ $loop->iteration }}</td>
                <td class="border border-1 border-dark " >{{ $purchase_product->product->product_name }}</td>
                <td class="border border-1 border-dark " >{{ $purchase_product->product->product_code }}</td>
                <td class="border border-1 border-dark " >Rp {{ $purchase_product->price }},00</td>
                <td class="border border-1 border-dark " >{{ $purchase_product->discount }}</td>
                <td class="border border-1 border-dark " >{{ $purchase_product->product->variant }}</td>
                <td class="border border-1 border-dark " >{{ $purchase_product->quantity }}</td>
                {{-- <td class="border border-1 border-dark " >{{ $p->user->name }}</td> --}}
                <td class="border border-1 border-dark " >
                    <div class="d-flex gap-5 w-100 justify-content-center">
                        <form action="{{ route("purchaseproduct-destroy", [$purchase->id, $purchase_product->id] ) }}" method="POST">
                            @csrf
                            <button class="btn btn-danger text-white" style="font-size: 10pt " onclick="return confirm('Do you want to remove this item from the purchase?')">
                                <i class="bi bi-trash"></i>
                                Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>

        @endforeach
    </table>

@endsection
