@extends('layouts.main-admin')

@section('content')
    <x-container>
        <div class="w-100 d-flex align-items-center justify-content-between">
            <h1 class="mt-4">Partner Transaction Log</h1>
        </div>

        <hr class="mt-2">

        <br>

        @php
            $total = 0;
        @endphp

        <!-- tabel list data-->

        @if($partner->purchases->count())
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary w-25">Partner name</th>
                    <td class="border border-1 border-secondary">{{ $partner->partner_name }}</td>
                </tr>
                <tr>
                    <th class="border border-1 border-secondary w-25">Partner role</th>
                    <td class="border border-1 border-secondary">{{ $partner->role }}</td>
                </tr>
                <tr>
                    <th class="border border-1 border-secondary w-25">Partner address</th>
                    <td class="border border-1 border-secondary">{{ $partner->address }}</td>
                </tr>
                <tr>
                    <th class="border border-1 border-secondary w-25">Partner contacts</th>
                    <td class="border border-1 border-secondary">
                        <ul>
                            <li>Email: {{ $partner->email ?? "N/A" }}</li>
                            <li>Fax: {{ $partner->fax ?? "N/A" }}</li>
                            <li>Mobile/Telephone: {{ $partner->phone ?? "N/A" }}/{{ $partner->contact ?? "N/A" }}</li>
                        </ul>
                    </td>
                </tr>
            </table>
        @endif

        <div class="overflow-x-auto mt-5">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary ">Nomor</th>
                    <th class="border border-1 border-secondary ">SKU Pembelian</th>
                    <th class="border border-1 border-secondary ">SKU Produk</th>
                    <th class="border border-1 border-secondary ">Nama Produk</th>
                    <th class="border border-1 border-secondary ">Varian</th>
                    <th class="border border-1 border-secondary ">Harga Akhir</th>
                    <th class="border border-1 border-secondary ">Qty</th>
                </tr>

                @php
                    $i = 0;
                @endphp
                @foreach ($partner->purchases as $pchase)
                    @foreach($pchase->products as $prd)
                        @php
                            $pp = App\Models\PurchaseProduct::where("product_id", $prd->id)->where("purchase_id", $pchase->id)->first();
                            $disc_price = ((100 - $prd->discount) / 100) * $prd->price;
                            $subtotal = $disc_price * $pp->quantity;
                            $total += $subtotal;
                            $i++;
                        @endphp
                        <tr>
                            <td class="border border-1 border-secondary ">{{ $i }}</td>
                            <td class="border border-1 border-secondary ">{{ $pchase->register }}</td>
                            <td class="border border-1 border-secondary ">{{ $prd->product_code }}</td>
                            <td class="border border-1 border-secondary ">{{ $prd->product_name }}</td>
                            <td class="border border-1 border-secondary ">{{ $prd->variant }}</td>
                            <td class="border border-1 border-secondary ">Rp {{ number_format($subtotal, 2, ',', '.') }}<br><i style="font-weight: 600;">(Price: {{ $prd->price }}, disc: {{ $prd->discount }}%, qty: {{ $pp->quantity }})</i></td>
                            <td class="border border-1 border-secondary ">{{ $pp->quantity }}</td>
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