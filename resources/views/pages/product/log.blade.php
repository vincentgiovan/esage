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

        {{-- @if (session()->has('successAddProduct'))
            <p class="text-success fw-bold">{{ session('successAddProduct') }}</p>
        @elseif (session()->has('successEditProduct'))
            <p class="text-success fw-bold">{{ session('successEditProduct') }}</p>
        @elseif (session()->has('successDeleteProduct'))
            <p class="text-success fw-bold">{{ session('successDeleteProduct') }}</p>
        @endif --}}

        <br>

        <!-- tabel list data-->
        @php
            $total = 0;
        @endphp

        @if($purchaseproducts->count())
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary w-25">Product name</th>
                    <td class="border border-1 border-secondary">{{ $purchaseproducts[0]->product->product_name }}</td>

                </tr>
                <tr>
                    <th class="border border-1 border-secondary w-25">Product variant</th>
                    <td class="border border-1 border-secondary">{{ $purchaseproducts[0]->product->variant }}</td>
                </tr>
            </table>
        @endif

        <div class="overflow-x-auto mt-5">
            <table class="w-100">
                <tr>
                    <th>No</th>
                    <th>SKU Pembelian</th>
                    <th>SKU Produk</th>
                    <th>Harga</th>
                    <th>Diskon</th>
                    <th>Harga Diskon</th>
                    <th>Stok</th>
                    <th>Subtotal</th>
                </tr>

                @foreach ($purchaseproducts as $pp)
                    @php
                        $disc_price = ((100 - $pp->product->discount) / 100) * $pp->product->price;
                        $subtotal = $disc_price * $pp->quantity;
                        $total += $subtotal;
                    @endphp
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td>
                            @php
                                if(request("page")){
                                    echo $loop->iteration + ((request("page") - 1) * $n_pagination);
                                } else {
                                    echo $loop->iteration;
                                }

                            @endphp
                        </td>
                        <td>{{ $pp->purchase->register }}</td>
                        <td>{{ $pp->product->product_code }}</td>
                        <td>Rp {{ number_format($pp->product->price, 2, ',', '.') }}</td>
                        <td>{{ $pp->product->discount }}%</td>
                        <td>Rp {{ number_format($disc_price, 2, ',', '.') }}</td>
                        <td>{{ $pp->quantity }}</td>
                        <td>Rp {{ number_format($subtotal, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        {{-- <div class="mt-4">
            {{ $products->links() }}
        </div> --}}

        <div class="d-flex w-100 justify-content-end mt-4 gap-2 fs-4 fw-bold">
            <div class="">Total: </div>
            <div class="">Rp {{ number_format($total, 2, ',', '.') }}</div>
        </div>

    </x-container>
@endsection
