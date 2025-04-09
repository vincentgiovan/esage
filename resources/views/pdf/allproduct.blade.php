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
        <h3>Warehouse Items</h3>
        <hr>

        <br>


        {{-- tabel list data--}}
        <table style="width: 100%; border: 1px solid black;">
            <tr>
                <th class="border border-1 border-secondary">Nomor</th>
                <th class="border border-1 border-secondary">Nama Produk </th>
                <th class="border border-1 border-secondary">SKU Produk </th>
                <th class="border border-1 border-secondary">Stok</th>
                <th class="border border-1 border-secondary">Harga</th>
                <th class="border border-1 border-secondary">Unit</th>
                <th class="border border-1 border-secondary">Variant</th>
                <th class="border border-1 border-secondary">Markup</th>
                <th class="border border-1 border-secondary">Status</th>
            </tr>

            @foreach ($products as $p)
                <tr>
                    <td class="border border-1 border-secondary">{{ $loop->iteration }}</td>
                    <td class="border border-1 border-secondary">{{ $p->product_name }}</td>
                    <td class="border border-1 border-secondary">{{ $p->product_code }}</td>
                    <td class="border border-1 border-secondary">{{ $p->stock }}</td>
                    <td class="border border-1 border-secondary">{{ number_format($p->price, 0, ',', '.') }}</td>
                    <td class="border border-1 border-secondary">{{ $p->unit }}</td>
                    <td class="border border-1 border-secondary">{{ $p->variant }}</td>
                    <td class="border border-1 border-secondary">{{ $p->markup }}</td>
                    <td class="border border-1 border-secondary">{{ $p->status }}</td>
                    {{-- <td class="border border-1 border-secondary" class="border border-1 border-dark " >{{ $p->user->name }}</td> --}}
                </tr>
            @endforeach
        </table>
</body>
</html>

