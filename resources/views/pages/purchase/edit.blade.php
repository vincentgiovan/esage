@extends('layouts.main-admin')

@section("content")

    <div class="d-flex justify-content-center align-items-center" style="min-height:100vh">
    <div class="container">

        <h2>Edit Item</h2>

{{-- @csrf kepake untuk token ,wajib --}}

            <form method="POST" action="{{ route("purchase-edit",$purchase->id ) }}"> 0
 ..               {{-- @csrf kepake untuk token ,wajib --}}
                            @csrf
                            <div

                            .
                            ..
                            class="mt-3">
                                <select name="product_name" class="form-select">
                                    @foreach ($product_name as $s)
                                        <option value="{{ $s }}" @if ($product->product_name == $s ) selected @endif>{{ $s }}</option>
                                    @endforeach

                                </select>
                                @error("product_name")
                                <p style = "color: red; font-size: 10px;">{{$message }}</p>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <select name="supplier_id" class="form-select">
                                    @foreach ($supplier_id as $s)
                                        <option value="{{ $s }}" @if ($product->supplier_id == $s ) selected @endif>{{ $s }}</option>
                                    @endforeach

                                </select>
                                @error("supplier_id")
                                <p style = "color: red; font-size: 10px;">{{$message }}</p>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <input type="text" class="form-control" name="variant" placeholder="Variant"  value = "{{ old("variant", $product->variant ) }}">
                                @error("variant")
                                <p style = "color: red; font-size: 10px;">{{$message }}</p>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <input type="text" class="form-control" name="product_code" placeholder="Kode Produk"  value = "{{ old("product_code", $product->product_code) }}">
                                @error("product_code")
                                <p style = "color: red; font-size: 10px;">{{$message }}</p>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <input type="number" class="form-control" name="price" placeholder="Harga" value = "{{ old("price" , $product->price)}}">
                                @error("price")
                                <p style = "color: red; font-size: 10px;">{{$message }}</p>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <input type="number" class="form-control" name="discount" placeholder="Diskon"  value = "{{ old("discount", $product->discount) }}">
                                @error("discount")
                                <p style = "color: red; font-size: 10px;">{{$message  }}</p>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <input type="number" class="form-control" name="stock" placeholder="Stok"  value = "{{ old("stock", $product->stock) }}">
                                @error("stock")
                                <p style = "color: red; font-size: 10px;">{{$message  }}</p>
                                @enderror
                            </div>

                            <div class="mt-3">
                            <input type="submit" class="btn btn-success px-3 py-1" value="Edit">
                            </div>
                        </form>
    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


@endsection
