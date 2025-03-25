@extends('layouts.main-admin')

@section('content')
    <x-container>
        <br>
        <div class="w-100 d-flex align-items-center justify-content-between">
            <h3>Validasi Kondisi Barang Pengembalian</h3>
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

        <div class="d-flex w-100 justify-content-between align-items-center mt-3">
            <div class="d-flex" style="gap: 1px;">
                <a href="{{ route('returnitem-conditionvalidation', ['content' => 'unvalidated']) }}" class="btn" style="border-radius: 0; width: 200px; @if(!request('content') || request('content') == 'unvalidated') border-bottom: 3px solid rgb(59, 59, 59); @else border-bottom: none; @endif">Belum Divalidasi</a>
                <a href="{{ route('returnitem-conditionvalidation', ['content' => 'validated']) }}" class="btn" style="border-radius: 0; width: 200px; @if(request('content') == 'validated') border-bottom: 3px solid rgb(59, 59, 59); @else border-bottom: none; @endif">Telah Divalidasi</a>
            </div>
            @if(!request('content') || request('content') == 'unvalidated')
                <div class="d-flex w-100 justify-content-end">
                    Memperlihatkan {{ $unvalidated_return_item_products->firstItem() }} - {{ $unvalidated_return_item_products->lastItem()  }} dari {{ $unvalidated_return_item_products->total() }} item
                </div>
            @endif
            @if(request('content') == 'validated')
                <div class="d-flex w-100 justify-content-end">
                    Memperlihatkan {{ $validated_return_item_products->firstItem() }} - {{ $validated_return_item_products->lastItem()  }} dari {{ $validated_return_item_products->total() }} item
                </div>
            @endif
        </div>

        {{-- tabel list data--}}
        @if(!request('content') || request('content') == 'unvalidated')
            <form action="{{ route('returnitem-saveunvalid') }}" method="post" id="unvalid-return-item-products" class="mt-3">
                @csrf

                <div class="d-flex justify-content-between align-items-center">
                    <button type="submit" class="btn btn-success" id="save-unvalids-btn" style="display: none;">Simpan</button>
                </div>
                <div class="overflow-x-auto mt-2">
                    <table class="w-100">
                        <thead>
                            <tr>
                                <th class="border border-1 border-secondary" rowspan="2">No</th>
                                <th class="border border-1 border-secondary" rowspan="2">Tanggal Pengembalian</th>
                                <th class="border border-1 border-secondary" rowspan="2">Proyek Asal</th>
                                <th class="border border-1 border-secondary" rowspan="2">Nama Produk</th>
                                <th class="border border-1 border-secondary" rowspan="2">Varian</th>
                                <th class="border border-1 border-secondary" rowspan="2">Jumlah</th>
                                {{-- <th class="border border-1 border-secondary" rowspan="2">Status</th> --}}
                                <th class="border border-1 border-secondary" colspan="2" class="text-center">Kondisi Barang</th>
                            </tr>

                            <tr>
                                <th class="border border-1 border-secondary" class="text-center">Bagus</th>
                                <th class="border border-1 border-secondary" class="text-center">Bekas</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($unvalidated_return_item_products as $rip)
                                <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                                    <td class="border border-1 border-secondary">
                                        {{ $loop->iteration }}
                                        <input type="hidden" name="rip[]" value="{{ $rip->id }}">
                                    </td>
                                    <td class="border border-1 border-secondary">{{ Carbon\Carbon::parse($rip->return_item->return_date)->translatedFormat('d M Y') }}</td>
                                    <td class="border border-1 border-secondary">{{ $rip->return_item->project->project_name }}</td>
                                    <td class="border border-1 border-secondary">{{ $rip->product->product_name }}</td>
                                    <td class="border border-1 border-secondary">{{ $rip->product->variant }}</td>
                                    <td class="border border-1 border-secondary">{{ $rip->qty }}</td>
                                    {{-- <td class="border border-1 border-secondary">{{ $rip->status }}</td> --}}
                                    <td class="border border-1 border-secondary" style="width: 100px;">
                                        <input type="number" min="0" max="{{ $rip->qty }}" data-num="{{ $rip->qty }}" value="0" class="w-100 inp-condition num-good" name="good[{{ $rip->id }}]">
                                    </td>
                                    <td class="border border-1 border-secondary" style="width: 100px;">
                                        <input type="number" min="0" max="{{ $rip->qty }}" data-num="{{ $rip->qty }}" value="0" class="w-100 inp-condition num-bad" name="bad[{{ $rip->id }}]">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="border border-1 border-secondary" colspan="8" class="bg-white text-center">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>
        @endif

        {{-- tabel list data--}}
        @if(request('content') == 'validated')
            <div class="mt-4 d-flex align-items-center justify-content-between" class="mt-3">
                <form action="{{ route('returnitem-conditionvalidation') }}" class="d-flex gap-2">
                    <input type="text" name="project" placeholder="Cari project..." value="{{ request('project') }}"
                        class="form-control border border-1 border-secondary">
                    <button class="btn btn-primary"><i class="bi bi-search"></i></button>
                </form>
            </div>
            <div class="overflow-x-auto mt-3">
                <table class="w-100">
                    <tr>
                        <th class="border border-1 border-secondary">No</th>
                        <th class="border border-1 border-secondary">Tanggal Pengembalian</th>
                        <th class="border border-1 border-secondary">Proyek Asal</th>
                        <th class="border border-1 border-secondary">Nama Produk</th>
                        <th class="border border-1 border-secondary">Varian</th>
                        <th class="border border-1 border-secondary">Jumlah</th>
                        <th class="border border-1 border-secondary">Status</th>
                    </tr>

                    @foreach ($validated_return_item_products as $rip)
                        <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                            <td class="border border-1 border-secondary">{{ $loop->iteration }}</td>
                            <td class="border border-1 border-secondary">{{ Carbon\Carbon::parse($rip->return_item->return_date)->translatedFormat('d M Y') }}</td>
                            <td class="border border-1 border-secondary">{{ $rip->return_item->project->project_name }}</td>
                            <td class="border border-1 border-secondary">{{ $rip->product->product_name }}</td>
                            <td class="border border-1 border-secondary">{{ $rip->product->variant }}</td>
                            <td class="border border-1 border-secondary">{{ $rip->qty }}</td>
                            <td class="border border-1 border-secondary">{{ ucwords($rip->product->condition) }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif
    </x-container>

    <script>
        $('.inp-condition').on({
            'input': function(){
                $('#save-unvalids-btn').show();

                if($(this).hasClass('num-good')){
                    const itemNum = $(this).data('num');
                    const currNum = $(this).val();
                    const otherNum = itemNum - currNum;

                    $(this).parent().next().find('input').val(otherNum);
                }
                else {
                    const itemNum = $(this).data('num');
                    const currNum = $(this).val();
                    const otherNum = itemNum - currNum;

                    $(this).parent().prev().find('input').val(otherNum);
                }
            },

            'blur': function(){
                if($(this).val() == ''){
                    $(this).val(0);
                }
            },
        });
    </script>
@endsection

