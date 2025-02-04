@extends('layouts.main-admin')

@section('content')
    <x-container>
        <br>
        <div class="w-100 d-flex align-items-center justify-content-between">
            <h2>Validasi Kondisi Barang Pengembalian</h2>
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
        {{-- <script>
            $(document).ready(() => {
                $("#dd-toggler").click(function(){
                    $("#dd-menu").toggle();
                });
            });
        </script> --}}
        <hr>

        @if (session()->has('successValidateCondition'))
            <p class="text-success fw-bold">{{ session('successValidateCondition') }}</p>
        @elseif (session()->has('successEditReturnItem'))
            <p class="text-success fw-bold">{{ session('successEditReturnItem') }}</p>
        @elseif (session()->has('successDeleteReturnItem'))
            <p class="text-success fw-bold">{{ session('successDeleteReturnItem') }}</p>
        @endif

        <!-- tabel list data-->
        <form action="{{ route('returnitem-saveunvalid') }}" method="post" id="unvalid-return-item-products">
            @csrf

            <div class="d-flex justify-content-between align-items-center">
                <h5>Kondisi Barang Belum Divalidasi</h5>
                <button type="submit" class="btn btn-success" id="save-unvalids-btn" style="display: none;">Simpan</button>
            </div>
            <div class="overflow-x-auto mt-2">
                <table class="w-100">
                    <tr>
                        <th rowspan="2">No</th>
                        <th rowspan="2">Tanggal Pengembalian</th>
                        <th rowspan="2">Proyek Asal</th>
                        <th rowspan="2">Nama Produk</th>
                        <th rowspan="2">Varian</th>
                        <th rowspan="2">Jumlah</th>
                        {{-- <th rowspan="2">Status</th> --}}
                        <th colspan="2" class="text-center">Kondisi Barang</th>
                    </tr>

                    <tr>
                        <th class="text-center">Bagus</th>
                        <th class="text-center">Bekas</th>
                    </tr>

                    @foreach ($unvalidated_return_item_products as $rip)
                        <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                            <td>
                                {{ $loop->iteration }}
                                <input type="hidden" name="rip[]" value="{{ $rip->id }}">
                            </td>
                            <td>{{ Carbon\Carbon::parse($rip->return_item->return_date)->translatedFormat('d M Y') }}</td>
                            <td>{{ $rip->return_item->project->project_name }}</td>
                            <td>{{ $rip->product->product_name }}</td>
                            <td>{{ $rip->product->variant }}</td>
                            <td>{{ $rip->qty }}</td>
                            {{-- <td>{{ $rip->status }}</td> --}}
                            <td style="width: 100px;">
                                <input type="text" value="0" class="w-100 inp-condition" name="good[{{ $rip->id }}]">
                            </td>
                            <td style="width: 100px;">
                                <input type="text" value="0" class="w-100 inp-condition" name="bad[{{ $rip->id }}]">
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </form>

        <!-- tabel list data-->
        <h5 class="mt-4">Kondisi Barang Telah Divalidasi</h5>
        <div class="overflow-x-auto">
            <table class="w-100">
                <tr>
                    <th>No</th>
                    <th>Tanggal Pengembalian</th>
                    <th>Proyek Asal</th>
                    <th>Nama Produk</th>
                    <th>Varian</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                </tr>

                @foreach ($validated_return_item_products as $rip)
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ Carbon\Carbon::parse($rip->return_item->return_date)->translatedFormat('d M Y') }}</td>
                        <td>{{ $rip->return_item->project->project_name }}</td>
                        <td>{{ $rip->product->product_name }}</td>
                        <td>{{ $rip->product->variant }}</td>
                        <td>{{ $rip->qty }}</td>
                        <td>{{ ucwords($rip->product->condition) }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </x-container>

    <script>
        $('.inp-condition').on('change', function(){
            $('#save-unvalids-btn').show();
        });
    </script>
@endsection

