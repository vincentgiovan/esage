<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>eSage</title>

        <!-- Import bootstrap class -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

        <!-- Bootstrap icons -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="style.css">

        <!-- Custom styles -->
        <style>
            body{
                background-color: rgb(224, 224, 224)
            }

            /* change button color on hover with style */
            /* #susbtn:hover {
                background-color: gray;
                border-color: gray;
            } */
        </style>
    </head>

    <body>
        <div style="min-height: 100vh;">
            <!-- Navbar -->
            @auth
                @include("component.navbar")
            @endauth

            <!-- Main content -->
            <div class="px-4 py-2">
                @yield("content")
            </div>
        </div>

        <!-- Import bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>
