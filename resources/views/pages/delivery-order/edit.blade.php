@extends('layouts.main-admin')

@section("content")

@include('navbar')
    <div class="d-flex justify-content-center align-items-center" style="min-height:100vh">
    <div class="container">
        ,
        <h2>Edit Item</h2>
        <form method="POST" action="/dashboard/{{ $product->id }}/edit">
{{-- @csrf kepake untuk token ,wajib --}}
            @csrf
            <input type="text" name="product_name" placeholder="Nama Barang" value = "{{ old("product_name", $product->product_name ) }}">
                @error("product_name")
                <p style = "color: red; font-size: 10px;">{{$message }}</p>
                @enderror

                <input type="text" name="price" placeholder="Harga" value = "{{ old("price", $product->price) }}">
                @error("price")
                <p style = "color: red; font-size: 10px;">{{$message }}</p>
                @enderror

                <input type="number" name="stock" placeholder="Stok"  value = "{{ old("stock", $product->stock) }}">
                @error("stock")
                <p style = "color: red; font-size: 10px;">{{$message  }}</p>
                @enderror

                <input type="text" name="variant" placeholder="Variant"  value = "{{ old("variant", $product->variant) }}">
                @error("variant")
                <p style = "color: red; font-size: 10px;">{{$message }}</p>
                @enderror

                <input type="text" name="unit" placeholder="Unit"  value = "{{ old("unit", $product->unit) }}">
                @error("unit")
                <p style = "color: red; font-size: 10px;">{{$message }}</p>
                @enderror

            @if (session()->has("passwordNotConfirmed"))
            <p class="text-success fw-bold">{{ session("passwordNotConfirmed") }}</p>

            @endif
            <input type="submit" value="Edit">

        </form>
    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


@endsection
