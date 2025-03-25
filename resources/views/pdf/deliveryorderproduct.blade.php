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
    <h3>NOTA PENGIRIMAN</h3>
    <hr>

    <h3>Data Transaksi</h3>
    <table>
        <tr>
            <th class="border border-1 border-secondary" style="width: 25%">SKU</th>
            <td class="border border-1 border-secondary">{{ $deliveryorder->register }}</td>
        </tr>
        <tr>
            <th class="border border-1 border-secondary" style="width: 25%">Tanggal Pengantaran</th>
            <td class="border border-1 border-secondary">{{ $deliveryorder->delivery_date }}</td>
        </tr>
        <tr>
            <th class="border border-1 border-secondary" style="width: 25%">Tanggal Modifikasi</th>
            <td class="border border-1 border-secondary">{{ $deliveryorder->updated_at }}</td>
        </tr>
        <tr>
            <th class="border border-1 border-secondary" style="width: 25%">Projek</th>
            <td class="border border-1 border-secondary">{{ $deliveryorder->project->project_name }}</td>
        </tr>

    </table>

    {{-- tabel list data--}}

    <h3 style="margin-top: 40px;">List Barang</h3>
    <table>
        <tr>
            <th class="border border-1 border-secondary">Nomor</th>
            <th class="border border-1 border-secondary">Nama Produk</th>
            <th class="border border-1 border-secondary">SKU Produk</th>
            <th class="border border-1 border-secondary">Quantity</th>
            <th class="border border-1 border-secondary">Variant</th>
        </tr>

        @foreach ($do as $deliveryorder_product)
            <tr>
                <td class="border border-1 border-secondary" >{{ $loop->iteration }}</td>
                <td class="border border-1 border-secondary" >{{ $deliveryorder_product->product->product_name }}</td>
                <td class="border border-1 border-secondary" >{{ $deliveryorder_product->product->product_code }}</td>
                <td class="border border-1 border-secondary" >{{ $deliveryorder_product->quantity }}</td>
                <td class="border border-1 border-secondary" >{{ $deliveryorder_product->product->variant }}</td>
            </tr>

        @endforeach

    </table>

    <div style="width: 100%; text-align: right; margin-top: 30px; border: solid 1px black; padding: 0.5rem 0; font-weight: bold; font-size: 16pt;">
        <span>
            Total Items:
        </span>
        <span>
            {{ $do->count() }}
        </span>
    </div>

</body>
</html>

