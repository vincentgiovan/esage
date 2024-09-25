@extends('layouts.main-admin')

@section("content")
    <x-container>
        <br>
        <div class="w-100 d-flex align-items-center justify-content-between">
            <h1>All Products in {{ $return->register }}</h1>
            <div class="d-flex gap-3">
                <a class="btn btn-secondary" href="{{ route('returnproduct-import', $return->id) }}"><i class="bi bi-file-earmark-arrow-down"></i> Import</a>
                {{-- <div class="position-relative d-flex flex-column align-items-end">
                    <button class="btn btn-secondary" type="button" id="dd-toggler">
                        <i class="bi bi-file-earmark-arrow-up"></i> Export
                    </button>
                    <div class="bg-white rounded-lg position-absolute z-2 border border-1" id="dd-menu" style="display: none; top: 40px;">
                        <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("returnproduct-export", [$return->id, 2]) }}" target="blank">Export (PDF Portrait)</a></li>
                        <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("returnproduct-export", [$return->id, 1]) }}" target="blank">Export (PDF Landscape)</a></li>
                    </div>
                </div> --}}
            </div>
        </div>

        {{-- <script>
            $(document).ready(() => {
                $("#dd-toggler").click(function(){
                    $("#dd-menu").toggle();
                });
            });
        </script> --}}

        <hr>
        <br>

        {{-- <h5>welcome back, {{ Auth::user()->name }}! </h5> --}}

        @if (session()->has("successAddProduct"))
                <p class="text-success fw-bold">{{ session("successAddProduct") }}</p>

        @elseif (session()->has("successEditProduct"))

                <p class="text-success fw-bold">{{ session("successEditProduct") }}</p>

        @elseif (session()->has("successDeleteProduct"))
                <p class="text-success fw-bold">{{ session("successDeleteProduct") }}</p>

        @endif

        <a href="{{ route('returnproduct-create1', $return->id) }}" class="btn btn-primary text-white mb-3" style="font-size: 10pt">
            <i class="bi bi-plus-square"></i> Add Item to List
        </a>

        <br>
        <!-- tabel list data-->

        <div class="overflow-x-auto">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary ">Nomor</th>
                    <th class="border border-1 border-secondary ">Foto</th>
                    <th class="border border-1 border-secondary ">Nama Produk</th>
                    <th class="border border-1 border-secondary ">Quantity</th>
                    <th class="border border-1 border-secondary ">Variant</th>
                    <th class="border border-1 border-secondary ">Action</th>
                </tr>

                @foreach ($do as $return_product)
                    <tr>
                        <td class="border border-1 border-secondary " >{{ $loop->iteration }}</td>
                        <td class="border border-1 border-secondary " >{{ $return_product->product->product_name }}</td>
                        <td class="border border-1 border-secondary " >{{ $return_product->product->product_code }}</td>
                        <td class="border border-1 border-secondary " >{{ $return_product->quantity }}</td>
                        <td class="border border-1 border-secondary " >{{ $return_product->product->variant }}</td>
                        <td class="border border-1 border-secondary " >
                            <div class="d-flex gap-5 w-100 justify-content-center">
                                <form action="{{ route("returnproduct-destroy", [$return->id, $return_product->id] ) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-danger text-white" style="font-size: 10pt " onclick="return confirm('Do you want to remove this item from the return?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                @endforeach

            </table>

            <div class="d-flex h-100 w-100 justify-content-end gap-3 fw-bold border border-1 border-secondary px-2" style="font-size: 14pt; background: linear-gradient(to right, rgb(113, 113, 113), rgb(213, 207, 207));">
                <div>
                    Total Items:
                </div>
                <div>
                    {{ $do->count() }}
                </div>
            </div>
        </div>
    </x-container>
@endsection
