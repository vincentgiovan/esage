@extends('layouts.main-admin')

@section("content")
<x-container>
    <br>
    <div class="w-100 d-flex align-items-center justify-content-between">
        <h3>Daftar Barang di Pembelian {{ $purchase->register }}</h3>
        <div class="d-flex gap-3">
            {{-- <a class="btn btn-secondary" href="{{ route('purchaseproduct-import', $purchase->id) }}"><i class="bi bi-file-earmark-arrow-down"></i> Import</a> --}}
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

    @if (session()->has("successAddProduct"))
        <p class="text-success fw-bold">{{ session("successAddProduct") }}</p>
    @elseif (session()->has("successEditProduct"))
        <p class="text-success fw-bold">{{ session("successEditProduct") }}</p>
    @elseif (session()->has("successDeleteProduct"))
        <p class="text-success fw-bold">{{ session("successDeleteProduct") }}</p>
    @endif

    {{-- <a href="{{ route('purchaseproduct-create1', $purchase->id) }}" class="btn btn-primary text-white mb-3" style="font-size: 10pt">
        <i class="bi bi-plus-square"></i> Tambah Barang ke Pembelian
    </a>
    <a href="{{ route('purchaseproduct-create2', $purchase->id) }}" class="btn btn-success text-white mb-3" style="font-size: 10pt">
        <i class="bi bi-plus-square"></i> Tambah dan Buat Data Barang Baru
    </a>
    <br> --}}

    <h5>Data Pembelian</h5>
    <table class="w-100">
        <tr>
            <th class="border border-1 border-secondary w-25">SKU</th>
            <td class="border border-1 border-secondary">{{ $purchase->register }}</td>
        </tr>
        <tr>
            <th class="border border-1 border-secondary w-25">Partner</th>
            <td class="border border-1 border-secondary">{{ $purchase->partner->partner_name }}</td>
        </tr>
        <tr>
            <th class="border border-1 border-secondary w-25">Tanggal Pembelian</th>
            <td class="border border-1 border-secondary">{{ Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}</td>
        </tr>
        <tr>
            <th class="border border-1 border-secondary w-25">Status</th>
            <td class="border border-1 border-secondary">{{ $purchase->purchase_status }}</td>
        </tr>
    </table>

    {{-- tabel list data--}}
    <h5 class="mt-4">Daftar Barang</h5>
    <div class="overflow-x-auto">
        <table class="w-100">
            <tr>
                <th class="border border-1 border-secondary">No</th>
                <th class="border border-1 border-secondary">Nama Produk </th>
                <th class="border border-1 border-secondary">SKU Produk </th>
                <th class="border border-1 border-secondary">Harga Beli</th>
                <th class="border border-1 border-secondary">Quantity</th>
                <th class="border border-1 border-secondary">Diskon</th>
                <th class="border border-1 border-secondary">Harga Setelah Diskon</th>
                <th class="border border-1 border-secondary">Variant</th>
                {{-- <th class="border border-1 border-secondary">Aksi</th> --}}
            </tr>

            @foreach ($pp as $purchase_product)
                <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                    <td class="border border-1 border-secondary">{{ $loop->iteration }}</td>
                    <td class="border border-1 border-secondary">{{ $purchase_product->product->product_name }}</td>
                    <td class="border border-1 border-secondary">{{ $purchase_product->product->product_code }}</td>
                    <td class="border border-1 border-secondary">{{ number_format($purchase_product->product->price, 0, ',', '.') }}</td>
                    <td class="border border-1 border-secondary">{{ $purchase_product->quantity }}</td>
                    <td class="border border-1 border-secondary">{{ $purchase_product->product->discount }}%</td>
                    <td class="border border-1 border-secondary">{{ number_format($purchase_product->product->price * (1 - ($purchase_product->product->discount / 100)), 0, ',', '.') }}</td>
                    {{-- <td class="border border-1 border-secondary">{{ $purchase_product->product->markup }}%</td>
                    <td class="border border-1 border-secondary">{{ $purchase_product->price * (1 + ($purchase_product->product->markup / 100)) }},00</td> --}}
                    <td class="border border-1 border-secondary">{{ $purchase_product->product->variant }}</td>

                    {{-- <td class="border border-1 border-secondary">{{ $p->user->name }}</td> --}}
                    {{-- <td class="border border-1 border-secondary">
                        <div class="d-flex gap-5 w-100">
                            <form action="{{ route("purchaseproduct-destroy", [$purchase->id, $purchase_product->id] ) }}" method="POST">
                                @csrf
                                <button class="btn btn-danger text-white" style="font-size: 10pt " onclick="return confirm('Apakah anda yakin ingin menghapus barang ini dari pembelian ini?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td> --}}
                </tr>

            @endforeach
        </table>

        <div class="d-flex h-100 w-100 justify-content-end gap-3 x-2 fw-bold mt-4" style="font-size: 16pt;">
            <div>
                Total:
            </div>
            <div>
                @php
                    $total = 0;
                    foreach ($pp as $purchase_product){
                        $total += $purchase_product->product->price * (1 - ($purchase_product->product->discount / 100));
                    }

                    echo  number_format($total, 0, ',', '.');
                @endphp
            </div>
        </div>
    </div>
</x-container>
@endsection
