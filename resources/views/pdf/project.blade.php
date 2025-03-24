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
        <h3>Projects</h3>
        <hr>

        <br>


        {{-- tabel list data--}}
        <table style="width: 100%; border: 1px solid black;">
            <tr>
                <th class="border border-1 border-secondary">Nomor</th>
                <th class="border border-1 border-secondary">Nama Project</th>
                <th class="border border-1 border-secondary">Location</th>
                <th class="border border-1 border-secondary">PIC Name</th>
                <th class="border border-1 border-secondary">Address</th>
            </tr>

            @foreach ($projects as $i => $p)
                <tr>
                    <td class="border border-1 border-secondary">{{ $i + 1 }}</td>
                    <td class="border border-1 border-secondary">{{ $p->project_name }}</td>
                    <td class="border border-1 border-secondary">{{ $p->location }}</td>
                    <td class="border border-1 border-secondary">{{ $p->PIC }}</td>
                    <td class="border border-1 border-secondary">{{ $p->address }}</td>
                </tr>
            @endforeach
        </table>
</body>
</html>

