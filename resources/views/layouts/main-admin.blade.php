<!doctype html>
<html lang="en">
    <head>
        <!-- Meta data & title -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>eSage</title>

        <!-- Style punya template halaman -->
        <link rel="stylesheet" href="{{ asset("vendors/ti-icons/css/themify-icons.css") }}">
        <link rel="stylesheet" href="{{ asset("template/vendors/base/vendor.bundle.base.css") }}">
        <link rel="stylesheet" href="{{ asset("template/css/style.css") }}">

        <!-- Bootstrap CSS & icon -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <!-- Original asset -->
        <link rel="shortcut icon" href="{{ asset("res/sageico.ico") }}" />
        <link rel="stylesheet" href="style.css">

        <!-- Custom styles -->
        <style>
            body{
                background-color: rgb(224, 224, 224)
            }

            /* button[type="button"], button[type="submit"]:hover {
                background-color: gray;
                border-color: gray;
            } */

            table th, td{
                padding: 5px 10px;
            }

            table th {
                background-color: rgb(194, 191, 191);
            }

            .select2-selection__arrow {
				margin-top: 5px;
				margin-right: 10px;
			}

            button#sidebarToggler {
                background-color: rgb(95, 95, 95);
                transition: all ease-in-out 150ms;
                border-radius: 5px;
            }

            button#sidebarToggler:hover {
                background-color: rgb(31, 31, 31);
                transform: scale(105%);
                font-weight: bold;
            }

            #main-content-div {
                padding-left: 250px; padding-top: 50px;
            }

            @media screen and (max-width: 600px) {
                #sidebar {
                    display: none;
                }

                #main-content-div {
                    padding-left: 0;
                }
            }
        </style>

        <!-- Include Select2 CSS -->
		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

        <!-- Include jQuery  -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    </head>

    <body style="min-height: 100vh;">
        <div class="container-scroller h-100 w-100">
            <!-- Navbar -->
            @auth
                <div class="fixed-top" style="z-index: 100;">
                    @include("component.navbar")
                </div>
            @endauth

            <div class="container-fluid d-flex justify-content-end position-relative w-100" style="padding: 0;">
                <!-- Sidebar -->
                @auth
                    @include("component.sidebar")
                @endauth

                <!-- Main -->
                <div class="main-panel d-flex" id="main-content-div" style="min-height: 100vh; width: 85%; padding-left: 0;">
                    <div class="content-wrapper d-flex flex-column gap-3" style="width: 100%;">
                        <!-- Breadcrumb -->
                        {{-- {{ Breadcrumbs::render() }} --}}
                        @if(Request::is("project*"))
                            <x-projectbc>
                                @yield("bcd")
                            </x-projectbc>

                        @elseif(Request::is("product*"))
                            <x-productbc>
                                @yield("bcd")
                            </x-productbc>

                        @elseif(Request::is("purchase*"))
                            <x-purchasebc>
                                @yield("bcd")
                            </x-purchasebc>

                        @elseif(Request::is("deliveryorder*"))
                            <x-deliveryorderbc>
                                @yield("bcd")
                            </x-deliveryorderbc>

                        @elseif(Request::is("partner*"))
                            <x-partnerbc>
                                @yield("bcd")
                            </x-partnerbc>

                        @endif

                        <!-- Content -->
                        <div class="flex-grow-1 d-flex flex-column">
                            @yield("content")
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Gatau buat apa -->
        <div style="color: rgb(197, 197, 197)"></div>

        <!-- Template halaman -->
        <script src="{{ asset("template/vendors/base/vendor.bundle.base.js") }}"></script>
        <script src="{{ asset("template/vendors/chart.js/Chart.min.js") }}"></script>
        <script src="{{ asset("template/js/jquery.cookie.js") }}" type="text/javascript"></script>
        <script src="{{ asset("template/js/off-canvas.js") }}"></script>
        <script src="{{ asset("template/js/hoverable-collapse.js") }}"></script>
        <script src="{{ asset("template/js/template.js") }}"></script>
        <script src="{{ asset("template/js/todolist.js") }}"></script>
        <script src="{{ asset("template/js/dashboard.js") }}"></script>

        <!-- Import bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

        <!-- Include Select2 JavaScript -->
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <!-- Custom javascript -->
        <script>
            $(document).ready(() => {
                // Buat ngubah dropdown select product jadi select2 (yang ada fitur searchnya) dan sedikit styling
                $('#select-product-dropdown').select2({
                    placeholder: "Select a Student",
                    allowClear: false
                });

                $('#select-product-dropdown').next('.select2-container').find('.select2-selection').css({
                    "height": "2.4rem",
                    "padding-top": "0.3rem",
                    "border": "none",
                    "width": "100%"
                });

                // Sidebar toggle
                $("#sidebarToggler").click(() => {
                    $("#sidebar").fadeToggle("slow", function(){ // sembunyiin kalo diklik and munculin kalo diklik lagi
                        if($("#sidebar").is(":hidden")){
                            $("#main-content-div").css({"width": "100%"}); // kalo sidebarnya hilang main kontennya dibalikin full screen
                        } else {
                            $("#main-content-div").css({"width": "85%"}); // kalo sidebarnya muncul main kontennya "digeser"
                        }
                    });
                });
            });
        </script>

    </body>
</html>
