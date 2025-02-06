@extends('layouts.main-admin')

@section('content')
    <x-container>
        <div class="w-100 d-flex align-items-center justify-content-between">
            <h2 class="mt-4">Daftar Pengembalian Barang</h2>
        </div>

        <hr class="mt-2">

        @php
            $total = 0;
        @endphp

        <!-- tabel list data-->

        <h5>Data Proyek</h5>
        <table class="w-100">
            <tr>
                <th class="border border-1 border-secondary w-25">Nama Proyek</th>
                <td class="border border-1 border-secondary">{{ $project->project_name }}</td>
            </tr>
            <tr>
                <th class="border border-1 border-secondary w-25">PIC</th>
                <td class="border border-1 border-secondary">{{ $project->PIC }}</td>
            </tr>
            <tr>
                <th class="border border-1 border-secondary w-25">Lokasi</th>
                <td class="border border-1 border-secondary">{{ $project->location }}</td>
            </tr>
            <tr>
                <th class="border border-1 border-secondary w-25">Alamat</th>
                <td class="border border-1 border-secondary">{{ $project->address }}</td>
            </tr>
            <tr>
                <th class="border border-1 border-secondary w-25">Nomor RAB</th>
                <td class="border border-1 border-secondary">{{ $project->RAB }}</td>
            </tr>
        </table>

        <h5 class="mt-4">Daftar Barang</h5>
        <div class="overflow-x-auto">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary ">No</th>
                    <th class="border border-1 border-secondary ">Tanggal Pengembalian</th>
                    <th class="border border-1 border-secondary ">SKU Produk</th>
                    <th class="border border-1 border-secondary ">Nama Produk</th>
                    <th class="border border-1 border-secondary ">Varian</th>
                    {{-- <th class="border border-1 border-secondary ">Harga Akhir</th> --}}
                    <th class="border border-1 border-secondary ">Jumlah</th>
                </tr>

                @forelse ($return_items as $ri)
                    @foreach($ri->return_item_products as $rip)
                        @php
                            $disc_price = ((100 - $rip->product->discount) / 100) * $rip->product->price;
                            // $subtotal = $disc_price * $dop->quantity;
                            // $total += $subtotal;
                        @endphp
                        <tr>
                            <td class="border border-1 border-secondary ">{{ $loop->iteration }}</td>
                            <td class="border border-1 border-secondary ">{{ Carbon\Carbon::parse($ri->return_date)->translatedFormat('d M Y') }}</td>
                            <td class="border border-1 border-secondary ">{{ $rip->product->product_code }}</td>
                            <td class="border border-1 border-secondary ">{{ $rip->product->product_name }}</td>
                            <td class="border border-1 border-secondary ">{{ $rip->product->variant }}</td>
                            {{-- <td class="border border-1 border-secondary ">Rp {{ number_format($subtotal, 2, ',', '.') }}<br><i style="font-weight: 600;">(Price: {{ $rip->product->price }}, disc: {{ $rip->product->discount }}%, qty: {{ $dop->quantity }})</i></td> --}}
                            <td class="border border-1 border-secondary ">{{ $rip->qty }}</td>
                        </tr>
                    @endforeach
                @empty
                @endforelse
            </table>
        </div>
        {{-- <div class="mt-4">
            {{ $products->links() }}
        </div> --}}

        {{-- <div class="d-flex w-100 justify-content-end mt-4 gap-2 fs-4 fw-bold">
            <div class="">Total: </div>
            <div class="">Rp {{ number_format($total, 2, ',', '.') }}</div>
        </div> --}}

    </x-container>
@endsection
