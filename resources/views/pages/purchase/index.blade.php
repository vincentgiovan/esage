@extends('layouts.main-admin')

@section('content')
    <x-container>
        <br>
        <div class="w-100 d-flex align-items-center justify-content-between">
            <h1>Warehouse Items</h1>
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

        <a href="{{ route('purchase-create') }}" class="btn btn-primary text-white mb-3" style="font-size: 10pt">
            <i class="bi bi-plus-square"></i>
            Add New Data</a>
        <br>
        <!-- tabel list data-->

        <div class="overflow-x-auto">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-dark ">Nomor</th>
                    <th class="border border-1 border-dark ">Supplier </th>
                    <th class="border border-1 border-dark ">Purchase Deadline</th>
                    <th class="border border-1 border-dark ">Register</th>
                    <th class="border border-1 border-dark ">Purchase Date</th>
                    <th class="border border-1 border-dark ">Note</th>
                    <th class="border border-1 border-dark ">Purchase Status</th>
                    <th class="border border-1 border-dark ">Action</th>
                </tr>

                @foreach ($purchases as $p)
                    <tr>
                        <td class="border border-1 border-dark ">{{ $loop->iteration }}</td>
                        <td class="border border-1 border-dark ">{{ $p->partner->partner_name }}</td>
                        <td class="border border-1 border-dark ">{{ $p->purchase_deadline }}</td>
                        <td class="border border-1 border-dark ">
                            <div class="d-flex gap-5 w-100 justify-content-center align-items-center">
                                {{ $p->register }}
                                <a href="{{ route('purchaseproduct-viewitem', $p->id) }}" class="btn btn-success text-white"
                                    style="font-size: 10pt"><i class="bi bi-cart"></i>View Cart</a>
                            </div>
                        </td>

                        <td class="border border-1 border-dark ">{{ $p->purchase_date }}</td>
                        <td class="border border-1 border-dark ">{{ $p->note }}</td>
                        <td class="border border-1 border-dark ">{{ $p->purchase_status }}</td>
                        {{-- <td class="border border-1 border-dark " >{{ $p->user->name }}</td> --}}
                        <td class="border border-1 border-dark ">
                            <div class="d-flex gap-5 w-100 justify-content-center">

                                <a href="{{ route('purchase-edit', $p->id) }}" class="btn btn-warning text-white"
                                    style="font-size: 10pt">
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

                    </tr>
                @endforeach
            </table>
        </div>
    </x-container>
@endsection
