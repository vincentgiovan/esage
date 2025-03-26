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
                        <input type="text" class="form-control" id="_register" name="_register" placeholder="(Dibuat otomatis oleh sistem)"  value="{{ __('PU/' . Carbon\Carbon::today()->format('dmY') . '/' . $purchases->where('purchase_date', Carbon\Carbon::today()->format('Y-m-d'))->count() + 1) }}" disabled>
                            <input type="hidden" name="register" id="register" value="{{ __('PU/' . Carbon\Carbon::today()->format('dmY') . '/' . $purchases->where('purchase_date', Carbon\Carbon::today()->format('Y-m-d'))->count() + 1) }}">
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
                            <th class="border border-1 border-secondary">Barang</th>
                            <th class="border border-1 border-secondary">Jumlah</th>
                            <th class="border border-1 border-secondary">Aksi</th>
                        </thead>
                        <tbody id="isibody-registered">
                            <tr>
                                <td class="border border-1 border-secondary">
                                    <select name="products[]" class="form-select select2">
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->product_name }} - {{ $product->variant }} (Harga: Rp {{ number_format($product->price, 2, ',', '.') }}, Stok:  {{ $product->stock }}, Diskon: {{ $product->discount }}%)
                                        </option>
                                    @endforeach
                                    </select>
                                </td>
                                <td class="border border-1 border-secondary">
                                    <input type="number" class="form-control qty-input" name="quantities[]" min="1" value="1">
                                </td>
                                <td class="border border-1 border-secondary"><button type="button" class="btn btn-danger remove-row-btn"><i class="bi bi-trash3"></i></button></td>
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
                                <th class="border border-1 border-secondary">Barang</th>
                                <th class="border border-1 border-secondary">Jumlah</th>
                                <th class="border border-1 border-secondary">Aksi</th>
                            </thead>
                            <tbody id="isibody-unregistered">
                                <tr>
                                    <td class="border border-1 border-secondary">
                                        <div class="d-flex flex-column gap-2">
                                            <div>
                                                <label for="product_name">Nama Produk</label>
                                                <input type="text" class="form-control productName" name="product_name[]" placeholder="Nama Barang">
                                                <p class="invalid-feedback errProductName">Harap masukkan nama produk</p>
                                            </div>

                                            <button class="btn btn-secondary newprod-detail-btn" type="button">Sembunyikan detail</button>

                                            <div class="collapse.show gap-2 newprod-detail-area">
                                                <div>
                                                    <label for="variant">Variant</label>
                                                    <input type="text" class="form-control variant" name="variant[]" placeholder="Variant">
                                                    <p class="invalid-feedback errVariant">Harap masukkan varian</p>
                                                </div>

                                                <div class="mt-2">
                                                    <label for="product_code">SKU</label>
                                                    <input type="text" class="form-control fakeCode" name="fake_product_code[]" placeholder="(Dibuat otomatis oleh sistem)" disabled>
                                                    <input type="hidden" name="product_code[]" class="productCode">
                                                </div>

                                                <div class="mt-2">
                                                    <div class="unit">Satuan</div>
                                                    <input type="text" class="form-control" name="unit[]" placeholder="Unit">
                                                    <p class="invalid-feedback errUnit">Harap masukkan satuan</p>
                                                </div>

                                                <div class="mt-2">
                                                    <label for="price">Harga</label>
                                                    <input type="number" class="form-control" name="price[]" placeholder="Harga" value="0">
                                                    <p class="invalid-feedback errPrice">Harap masukkan nilai minimal 1</p>
                                                </div>

                                                <div class="mt-2">
                                                    <label for="markup">Markup</label>
                                                    <input type="text" class="form-control" name="markup[]" placeholder="Markup"  value="0">
                                                    <p class="invalid-feedback errMarkup">Harap masukkan nilai minimal 0</p>
                                                </div>

                                                <div class="mt-2">
                                                    <label for="discount">Diskon</label>
                                                    <input type="text" class="form-control" name="discount[]" placeholder="Diskon"  value="0">
                                                    <p class="invalid-feedback errDiscount">Harap masukkan nilai minimal 0</p>
                                                </div>

                                                <div class="mt-2">
                                                    <label>Jenis Barang</label>

                                                    <div class="d-flex gap-3">
                                                        <div class="d-flex gap-2 rounded-3 py-2">
                                                            <input class="form-check-input" type="radio" name="type[{{ round(microtime(true) * 1000) }}]" id="type{{ round(microtime(true) * 1000) }}_fm" value="fast moving" checked>
                                                            <label class="form-check-label" for="type{{ round(microtime(true) * 1000) }}_fm">Fast Moving</label>
                                                        </div>
                                                        <div class="d-flex gap-2 rounded-3 py-2">
                                                            <input class="form-check-input" type="radio" name="type[{{ round(microtime(true) * 1000) }}]" id="type{{ round(microtime(true) * 1000) }}_sm" value="slow moving">
                                                            <label class="form-check-label" for="type{{ round(microtime(true) * 1000) }}_sm">Slow Moving</label>
                                                        </div>
                                                        <div class="d-flex gap-2 rounded-3 py-2">
                                                            <input class="form-check-input" type="radio" name="type[{{ round(microtime(true) * 1000) }}]" id="type{{ round(microtime(true) * 1000) }}_as" value="asset">
                                                            <label class="form-check-label" for="type{{ round(microtime(true) * 1000) }}_as">Aset</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="border border-1 border-secondary">
                                        <div class="d-flex h-100 align-items-start">
                                            <input type="number" class="form-control qty-input" name="stock[]" min="1" value="1">
                                        </div>
                                    </td>
                                    <td class="border border-1 border-secondary">
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

        let templateNewProd = undefined;

        // Ambil data dari Laravel
        const allpurchasesdata = @json($purchases);
        const allproducts = @json($products);

        $(document).on('click', '.remove-row-btn', function(){
            if(confirm('Apakah anda yakin ingin menghapus item ini?')){
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
                            $('<td class="border border-1 border-secondary">').append(newProdSel)
                        )
                        .append(
                            $('<td class="border border-1 border-secondary">').append(
                                $('<input>').attr({'type': 'number', 'name': 'quantities[]', 'min': 1}).addClass('form-control qty-input').val(1)
                            )
                        )
                        .append(
                            $('<td class="border border-1 border-secondary">').append(
                                $('<button>').attr('type', 'button').addClass('btn btn-danger remove-row-btn').html('<i class="bi bi-trash3"></i>')
                            )
                        )
                );

                reinitializeselect2();
            });

            $('#add-row-btn-unregistered').on('click', function(){
                let idnum = Date.now().toString();
                $('#isibody-unregistered').append(
                    $('<tr>')
                        .append(
                            $('<td class="border border-1 border-secondary">').append($('<div>').addClass('d-flex flex-column gap-2')
                                .append($('<div>')
                                    .append(
                                        $('<label>').attr('for', `product_name${idnum}`).text('Nama Produk')
                                    )
                                    .append(
                                        $('<input>').attr({'type': 'text', 'name': 'product_name[]', 'id': `product_name${idnum}`, 'placeholder': 'Nama Barang'}).addClass('form-control productName')
                                    )
                                    .append(
                                        $('<p>').addClass('invalid-feedback errProductName').text('Harap masukkan nama barang')
                                    )
                                )
                                .append(
                                    $('<button>').addClass('btn btn-secondary newprod-detail-btn').attr('type', 'button').text('Sembunyikan detail')
                                )
                                .append($('<div>').addClass('collapse.show gap-2 newprod-detail-area')
                                    .append($('<div>')
                                        .append(
                                            $('<label>').attr('for', `variant${idnum}`).text('Variant')
                                        )
                                        .append(
                                            $('<input>').attr({'type': 'text', 'name': 'variant[]', 'id': `variant${idnum}`, 'placeholder': 'Variant'}).addClass('form-control variant')
                                        )
                                        .append(
                                            $('<p>').addClass('invalid-feedback errVariant').text('Harap masukkan varian')
                                        )
                                    )
                                    .append($('<div>').addClass('mt-2')
                                        .append(
                                            $('<label>').attr('for', `product_code${idnum}`).text('SKU')
                                        )
                                        .append(
                                            $('<input>').attr({'type': 'text', 'name': 'fake_product_code[]', 'id': `product_code${idnum}`, 'placeholder': '(Dibuat otomatis oleh sistem)'}).addClass('form-control fakeCode').prop('disabled', true)
                                        )
                                        .append(
                                            $('<input>').attr({'type': 'hidden', 'name': 'product_code[]'}).addClass('productCode')
                                        )
                                    )
                                    .append($('<div>').addClass('mt-2')
                                        .append(
                                            $('<label>').attr('for', `unit${idnum}`).text('Satuan')
                                        )
                                        .append(
                                            $('<input>').attr({'type': 'text', 'name': 'unit[]', 'id': `unit${idnum}`, 'placeholder': 'Unit'}).addClass('form-control')
                                        )
                                        .append(
                                            $('<p>').addClass('invalid-feedback errUnit').text('Harap masukkan satuan')
                                        )
                                    )
                                    .append($('<div>').addClass('mt-2')
                                        .append(
                                            $('<label>').attr('for', `price${idnum}`).text('Harga')
                                        )
                                        .append(
                                            $('<input>').attr({'type': 'number', 'name': 'price[]', 'id': `price${idnum}`, 'placeholder': 'Harga'}).addClass('form-control').val(0)
                                        )
                                        .append(
                                            $('<p>').addClass('invalid-feedback errPrice').text('Harap masukkan nilai minimal 1')
                                        )
                                    )
                                    .append($('<div>').addClass('mt-2')
                                        .append(
                                            $('<label>').attr('for', `markup${idnum}`).text('Markup')
                                        )
                                        .append(
                                            $('<input>').attr({'type': 'text', 'name': 'markup[]', 'id': `markup${idnum}`, 'placeholder': 'Markup'}).addClass('form-control').val(0)
                                        )
                                        .append(
                                            $('<p>').addClass('invalid-feedback errMarkup').text('Harap masukkan nilai minimal 0')
                                        )
                                    )
                                    .append($('<div>').addClass('mt-2')
                                        .append(
                                            $('<label>').attr('for', `discount${idnum}`).text('Diskon')
                                        )
                                        .append(
                                            $('<input>').attr({'type': 'text', 'name': 'discount[]', 'id': `discount${idnum}`, 'placeholder': 'Diskon'}).addClass('form-control').val(0)
                                        )
                                        .append(
                                            $('<p>').addClass('invalid-feedback errDiscount').text('Harap masukkan nilai minimal 0')
                                        )
                                    )
                                    .append($('<div>').addClass('mt-2')
                                        .append(
                                            $('<label>').text('Jenis Barang')
                                        )
                                        .append(
                                            $('<div>').addClass('d-flex gap-3')
                                                .append(
                                                    $('<div>').addClass('d-flex gap-2 rounded-3 py-2')
                                                        .append(
                                                            $('<input>').addClass('form-check-input').attr({'type': 'radio', 'name': `type[${idnum}]`, 'id': `type${idnum}_fm`}).val('fast moving').prop('checked', true)
                                                        )
                                                        .append(
                                                            $('<label>').attr('for', `type${idnum}_fm`).text('Fast Moving')
                                                        )
                                                )
                                                .append(
                                                    $('<div>').addClass('d-flex gap-2 rounded-3 py-2')
                                                        .append(
                                                            $('<input>').addClass('form-check-input').attr({'type': 'radio', 'name': `type[${idnum}]`, 'id': `type${idnum}_sm`}).val('slow moving')
                                                        )
                                                        .append(
                                                            $('<label>').attr('for', `type${idnum}_sm`).text('Slow Moving')
                                                        )
                                                )
                                                .append(
                                                    $('<div>').addClass('d-flex gap-2 rounded-3 py-2')
                                                        .append(
                                                            $('<input>').addClass('form-check-input').attr({'type': 'radio', 'name': `type[${idnum}]`, 'id': `type${idnum}_as`}).val('asset')
                                                        )
                                                        .append(
                                                            $('<label>').attr('for', `type${idnum}_as`).text('Aset')
                                                        )
                                                )
                                        )
                                    )
                                )
                            )
                        )
                        .append(
                            $('<td class="border border-1 border-secondary">').append(
                                $('<div>').addClass('d-flex h-100 align-items-start').append(
                                    $('<input>').attr({'type': 'number', 'name': 'stock[]', 'min': 1}).addClass('form-control qty-input').val(1)
                                )
                            )
                        )
                        .append(
                            $('<td class="border border-1 border-secondary">').append(
                                $('<div>').addClass('d-flex h-100 align-items-start').append(
                                    $('<button>').attr('type', 'button').addClass('btn btn-danger remove-row-btn').html('<i class="bi bi-trash3"></i>')
                                )
                            )
                        )
                );
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
                const generatedsku = "PU/"+ formattedDate +"/"+ (n + 1);
                $('#_register').val(generatedsku);
                $('#register').val(generatedsku);
            });

            $('#submit-btn').on('click', function(){
                $('input, select').removeClass('is-invalid');

                // Validations
                const inpNewProductName = $('input[name="product_name[]"]');
                const inpNewVariant = $('input[name="variant[]"]');
                const inpNewUnit = $('input[name="unit[]"]');
                const inpNewPrice = $('input[name="price[]"]');
                const inpNewMarkup = $('input[name="markup[]"]');
                const inpNewDiscount = $('input[name="discount[]"]');

                const inpPDate = $('#purchase_date');
                const inpPDeadline = $('#purchase_deadline');

                let inputError = false;

                if(!inpPDate.val()){
                    inpPDate.addClass('is-invalid');
                    inputError = true;
                }

                if(!inpPDeadline.val()){
                    inpPDeadline.addClass('is-invalid');
                    inputError = true;
                }

                inpNewProductName.each(function(){
                    if(!$(this).val()){
                        $(this).addClass('is-invalid');
                        inputError = true;
                    }
                });

                inpNewVariant.each(function(){
                    if(!$(this).val()){
                        $(this).addClass('is-invalid');
                        inputError = true;
                    }
                });

                inpNewUnit.each(function(){
                    if(!$(this).val()){
                        $(this).addClass('is-invalid');
                        inputError = true;
                    }
                });

                inpNewPrice.each(function(){
                    if(!$(this).val()){
                        $(this).addClass('is-invalid');
                        inputError = true;
                    }
                });

                inpNewMarkup.each(function(){
                    if(!$(this).val()){
                        $(this).addClass('is-invalid');
                        inputError = true;
                    }
                });

                inpNewDiscount.each(function(){
                    if(!$(this).val()){
                        $(this).addClass('is-invalid');
                        inputError = true;
                    }
                });

                if($('#isibody-unregistered').find('tr').length + $('#isibody-registered').find('tr').length < 1){
                    alert('Harap masukkan minimal 1 item ke cart!');
                    inputError = true;
                }

                if(!inputError){
                    $('form').submit();
                }
            });
        });
    </script>
@endsection
