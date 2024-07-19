<nav class="navbar navbar-expand-lg w-100" style="background: rgb(95, 95, 95);">
    <div class="container-fluid">
        <button class="px-3 py-2 text-white border-0" style="" type="button"
            id="sidebarToggler"><i class="bi bi-list"></i></button>

        <a class="navbar-brand text-white mx-2 d-flex align-items-center gap-1" href={{ route('dashboard') }}>
            <img src="{{ asset('res/output-onlinepngtools.png') }}" width="25px" alt="">
            <span>eSage V2.3.x</span>
        </a>

        <form class="nav-item ms-auto" action="{{ route('keluar') }}" method="post">
            @csrf
            <button class="nav-link text-white text-decoration-none mx-2" id="logout-btn-pc"><i
                    class="bi bi-box-arrow-left"></i> Logout ({{ Auth::user()->name }})</button>
            <button class="nav-link text-white text-decoration-none mx-2" id="logout-btn-hp"><i
                class="bi bi-box-arrow-left"></i> {{ explode(" ", Auth::user()->name)[0]; }}...</button>
        </form>

    </div>

</nav>

<script>
    const changeLogoutText = () => {
        if($(window).width() < 600){
            $("#logout-btn-pc").css("display", "none");
            $("#logout-btn-hp").css("display", "block");
        } else {
            $("#logout-btn-pc").css("display", "block");
            $("#logout-btn-hp").css("display", "none");
        }
    }

    $(document).ready(function(){
        changeLogoutText();
    });

    $(window).on("resize", function(){
        changeLogoutText();
    });
</script>
