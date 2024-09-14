@extends('layouts.main-admin')

@section('bcd')
    <span>> <a href="#">Create</a></span>
@endsection

@section('content')
    <x-container-middle>
        <div class="container bg-white rounded-4 p-5 mt-4 border border-1 card">
            <h2 class="text-center fw-bold">Add New Product</h2>
            <form method="POST" action="{{ route('product-store') }}" id="folm">
                {{-- @csrf kepake untuk token ,wajib --}}
                @csrf

                <div class="mt-3">
                    <label for="product_name">Nama Produk</label>
                    <input type="text" class="form-control @error('product_name') is-invalid @enderror" name="product_name" id="product_name" placeholder="Nama Barang"
                        value="{{ old('product_name') }}">
                    @error('product_name')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="variant">Variant</label>
                    <input type="text" class="form-control @error('variant') is-invalid @enderror" name="variant" id="variant" placeholder="Variant"
                        value="{{ old('variant') }}">
                    @error('variant')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="unit">Satuan Produk</label>
                    <input type="text" class="form-control @error('unit') is-invalid @enderror" name="unit" id="unit" placeholder="Unit"
                        value="{{ old('unit') }}">
                    @error('unit')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="stock">Stok</label>
                    <input type="number" class="form-control @error('stock') is-invalid @enderror" name="stock" id="stock" placeholder="Stok"
                        value="{{ old('stock') }}">
                    @error('stock')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="Ready">Ready</option>
                        <option value="Out of Stock">Out Of Stock</option>
                    </select>
                    @error('status')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="fake_product_code">Kode Produk</label>
                    <input type="text" class="form-control @error('product_code') is-invalid @enderror" name="fake_product_code" id="fake_product_code" placeholder="Kode Produk"
                        value="{{ old('fake_product_code') }}" disabled>
                    @error('product_code')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="price">Harga</label>
                    <input type="number" class="form-control @error('price') is-invalid @enderror" name="price" id="price" placeholder="Harga"
                        value="{{ old('price') }}">
                    @error('price')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="markup">Markup</label>
                    <input type="number" class="form-control @error('markup') is-invalid @enderror" name="markup" id="markup" placeholder="Markup"
                        value="{{ old('markup') }}">
                    @error('markup')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    @if (session()->has('passwordNotConfirmed'))
                        <p class="text-success fw-bold">{{ session('passwordNotConfirmed') }}</p>
                    @endif
                    <input type="submit" class="btn btn-success px-3 py-1" value="Submit">
                </div>
            </form>

        </div>
    </x-container-middle>

    <script>
        // Auto ubah status berdasarkan jumlah stock (kalo 0 jadi out of stock, kalo ngga ya ready)
        $("#stock").change(function(){
            if($(this).val() == 0){
                $("#status").find('option[value="Out of Stock"]').prop("selected", true);
                $("#status").find('option[value="Ready"]').prop("selected", false);
            } else {
                $("#status").find('option[value="Out of Stock"]').prop("selected", false);
                $("#status").find('option[value="Ready"]').prop("selected", true);
            }
        });

        function makeCapitalizedEachWordAndNoSpace(text){
            // Remove extra spaces, split into words, capitalize each, and join back together
            const processedText = text
                .trim() // Trim any leading/trailing spaces
                .split(/\s+/) // Split by any space characters
                .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()) // Capitalize each word
                .join(''); // Join without spaces

            return processedText;
        }

        $("#variant").change(function(){
            const inputProductName = $("#product_name").val();
            const inputVariant = $(this).val();

            if(!inputProductName || !inputVariant){
                $("#fake_product_code").val("");

                return;
            }

            const allProducts = @json($products);

            let n = 1;
            for (let product of allProducts) {
                if (product.product_name == inputProductName && product.variant == inputVariant) {
                    n++;
                }
            }

            let productCode = `${makeCapitalizedEachWordAndNoSpace(inputProductName)}/${makeCapitalizedEachWordAndNoSpace(inputVariant)}/${n.toString()}`;

            $("#fake_product_code").val(productCode);
        });

        $("#product_name").change(function(){
            const inputProductName = $(this).val();
            const inputVariant = $("#variant").val();

            if(!inputProductName || !inputVariant){
                $("#fake_product_code").val("");

                return;
            }

            const allProducts = @json($products);

            let n = 1;
            for (let product of allProducts) {
                if (product.product_name == inputProductName && product.variant == inputVariant) {
                    n++;
                }
            }

            let productCode = `${makeCapitalizedEachWordAndNoSpace(inputProductName)}/${makeCapitalizedEachWordAndNoSpace(inputVariant)}/${n.toString()}`;

            $("#fake_product_code").val(productCode);
        });

        $("#folm").on("submit", function(event){
            event.preventDefault();

            $(this).append($("<input>").attr({"type":"hidden", "name": "product_code", "value": $("#fake_product_code").val()}));

            this.submit();
        });
    </script>
@endsection
