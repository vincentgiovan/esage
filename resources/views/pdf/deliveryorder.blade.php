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
        <h3>Delivery Orders</h3>
        <hr>

        <br>


        {{-- tabel list data--}}
        <table style="width: 100%; border: 1px solid black;">
            <tr>
                <th class="border border-1 border-secondary">Nomor</th>
                <th class="border border-1 border-secondary">Delivery Date</th>
                <th class="border border-1 border-secondary">Project</th>
                <th class="border border-1 border-secondary">Register</th>
                <th class="border border-1 border-secondary">Delivery Status</th>
                <th class="border border-1 border-secondary">Note</th>
            </tr>

            @foreach ($deliveryorders as $i => $do)
                <tr>
                    <td class="border border-1 border-secondary">{{ $i + 1 }}</td>
                    <td class="border border-1 border-secondary">{{ $do->delivery_date }}</td>
                    <td class="border border-1 border-secondary">{{ $do->project->project_name }}</td>
                    <td class="border border-1 border-secondary">{{ $do->register }}</td>
                    <td class="border border-1 border-secondary">{{ $do->delivery_status }}</td>
                    <td class="border border-1 border-secondary">{{ $do->note }}</td>
                </tr>
            @endforeach
        </table>
</body>
</html>

