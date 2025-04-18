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

        {{-- Custom styles --}}
        <style>
            .darken-image{
                filter: brightness(0.7);
            }

            #sage-container {
                width: 250px;
            }

            #logo-sage {
                width: 80%;
            }

            #text-sage {
                font-size: 12pt;
            }

            #login-container {
                width: 75%;
            }

            @media screen and (max-width: 576px){
                .darken-image{
                    object-fit: cover;
                    object-position: bottom;
                }

                #sage-container {
                    width: 150px;
                }

                #logo-sage {
                    width: 80%;
                }

                #text-sage {
                    font-size: 9pt;
                }

                #login-container {
                    width: 100%;
                }
            }
        </style>

        {{-- Include jQuery  --}}
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    </head>

    <body  class="img-fluid ">
        <img src="{{ asset('res/FOTO_BG.png') }}" alt="alt_img" class="darken-image position-absolute vh-100 vw-100 z-0" >
        <div class="z-10 position-absolute d-flex flex-column align-items-center gap-2" id="sage-container" style="bottom: 0px; right: 15px;">
            <img src="{{ asset('res/PNG_SAGE.png') }}" alt="logo" id="logo-sage">
            <p class="text-white fw-bold text-center" id="text-sage">PT Sage Konstruksi Indonesia</p>
        </div>
        <div class="z-10 position-absolute vw-100 darken">
            @yield("content")
        </div>
        {{-- Import bootstrap --}}
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>
