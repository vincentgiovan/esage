@extends('layouts.main-admin')

@section('content')
    <x-container>
        <div class="w-100 d-flex align-items-center justify-content-between">
            <h1 class="mt-4">Product Transaction Log</h1>
        </div>

        <hr class="mt-2">

        <br>

        @php
            $total = 0;
        @endphp

        <!-- tabel list data-->

        @if($project->delivery_orders->count())
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary w-25">Project name</th>
                    <td class="border border-1 border-secondary">{{ $project->project_name }}</td>
                </tr>
                <tr>
                    <th class="border border-1 border-secondary w-25">Project PIC</th>
                    <td class="border border-1 border-secondary">{{ $project->PIC }}</td>
                </tr>
                <tr>
                    <th class="border border-1 border-secondary w-25">Project location</th>
                    <td class="border border-1 border-secondary">{{ $project->location }}</td>
                </tr>
                <tr>
                    <th class="border border-1 border-secondary w-25">Project address</th>
                    <td class="border border-1 border-secondary">{{ $project->address }}</td>
                </tr>
            </table>
        @endif

        <div class="overflow-x-auto mt-5">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary ">Nomor</th>
                    <th class="border border-1 border-secondary ">SKU Delivery Order</th>
                    <th class="border border-1 border-secondary ">SKU Produk</th>
                    <th class="border border-1 border-secondary ">Nama Produk</th>
                    <th class="border border-1 border-secondary ">Varian</th>
                    <th class="border border-1 border-secondary ">Harga Akhir</th>
                    <th class="border border-1 border-secondary ">Qty</th>
                </tr>

                @foreach ($project->delivery_orders as $do)
                    @foreach($do->products as $prd)
                        {{-- @php
                            $disc_price = ((100 - $pp->discount) / 100) * $pp->price;
                            $subtotal = $disc_price * $pp->quantity;
                            $total += $subtotal;
                        @endphp --}}
                        @php
                            $dop = App\Models\DeliveryOrderProduct::where("product_id", $prd->id)->where("delivery_order_id", $do->id)->first()
                        @endphp
                        <tr>
                            <td class="border border-1 border-secondary ">{{ $loop->iteration }}</td>
                            <td class="border border-1 border-secondary ">{{ $do->register }}</td>
                            <td class="border border-1 border-secondary ">{{ $prd->product_code }}</td>
                            <td class="border border-1 border-secondary ">{{ $prd->product_name }}</td>
                            <td class="border border-1 border-secondary ">{{ $prd->variant }}</td>
                            <td class="border border-1 border-secondary "></td>
                            <td class="border border-1 border-secondary ">{{ $dop->quantity }}</td>
                        </tr>
                    @endforeach
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
