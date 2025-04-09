@extends('layouts.main-admin')

@section('bcd')
    <span>> <a href="#">Create</a></span>
@endsection

@section('content')
    <x-container-middle>
        <div class="container bg-white rounded-4 p-5 mt-4 border border-1 card">
            <h3>Tambah Produk Baru</h3>
            <form method="POST" action="{{ route('product-store') }}" id="folm">

                @csrf

                <div class="d-flex gap-3">
                    <div class="mt-3 w-50">
                        <label for="product_name">Nama Barang<span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('product_name') is-invalid @enderror" name="product_name" id="product_name" placeholder="Nama Barang"
                            value="{{ old('product_name') }}">
                        @error('product_name')
                            <p class="text-danger">Harap masukkan nama barang.</p>
                        @enderror
                    </div>

                    <div class="mt-3 w-50">
                        <label for="variant">Varian<span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('variant') is-invalid @enderror" name="variant" id="variant" placeholder="Variant"
                            value="{{ old('variant') }}">
                        @error('variant')
                            <p class="text-danger">Harap masukkan varian barang.</p>
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <div class="mt-3 w-50">
                        <label for="fake_product_code">SKU<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="fake_product_code" id="fake_product_code" placeholder="(Dibuat otomatis oleh sistem)" value="{{ old('fake_product_code') }}" disabled>
                    </div>

                    <div class="mt-3 w-50">
                        <label for="stock">Stok<span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('stock') is-invalid @enderror" name="stock" id="stock" placeholder="Stok"
                            value="{{ old('stock') }}">
                        @error('stock')
                            <p class="text-danger">Harap masukkan nilai minimal 0.</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-3">
                    <label>Status<span class="text-danger">*</span></label>

                    <div class="d-flex gap-3">
                        <div class="d-flex gap-2 rounded-3 py-2">
                            <input class="form-check-input" type="radio" name="status" id="status1" value="Ready" @if(old('status') == "Ready") checked @endif checked>
                            <label class="form-check-label" for="status1">Tersedia</label>
                        </div>
                        <div class="d-flex gap-2 rounded-3 py-2">
                            <input class="form-check-input" type="radio" name="status" id="status2" value="Out of Stock" @if(old('status') == "Out of Stock") checked @endif>
                            <label class="form-check-label" for="status2">Stok Kosong</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <div class="mt-3 w-50">
                        <label for="unit">Satuan<span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('unit') is-invalid @enderror" name="unit" id="unit" placeholder="Unit"
                            value="{{ old('unit') }}">
                        @error('unit')
                            <p class="text-danger">Harap masukkan satuan barang.</p>
                        @enderror
                    </div>

                    <div class="mt-3 w-50">
                        <label for="price">Harga<span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('price') is-invalid @enderror" name="price" id="price" placeholder="Harga"
                            value="0">
                        @error('price')
                            <p class="text-danger">Harap masukkan nilai minimal 1.</p>
                        @enderror
                    </div>
                </div>

                @if(in_array(Auth::user()->role->role_name, ['master', 'accounting_admin']))
                    <div class="d-flex gap-3">
                        <div class="mt-3 w-50">
                            <label for="discount">Diskon<span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('discount') is-invalid @enderror" name="discount" id="discount" placeholder="Diskon"
                                value="0">
                            @error('discount')
                                <p class="text-danger">Harap masukkan nilai minimal 0. Gunakan tanda titik untuk desimal.</p>
                            @enderror
                        </div>

                        <div class="mt-3 w-50">
                            <label for="markup">Markup<span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('markup') is-invalid @enderror" name="markup" id="markup" placeholder="Markup"
                                value="0">
                            @error('markup')
                                <p class="text-danger">Harap masukkan nilai minimal 0. Gunakan tanda titik untuk desimal.</p>
                            @enderror
                        </div>
                    </div>
                @endif

                {{-- <div class="mt-3">
                    <label for="type">Jenis Barang<span class="text-danger">*</span></label>
                    <select class="form-select @error('type') is-invalid @enderror" name="type" id="type">
                        <option value="fast moving" @if(old('type') == 'fast moving') selected @endif>Fast Moving</option>
                        <option value="asset" @if(old('type') == 'asset') selected @endif>Aset</option>
                    </select>
                    @error('type')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div> --}}

                <div class="mt-3">
                    <label>Jenis Barang<span class="text-danger">*</span></label>

                    <div class="d-flex gap-3">
                        <div class="d-flex gap-2 rounded-3 py-2">
                            <input class="form-check-input" type="radio" name="type" id="type1" value="fast moving" @if(old('type') == "fast moving") checked @endif checked>
                            <label class="form-check-label" for="type1">Fast Moving</label>
                        </div>
                        <div class="d-flex gap-2 rounded-3 py-2">
                            <input class="form-check-input" type="radio" name="type" id="type2" value="slow moving" @if(old('type') == "slow moving") checked @endif>
                            <label class="form-check-label" for="type2">Slow Moving</label>
                        </div>
                        <div class="d-flex gap-2 rounded-3 py-2">
                            <input class="form-check-input" type="radio" name="type" id="type3" value="asset" @if(old('type') == "asset") checked @endif>
                            <label class="form-check-label" for="type3">Asset</label>
                        </div>
                    </div>
                </div>

                {{-- <div class="mt-3">
                    <label for="condition">Kondisi Barang<span class="text-danger">*</span></label>
                    <select class="form-select @error('condition') is-invalid @enderror" name="condition" id="condition">
                        <option value="good">Bagus</option>
                        <option value="degraded">Rusak</option>
                        <option value="refurbish">Rekondisi</option>
                    </select>
                    @error('condition')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div> --}}

                <div class="mt-3">
                    <label>Kondisi<span class="text-danger">*</span></label>

                    <div class="d-flex gap-3">
                        <div class="d-flex gap-2 rounded-3 py-2">
                            <input class="form-check-input" type="radio" name="condition" id="condition1" value="good" @if(old('condition') == "good") checked @endif checked>
                            <label class="form-check-label" for="condition1">Bagus</label>
                        </div>
                        <div class="d-flex gap-2 rounded-3 py-2">
                            <input class="form-check-input" type="radio" name="condition" id="condition2" value="degraded" @if(old('condition') == "degraded") checked @endif>
                            <label class="form-check-label" for="condition2">Rusak</label>
                        </div>
                        <div class="d-flex gap-2 rounded-3 py-2">
                            <input class="form-check-input" type="radio" name="condition" id="condition3" value="refurbish" @if(old('condition') == "refurbish") checked @endif>
                            <label class="form-check-label" for="condition3">Rekondisi</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Simpan Data Baru">
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
