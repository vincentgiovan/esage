<nav class="fixed-top h-100" id="sidebar" style="background-color: rgb(69, 69, 69); z-index: 10;">
    <ul class="nav d-flex flex-column items-stretch flex-nowrap gap-2 py-3 overflow-y-auto" id="sidebar-menu">
        <li class="nav-item">
            <a class="nav-link text-decoration-none px-4" href="{{ route("dashboard") }}" style="color: white; font-weight: bold; @if (Request::is("dashboard*")) background-color: green; @else rgb(69, 69, 69); @endif"><i class="bi bi-grid-1x2 me-2"></i> Dashboard</a>
        </li>

        @if(in_array(Auth::user()->role, ['master', 'accounting_admin']))
            <li class="nav-item">
                <a class="nav-link text-decoration-none px-4" href="{{ route("purchase-index") }}" style="color: white; font-weight: bold; @if (Request::is("purchase*")) background-color: green; @else rgb(69, 69, 69); @endif"><i class="bi bi-card-checklist me-2"></i> Pembelian Barang</a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-decoration-none px-4" href="{{ route("deliveryorder-index") }}"  style="color: white; font-weight: bold; @if (Request::is("delivery-order*")) background-color: green; @else rgb(69, 69, 69); @endif"><i class="bi bi-truck me-2"></i> Pengiriman Barang</a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-decoration-none px-4" href="{{ route("partner-index") }}" style="color: white; font-weight: bold; @if (Request::is("partner*")) background-color: green; @else rgb(69, 69, 69); @endif"><i class="bi bi-shop me-2"></i> Partner Sage</a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-decoration-none px-4" style="color: white; font-weight: bold; @if (Request::is("project*")) background-color: green; @else rgb(69, 69, 69); @endif" href="{{ route("project-index") }}"><i class="bi bi-building me-2"></i> Proyek Sage</a>
            </li>
        @endif

        @if(in_array(Auth::user()->role, ['master', 'accounting_admin', 'project_manager']))
            <li class="nav-item">
                <a class="nav-link text-decoration-none px-4" style="color: white; font-weight: bold; @if (Request::is("product*")) background-color: green; @else rgb(69, 69, 69); @endif" href="{{ route("product-index") }}"><i class="bi bi-box-seam me-2"></i> Data Barang</a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-decoration-none px-4" style="color: white; font-weight: bold; @if (Request::is("request*")) background-color: green; @else rgb(69, 69, 69); @endif" href="{{ route("requestitem-index") }}" ><i class="bi bi-bag-plus me-2"></i> Request Barang</a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-decoration-none px-4" style="color: white; font-weight: bold; @if (Request::is("return*")) background-color: green; @else rgb(69, 69, 69); @endif" href="{{ route("returnitem-index") }}" ><i class="bi bi-bag-dash me-2"></i> Pengembalian</a>
            </li>
        @endif

        @if(in_array(Auth::user()->role, ['master']))
            <li class="nav-item">
                <a class="nav-link text-decoration-none px-4" style="color: white; font-weight: bold; @if (Request::is("account*")) background-color: green; @else rgb(69, 69, 69); @endif" href="{{ route("account.index") }}" ><i class="bi bi-person me-2"></i> Pengelolaan Akun</a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-decoration-none px-4" style="color: white; font-weight: bold; @if (Request::is("employee*") && !Request::is("*employee*leaves*")) background-color: green; @else rgb(69, 69, 69); @endif" href="{{ route("employee-index") }}" ><i class="bi bi-person-vcard me-2"></i> Data Pegawai</a>
            </li>
        @endif

        @if(in_array(Auth::user()->role, ['master', 'accounting_admin']))
            <li class="nav-item">
                <a class="nav-link text-decoration-none px-4" style="color: white; font-weight: bold; @if (Request::is("salary*")) background-color: green; @else rgb(69, 69, 69); @endif" href="{{ route("salary-index") }}" ><i class="bi bi-currency-dollar me-2"></i> Gaji Pegawai</a>
            </li>

            <li class="nav-item">
                <a class="nav-link text-decoration-none px-4" style="color: white; font-weight: bold; @if (!Request::is('attendance*self*') && Request::is('attendance*')) background-color: green; @else rgb(69, 69, 69); @endif" href="{{ route("attendance-index") }}" ><i class="bi bi-clipboard2-check me-2"></i> Presensi Pegawai</a>
            </li>
        @endif

        @if(in_array(Auth::user()->role, ['master', 'accounting_admin', 'project_manager']))
            <li class="nav-item">
                <a class="nav-link text-decoration-none px-4" style="color: white; font-weight: bold; @if (Request::is("attendance*self*")) background-color: green; @else rgb(69, 69, 69); @endif" href="{{ route("attendance-self-index") }}" ><i class="bi bi-clipboard2-check me-2"></i> Presensi Mandiri</a>
            </li>
        @endif

        @if(in_array(Auth::user()->role, ['master', 'accounting_admin']))
            <li class="nav-item">
                <a class="nav-link text-decoration-none px-4" style="color: white; font-weight: bold; @if (Request::is("visit-log*")) background-color: green; @else rgb(69, 69, 69); @endif" href="{{ route("visitlog-index") }}" ><i class="bi bi-person-lines-fill me-2"></i> Visit Log</a>
            </li>
        @endif

        @if(in_array(Auth::user()->role, ['master']))
            <li class="nav-item">
                <a class="nav-link text-decoration-none px-4" style="color: white; font-weight: bold; @if (Request::is("*employee*leaves*") && !Request::is("*employee*leaves*mine*")) background-color: green; @else rgb(69, 69, 69); @endif" href="{{ route("leave-admin-index") }}" ><i class="bi bi-building-fill-x me-2"></i> Pengajuan Cuti Pegawai</a>
            </li>
        @endif

        @if(in_array(Auth::user()->role, ['master', 'accounting_admin']))
            <li class="nav-item">
                <a class="nav-link text-decoration-none px-4" style="color: white; font-weight: bold; @if (Request::is("*employee*leaves*mine*")) background-color: green; @else rgb(69, 69, 69); @endif" href="{{ route("leave-user-index") }}" ><i class="bi bi-building-fill-x me-2"></i> Pengajuan Cuti Saya</a>
            </li>
        @endif
    </ul>
</nav>

<script>
    $(document).ready(() => {
        const adjustSidebarHeight = () => {
            $("#sidebar-menu").css({"height": $(window).height() - $('.navbar').innerHeight(), "margin-top": $('.navbar').innerHeight()});
        };

        adjustSidebarHeight(); // Initial adjustment
        $(window).resize(adjustSidebarHeight); // Re-adjust on window resize
    });

</script>

