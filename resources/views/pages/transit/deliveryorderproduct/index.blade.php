@extends('layouts.main-admin')

@section("content")
    <x-container>
        <br>
        <div class="w-100 d-flex align-items-center justify-content-between">
            <h3>Daftar Barang Pengiriman</h3>

            @if(!in_array(Auth::user()->role->role_name, ['gudang', 'subgudang', 'project_manager']))
                <div class="d-flex gap-3">
                    {{-- <a class="btn btn-secondary" href="{{ route('deliveryorderproduct-import', $deliveryorder->id) }}"><i class="bi bi-file-earmark-arrow-down"></i> Import</a> --}}
                    <div class="position-relative d-flex flex-column align-items-end">
                        <button class="btn btn-secondary" type="button" id="dd-toggler">
                            <i class="bi bi-file-earmark-arrow-up"></i> Export
                        </button>
                        <div class="bg-white rounded-lg position-absolute z-2 border border-1" id="dd-menu" style="display: none; top: 40px;">
                            <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("deliveryorderproduct-export", [$deliveryorder->id, 2]) }}" target="blank">Export (PDF Portrait)</a></li>
                            <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("deliveryorderproduct-export", [$deliveryorder->id, 1]) }}" target="blank">Export (PDF Landscape)</a></li>
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

        <hr>

        @if (session()->has("successAddProduct"))
            <p class="text-success fw-bold">{{ session("successAddProduct") }}</p>
        @elseif (session()->has("successEditProduct"))
            <p class="text-success fw-bold">{{ session("successEditProduct") }}</p>
        @elseif (session()->has("successDeleteProduct"))
            <p class="text-success fw-bold">{{ session("successDeleteProduct") }}</p>
        @endif

        {{-- @if(!in_array(Auth::user()->role->role_name, ['gudang', 'subgudang', 'project_manager']))
            <a href="{{ route('deliveryorderproduct-create1', $deliveryorder->id) }}" class="btn btn-primary text-white mb-3" style="font-size: 10pt">
                <i class="bi bi-plus-square"></i> Tambah Barang ke Pengiriman
            </a>
        @endif --}}

        <h5>Data Pengiriman</h5>
        <table class="w-100">
            <tr>
                <th class="border border-1 border-secondary w-25">SKU</th>
                <td class="border border-1 border-secondary">{{ $deliveryorder->register }}</td>
            </tr>
            <tr>
                <th class="border border-1 border-secondary w-25">Proyek</th>
                <td class="border border-1 border-secondary">{{ $deliveryorder->project->project_name }}</td>
            </tr>
            <tr>
                <th class="border border-1 border-secondary w-25">Tanggal</th>
                <td class="border border-1 border-secondary">{{ Carbon\Carbon::parse($deliveryorder->delivery_date)->format('d M Y') }}</td>
            </tr>
            <tr>
                <th class="border border-1 border-secondary w-25">Status</th>
                <td class="border border-1 border-secondary">{{ $deliveryorder->delivery_status }}</td>
            </tr>
        </table>

        {{-- tabel list data--}}
        <h5 class="mt-4">Daftar Barang</h5>
        <div class="overflow-x-auto">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary">No</th>
                    <th class="border border-1 border-secondary">Nama Produk</th>
                    <th class="border border-1 border-secondary">Varian</th>
                    <th class="border border-1 border-secondary">SKU Produk</th>
                    <th class="border border-1 border-secondary">Harga</th>
                    <th class="border border-1 border-secondary">Diskon</th>
                    <th class="border border-1 border-secondary">Harga Diskon</th>
                    <th class="border border-1 border-secondary">Jumlah</th>
                    <th class="border border-1 border-secondary">Subtotal</th>
                    {{-- @if(!in_array(Auth::user()->role->role_name, ['gudang', 'subgudang', 'project_manager']))
                        <th class="border border-1 border-secondary">Aksi</th>
                    @endif --}}
                </tr>

                @php
                    $total = 0;
                @endphp

                @foreach ($do as $deliveryorder_product)
                    @php
                        $prod = $deliveryorder_product->product;

                        $disc_price = ((100 - $prod->discount) / 100) * $prod->price;
                        $subtotal = $disc_price * $deliveryorder_product->quantity;
                        $total += $subtotal;
                    @endphp

                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td class="border border-1 border-secondary">{{ $loop->iteration }}</td>
                        <td class="border border-1 border-secondary">{{ $prod->product_name }} @if($prod->is_returned == 'yes'){{ __('- Returned') }}@endif</td>
                        <td class="border border-1 border-secondary">{{ $prod->variant }}</td>
                        <td class="border border-1 border-secondary">{{ $prod->product_code }}</td>
                        <td class="border border-1 border-secondary">{{ number_format($prod->price, 0, ',', '.') }}</td>
                        <td class="border border-1 border-secondary">{{ $prod->discount }}%</td>
                        <td class="border border-1 border-secondary">{{ number_format($disc_price, 0, ',', '.') }}</td>
                        <td class="border border-1 border-secondary">{{ $deliveryorder_product->quantity }}</td>
                        <td class="border border-1 border-secondary">{{ number_format($subtotal, 0, ',', '.') }}</td>
                        {{-- @if(!in_array(Auth::user()->role->role_name, ['gudang', 'subgudang', 'project_manager']))
                            <td class="border border-1 border-secondary">
                                <div class="d-flex gap-5 w-100">
                                    <form action="{{ route("deliveryorderproduct-destroy", [$deliveryorder->id, $deliveryorder_product->id] ) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-danger text-white" style="font-size: 10pt " onclick="return confirm('Apakah anda yakin ingin menghapus barang ini dari pengiriman ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        @endif --}}
                    </tr>

                @endforeach
            </table>

            <div class="d-flex h-100 w-100 justify-content-end gap-3 x-2 fw-bold mt-4" style="font-size: 16pt;">
                <div>
                    Total:
                </div>
                <div>
                    {{ number_format($total, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </x-container>
@endsection
