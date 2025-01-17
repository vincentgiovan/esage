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
        <h2>Projects</h2>
        <hr>

        <br>


        <!-- tabel list data-->
        <table style="width: 100%; border: 1px solid black;">
            <tr>
                <th>Nomor</th>
                <th>Nama Project</th>
                <th>Location</th>
                <th>PIC Name</th>
                <th>Address</th>
            </tr>

            @foreach ($projects as $i => $p)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $p->project_name }}</td>
                    <td>{{ $p->location }}</td>
                    <td>{{ $p->PIC }}</td>
                    <td>{{ $p->address }}</td>
                </tr>
            @endforeach
        </table>
</body>
</html>

