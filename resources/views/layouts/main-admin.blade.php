<!doctype html>
<html lang="en">
    <head>
        {{-- Meta data & title --}}
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>WMS Sage</title>

        {{-- Bootstrap CSS & icon --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        {{-- Original asset --}}
        <link rel="shortcut icon" href="{{ asset("res/sageico.ico") }}" />

        {{-- Custom styles --}}
        <style>
            body{
                background-color: white;
                font-size: 11pt;
            }

            table th, td{
                padding: 5px 10px;
            }

            table th {
                background-color: rgb(194, 191, 191);
            }

            ol, ul {
                list-style-position: inside; /* Ensure the bullet/number is inside */
                padding-left: 0;            /* Remove padding */
                margin-left: 0;             /* Remove margin */
            }

            .select2-selection__arrow {
				margin-top: 5px !important;
				margin-right: 10px !important;
			}

            button.btn {
                font-size: 10pt !important;
            }

            a.btn {
                font-size: 10pt !important;
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
                width: 85%;
            }

            #sidebar {
                width: 15%;
            }

            .nav-link:hover{
                background-color: rgb(100, 100, 100);
            }

            input:disabled, select:disabled, textarea:disabled {
                cursor: not-allowed;
            }

            input:not(.is-invalid), select:not(.is-invalid), input:not([type="submit"]) {
                border-width: 2px !important;
                border-color: rgb(169, 169, 169) !important;
            }

            @media screen and (max-width: 600px) {
                #sidebar {
                    display: none;
                    width: 50%;
                }

                #main-content-div {
                    padding-left: 0;
                    width: 100%;
                }

                body {
                    font-size: 8pt;
                }
            }
        </style>

        {{-- Include Select2 CSS --}}
		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

        {{-- Include jQuery  --}}
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    </head>

    <body style="min-height: 100vh;">
        <div class="h-100 w-100">
            {{-- Navbar --}}
            @auth
                <div class="fixed-top" style="z-index: 100;">
                    @include("component.navbar")
                </div>
            @endauth

            <div class="container-fluid d-flex justify-content-end position-relative w-100" style="padding: 0;">
                {{-- Sidebar --}}
                @auth
                    @include("component.sidebar")
                @endauth

                {{-- Main --}}
                <div class="d-flex flex-column justify-content-between" id="main-content-div" style="min-height: 100vh; padding-left: 0;">
                    <div class="content-wrapper d-flex flex-column bg-light p-4 flex-grow-1 " style="width: 100%;">
                        @if(!Request::is("dashboard"))
                            <div class="d-flex gap-2 flex-wrap align-items-start fs-6">
                                <a href="{{ route('dashboard') }}" class="text-decoration-none fw-semibold">Dashboard</a>
                                <?php $link = "" ?>
                                @foreach(Request::segments() as $index => $segment)
                                    {{-- Construct the full URL --}}
                                    @php
                                        $link .= "/" . $segment;
                                    @endphp

                                    {{-- Separator --}}
                                    <span><i class="bi bi-chevron-right"></i></span>

                                    {{-- Link activation logic --}}
                                    @if ($index < count(Request::segments()) - 1 && !is_numeric($segment))
                                        <a href="{{ url($link) }}" class="text-decoration-none fw-semibold">{{ ucwords(str_replace('-', ' ', $segment)) }}</a>
                                    @else
                                        {{ ucwords(str_replace('-', ' ', $segment)) }}
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        {{-- Content --}}
                        <div class="d-flex flex-column">
                            @yield("content")
                        </div>
                    </div>

                    @include("component.footer")

                </div>
            </div>
        </div>

        {{-- Gatau buat apa --}}
        <div style="color: rgb(197, 197, 197)"></div>

        {{-- Custom javascript --}}
        <script>
            function reinitializeselect2(){
                // select-2 initialization
                $('.select2').select2({
                    allowClear: false
                });

                $('.select2').next('.select2-container').find('.select2-selection').css({
                    "height": "2.4rem",
                    "padding-top": "0.3rem",
                    "border": "rgb(169, 169, 169) solid 2px",
                    "width": "100%",
                    "border-radius": "6px"
                });
            }

            $(document).ready(() => {
                // Buat ngubah dropdown select product jadi select2 (yang ada fitur searchnya) dan sedikit styling
                reinitializeselect2();

                // Sidebar toggle
                $("#sidebarToggler").click(() => {
                    $("#sidebar").fadeToggle("slow", function(){ // sembunyiin kalo diklik and munculin kalo diklik lagi
                        if($("#sidebar").is(":hidden")){
                            if($(window).width() > 600){
                                $("#main-content-div").css({"width": "100%"}); // kalo sidebarnya hilang main kontennya dibalikin full screen
                            }

                        } else {
                            if($(window).width() > 600){
                                $("#main-content-div").css({"width": "85%"}); // kalo sidebarnya muncul main kontennya "digeser"
                            }
                        }
                    });
                });

                $('input[type=date]').on('click', function(){
                    this.showPicker();
                });
            });
        </script>

        {{-- Include Select2 JavaScript --}}
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        {{-- Import bootstrap --}}
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    </body>
</html>
