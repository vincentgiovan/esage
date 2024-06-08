<nav class="sidebar sidebar-offcanvas" id="sidebar">



    <ul class="nav">

        <li class="nav-item">
            <a class="nav-link text-decoration-none mx-2 @if (Request::is("dashboard*")) text-warning @else text-dark @endif" href="{{ route("dashboard") }}"><i class="bi bi-grid-1x2 me-2"></i> Dashboard</a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-decoration-none mx-2 @if (Request::is("deliveryorder*")) text-warning @else text-dark @endif" href="{{ route("deliveryorder-index") }}"><i class="bi bi-truck me-2"></i> D-Order</a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-decoration-none mx-2 @if (Request::is("purchase*")) text-warning @else text-dark @endif" href="{{ route("purchase-index") }}"><i class="bi bi-card-checklist me-2"></i> Pembelian</a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-decoration-none mx-2 @if (Request::is("partner*")) text-warning @else text-dark @endif" href="{{ route("partner-index") }}"><i class="bi bi-shop me-2"></i>  Partner</a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-decoration-none mx-2 @if (Request::is("product*")) text-warning @else text-dark @endif" href="{{ route("product-index") }}"><i class="bi bi-box me-2"></i> Produk</a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-decoration-none mx-2 @if (Request::is("project*")) text-warning @else text-dark @endif" href="{{ route("project-index") }}"><i class="bi bi-building me-2"></i> Proyek</a>
        </li>
    </ul>
    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
        <span class="ti-view-list"></span>
    </button>
</nav>

