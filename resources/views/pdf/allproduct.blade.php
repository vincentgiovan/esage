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
        <h1>Warehouse Items</h1>
        <hr>

        <br>


        <!-- tabel list data-->
        <table style="width: 100%; border: 1px solid black;">
            <tr>
                <th>Nomor</th>
                <th>Nama Produk </th>
                <th>SKU Produk </th>
                <th>Stok</th>
                <th>Harga</th>
                <th>Unit</th>
                <th>Variant</th>
                <th>Markup</th>
                <th>Status</th>
            </tr>

            @foreach ($products as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->product_name }}</td>
                    <td>{{ $p->product_code }}</td>
                    <td>{{ $p->stock }}</td>
                    <td>Rp {{ number_format($p->price, 2, ',', '.') }}</td>
                    <td>{{ $p->unit }}</td>
                    <td>{{ $p->variant }}</td>
                    <td>{{ $p->markup }}</td>
                    <td>{{ $p->status }}</td>
                    {{-- <td class="border border-1 border-dark " >{{ $p->user->name }}</td> --}}
                </tr>
            @endforeach
        </table>
</body>
</html>

