@extends('layouts.main-admin')
@section('content')
    {{-- Back to top --}}
    {{-- <a type="button" class="text-info px-3 py-2 border-0 btn btn-light rounded-circle"
        style="bottom: 10px; right: 10px; position: fixed; background-color:transparent; " href="#">
        <i class="bi bi-arrow-up-circle" style="font-size: 40px; z-index: 10;"></i>
    </a> --}}

    <div class="">
        @if (session()->has('successfulLogin'))
            <p class="text-success fs-5">{{ session('successfulLogin') }}</p>
        @endif

        <!-- Title -->
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="d-flex justify-content-between align-items-center gap-3">
                    <div>
                        <h4 class="font-weight-bold mb-0">WMS Sage Summary</h4>
                    </div>

                    {{-- Gabisa muncul di project manager --}}
                    @if(!in_array(Auth::user()->role->role_name, ['project_manager']))
                        <div>
                            <div class="w-100 d-flex align-items-center justify-content-between">
                                <div class="position-relative d-flex flex-column align-items-end">
                                    <button class="btn btn-primary" type="button" id="dd-toggler">
                                        <i class="bi bi-file-earmark-arrow-up"></i> Laporan Bulanan
                                    </button>
                                    <div class="bg-white rounded-lg position-absolute z-2 border border-1" id="dd-menu" style="display: none; top: 40px;">
                                        <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("deliveryorder-export", 1) }}" target="blank">Delivery Order</a></li>
                                        <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("purchase-export", 1) }}" target="blank">Purchase</a></li>
                                        <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("product-export-pdf", 1) }}" target="blank">Product</a></li>
                                        <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("partner-export", 1) }}" target="blank">Partner</a></li>
                                        <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("project-export", 1) }}" target="blank">Project</a></li>
                                    </div>
                                </div>
                            </div>

                            <script>
                                $(document).ready(() => {
                                    $("#dd-toggler").click(function(){
                                        $("#dd-menu").toggle();
                                    });
                                });
                            </script>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Summary -->
        {{-- Gaboleh muncul di project manager --}}
        @if(!in_array(Auth::user()->role->role_name, ['project_manager']))
            <div class="d-flex align-items-stretch justify-content-between w-100 mb-4">
                <div style="width: 18%;">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-title text-md-center text-xl-left fs-6"><i class="bi bi-clipboard-x"></i> Stok Kosong</p>
                            <div
                                class="d-flex flex-wrap justify-content-md-center justify-content-xl-center align-items-center">
                                <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0 text-center fs-1">{{ $totalemptyproduct }}</h3>
                                <i class="ti-calendar icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
                            </div>
                            <p class="mb-0 mt-2 text-center"><span class="text-black "><small>(30 hari terakhir)</small></span>
                            </p>
                        </div>
                    </div>
                </div>

                <div style="width: 18%;">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-title text-md-center text-xl-left fs-6"><i class="bi bi-truck"></i> Total D.Order</p>
                            <div
                                class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-center align-items-center">
                                <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0 text-center fs-1">{{ $totaldelivery }}</h3>
                                <i class="ti-user icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
                            </div>
                            <p class="mb-0 mt-2 text-center "><span class="text-black "><small>(30 hari terakhir)</small></span>
                            </p>
                        </div>
                    </div>
                </div>

                <div style="width: 18%;">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-title text-md-center text-xl-left fs-6"><i class="bi bi-cart4"></i> Total Pembelian</p>
                            <div
                                class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-center align-items-center">
                                <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0 text-center fs-1">{{ $totalpurchase }}</h3>
                                <i class="ti-agenda icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
                            </div>
                            <p class="mb-0 mt-2 text-center "><span class="text-black "><small>(30 hari terakhir)</small></span></p>
                        </div>
                    </div>
                </div>

                <div style="width: 18%;">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-title text-md-center text-xl-left fs-6"><i class="bi bi-building"></i> Proyek Baru</p>
                            <div
                                class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-center align-items-center">
                                <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0 text-center fs-1">{{ $totalnewproject }}</h3>
                                <i class="ti-layers-alt icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
                            </div>
                            <p class="mb-0 mt-2 text-center"><span class="text-black "><small>(30 hari terakhir)</small></span></p>
                        </div>
                    </div>
                </div>
                <div style="width: 18%;">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-title text-md-center text-xl-left fs-6"><i class="bi bi-building"></i> Pegawai Aktif</p>
                            <div
                                class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-center align-items-center">
                                <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0 text-center fs-1">{{ $activeemployee }}</h3>
                                <i class="ti-layers-alt icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
                            </div>
                            <p class="mb-0 mt-2 text-center"><span class="text-black "><small>Dari total {{ $totalemployee }} pegawai</small></span></p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title">Projects Items Usage</p>
                        <p class="text-muted font-weight-light">Received overcame oh sensible so at an. Formed do change
                            merely to county it. Am separate contempt domestic to to oh. On relation my so addition
                            branched.</p>
                        <div id="sales-legend" class="chartjs-legend mt-4 mb-2"></div>
                        <canvas id="sales-chart"></canvas>
                    </div>
                    <div class="card border-right-0 border-left-0 border-bottom-0">
                        <div class="d-flex justify-content-center justify-content-md-end">
                            <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                                <button
                                    class="btn btn-lg btn-outline-light dropdown-toggle rounded-0 border-top-0 border-bottom-0"
                                    type="button" id="dropdownMenuDate2" data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="true">
                                    Today
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuDate2">
                                    <a class="dropdown-item" href="#">January - March</a>
                                    <a class="dropdown-item" href="#">March - June</a>
                                    <a class="dropdown-item" href="#">June - August</a>
                                    <a class="dropdown-item" href="#">August - November</a>
                                </div>
                            </div>
                            <button class="btn btn-lg btn-outline-light text-primary rounded-0 border-0 d-none d-md-block"
                                type="button"> View all </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title">Most Used Items</p>
                        <p class="text-muted font-weight-light">Received overcame oh sensible so at an. Formed do change
                            merely to county it. Am separate contempt domestic to to oh. On relation my so addition
                            branched.</p>
                        <div id="sales-legend" class="chartjs-legend mt-4 mb-2"></div>
                        <canvas id="sales-chart"></canvas>
                    </div>
                    <div class="card border-right-0 border-left-0 border-bottom-0">
                        <div class="d-flex justify-content-center justify-content-md-end">
                            <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                                <button
                                    class="btn btn-lg btn-outline-light dropdown-toggle rounded-0 border-top-0 border-bottom-0"
                                    type="button" id="dropdownMenuDate2" data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="true">
                                    Today
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuDate2">
                                    <a class="dropdown-item" href="#">January - March</a>
                                    <a class="dropdown-item" href="#">March - June</a>
                                    <a class="dropdown-item" href="#">June - August</a>
                                    <a class="dropdown-item" href="#">August - November</a>
                                </div>
                            </div>
                            <button class="btn btn-lg btn-outline-light text-primary rounded-0 border-0 d-none d-md-block"
                                type="button"> View all </button>
                        </div>
                    </div>
                </div>
        </div> --}}
        <div class="row">
            {{-- <div class="col-md-7 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title mb-0">Top Products</p>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Product</th>
                                        <th>Sale</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Jacob</td>
                                        <td>Photoshop</td>
                                        <td class="text-danger"> 28.76% <i class="ti-arrow-down"></i></td>
                                        <td><label class="badge badge-danger">Pending</label></td>
                                    </tr>
                                    <tr>
                                        <td>Messsy</td>
                                        <td>Flash</td>
                                        <td class="text-danger"> 21.06% <i class="ti-arrow-down"></i></td>
                                        <td><label class="badge badge-warning">In progress</label></td>
                                    </tr>
                                    <tr>
                                        <td>John</td>
                                        <td>Premier</td>
                                        <td class="text-danger"> 35.00% <i class="ti-arrow-down"></i></td>
                                        <td><label class="badge badge-info">Fixed</label></td>
                                    </tr>
                                    <tr>
                                        <td>Peter</td>
                                        <td>After effects</td>
                                        <td class="text-success"> 82.00% <i class="ti-arrow-up"></i></td>
                                        <td><label class="badge badge-success">Completed</label></td>
                                    </tr>
                                    <tr>
                                        <td>Dave</td>
                                        <td>53275535</td>
                                        <td class="text-success"> 98.05% <i class="ti-arrow-up"></i></td>
                                        <td><label class="badge badge-warning">In progress</label></td>
                                    </tr>
                                    <tr>
                                        <td>Messsy</td>
                                        <td>Flash</td>
                                        <td class="text-danger"> 21.06% <i class="ti-arrow-down"></i></td>
                                        <td><label class="badge badge-info">Fixed</label></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> --}}
            <!-- nanti ubah jadi col-md-5 kalo top product mau dimunculin -->

            <!-- Todo list -->
            <div class="col-md-12 grid-margin stretch-card mt-4">
                <div class="card">
                    <form action="{{ route('todo-update') }}" method="post" class="card-body" id="porm">
                        @csrf
                        <h4 class="card-title fs-6"><i class="bi bi-journal-text"></i> To Do Lists</h4>

                        <div class="list-wrapper pt-2 h-auto px-3">
                            <ul class="d-flex flex-column-reverse todo-list todo-list-custom">
                                @forelse($todos as $todo)
                                    <li class="">
                                        <div class="">
                                            <label class="form-check-label">
                                                <input type="checkbox" name="checkbox{{ $todo->id }}" @if($todo->status == 'done') checked @endif>
                                                <span class="@if($todo->status == 'done') text-decoration-line-through @endif todo-detail">{{ $todo->task }}</span>
                                            </label>
                                        </div>
                                        <i class="remove ti-trash"></i>
                                    </li>
                                @empty
                                    <p class="text-center">- No to do lists -</p>
                                @endforelse
                            </ul>
                        </div>

                        <div class="d-flex justify-content-end w-100">
                            <button class="btn btn-primary mt-4" type="submit" style="display: none;" id="save-btn">Save</button>
                        </div>
                    </form>

                    <form action="{{ route('todo-store') }}" method="post" class="add-items d-flex align-items-center px-4">
                        @csrf
                        <input type="text" name="new_task" class="form-control todo-list-input me-2" placeholder="Add new task">
                        <button class="btn text-primary bg-transparent" type="submit">
                            <i class="bi bi-plus"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function(){
                const allCheckbox = document.querySelectorAll("input[type='checkbox']");

                allCheckbox.forEach(element => {
                    element.addEventListener("change", function() {
                        $("#save-btn").show();
                        if($(this).is(":checked")){
                            $(this).closest("label").find(".todo-detail").addClass("text-decoration-line-through");
                        }
                        else {
                            $(this).closest("label").find(".todo-detail").removeClass("text-decoration-line-through");
                        }

                    });
                });

                const form = document.querySelector("#porm");

                form.addEventListener("submit", function(event){
                    event.preventDefault();

                    const allCheckbox = document.querySelectorAll("input[type='checkbox']");
                    let cbvals = [];
                    allCheckbox.forEach(element => {
                        let cbval = (element.checked)? "on" : "off";

                        cbvals.push(cbval);
                    });

                    for(let i = cbvals.length - 1; i >= 0; i--){
                        const newHiddenInput = document.createElement("input");
                        newHiddenInput.setAttribute("type", "hidden");
                        newHiddenInput.setAttribute("name", "checkboxes[]");
                        newHiddenInput.setAttribute("value", cbvals[i]);

                        form.appendChild(newHiddenInput);
                    }

                    this.submit();

                });


            });

        </script>

        {{-- <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card position-relative">
                    <div class="card-body">
                        <p class="card-title">Detailed Reports</p>
                        <div class="row">
                            <div class="col-md-12 col-xl-3 d-flex flex-column justify-content-center">
                                <div class="ml-xl-4">
                                    <h2>33500</h2>
                                    <h3 class="font-weight-light mb-xl-4">Sales</h3>
                                    <p class="text-muted mb-2 mb-xl-0">The total number of sessions within the date range.
                                        It is the period time a user is actively engaged with your website, page or app, etc
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-12 col-xl-9">
                                <div class="row">
                                    <div class="col-md-6 mt-3 col-xl-5">
                                        <canvas id="north-america-chart"></canvas>
                                        <div id="north-america-legend"></div>
                                    </div>
                                    <div class="col-md-6 col-xl-7">
                                        <div class="table-responsive mb-3 mb-md-0">
                                            <table class="table table-borderless report-table">
                                                <tr>
                                                    <td class="text-muted">Illinois</td>
                                                    <td class="w-100 px-0">
                                                        <div class="progress progress-md mx-4">
                                                            <div class="progress-bar bg-primary" role="progressbar"
                                                                style="width: 70%" aria-valuenow="70" aria-valuemin="0"
                                                                aria-valuemax="100"></div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <h5 class="font-weight-bold mb-0">524</h5>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">Washington</td>
                                                    <td class="w-100 px-0">
                                                        <div class="progress progress-md mx-4">
                                                            <div class="progress-bar bg-primary" role="progressbar"
                                                                style="width: 30%" aria-valuenow="30" aria-valuemin="0"
                                                                aria-valuemax="100"></div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <h5 class="font-weight-bold mb-0">722</h5>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">Mississippi</td>
                                                    <td class="w-100 px-0">
                                                        <div class="progress progress-md mx-4">
                                                            <div class="progress-bar bg-primary" role="progressbar"
                                                                style="width: 95%" aria-valuenow="95" aria-valuemin="0"
                                                                aria-valuemax="100"></div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <h5 class="font-weight-bold mb-0">173</h5>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">California</td>
                                                    <td class="w-100 px-0">
                                                        <div class="progress progress-md mx-4">
                                                            <div class="progress-bar bg-primary" role="progressbar"
                                                                style="width: 60%" aria-valuenow="60" aria-valuemin="0"
                                                                aria-valuemax="100"></div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <h5 class="font-weight-bold mb-0">945</h5>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">Maryland</td>
                                                    <td class="w-100 px-0">
                                                        <div class="progress progress-md mx-4">
                                                            <div class="progress-bar bg-primary" role="progressbar"
                                                                style="width: 40%" aria-valuenow="40" aria-valuemin="0"
                                                                aria-valuemax="100"></div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <h5 class="font-weight-bold mb-0">553</h5>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">Alaska</td>
                                                    <td class="w-100 px-0">
                                                        <div class="progress progress-md mx-4">
                                                            <div class="progress-bar bg-primary" role="progressbar"
                                                                style="width: 75%" aria-valuenow="75" aria-valuemin="0"
                                                                aria-valuemax="100"></div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <h5 class="font-weight-bold mb-0">912</h5>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->

    <!-- partial -->
@endsection
