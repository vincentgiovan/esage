@extends('layouts.main-admin')

@section('content')
    <x-container>
        <br>
        <div class="w-100 d-flex align-items-center justify-content-between">
            <h3>Pengembalian Barang</h3>
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

        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-2">
            <div class="d-flex gap-2 ">
                <form action="{{ route('returnitem-index') }}" class="d-flex gap-2">
                    <div class="position-relative">
                    <input type="text" name="search" placeholder="Cari pengembalian..." value="{{ request('search') }}" class="form-control border border-1 border-secondary pe-5" style="width: 300px;">
                        <a href="{{ route('returnitem-index') }}" class="btn position-absolute top-0 end-0"><i class="bi bi-x-lg"></i></a>
                    </div>
                    <button class="btn btn-primary"><i class="bi bi-search"></i></button>
                </form>
            </div>
            <div>
                <a href="{{ route('returnitem-create') }}" class="btn btn-primary text-white" style="font-size: 10pt">
                    <i class="bi bi-plus-square"></i> Buat Pengembalian Barang Baru
                </a>
                @if(in_array(Auth::user()->role->role_name, ['gudang', 'master']))
                    <a href="{{ route('returnitem-conditionvalidation') }}" class="btn btn-success" style="font-size: 10pt">
                        <i class="bi bi-check-square"></i> Validasi Kondisi Barang Pengembalian
                    </a>
                @endif
            </div>
        </div>

        <div class="d-flex w-100 justify-content-end">
            Memperlihatkan {{ $return_items->firstItem() }} - {{ $return_items->lastItem()  }} dari {{ $return_items->total() }} item
        </div>

        {{-- tabel list data--}}
        <div class="overflow-x-auto mt-3">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary">No</th>
                    <th class="border border-1 border-secondary">Tanggal</th>
                    <th class="border border-1 border-secondary">Proyek Asal</th>
                    <th class="border border-1 border-secondary">PIC Return</th>
                    <th class="border border-1 border-secondary">Supir</th>
                    <th class="border border-1 border-secondary">Status</th>
                    <th class="border border-1 border-secondary">Aksi</th>
                </tr>

                @foreach ($return_items as $ri)
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td class="border border-1 border-secondary">{{ ($loop->index + 1) + ((request('page') ?? 1) - 1) * 30 }}</td>
                        <td class="border border-1 border-secondary">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                {{ Carbon\Carbon::parse($ri->return_date)->translatedFormat('d M Y') }}
                                <a href="{{ route('returnitem-list-view', $ri->id) }}" class="btn btn-success">Lihat Item</a>
                            </div>
                            {{-- <ul>
                                <li>Proyek: {{ $ri->project->project_name }}</li>
                                <li>Tanggal: {{ Carbon\Carbon::parse($ri->created_at)->translatedFormat("d M Y") }}</li>
                            </ul> --}}
                        </td>
                        <td class="border border-1 border-secondary">
                            {{ $ri->project->project_name }}
                            {{-- <ul>
                                <li>Nama: {{ $ri->product->product_name }}</li>
                                <li>Varian: {{ $ri->product->variant }}</li>

                                    <li>Harga: {{ number_format($ri->product->price, "2", ",", ".") }}</li>
                                    <li>Diskon: {{ $ri->product->discount }}%</li>

                                <li>Jumlah: {{ $ri->quantity }}</li>
                            </ul> --}}
                        </td>
                        <td class="border border-1 border-secondary">
                            {{ $ri->PIC }}
                            {{-- @if($ri->foto)
                                <img class="w-100" src="{{ Storage::url("app/public/" . $ri->foto) }}">
                            @else
                                N/A
                            @endif --}}
                        </td>
                        <td class="border border-1 border-secondary">{{ $ri->driver }}</td>
                        <td class="border border-1 border-secondary">
                            @if($ri->status == 'Ready to pickup')
                                <i class="bi bi-check-circle-fill fs-4" style="color: green"></i>
                            @else
                                <i class="bi bi-x-circle-fill fs-4" style="color: red"></i>
                            @endif
                        </td>

                        <td class="border border-1 border-secondary">
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

        <div class="mt-4">
            {{ $return_items->links() }}
        </div>
    </x-container>
@endsection

