@extends('layouts.main-admin')

@section('content')
    <x-container>
        <br>
        <div class="w-100 d-flex align-items-center justify-content-between">
            <h1>Return Items</h1>
            {{-- <div class="d-flex gap-3">
                <a class="btn btn-secondary" href="{{ route('deliveryorder-import') }}"><i class="bi bi-file-earmark-arrow-down"></i> Import</a>
                <div class="position-relative d-flex flex-column align-items-end">
                    <button class="btn btn-secondary" type="button" id="dd-toggler">
                        <i class="bi bi-file-earmark-arrow-up"></i> Export
                    </button>
                    <div class="bg-white rounded-lg position-absolute z-2 border border-1" id="dd-menu" style="display: none; top: 40px;">
                        <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("deliveryorder-export", 2) }}" target="blank">Export (PDF Portrait)</a></li>
                        <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("deliveryorder-export", 1) }}" target="blank">Export (PDF Landscape)</a></li>
                    </div>
                </div>
            </div> --}}
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

        @if (session()->has('successAddOrder'))
            <p class="text-success fw-bold">{{ session('successAddOrder') }}</p>
        @elseif (session()->has('successEditOrder'))
            <p class="text-success fw-bold">{{ session('successEditOrder') }}</p>
        @elseif (session()->has('successDeleteOrder'))
            <p class="text-success fw-bold">{{ session('successDeleteOrder') }}</p>
        @endif

        <a href="{{ route('returnitem-create') }}" class="btn btn-primary text-white mb-3" style="font-size: 10pt">
            <i class="bi bi-plus-square"></i>
            Return New Items</a>
        <br>
        <!-- tabel list data-->

        <div class="overflow-x-auto">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary ">No</th>
                    <th class="border border-1 border-secondary ">Proyek Asal</th>
                    <th class="border border-1 border-secondary ">Foto</th>
                    <th class="border border-1 border-secondary ">PIC Return</th>
                    {{-- <th class="border border-1 border-secondary ">Nama Produk</th>
                    <th class="border border-1 border-secondary ">Quantity</th>
                    <th class="border border-1 border-secondary ">Variant</th> --}}
                    <th class="border border-1 border-secondary ">Action</th>
                </tr>

                @foreach ($returnitems as $p)
                    <tr>
                        <td class="border border-1 border-secondary ">{{ $loop->iteration }}</td>
                        <td class="border border-1 border-secondary ">{{ $p->delivery_date }}</td>
                        <td class="border border-1 border-secondary ">{{ $p->project->project_name }}</td>
                        <td class="border border-1 border-secondary ">
                            <div class="d-flex gap-5 w-100 justify-content-center align-items-center">
                                {{ $p->register }}
                                <a href="{{ route('deliveryorderproduct-viewitem', $p->id) }}" class="btn btn-success text-white"
                                    style="font-size: 10pt"><i class="bi bi-cart"></i>View Cart</a>
                            </div>
                        </td>

                        <td class="border border-1 border-secondary ">{{ $p->delivery_status }}</td>
                        <td class="border border-1 border-secondary ">{{ $p->note }}</td>
                        {{-- <td class="border border-1 border-secondary " >{{ $p->user->name }}</td> --}}
                        <td class="border border-1 border-secondary">
                            <div class="d-flex gap-5 w-100 justify-content-center">
                                <a href="{{ route('deliveryorder-edit', $p->id) }}" class="btn text-white"
                                    style="font-size: 10pt; background-color: rgb(197, 167, 0);">
                                    <i class="bi bi-pencil"></i>
                                    Edit Data</a>
                                <form action="{{ route('deliveryorder-destroy', $p->id) }}" method="POST">
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

