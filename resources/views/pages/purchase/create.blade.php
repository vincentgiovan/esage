@extends('layouts.main-admin')

@section('content')
    <x-container>
        <form method="POST" action="{{ route('purchase-store') }}" id="bikinpurchase">
            @csrf

            <h3 class="mt-4">Tambah Pembelian Baru</h3>

            <div class="container bg-white rounded-4 p-4 border border-1 card mt-4">
                <h4>Data Pembelian</h4>

                <div class="d-flex gap-3">
                    <div class="mt-3 w-50">
                        <label for="partner_id">Supplier</label>
                        <select name="partner_id" id="partner_id" class="form-select">
                            @foreach ($supplier as $s)
                                <option value="{{ $s->id }}">
                                    {{ $s->partner_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-3 w-50">
                        <label for="purchase_date">Tanggal Pembelian</label>
                        <input type="date" class="form-control @error("purchase_date") is-invalid @enderror" name="purchase_date" id="purchase_date"
                            value="{{ Carbon\Carbon::today()->format('Y-m-d') }}">
                        <p class="invalid-feedback">Harap masukkan tanggal pembelian</p>
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <div class="mt-3 w-50">
                        <label for="purchase_deadline">Deadline/Tenggat Pembelian</label>
                        <input type="date" class="form-control @error("purchase_deadline") is-invalid @enderror" name="purchase_deadline" id="purchase_deadline"
                            value="{{ Carbon\Carbon::today()->format('Y-m-d') }}">
                        <p class="invalid-feedback">Harap masukkan deadline/tenggat pembelian.</p>
                    </div>

                    <div class="mt-3 w-50">
                        <label for="fakeregister">SKU</label>
                        <input type="text" class="form-control" id="_register" name="_register" placeholder="(Dibuat otomatis oleh sistem)"  value="{{ __('DO/' . Carbon\Carbon::today()->format('dmY') . '/' . $purchases->where('purchase_date', Carbon\Carbon::today()->format('Y-m-d'))->count() + 1) }}" disabled>
                            <input type="hidden" name="register" id="register" value="{{ __('DO/' . Carbon\Carbon::today()->format('dmY') . '/' . $purchases->where('purchase_date', Carbon\Carbon::today()->format('Y-m-d'))->count() + 1) }}">
                    </div>
                </div>

                <div class="mt-3">
                    <label>Status Pembelian</label>

                    <div class="d-flex gap-3">
                        <div class="d-flex gap-2 rounded-3 py-2">
                            <input class="form-check-input" type="radio" name="purchase_status" id="purchase_status1" value="Ordered" @if(old('purchase_status') == "Ordered") checked @endif checked>
                            <label class="form-check-label" for="purchase_status1">Telah dipesan</label>
                        </div>
                        <div class="d-flex gap-2 rounded-3 py-2">
                            <input class="form-check-input" type="radio" name="purchase_status" id="purchase_status2" value="Retrieved" @if(old('purchase_status') == "Retrieved") checked @endif>
                            <label class="form-check-label" for="purchase_status2">Diterima</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container bg-white rounded-4 p-4 border border-1 card mt-4">
                <h4>Daftar Barang</h4>

                <p class="fst-italic fw-semibold mt-3">Barang yang sudah teregistrasi di gudang</p>
                <div class="overflow-x-auto">
                    <table class="w-100">
                        <thead>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </thead>
                        <tbody id="isibody-registered">
                            <tr>
                                <td>
                                    <select name="products[]" class="form-select select2">
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->product_name }} - {{ $product->variant }} (Harga: Rp {{ number_format($product->price, 2, ',', '.') }}, Stok:  {{ $product->stock }}, Diskon: {{ $product->discount }}%)
                                        </option>
                                    @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control qty-input" name="quantities[]" min="1" value="1">
                                </td>
                                <td><button type="button" class="btn btn-danger remove-row-btn"><i class="bi bi-trash3"></i></button></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="button" class="btn btn-primary" id="add-row-btn-registered">Tambah Data Baru</button>
                    </div>
                </div>

                <p class="fst-italic fw-semibold mt-3">Barang baru</p>
                <div class="d-flex flex-column">
                    <div class="d-flex flex-column">
                        <table class="w-100">
                            <thead>
                                <th>Barang</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                            </thead>
                            <tbody id="isibody-unregistered">
                                <tr>
                                    <td>
                                        <div class="d-flex flex-column gap-2">
                                            <div>
                                                <label for="product_name">Nama Produk</label>
                                                <input type="text" class="form-control productName" name="product_name[]" placeholder="Nama Barang" value = "{{ old("product_name" ) }}">
                                                <p class="invalid-feedback errProductName"></p>
                                            </div>

                                            <button class="btn btn-secondary newprod-detail-btn" type="button">Sembunyikan detail</button>

                                            <div class="collapse.show gap-2 newprod-detail-area">
                                                <div>
                                                    <label for="variant">Variant</label>
                                                    <input type="text" class="form-control variant" name="variant[]" placeholder="Variant">
                                                    <p class="invalid-feedback errVariant"></p>
                                                </div>

                                                <div class="mt-2">
                                                    <label for="product_code">SKU</label>
                                                    <input type="text" class="form-control fakeCode" name="fake_product_code" placeholder="(Dibuat otomatis oleh sistem)" disabled>
                                                    <input type="hidden" name="product_code[]" class="productCode">
                                                    <p class="invalid-feedback errProductCode"></p>
                                                </div>

                                                <div class="mt-2">
                                                    <div class="unit">Satuan</div>
                                                    <input type="text" class="form-control" name="unit[]" placeholder="Unit">
                                                    <p class="invalid-feedback errUnit"></p>
                                                </div>

                                                <div class="mt-2">
                                                    <label for="price">Harga</label>
                                                    <input type="number" class="form-control price" name="price[]" placeholder="Harga" value="0">
                                                    <p class="invalid-feedback errPrice"></p>
                                                </div>

                                                <div class="mt-2">
                                                    <label for="markup">Markup</label>
                                                    <input type="text" class="form-control markup" name="markup[]" placeholder="Markup"  value="0">
                                                    <p class="invalid-feedback errMarkup"></p>
                                                </div>

                                                <div class="mt-2">
                                                    <label for="discount">Diskon</label>
                                                    <input type="text" class="form-control discount" name="discount[]" placeholder="Diskon"  value="0">
                                                    <p class="invalid-feedback errDiscount"></p>
                                                </div>

                                                <div class="mt-2">
                                                    <label>Jenis Barang</label>

                                                    <div class="d-flex gap-3">
                                                        <div class="d-flex gap-2 rounded-3 py-2">
                                                            <input class="form-check-input type1" type="radio" name="type[{{ round(microtime(true) * 1000) }}]" id="type{{ round(microtime(true) * 1000) }}" value="fast moving" checked>
                                                            <label class="form-check-label" for="type{{ round(microtime(true) * 1000) }}">Fast Moving</label>
                                                        </div>
                                                        <div class="d-flex gap-2 rounded-3 py-2">
                                                            <input class="form-check-input type2" type="radio" name="type[{{ round(microtime(true) * 1000) }}]" id="type{{ round(microtime(true) * 1000) }}2" value="asset">
                                                            <label class="form-check-label" for="type{{ round(microtime(true) * 1000) }}2">Aset</label>
                                                        </div>
                                                    </div>
                                                    <p class="invalid-feedback errType"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex h-100 align-items-start">
                                            <input type="number" class="form-control qty-input" name="quantities[]" min="1" value="1">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex h-100 align-items-start">
                                            <button type="button" class="btn btn-danger remove-row-btn"><i class="bi bi-trash3"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="button" class="btn btn-primary" id="add-row-btn-unregistered">Tambah Data Baru</button>
                    </div>
                </div>

                <div>
                    <button type="button" class="btn btn-success px-3 py-1" id="submit-btn">Simpan</button>
                </div>
            </div>
        </form>
    </x-container>

    <script>
        function formatNumber(number) {
            return new Intl.NumberFormat('de-DE', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }).format(number);
        }

        function formatCurrency(number){
            return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(number);
        }

        function formatDate(dateString) {
            let date = new Date(dateString);
            let day = String(date.getDate()).padStart(2, '0');
            let month = String(date.getMonth() + 1).padStart(2, '0');
            let year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }

        function makeCapitalizedEachWordAndNoSpace(text){
            // Remove extra spaces, split into words, capitalize each, and join back together
            const processedText = text
                .trim() // Trim any leading/trailing spaces
                .split(/\s+/) // Split by any space characters
                .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()) // Capitalize each word
                .join(''); // Join without spaces

            return processedText;
        }

        // Ambil data dari Laravel
        const allpurchasesdata = @json($purchases);
        const allproducts = @json($products);

        $(document).on('click', '.remove-row-btn', function(){
            if($(this).closest('tbody').find('tr').length > 1){
                $(this).closest('tr').remove();
            }
        });

        $(document).on('change', '.qty-input', function(){
            if($(this).val() < 1){
                $(this).val(1);
            }
        });

        $(document).on('click', '.newprod-detail-btn', function(){
            const botan = $(this);
            $(this).closest('td').find('.newprod-detail-area').toggle(0, function(){
                if($(this).is(':visible')){
                    botan.text('Sembuyikan Detail');
                } else {
                    botan.text('Perlihatkan Detail');
                }
            });
        });

        $(document).on('change', '.productName', function(){
            if($(this).closest('td').find('.variant').val()){
                const inputProductName = $(this).val();
                const inputVariant = $(this).closest('td').find('.variant').val();

                const allProducts = @json($products);

                let n = 1;
                for (let product of allProducts) {
                    if (product.product_name == inputProductName && product.variant == inputVariant) {
                        n++;
                    }
                }

                const productCode = `${makeCapitalizedEachWordAndNoSpace(inputProductName)}/${makeCapitalizedEachWordAndNoSpace(inputVariant)}/${n.toString()}`;

                console.log(productCode);

                $(this).closest('td').find('.fakeCode').val(productCode);
                $(this).closest('td').find('.productCode').val(productCode);
            }
        });

        $(document).on('change', '.variant', function(){
            if($(this).closest('td').find('.productName').val()){
                const inputProductName = $(this).closest('td').find('.productName').val();
                const inputVariant = $(this).val();

                const allProducts = @json($products);

                let n = 1;
                for (let product of allProducts) {
                    if (product.product_name == inputProductName && product.variant == inputVariant) {
                        n++;
                    }
                }

                const productCode = `${makeCapitalizedEachWordAndNoSpace(inputProductName)}/${makeCapitalizedEachWordAndNoSpace(inputVariant)}/${n.toString()}`;

                $(this).closest('td').find('.fakeCode').val(productCode);
                $(this).closest('td').find('.productCode').val(productCode);
            }
        });

        $(document).ready(() => {
            $('#add-row-btn-registered').on('click', function(){
                const newProdSel = $('<select>').attr('name', 'products[]').addClass('form-select select2');
                allproducts.forEach(prod => {
                    newProdSel.append($('<option>').attr('value', prod.id).text(`${prod.product_name} - ${prod.variant} (Harga: Rp ${formatCurrency(prod.price)}, Stok: ${[prod.stock]}, Diskon: ${prod.discount}%)`));
                });

                $('#isibody-registered').append(
                    $('<tr>')
                        .append(
                            $('<td>').append(newProdSel)
                        )
                        .append(
                            $('<td>').append(
                                $('<input>').attr({'type': 'number', 'name': 'quantities[]', 'min': 1}).addClass('form-control qty-input').val(1)
                            )
                        )
                        .append(
                            $('<td>').append(
                                $('<button>').attr('type', 'button').addClass('btn btn-danger remove-row-btn').html('<i class="bi bi-trash3"></i>')
                            )
                        )
                );

                reinitializeselect2();
            });

            $('#add-row-btn-unregistered').on('click', function(){
                const newRow = $('#isibody-unregistered').children().first().clone();
                newRow.find('label').each(function(){
                    const oldFor = $(this).attr('for');
                    $(this).attr('for', oldFor + Date.now().toString());
                });
                newRow.find('input').val('');
                newRow.find('.price, .markup, .discount').val(0);
                newRow.find('.qty-input').val(1);
                newRow.find('.type1').attr({'name': `type[${Date.now().toString()}]`, 'id': `type${Date.now().toString()}`});
                newRow.find('.type1').next().attr({'for': `type${Date.now().toString()}`});
                newRow.find('.type2').attr({'name': `type[${Date.now().toString()}]`, 'id': `type${Date.now().toString()}2`});
                newRow.find('.type2').next().attr({'for': `type${Date.now().toString()}2`});
                $('#isibody-unregistered').append(newRow);
            });

            $('#purchase_date').on('change', function(){
                // Hitung berapa data delivery order yang punya delivery_date yang sama
                let n = 0;
                for(let purchase of allpurchasesdata) {
                    if(purchase.purchase_date == $(this).val()){
                        n++;
                    }
                }

                // Formatting ulang data tanggal dari Y-M-D jadi DMY
                const [year, month, day] = $(this).val().split('-');
                const formattedDate = `${day}${month}${year}`;

                // Generate SKU dan masukin hasilnya langsung ke input fakeregister (yang tampil di user)
                const generatedsku = "DO/"+ formattedDate +"/"+ (n + 1);
                $('#_register').val(generatedsku);
                $('#register').val(generatedsku);
            });

            $('#submit-btn').on('click', function(){
                $('input, select').removeClass('is-invalid');

                // Validations
                const inpPurchaseDate = $('#purchase_date');

                let inputError = false;

                if(!inpPurchaseDate.val()){
                    inpPurchaseDate.addClass('is-invalid');
                    inputError = true;
                }

                if(!inputError){
                    $('form').submit();
                }
            });
        });
    </script>
@endsection
