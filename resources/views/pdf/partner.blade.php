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
        <h3>Partners</h3>
        <hr>

        <br>


        {{-- tabel list data--}}
        <table style="width: 100%; border: 1px solid black;">
            <tr>
                <th class="border border-1 border-secondary">Nomor</th>
                <th class="border border-1 border-secondary">Nama Partner</th>
                <th class="border border-1 border-secondary">Partner Role</th>
                <th class="border border-1 border-secondary">Remark</th>
                <th class="border border-1 border-secondary">Address</th>
                <th class="border border-1 border-secondary">Contact</th>
                <th class="border border-1 border-secondary">Phone</th>
                <th class="border border-1 border-secondary">Fax</th>
                <th class="border border-1 border-secondary">Email</th>
                <th class="border border-1 border-secondary">Tempo</th>
            </tr>

            @foreach ($partners as $i => $p)
                <tr>
                    <td class="border border-1 border-secondary">{{ $i + 1 }}</td>
                    <td class="border border-1 border-secondary">{{ $p->partner_name }}</td>
                    <td class="border border-1 border-secondary">{{ $p->role }}</td>
                    <td class="border border-1 border-secondary">{{ $p->remark }}</td>
                    <td class="border border-1 border-secondary">{{ $p->address }}</td>
                    <td class="border border-1 border-secondary">{{ $p->contact }}</td>
                    <td class="border border-1 border-secondary">{{ $p->phone }}</td>
                    <td class="border border-1 border-secondary">{{ $p->fax }}</td>
                    <td class="border border-1 border-secondary">{{ $p->email }}</td>
                    <td class="border border-1 border-secondary">{{ $p->tempo }}</td>
                </tr>
            @endforeach
        </table>
</body>
</html>

