@extends('layouts.main-admin')

@section('content')
    <x-container>
        <br>
        <div class="w-100 d-flex align-items-center justify-content-between">
            <h2>Purchases</h2>
            @can("admin")
                <div class="d-flex gap-3">
                    {{-- <a class="btn btn-secondary" href="{{ route('purchase-import') }}"><i class="bi bi-file-earmark-arrow-down"></i> Import</a> --}}
                    <div class="position-relative d-flex flex-column align-items-end">
                        <button class="btn btn-secondary" type="button" id="import-toggler">
                            <i class="bi bi-file-earmark-arrow-down"></i> Import
                        </button>
                        <div class="bg-white rounded-lg position-absolute z-2 border border-1" id="import-menu" style="display: none; top: 40px;">
                            <a class="dropdown-item border border-1 py-2 px-3" href="{{ route('purchase-import') }}">Purchase Data Only</a></li>
                            <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("purchase-importwpform") }}">With Products</a></li>
                        </div>
                    </div>
                    <div class="position-relative d-flex flex-column align-items-end">
                        <button class="btn btn-secondary" type="button" id="dd-toggler">
                            <i class="bi bi-file-earmark-arrow-up"></i> Export
                        </button>
                        <div class="bg-white rounded-lg position-absolute z-2 border border-1" id="dd-menu" style="display: none; top: 40px;">
                            <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("purchase-export", 2) }}" target="blank">Export (PDF Portrait)</a></li>
                            <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("purchase-export", 1) }}" target="blank">Export (PDF Landscape)</a></li>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
        <script>
            $(document).ready(() => {
                $("#dd-toggler").click(function(){
                    $("#dd-menu").toggle();
                });

                $("#import-toggler").click(function(){
                    $("#import-menu").toggle();
                });
            });
        </script>
        <hr>
        <br>

        {{-- <h5>welcome back, {{ Auth::user()->name }}! </h5> --}}

        @if (session()->has('successAddPurchase'))
            <p class="text-success fw-bold">{{ session('successAddPurchase') }}</p>
        @elseif (session()->has('successEditPurchase'))
            <p class="text-success fw-bold">{{ session('successEditPurchase') }}</p>
        @elseif (session()->has('successDeletePurchase'))
            <p class="text-success fw-bold">{{ session('successDeletePurchase') }}</p>
        @elseif (session()->has('successImportPurchase'))
            <p class="text-success fw-bold">{{ session('successImportPurchase') }}</p>
        @endif

        @can("admin")
            <a href="{{ route('purchase-create') }}" class="btn btn-primary text-white mb-3" style="font-size: 10pt">
                <i class="bi bi-plus-square"></i>
                Add New Data</a>
            <br>
        @endcan

        <!-- tabel list data-->

        <div class="overflow-x-auto">
            <table class="w-100">
                <tr>
                    <th>No</th>
                    <th>Supplier </th>
                    <th>Purchase Deadline</th>
                    <th>Register</th>
                    <th>Purchase Date</th>
                    <th>Note</th>
                    <th>Purchase Status</th>
                    @can("admin")
                        <th>Action</th>
                    @endcan
                </tr>

                @foreach ($purchases as $p)
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $p->partner->partner_name }}</td>
                        <td>{{ Carbon\Carbon::parse($p->purchase_deadline)->format("d M Y") }}</td>
                        <td>
                            <div class="d-flex gap-5 w-100 justify-content-between align-items-center">
                                {{ $p->register }}
                                @can('admin')
                                    <a href="{{ route('purchaseproduct-viewitem', $p->id) }}" class="btn btn-success text-white"
                                        style="font-size: 10pt"><i class="bi bi-cart"></i>View Cart</a>
                                @endcan
                            </div>
                        </td>

                        <td>{{ Carbon\Carbon::parse($p->purchase_date)->format("d M Y") }}</td>
                        <td>{{ $p->note }}</td>
                        <td class="fw-bold" style="color: @if($p->purchase_status == 'Ordered') blue @else green @endif;">{{ $p->purchase_status }}</td>
                        {{-- <td >{{ $p->user->name }}</td> --}}

                        @can("admin")
                            <td>
                                <div class="d-flex gap-2 w-100">

                                    <a href="{{ route('purchase-edit', $p->id) }}" class="btn text-white"
                                        style="font-size: 10pt; background-color: rgb(197, 167, 0);">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('purchase-destroy', $p->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-danger text-white" style="font-size: 10pt "
                                            onclick="return confirm('Do you want to delete this item?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        @endcan

                    </tr>
                @endforeach
            </table>
        </div>
    </x-container>
@endsection
