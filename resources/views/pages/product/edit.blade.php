@extends('layouts.main-admin')

@section('bcd')
    <span>> <a href="#">Edit</a></span>
@endsection

@section('content')
    <x-container-middle>
        <div class="container bg-white py-4 px-5 rounded-4 mt-4 border border-1 card">

            <h2>Edit Data Barang</h2>

            {{-- @csrf kepake untuk token ,wajib --}}

            <form method="POST" action="{{ route('product-edit', $product->id) }}" id="folm">
                {{-- @csrf kepake untuk token ,wajib --}}
                @csrf

                <div class="mt-3">
                    <label for="product_name">Nama Barang</label>
                    <input type="text" class="form-control" name="product_name" id="product_name" placeholder="Nama Barang"
                        value="{{ old('product_name', $product->product_name) }}"
                        @if(in_array(Auth::user()->role->role_name, ['purchasing_admin', 'gudang'])) disabled @endif/>

                    @error('product_name')
                        <p class="text-danger">Harap masukkan nama barang.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="variant">Varian</label>
                    <input type="text" class="form-control" name="variant" id="variant" placeholder="Variant"
                        value="{{ old('variant', $product->variant) }}"
                        @if(in_array(Auth::user()->role->role_name, ['purchasing_admin', 'gudang'])) disabled @endif/>

                    @error('variant')
                        <p class="text-danger">Harap masukkan varian barang.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="unit">Satuan</label>
                    <input type="text" class="form-control" name="unit" id="unit" placeholder="Unit"
                        value="{{ old('unit', $product->unit) }}"
                        @if(in_array(Auth::user()->role->role_name, ['purchasing_admin', 'gudang'])) disabled @endif/>

                    @error('unit')
                        <p class="text-danger">Harap masukkan satuan barang.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="stock">Stok</label>
                    <input type="number" class="form-control" name="stock" id="stock" placeholder="Stok"
                        value="{{ old('stock', $product->stock) }}"
                        @if(in_array(Auth::user()->role->role_name, ['purchasing_admin'])) disabled @endif/>

                    @error('stock')
                        <p class="text-danger">Harap masukkan nilai minimal 0.</p>
                    @enderror
                </div>

                {{-- <div class="mt-3">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-select"
                        @if(in_array(Auth::user()->role->role_name, ['purchasing_admin'])) disabled @endif>

                        <option value="Ready" @if(old('status', $product->status) == 'Ready') selected @endif>Tersedia</option>
                        <option value="Out of Stock" @if(old('status', $product->status) == 'Out of Stock') selected @endif>Stok kosong</option>
                    </select>
                </div> --}}

                <div class="mt-3">
                    <label>Status</label>

                    <div class="d-flex gap-3">
                        <div class="d-flex gap-2 rounded-3 py-2">
                            <input class="form-check-input" type="radio" name="status" id="status1" value="Ready" @if(old('status', $product->status) == "Ready") checked @endif checked @if(in_array(Auth::user()->role->role_name, ['purchasing_admin', 'gudang'])) disabled @endif>
                            <label class="form-check-label" for="status1">Tersedia</label>
                        </div>
                        <div class="d-flex gap-2 rounded-3 py-2">
                            <input class="form-check-input" type="radio" name="status" id="status2" value="Out of Stock" @if(old('status', $product->status) == "Out of Stock") checked @endif @if(in_array(Auth::user()->role->role_name, ['purchasing_admin', 'gudang'])) disabled @endif>
                            <label class="form-check-label" for="status2">Stok Kosong</label>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <label for="fake_product_code">SKU</label>
                    <input type="text" class="form-control" name="fake_product_code" id="fake_product_code" value="{{ old('product_code', $product->product_code) }}" placeholder="(Dibuat otomatis oleh sistem)" disabled>
                </div>

                <div class="mt-3">
                    <label for="price">Harga</label>
                    <input type="number" class="form-control" name="price" id="price" placeholder="Harga"
                        value="{{ old('price', $product->price) }}"
                        @if(in_array(Auth::user()->role->role_name, ['purchasing_admin', 'gudang'])) disabled @endif/>

                    @error('price')
                        <p class="text-danger">Harap masukkan nilai minimal 1.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="markup">Markup</label>
                    <input type="text" class="form-control" name="markup" id="markup" placeholder="Markup"
                        value="{{ old('markup', $product->markup) }}"
                        @if(in_array(Auth::user()->role->role_name, ['gudang'])) disabled @endif/>

                    @error('markup')
                        <p class="text-danger">Harap masukkan nilai minimal 0. Gunakan tanda titik untuk desimal.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="discount">Diskon</label>
                    <input type="text" class="form-control @error('discount') is-invalid @enderror" name="discount" id="discount" placeholder="Diskon"
                        value="{{ old('discount', $product->discount) }}">
                    @error('discount')
                        <p class="text-danger">Harap masukkan nilai minimal 0. Gunakan tanda titik untuk desimal.</p>
                    @enderror
                </div>

                {{-- <div class="mt-3">
                    <label for="type">Jenis Barang</label>
                    <select class="form-select @error('type') is-invalid @enderror" name="type" id="type">
                        <option value="fast moving" @if(old('type', $product->condition) == 'fast moving') selected @endif>Fast Moving</option>
                        <option value="asset" @if(old('type', $product->condition) == 'asset') selected @endif>Aset</option>
                    </select>
                    @error('type')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div> --}}

                <div class="mt-3">
                    <label>Jenis Barang</label>

                    <div class="d-flex gap-3">
                        <div class="d-flex gap-2 rounded-3 py-2">
                            <input class="form-check-input" type="radio" name="type" id="type1" value="fast moving" @if(old('type', $product->type) == "fast moving") checked @endif checked>
                            <label class="form-check-label" for="type1">Fast Moving</label>
                        </div>
                        <div class="d-flex gap-2 rounded-3 py-2">
                            <input class="form-check-input" type="radio" name="type" id="type2" value="asset" @if(old('type', $product->type) == "asset") checked @endif>
                            <label class="form-check-label" for="type2">Asset</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Simpan Perubahan">
                </div>
            </form>
        </div>
    </x-container-middle>

    <script>
        $("#stock").change(function(){
            if($(this).val() == 0){
                $("#status").find('option[value="Out of Stock"]').prop("selected", true);
                $("#status").find('option[value="Ready"]').prop("selected", false);
            } else {
                $("#status").find('option[value="Out of Stock"]').prop("selected", false);
                $("#status").find('option[value="Ready"]').prop("selected", true);
            }
        });

        $("#folm").on("submit", function(event){
            event.preventDefault();

            $(this).append($("<input>").attr({"type":"hidden", "name": "product_code", "value": $("#fake_product_code").val()}));

            this.submit();
        });
    </script>
@endsection
