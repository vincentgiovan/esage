
@extends('layouts.main-admin')

@section("content")

    <div class="d-flex justify-content-center align-items-center" style="min-height:100vh">
    <div class="container">

        <h2>Add New Purchase</h2>

            <form method="POST" action="{{ route("purchaseproduct-store1", $purchase->id ) }}">
            {{-- @csrf kepake untuk token ,wajib --}}
                @csrf
                <div class="mt-3">
                    <select name="product_name" class="form-select" id="select-product-dropdown">
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @if ($product->product_name == old("product_name")) selected @endif>{{ $product->product_name }}</option>
                        @endforeach

                    </select>
                    @error("product_name")
                    <p style = "color: red; font-size: 10px;">{{$message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <input type="number" class="form-control" name="price" id="price"  placeholder="Price"  value = "{{ old("price") }}">
                    @error("price")
                    <p style = "color: red; font-size: 10px;">{{$message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <input type="number" class="form-control" name="discount" placeholder="Diskon"  value = "{{ old("discount") }}">
                    @error("discount")
                    <p style = "color: red; font-size: 10px;">{{$message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <input type="number" class="form-control" name="quantity" id="quantity"  placeholder="Quantity" value = "{{ old("quantity")}}">
                    @error("quantity")
                    <p style = "color: red; font-size: 10px;">{{$message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Add">
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    

@endsection
