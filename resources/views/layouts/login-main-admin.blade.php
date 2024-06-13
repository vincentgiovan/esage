<!doctype html>
<html lang="en">
    <head>
        <title>eSage</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="stylesheet" href="{{ asset("vendors/ti-icons/css/themify-icons.css") }}">
        <link rel="stylesheet" href="{{ asset("template/vendors/base/vendor.bundle.base.css") }}">

        <link rel="stylesheet" href="{{ asset("template/css/style.css") }}">

        <link rel="shortcut icon" href="{{ asset("res/sageico.ico") }}" />

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="style.css">

        <!-- Custom styles -->
        <style>
            body{
                background-color: rgb(209, 209, 209)
            }

            /* change button color on hover with style */
            /* #susbtn:hover {
                background-color: gray;
                border-color: gray;
            } */
        </style>
    </head>

    <body>
        @yield("content")
        <!-- Import bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>
