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
                <th>Nomor</th>
                <th>Supplier</th>
                <th>Purchase Deadline</th>
                <th>Register</th>
                <th>Purchase Date</th>
                <th>Note</th>
                <th>Status</th>
            </tr>

            @foreach ($purchases as $i => $p)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $p->partner->partner_name }}</td>
                    <td>{{ $p->purchase_deadline }}</td>
                    <td>{{ $p->register }}</td>
                    <td>{{ $p->purchase_date }}</td>
                    <td>{{ $p->note }}</td>
                    <td>{{ $p->purchase_status }}</td>
                </tr>
            @endforeach
        </table>
</body>
</html>

