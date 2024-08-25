<!DOCTYPE html>
<html>
<head>
    <title>Upload CSV</title>
</head>
<body>
    @if (session('success'))
        <div style="color: green;">
            {{ session('success') }}
        </div>
    @endif

    <h1>Import User Data</h1>
    <p>Please upload in .csv file format.</p>

    <form action="{{ route('product-import-store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="csv_file" accept=".csv" />
        <button type="submit">Upload CSV</button>
    </form>
</body>
</html>
