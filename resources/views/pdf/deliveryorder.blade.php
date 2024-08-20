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
        <h1>Delivery Orders</h1>
        <hr>

        <br>


        <!-- tabel list data-->
        <table style="width: 100%; border: 1px solid black;">
            <tr>
                <th>Nomor</th>
                <th>Delivery Date</th>
                <th>Project</th>
                <th>Register</th>
                <th>Delivery Status</th>
                <th>Note</th>
            </tr>

            @foreach ($deliveryorders as $i => $do)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $do->delivery_date }}</td>
                    <td>{{ $do->project->project_name }}</td>
                    <td>{{ $do->register }}</td>
                    <td>{{ $do->delivery_status }}</td>
                    <td>{{ $do->note }}</td>
                </tr>
            @endforeach
        </table>
</body>
</html>

