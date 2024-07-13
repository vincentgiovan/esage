
    <nav class="navbar navbar-expand-lg w-100" style="background: rgb(95, 95, 95);">
        <div class="container-fluid">
        <a class="navbar-brand text-white mx-2 d-flex align-items-center gap-1" href={{ route("dashboard") }}>
            <img src="{{ asset("res/output-onlinepngtools.png") }}" width="25px" alt="">
            <span>eSage V2.3.x</span>
        </a>

        <button class="px-3 py-2 mx-2 text-white border-0" style="top: 0; left: 180px;" type="button" id="sidebarToggler"><i class="bi bi-list"></i> Menu</button>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">

            <i class="bi bi-list primary h3" style="color:white"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

            {{-- <li class="nav-item dropdown">
                <a class="nav-link text-white text-decoration-none mx-2 dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Dropdown
                </a>

                <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Action</a></li>
                <li><a class="dropdown-item" href="#">Another action</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
            </li> --}}
            </ul>
            <form class="nav-item ms-auto" action="/logout" method="post">
                @csrf
                <button class="nav-link text-white text-decoration-none mx-2" href="#"><i class="bi bi-box-arrow-left"></i> Logout ({{ Auth::user()->name }})</button>
            </form>

        </div>

        </div>
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="ti-view-list"></span>
        </button>
    </nav>


