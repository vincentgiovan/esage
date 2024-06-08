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

        <div class="container-scroller">
            <!-- Navbar -->
            @auth
                @include("component.navbar")
            @endauth

            <div class="container-fluid page-body-wrapper" style="padding: 0;">
                @auth
                    @include("component.sidebar")
                @endauth

                <!-- Main content -->
                <div class="main-panel">
                    <div class="content-wrapper">
                        @yield("content")
                    </div>
                </div>
            </div>

        </div>

        <!-- plugins:js -->
        <script src="{{ asset("template/vendors/base/vendor.bundle.base.js") }}"></script>
        <!-- endinject -->
        <!-- Plugin js for this page-->
        <script src="{{ asset("template/vendors/chart.js/Chart.min.js") }}"></script>
        <script src="{{ asset("template/js/jquery.cookie.js") }}" type="text/javascript"></script>
        <!-- End plugin js for this page-->
        <!-- inject:js -->
        <script src="{{ asset("template/js/off-canvas.js") }}"></script>
        <script src="{{ asset("template/js/hoverable-collapse.js") }}"></script>
        <script src="{{ asset("template/js/template.js") }}"></script>
        <script src="{{ asset("template/js/todolist.js") }}"></script>
        <!-- endinject -->
        <!-- Custom js for this page-->
        <script src="{{ asset("template/js/dashboard.js") }}"></script>
        <!-- End custom js for this page-->

        <!-- Import bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>
