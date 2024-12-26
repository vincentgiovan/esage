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
        <h2>Partners</h2>
        <hr>

        <br>


        <!-- tabel list data-->
        <table style="width: 100%; border: 1px solid black;">
            <tr>
                <th>Nomor</th>
                <th>Nama Partner</th>
                <th>Partner Role</th>
                <th>Remark</th>
                <th>Address</th>
                <th>Contact</th>
                <th>Phone</th>
                <th>Fax</th>
                <th>Email</th>
                <th>Tempo</th>
            </tr>

            @foreach ($partners as $i => $p)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $p->partner_name }}</td>
                    <td>{{ $p->role }}</td>
                    <td>{{ $p->remark }}</td>
                    <td>{{ $p->address }}</td>
                    <td>{{ $p->contact }}</td>
                    <td>{{ $p->phone }}</td>
                    <td>{{ $p->fax }}</td>
                    <td>{{ $p->email }}</td>
                    <td>{{ $p->tempo }}</td>
                </tr>
            @endforeach
        </table>
</body>
</html>

