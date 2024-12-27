@extends('layouts.main-admin')

@section('content')
    <x-container>
        <br>
        <div class="w-100 d-flex align-items-center justify-content-between">
            <h2>Pengembalian Barang</h2>
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



        @if (session()->has('successAddReturnItem'))
            <p class="text-success fw-bold">{{ session('successAddReturnItem') }}</p>
        @elseif (session()->has('successEditReturnItem'))
            <p class="text-success fw-bold">{{ session('successEditReturnItem') }}</p>
        @elseif (session()->has('successDeleteReturnItem'))
            <p class="text-success fw-bold">{{ session('successDeleteReturnItem') }}</p>
        @endif

        <a href="{{ route('returnitem-create') }}" class="btn btn-primary text-white mb-3" style="font-size: 10pt">
            <i class="bi bi-plus-square"></i>
            Buat Pengembalian Barang Baru</a>
        <br>
        <!-- tabel list data-->

        <div class="overflow-x-auto">
            <table class="w-100">
                <tr>
                    <th>No</th>
                    <th>Proyek Asal</th>
                    <th>Produk</th>
                    <th>Foto</th>
                    <th>PIC Return</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>

                @foreach ($return_items as $ri)
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <ul>
                                <li>Proyek: {{ $ri->project->project_name }}</li>
                                <li>Tanggal: {{ Carbon\Carbon::parse($ri->created_at)->translatedFormat("d M Y") }}</li>
                            </ul>

                        </td>
                        <td>
                            <ul>
                                <li>Nama: {{ $ri->product->product_name }}</li>
                                <li>Varian: {{ $ri->product->variant }}</li>
                                @can('admin')
                                    <li>Harga: Rp {{ number_format($ri->product->price, "2", ",", ".") }}</li>
                                    <li>Diskon: {{ $ri->product->discount }}%</li>
                                @endcan
                                <li>Jumlah: {{ $ri->quantity }}</li>
                            </ul>
                        </td>
                        <td style="max-width: 200px;">
                            @if($ri->foto)
                                <img class="w-100" src="{{ Storage::url("app/public/" . $ri->foto) }}">
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $ri->PIC }}</td>
                        <td>
                            @if($ri->status == 'Ready to pickup')
                                <i class="bi bi-check-circle-fill fs-4" style="color: green"></i>
                            @else
                                <i class="bi bi-x-circle-fill fs-4" style="color: red"></i>
                            @endif
                        </td>

                        <td>
                            <div class="d-flex gap-2 w-100">
                                <a href="{{ route('returnitem-edit', $ri->id) }}" class="btn text-white"
                                    style="font-size: 10pt; background-color: rgb(197, 167, 0);">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('returnitem-destroy', $ri->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-danger text-white" style="font-size: 10pt "
                                        onclick="return confirm('Do you want to delete this item?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                @endforeach
            </table>
        </div>
    </x-container>
@endsection

