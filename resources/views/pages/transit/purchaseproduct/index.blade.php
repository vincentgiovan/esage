@extends('layouts.main-admin')

@section("content")
<x-container>
    <br>
    <div class="w-100 d-flex align-items-center justify-content-between">
        <h1>All Products in {{ $purchase->register }}</h1>
        <div class="d-flex gap-3">
            <a class="btn btn-secondary" href="{{ route('purchaseproduct-import', $purchase->id) }}"><i class="bi bi-file-earmark-arrow-down"></i> Import</a>
            <div class="position-relative d-flex flex-column align-items-end">
                <button class="btn btn-secondary" type="button" id="dd-toggler">
                    <i class="bi bi-file-earmark-arrow-up"></i> Export
                </button>
                <div class="bg-white rounded-lg position-absolute z-2 border border-1" id="dd-menu" style="display: none; top: 40px;">
                    <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("purchaseproduct-export", [$purchase->id, 2]) }}" target="blank">Export (PDF Portrait)</a></li>
                    <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("purchaseproduct-export", [$purchase->id, 1]) }}" target="blank">Export (PDF Landscape)</a></li>
                </div>
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
                <th>No</th>
                <th>Nama Produk </th>
                <th>SKU Produk </th>
                <th>Harga Beli</th>
                <th>Quantity</th>
                <th>Diskon</th>
                <th>Harga Setelah Diskon</th>
                <th>Variant</th>
                <th>Action</th>
            </tr>

            @foreach ($pp as $purchase_product)
                <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $purchase_product->product->product_name }}</td>
                    <td>{{ $purchase_product->product->product_code }}</td>
                    <td>Rp {{ number_format($purchase_product->product->price, 2, ',' , '.') }}</td>
                    <td>{{ $purchase_product->quantity }}</td>
                    <td>{{ $purchase_product->product->discount }}%</td>
                    <td>Rp {{ number_format($purchase_product->product->price * (1 - ($purchase_product->product->discount / 100)), 2, ',' , '.') }}</td>
                    {{-- <td>{{ $purchase_product->product->markup }}%</td>
                    <td>Rp {{ $purchase_product->price * (1 + ($purchase_product->product->markup / 100)) }},00</td> --}}
                    <td>{{ $purchase_product->product->variant }}</td>

                    {{-- <td>{{ $p->user->name }}</td> --}}
                    <td>
                        <div class="d-flex gap-5 w-100">
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
        <div class="d-flex h-100 w-100 justify-content-end gap-3 x-2" style="font-size: 14pt; background: linear-gradient(to right, rgb(113, 113, 113), rgb(213, 207, 207));">
            <div>
                Total:
            </div>
            <div>
                @php
                    $total = 0;
                    foreach ($pp as $purchase_product){
                        $total += $purchase_product->product->price * (1 - ($purchase_product->product->discount / 100));
                    }

                    echo "Rp " . number_format($total, 2, ',' , '.');
                @endphp
            </div>
        </div>
    </div>
</x-container>
@endsection
