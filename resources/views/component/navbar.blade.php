
    <nav class="navbar navbar-expand-lg " style="background: rgb(95, 95, 95)">

        <div class="container-fluid">
        <a class="navbar-brand text-white mx-2 d-flex align-items-center gap-1" href="/dashboard">
            <img src="res/output-onlinepngtools.png" width="25px" alt="">
            <span>eSage V2.0</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">

            <i class="bi bi-list primary h3" style="color:white"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                <a class="nav-link text-white text-decoration-none mx-2" aria-current="page" href="/deliveryorder"><i class="bi bi-truck"></i> D-Order</a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white text-decoration-none mx-2" href="https://aniwave.to/home"><i class="bi bi-card-checklist"></i> Pembelian</a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-decoration-none mx-2 @if (Request::is("partner*")) text-warning @else text-white @endif" href="{{ route("partner-index") }}"><i class="bi bi-shop"></i> Partner</a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-decoration-none mx-2 @if (Request::is("product*")) text-warning @else text-white @endif" href="{{ route("product-index") }}"><i class="bi bi-box"></i> Produk</a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-decoration-none mx-2 @if (Request::is("project*")) text-warning @else text-white @endif" href="{{ route("project-index") }}"><i class="bi bi-building"></i> Proyek</a>
            </li>
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
    </nav>


