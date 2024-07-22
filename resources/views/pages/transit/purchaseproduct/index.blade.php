@extends('layouts.main-admin')

@section("content")
<x-container>
    <br>
    <h1>All Products in {{ $purchase->register }}</h1>
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

    <a href="{{ route('purchaseproduct-create1', $purchase->id) }}" class="btn btn-primary text-white mb-3" style="font-size: 10pt">
        <i class="bi bi-plus-square"></i> Add Item to List
    </a>
    <a href="{{ route('purchaseproduct-create2', $purchase->id) }}" class="btn btn-success text-white mb-3" style="font-size: 10pt">
        <i class="bi bi-plus-square"></i> New Product
    </a>
    <br>
    <!-- tabel list data-->

    <div class="overflow-x-auto">
        <table class="w-100">
            <tr>
                <th class="border border-1 border-dark ">Nomor</th>
                <th class="border border-1 border-dark ">Nama Produk </th>
                <th class="border border-1 border-dark ">SKU Produk </th>
                <th class="border border-1 border-dark ">Harga Beli</th>
                <th class="border border-1 border-dark ">Quantity</th>
                <th class="border border-1 border-dark ">Diskon</th>
                <th class="border border-1 border-dark ">Harga Setelah Diskon</th>
                <th class="border border-1 border-dark ">Variant</th>
                <th class="border border-1 border-dark ">Action</th>
            </tr>

            @foreach ($pp as $purchase_product)
                <tr>
                    <td class="border border-1 border-dark " >{{ $loop->iteration }}</td>
                    <td class="border border-1 border-dark " >{{ $purchase_product->product->product_name }}</td>
                    <td class="border border-1 border-dark " >{{ $purchase_product->product->product_code }}</td>
                    <td class="border border-1 border-dark " >Rp {{ number_format($purchase_product->price, 2, ',' , '.') }}</td>
                    <td class="border border-1 border-dark " >{{ $purchase_product->quantity }}</td>
                    <td class="border border-1 border-dark " >{{ $purchase_product->discount }}%</td>
                    <td class="border border-1 border-dark " >Rp {{ number_format($purchase_product->price * (1 - ($purchase_product->discount / 100)), 2, ',' , '.') }}</td>
                    {{-- <td class="border border-1 border-dark " >{{ $purchase_product->product->markup }}%</td>
                    <td class="border border-1 border-dark " >Rp {{ $purchase_product->price * (1 + ($purchase_product->product->markup / 100)) }},00</td> --}}
                    <td class="border border-1 border-dark " >{{ $purchase_product->product->variant }}</td>

                    {{-- <td class="border border-1 border-dark " >{{ $p->user->name }}</td> --}}
                    <td class="border border-1 border-dark " >
                        <div class="d-flex gap-5 w-100 justify-content-center">
                            <form action="{{ route("purchaseproduct-destroy", [$purchase->id, $purchase_product->id] ) }}" method="POST">
                                @csrf
                                <button class="btn btn-danger text-white" style="font-size: 10pt " onclick="return confirm('Do you want to remove this item from the purchase?')">
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
                            Total:
                        </div>
                        <div>
                            @php
                                $total = 0;
                                foreach ($pp as $purchase_product){
                                    $total += $purchase_product->price * (1 - ($purchase_product->discount / 100));
                                }

                                echo "Rp " . number_format($total, 2, ',' , '.');
                            @endphp
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</x-container>
@endsection
