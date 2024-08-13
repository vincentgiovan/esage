<nav class="navbar navbar-expand-lg w-100" style="background: rgb(95, 95, 95);">
    <div class="container-fluid d-flex justify-content-between">
        <div class="d-flex">
            <button class="px-3 py-2 text-white border-0" style="" type="button"
                id="sidebarToggler"><i class="bi bi-list"></i></button>

            <a class="navbar-brand text-white mx-2 d-flex align-items-center gap-1" href={{ route('dashboard') }}>
                <img src="{{ asset('res/output-onlinepngtools.png') }}" width="25px" alt="">
                <span>WMS Sage</span>
            </a>
        </div>

        <div class="position-relative d-flex flex-column align-items-end">
            <button class="bg-transparent text-white border-0 prof-toggler" type="button"  id="logout-btn-pc">
                <div class="d-flex gap-2">
                    <i class="bi bi-person-circle"></i>
                    <span>{{ Auth::user()->name }}</span>
                </div>
            </button>
            <button class="bg-transparent text-white border-0 prof-toggler" type="button" id="logout-btn-hp">
                <div class="d-flex gap-2">
                    <i class="bi bi-person-circle"></i>
                    <span>{{ explode(" ", Auth::user()->name)[0]; }}...</span>
                </div>

            </button>
            <div class="bg-white rounded-3 overflow-hidden position-absolute z-2 border border-1" id="prof-menu" style="display: none; top: 40px;">
                <form class="dropdown-item border border-1" action="{{ route('logout') }}" method="post">
                    @csrf
                    <button type="submit" class="h-100 w-100 border-0 py-2 px-4"><i class="bi bi-box-arrow-left"></i> Logout</button>
                </form>
                <a class="dropdown-item border border-1 py-2 px-4" href="#" target="blank"><i class="bi bi-person-vcard"></i> Profile</a></li>
            </div>
        </div>

        <script>
            $(document).ready(() => {
                $(".prof-toggler").click(function(){
                    $("#prof-menu").toggle();
                });
            });
        </script>

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
