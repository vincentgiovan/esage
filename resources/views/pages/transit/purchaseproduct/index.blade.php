@extends('layouts.main-admin')

@section("content")
<x-container>
    <br>
    <div class="w-100 d-flex align-items-center justify-content-between">
        <h1>All Products in {{ $purchase->register }}</h1>
        <div class="position-relative d-flex flex-column align-items-end">
            <button class="btn btn-secondary" type="button" id="dd-toggler">
                <i class="bi bi-file-earmark-arrow-up"></i> Export
            </button>
            <div class="bg-white rounded-lg position-absolute z-2 border border-1" id="dd-menu" style="display: none; top: 40px;">
                <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("purchaseproduct-export", [$purchase->id, 2]) }}" target="blank">Export (PDF Portrait)</a></li>
                <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("purchaseproduct-export", [$purchase->id, 1]) }}" target="blank">Export (PDF Landscape)</a></li>
            </div>{{ w{{  }}
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
                <th class="border border-1 border-secondary ">Nomor</th>
                <th class="border border-1 border-secondary ">Nama Produk </th>
                <th class="border border-1 border-secondary ">SKU Produk </th>
                <th class="border border-1 border-secondary ">Harga Beli</th>
                <th class="border border-1 border-secondary ">Quantity</th>
                <th class="border border-1 border-secondary ">Diskon</th>
                <th class="border border-1 border-secondary ">Harga Setelah Diskon</th>
                <th class="border border-1 border-secondary ">Variant</th>
                <th class="border border-1 border-secondary ">Action</th>
            </tr>

            @foreach ($pp as $purchase_product)
                <tr>
                    <td class="border border-1 border-secondary " >{{ $loop->iteration }}</td>
                    <td class="border border-1 border-secondary " >{{ $purchase_product->product->product_name }}</td>
                    <td class="border border-1 border-secondary " >{{ $purchase_product->product->product_code }}</td>
                    <td class="border border-1 border-secondary " >Rp {{ number_format($purchase_product->price, 2, ',' , '.') }}</td>
                    <td class="border border-1 border-secondary " >{{ $purchase_product->quantity }}</td>
                    <td class="border border-1 border-secondary " >{{ $purchase_product->discount }}%</td>
                    <td class="border border-1 border-secondary " >Rp {{ number_format($purchase_product->price * (1 - ($purchase_product->discount / 100)), 2, ',' , '.') }}</td>
                    {{-- <td class="border border-1 border-secondary " >{{ $purchase_product->product->markup }}%</td>
                    <td class="border border-1 border-secondary " >Rp {{ $purchase_product->price * (1 + ($purchase_product->product->markup / 100)) }},00</td> --}}
                    <td class="border border-1 border-secondary " >{{ $purchase_product->product->variant }}</td>

                    {{-- <td class="border border-1 border-secondary " >{{ $p->user->name }}</td> --}}
                    <td class="border border-1 border-secondary " >
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


        </table>
        <div class="d-flex h-100 w-100 justify-content-end gap-3 fw-bold border border-1 border-secondary px-2" style="font-size: 14pt; background: linear-gradient(to right, rgb(113, 113, 113), rgb(213, 207, 207));">
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
    </div>
</x-container>
@endsection
