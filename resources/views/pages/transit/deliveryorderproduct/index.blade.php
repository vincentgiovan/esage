@extends('layouts.main-admin')

@section("content")

    <br>
    <h1>All Products in {{ $deliveryorder->register }}</h1>
    <hr>
    <br>

    {{-- <h5>welcome back, {{ Auth::user()->name }}! </h5> --}}

    @if (session()->has("successAddProduct"))
            <p class="text-success fw-bold">{{ session("successAddProduct") }}</p>

    @elseif (session()->has("successEditProduct"))

            <p class="text-success fw-bold">{{ session("successEditProduct") }}</p>

    @elseif (session()->has("successDeleteProduct"))
            <p class="text-success fw-bold">{{ session("successDeleteProduct") }}</p>

    @endif

    <a href="{{ route('deliveryorderproduct-create1', $deliveryorder->id) }}" class="btn btn-primary text-white mb-3" style="font-size: 10pt">
        <i class="bi bi-plus-square"></i> Add Item to List
    </a>
    
    <br>
    <!-- tabel list data-->

    <table class="w-100">
        <tr>
            <th class="border border-1 border-dark ">Nomor</th>
            <th class="border border-1 border-dark ">Nama Produk </th>
            <th class="border border-1 border-dark ">SKU Produk </th>
            <th class="border border-1 border-dark ">Quantity</th>
            <th class="border border-1 border-dark ">Variant</th>
            <th class="border border-1 border-dark ">Action</th>
        </tr>

        @foreach ($do as $deliveryorder_product)
            <tr>
                <td class="border border-1 border-dark " >{{ $loop->iteration }}</td>
                <td class="border border-1 border-dark " >{{ $deliveryorder_product->product->product_name }}</td>
                <td class="border border-1 border-dark " >{{ $deliveryorder_product->product->product_code }}</td>
                <td class="border border-1 border-dark " >{{ $deliveryorder_product->quantity }}</td>
                <td class="border border-1 border-dark " >{{ $deliveryorder_product->product->variant }}</td>
                <td class="border border-1 border-dark " >
                    <div class="d-flex gap-5 w-100 justify-content-center">
                        <form action="{{ route("deliveryorderproduct-destroy", [$deliveryorder->id, $deliveryorder_product->id] ) }}" method="POST">
                            @csrf
                            <button class="btn btn-danger text-white" style="font-size: 10pt " onclick="return confirm('Do you want to remove this item from the deliveryorder?')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>

        @endforeach

        <tr>
            <td colspan="9" style="height: 50px;">
            </td>
        </tr>
        <tr>
            <td colspan="9" class="border border-1 border-dark" style="background: linear-gradient(to right, rgb(113, 113, 113), rgb(213, 207, 207));">
                <div class="d-flex h-100 w-100 justify-content-end gap-3 fw-bold" style="font-size: 14pt;">
                    <div>
                        Total Items:
                    </div>
                    <div>
                        {{ $do->count() }}
                    </div>
                </div>
            </td>
        </tr>
    </table>

@endsection
