<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link text-dark text-decoration-none mx-2" aria-current="page" href="/deliveryorder"><i class="bi bi-truck me-2"></i> D-Order</a>
        </li>

        <li class="nav-item">
            <a class="nav-link text-dark text-decoration-none mx-2" href="https://aniwave.to/home"><i class="bi bi-card-checklist me-2"></i> Pembelian</a>
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
</nav>

