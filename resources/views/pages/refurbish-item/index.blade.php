@extends("layouts.main-admin")

@section("content")
    <x-container>

        <br>
        <h2>Rekondisi Barang</h2>
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

        <div class="overflow-x-auto mt-3">
            <table class="w-100">
                <tr>
                    <th>No</th>
                    <th>Tanggal Rekondisi</th>
                    <th>Nama Barang</th>
                    <th>Varian</th>
                    <th>Jumlah</th>
                    <th>Catatan</th>
                    <th>Aksi</th>
                </tr>

                @foreach ($refurbish_items as $r)
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ Carbon\Carbon::parse($r->refurbish_date)->translatedFormat("d M Y") }}</td>
                        <td>{{ $r->product->product_name }}</td>
                        <td>{{ $r->product->variant }}</td>
                        <td>{{ $r->qty }}</td>
                        <td>{{ $r->notes }}</td>
                        <td>
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
    </x-container>

@endsection
