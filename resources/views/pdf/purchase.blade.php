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
        <h3>Purchases</h3>
        <hr>

        <br>


        {{-- tabel list data--}}
        <table style="width: 100%; border: 1px solid black;">
            <tr>
                <th class="border border-1 border-secondary">Nomor</th>
                <th class="border border-1 border-secondary">Supplier</th>
                <th class="border border-1 border-secondary">Purchase Deadline</th>
                <th class="border border-1 border-secondary">Register</th>
                <th class="border border-1 border-secondary">Purchase Date</th>
                <th class="border border-1 border-secondary">Note</th>
                <th class="border border-1 border-secondary">Status</th>
            </tr>

            @foreach ($purchases as $i => $p)
                <tr>
                    <td class="border border-1 border-secondary">{{ $i + 1 }}</td>
                    <td class="border border-1 border-secondary">{{ $p->partner->partner_name }}</td>
                    <td class="border border-1 border-secondary">{{ $p->purchase_deadline }}</td>
                    <td class="border border-1 border-secondary">{{ $p->register }}</td>
                    <td class="border border-1 border-secondary">{{ $p->purchase_date }}</td>
                    <td class="border border-1 border-secondary">{{ $p->note }}</td>
                    <td class="border border-1 border-secondary">{{ $p->purchase_status }}</td>
                </tr>
            @endforeach
        </table>
</body>
</html>

