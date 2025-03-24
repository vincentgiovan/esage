@extends("layouts.main-admin")

@section("content")
    <x-container>

        <br>
        <h3>Rekondisi Barang</h3>
        <hr>

        @if (session()->has('successCreateRefurbishItem'))
            <p class="text-success fw-bold">{{ session('successCreateRefurbishItem') }}</p>
        @elseif (session()->has('successEditRequest'))
            <p class="text-success fw-bold">{{ session('successEditRequest') }}</p>
        @elseif (session()->has('successDeleteRefurbishItem'))
            <p class="text-success fw-bold">{{ session('successDeleteRefurbishItem') }}</p>
        @endif

        <a href="{{ route('refurbishitem-create') }}" class="btn btn-primary text-white" style="font-size: 10pt">
            <i class="bi bi-plus-square"></i>
            Buat Rekondisi Baru</a>

        <div class="d-flex w-100 justify-content-end">
            Memperlihatkan {{ $refurbish_items->firstItem() }} - {{ $refurbish_items->lastItem()  }} dari {{ $refurbish_items->total() }} item
        </div>

        <div class="overflow-x-auto mt-3">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary">No</th>
                    <th class="border border-1 border-secondary">Tanggal Rekondisi</th>
                    <th class="border border-1 border-secondary">Nama Barang</th>
                    <th class="border border-1 border-secondary">Varian</th>
                    <th class="border border-1 border-secondary">Jumlah</th>
                    <th class="border border-1 border-secondary">Catatan</th>
                    <th class="border border-1 border-secondary">Aksi</th>
                </tr>

                @foreach ($refurbish_items as $r)
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td class="border border-1 border-secondary">{{ ($loop->index + 1) + ((request('page') ?? 1) - 1) * 30 }}</td>
                        <td class="border border-1 border-secondary">{{ Carbon\Carbon::parse($r->refurbish_date)->translatedFormat("d M Y") }}</td>
                        <td class="border border-1 border-secondary">{{ $r->product->product_name }}</td>
                        <td class="border border-1 border-secondary">{{ $r->product->variant }}</td>
                        <td class="border border-1 border-secondary">{{ $r->qty }}</td>
                        <td class="border border-1 border-secondary">{{ $r->notes }}</td>
                        <td class="border border-1 border-secondary">
                            <div class="d-flex gap-2 w-100">
                                {{-- <a href="{{ route('requestitem-show', $r->id) }}" class="btn btn-success text-white"
                                    style="font-size: 10pt;">
                                    <i class="bi bi-eye"></i>
                                </a> --}}
                                {{-- <a href="{{ route('refurbishitem-edit', $r->id) }}" class="btn btn-warning text-white"
                                    style="font-size: 10pt; background-color: rgb(197, 167, 0);">
                                    <i class="bi bi-pencil"></i>
                                </a> --}}
                                <form action="{{ route('refurbishitem-destroy', $r->id) }}" method="post">
                                    @csrf
                                    <button onclick="return confirm('This request will be deleted, are you sure?')" type="submit" class="btn btn-danger"><i class="bi bi-trash3"></i></button>
                                </form>
                            </div>
                        </td>

                    </tr>
                @endforeach
            </table>
        </div>

        <div class="mt-4">
            {{ $refurbish_items->links() }}
        </div>
    </x-container>

@endsection
