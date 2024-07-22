@extends('layouts.main-admin')

@section('bcd')
    <span>> <a href="#">Edit</a></span>
@endsection

@section('content')
    <x-container-middle>
        <div class="container bg-white p-5 rounded-4">

            <h2>Edit Item</h2>

            {{-- @csrf kepake untuk token ,wajib --}}

            <form method="POST" action="{{ route('product-edit', $product->id) }}">
                {{-- @csrf kepake untuk token ,wajib --}}
                @csrf

                <div class="mt-3">
                    <label for="product_name">Nama Barang</label>
                    <input type="text" class="form-control" name="product_name" id="product_name" placeholder="Nama Barang"
                        value="{{ old('product_name', $product->product_name) }}">
                    @error('product_name')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="unit">Unit</label>
                    <input type="text" class="form-control" name="unit" id="unit" placeholder="Unit"
                        value="{{ old('unit', $product->unit) }}">
                    @error('unit')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-select">
                        @foreach ($status as $s)
                            <option value="{{ $s }}" @if ($product->status == $s) selected @endif>
                                {{ $s }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="variant">Variant</label>
                    <input type="text" class="form-control" name="variant" id="variant" placeholder="Variant"
                        value="{{ old('variant', $product->variant) }}">
                    @error('variant')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="product_code">Kode Produk</label>
                    <input type="text" class="form-control" name="product_code" id="product_code" placeholder="Kode Produk"
                        value="{{ old('product_code', $product->product_code) }}">
                    @error('product_code')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="price">Harga</label>
                    <input type="number" class="form-control" name="price" id="price" placeholder="Harga"
                        value="{{ old('price', $product->price) }}">
                    @error('price')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="markup">Markup</label>
                    <input type="number" class="form-control" name="markup" id="markup" placeholder="Markup"
                        value="{{ old('markup', $product->markup) }}">
                    @error('markup')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="stock">Stok</label>
                    <input type="number" class="form-control" name="stock" id="stock" placeholder="Stok"
                        value="{{ old('stock', $product->stock) }}">
                    @error('stock')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Edit">
                </div>
            </form>
        </div>
    </x-container-middle>
@endsection
