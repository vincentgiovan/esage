@extends("layouts.main-admin")

@section("content")
    <x-container>

        <br>
        <h2>Request Items</h2>
        <br>

        @if (session()->has('successAddRequest'))
            <p class="text-success fw-bold">{{ session('successAddRequest') }}</p>
        @elseif (session()->has('successEditRequest'))
            <p class="text-success fw-bold">{{ session('successEditRequest') }}</p>
        @elseif (session()->has('successDeleteRequest'))
            <p class="text-success fw-bold">{{ session('successDeleteRequest') }}</p>
        @endif

        <button class="btn btn-primary" onclick="history.back();"><i class="bi bi-arrow-left"></i> Return</button>

        <div class="overflow-x-auto mt-3">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary ">#</th>
                    <th class="border border-1 border-secondary ">Product Name</th>
                    <th class="border border-1 border-secondary ">Product Variant</th>
                    <th class="border border-1 border-secondary ">Price</th>
                    <th class="border border-1 border-secondary ">Discount</th>
                    <th class="border border-1 border-secondary ">Qty</th>
                    {{-- <th class="border border-1 border-secondary ">Aksi</th> --}}
                </tr>

                @foreach ($request_item_products as $r)
                    <tr>
                        <td class="border border-1 border-secondary ">{{ $loop->iteration }}</td>
                        <td class="border border-1 border-secondary ">{{ $r->product->product_name }}</td>
                        <td class="border border-1 border-secondary ">{{ $r->product->variant }}</td>
                        <td class="border border-1 border-secondary ">{{ $r->product->price }}</td>
                        <td class="border border-1 border-secondary ">{{ $r->product->discount }}</td>
                        <td class="border border-1 border-secondary ">{{ $r->quantity }}</td>
                        {{-- <td class="border border-1 border-secondary ">
                            <div class="d-flex gap-2 w-100 justify-content-center">
                                <a href="{{ route('requestitem-edit', $r->id) }}" class="btn btn-success text-white"
                                    style="font-size: 10pt;">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form action="{{ route('requestitem-destroy', $r->id) }}" method="post">
                                    @csrf
                                    <button onclick="return confirm('This request will be deleted, are you sure?')" type="submit" class="btn btn-danger"><i class="bi bi-trash3"></i></button>
                                </form>
                            </div>
                        </td> --}}

                    </tr>
                @endforeach
            </table>
        </div>
    </x-container>

@endsection
