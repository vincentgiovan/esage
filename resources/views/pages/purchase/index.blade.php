@extends('layouts.main-admin')

@section('content')
    <x-container>
        <br>
        <div class="w-100 d-flex align-items-center justify-content-between">
            <h1>Warehouse Items</h1>
            @can("admin")
                <div class="d-flex gap-3">
                    <a class="btn btn-secondary" href="{{ route('purchase-import') }}"><i class="bi bi-file-earmark-arrow-down"></i> Import</a>
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
                    <th class="border border-1 border-secondary ">Nomor</th>
                    <th class="border border-1 border-secondary ">Supplier </th>
                    <th class="border border-1 border-secondary ">Purchase Deadline</th>
                    <th class="border border-1 border-secondary ">Register</th>
                    <th class="border border-1 border-secondary ">Purchase Date</th>
                    <th class="border border-1 border-secondary ">Note</th>
                    <th class="border border-1 border-secondary ">Purchase Status</th>
                    @can("admin")
                        <th class="border border-1 border-secondary ">Action</th>
                    @endcan
                </tr>

                @foreach ($purchases as $p)
                    <tr>
                        <td class="border border-1 border-secondary ">{{ $loop->iteration }}</td>
                        <td class="border border-1 border-secondary ">{{ $p->partner->partner_name }}</td>
                        <td class="border border-1 border-secondary ">{{ $p->purchase_deadline }}</td>
                        <td class="border border-1 border-secondary ">
                            <div class="d-flex gap-5 w-100 justify-content-between align-items-center">
                                {{ $p->register }}
                                @can('admin')
                                    <a href="{{ route('purchaseproduct-viewitem', $p->id) }}" class="btn btn-success text-white"
                                        style="font-size: 10pt"><i class="bi bi-cart"></i>View Cart</a>
                                @endcan
                            </div>
                        </td>

                        <td class="border border-1 border-secondary ">{{ $p->purchase_date }}</td>
                        <td class="border border-1 border-secondary ">{{ $p->note }}</td>
                        <td class="border border-1 border-secondary ">{{ $p->purchase_status }}</td>
                        {{-- <td class="border border-1 border-secondary " >{{ $p->user->name }}</td> --}}

                        @can("admin")
                            <td class="border border-1 border-secondary ">
                                <div class="d-flex gap-5 w-100 justify-content-center">

                                    <a href="{{ route('purchase-edit', $p->id) }}" class="btn text-white"
                                        style="font-size: 10pt; background-color: rgb(197, 167, 0);">
                                        <i class="bi bi-pencil"></i>
                                        Edit Data</a>
                                    <form action="{{ route('purchase-destroy', $p->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-danger text-white" style="font-size: 10pt "
                                            onclick="return confirm('Do you want to delete this item?')">
                                            <i class="bi bi-trash"></i>
                                            Delete</button>
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
