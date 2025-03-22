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
            <th style="width: 25%">SKU</th>
            <td>{{ $deliveryorder->register }}</td>
        </tr>
        <tr>
            <th style="width: 25%">Tanggal Pengantaran</th>
            <td>{{ $deliveryorder->delivery_date }}</td>
        </tr>
        <tr>
            <th style="width: 25%">Tanggal Modifikasi</th>
            <td>{{ $deliveryorder->updated_at }}</td>
        </tr>
        <tr>
            <th style="width: 25%">Projek</th>
            <td>{{ $deliveryorder->project->project_name }}</td>
        </tr>

    </table>

    {{-- tabel list data--}}

    <h3 style="margin-top: 40px;">List Barang</h3>
    <table>
        <tr>
            <th>Nomor</th>
            <th>Nama Produk</th>
            <th>SKU Produk</th>
            <th>Quantity</th>
            <th>Variant</th>
        </tr>

        @foreach ($do as $deliveryorder_product)
            <tr>
                <td >{{ $loop->iteration }}</td>
                <td >{{ $deliveryorder_product->product->product_name }}</td>
                <td >{{ $deliveryorder_product->product->product_code }}</td>
                <td >{{ $deliveryorder_product->quantity }}</td>
                <td >{{ $deliveryorder_product->product->variant }}</td>
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

