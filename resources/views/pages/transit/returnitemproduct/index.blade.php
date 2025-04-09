@extends('layouts.main-admin')

@section("content")
<x-container>
    <br>
    <div class="w-100 d-flex align-items-center justify-content-between">
        <h3>Daftar Barang di Pengembalian {{ Carbon\Carbon::parse($return_item->return_date)->translatedFormat('d M Y') }} - {{ $return_item->project->project_name }}</h3>

        {{-- <div class="d-flex gap-3">
            <a class="btn btn-secondary" href="{{ route('purchaseproduct-import', $purchase->id) }}"><i class="bi bi-file-earmark-arrow-down"></i> Import</a>
            <div class="position-relative d-flex flex-column align-items-end">
                <button class="btn btn-secondary" type="button" id="dd-toggler">
                    <i class="bi bi-file-earmark-arrow-up"></i> Export
                </button>
                <div class="bg-white rounded-lg position-absolute z-2 border border-1" id="dd-menu" style="display: none; top: 40px;">
                    <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("purchaseproduct-export", [$purchase->id, 2]) }}" target="blank">Export (PDF Portrait)</a></li>
                    <a class="dropdown-item border border-1 py-2 px-3" href="{{ route("purchaseproduct-export", [$purchase->id, 1]) }}" target="blank">Export (PDF Landscape)</a></li>
                </div>
            </div>
        </div> --}}
    </div>

    <script>
        // $(document).ready(() => {
        //     $("#dd-toggler").click(function(){
        //         $("#dd-menu").toggle();
        //     });
        // });
    </script>

    <hr>

    @if (session()->has("successAddItem"))
        <p class="text-success fw-bold">{{ session("successAddItem") }}</p>
    @elseif (session()->has("successAddImages"))
        <p class="text-success fw-bold">{{ session("successAddImages") }}</p>
    @elseif (session()->has("successRemoveItem"))
        <p class="text-success fw-bold">{{ session("successRemoveItem") }}</p>
    @endif

    <a href="{{ route('returnitem-list-add', $return_item->id) }}" class="btn btn-primary text-white mb-3" style="font-size: 10pt">
        <i class="bi bi-plus-square"></i> Tambah Barang ke Pengembalian
    </a>
    <a href="{{ route('returnitem-image-add', $return_item->id) }}" class="btn btn-success text-white mb-3" style="font-size: 10pt">
        <i class="bi bi-plus-square"></i> Tambah Foto
    </a>

    <br>
    {{-- tabel list data--}}

    <h5>Daftar Barang</h5>
    <div class="overflow-x-auto">
        <table class="w-100">
            <tr>
                <th class="border border-1 border-secondary">No</th>
                <th class="border border-1 border-secondary">Nama Barang</th>
                <th class="border border-1 border-secondary">Variant</th>
                <th class="border border-1 border-secondary">Jumlah</th>
                <th class="border border-1 border-secondary">Aksi</th>
            </tr>

            @foreach ($return_item->return_item_products as $retprod)
                <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                    <td class="border border-1 border-secondary">{{ $loop->iteration }}</td>
                    <td class="border border-1 border-secondary">{{ $retprod->product->product_name }}</td>
                    <td class="border border-1 border-secondary">{{ $retprod->product->variant }}</td>
                    <td class="border border-1 border-secondary">{{ $retprod->qty }}</td>
                    <td class="border border-1 border-secondary">
                        <div class="d-flex gap-5 w-100">
                            <form action="{{ route('returnitem-list-remove', $retprod->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-danger text-white" style="font-size: 10pt " onclick="return confirm('Apakah anda yakin ingin menghapus barang ini dari pengembalian ini?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

            @endforeach
        </table>
        {{-- <div class="d-flex h-100 w-100 justify-content-end gap-3 x-2" style="font-size: 14pt; background: linear-gradient(to right, rgb(113, 113, 113), rgb(213, 207, 207));">
            <div>
                Total:
            </div>
            <div>
                @php
                    $total = 0;
                    foreach ($pp as $purchase_product){
                        $total += $purchase_product->product->price * (1 - ($purchase_product->product->discount / 100));
                    }

                    echo  number_format($total, 0, ',', '.');
                @endphp
            </div>
        </div> --}}
    </div>

    <h5 class="mt-4">Foto Barang</h5>

    <div class="d-flex flex-wrap">
        @forelse ($return_item->return_item_images as $retimg)
            <div class="position-relative imeeji">
                <form action="{{ route('returnitem-image-remove', $retimg->id) }}" method="post" class="position-absolute" style="top: 10px; right: 10px; z-index: 5;">
                    @csrf
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah anda yakin ingin menghapus foto ini dari pengembalian ini?')"><i class="bi bi-trash3"></i></button>
                </form>
                <img src="{{ Storage::url("app/public/" . $retimg->return_image_path) }}" alt="img" style="width: 400px; height: 300px; object-fit: cover;">
            </div>
        @empty
            - N/A -
        @endforelse
    </div>

</x-container>

<script>
    $('.imeeji').on({
        'mouseover': function(){
            $(this).find('img').css('filter', 'brightness(0.5)');
        },
        'mouseout': function(){
            $(this).find('img').css('filter', 'brightness(1)');
        }
    });
</script>
@endsection
