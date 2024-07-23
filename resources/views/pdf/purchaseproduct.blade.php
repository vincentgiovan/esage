<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>NOTA PEMBELIAN</h2>
    <hr>

    <h3>Data Transaksi</h3>
    <table>
        <tr>
            <th style="width: 25%">SKU</th>
            <td>{{ $purchase->register }}</td>
        </tr>
        <tr>
            <th style="width: 25%">Tanggal Pembelian</th>
            <td>{{ $purchase->purchase_date }}</td>
        </tr>
        <tr>
            <th style="width: 25%">Deadline Pembelian</th>
            <td>{{ $purchase->purchase_deadline }}</td>
        </tr>
        <tr>
            <th style="width: 25%">Tanggal Modifikasi</th>
            <td>{{ $purchase->updated_at }}</td>
        </tr>
        <tr>
            <th style="width: 25%">Partner</th>
            <td>{{ $purchase->partner->partner_name }}</td>
        </tr>

    </table>

    <!-- tabel list data-->

    <h3 style="margin-top: 40px;">List Barang</h3>
    <table>
        <tr>
            <th>No</th>
            <th>Nama Produk </th>
            <th>SKU Produk </th>
            <th>Harga Beli</th>
            <th>Qty</th>
            <th>Disc</th>
            <th>Harga Setelah Diskon</th>
            <th>Variant</th>
        </tr>

        @foreach ($pp as $purchase_product)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $purchase_product->product->product_name }}</td>
                <td>{{ $purchase_product->product->product_code }}</td>
                <td>Rp {{ number_format($purchase_product->price, 2, ',' , '.') }}</td>
                <td>{{ $purchase_product->quantity }}</td>
                <td>{{ $purchase_product->discount }}%</td>
                <td>Rp {{ number_format($purchase_product->price * (1 - ($purchase_product->discount / 100)), 2, ',' , '.') }}</td>
                {{-- <td>{{ $purchase_product->product->markup }}%</td>
                <td>Rp {{ $purchase_product->price * (1 + ($purchase_product->product->markup / 100)) }},00</td> --}}
                <td>{{ $purchase_product->product->variant }}</td>

                {{-- <td>{{ $p->user->name }}</td> --}}
            </tr>

        @endforeach

    </table>

    <div style="width: 100%; text-align: right; margin-top: 30px; border: solid 1px black; padding: 0.5rem 0; font-weight: bold; font-size: 16pt;">
        <span>
            Total Purchase:
        </span>
        <span>
            @php
                $total = 0;
                foreach ($pp as $purchase_product){
                    $total += $purchase_product->price * (1 - ($purchase_product->discount / 100));
                }

                echo "Rp " . number_format($total, 2, ',' , '.');
            @endphp
        </span>
    </div>

</body>
</html>

