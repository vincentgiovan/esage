@extends('layouts.main-admin')

@section('content')
    <x-container>
        <form method="POST" action="{{ route('purchase-edit', $purchase->id) }}" id="bikinpurchase">
            @csrf

            <h3 class="mt-4">Edit Data Pembelian</h3>

            <div class="container bg-white rounded-4 p-4 border border-1 card mt-4">
                <h4>Data Pembelian</h4>

                <div class="d-flex gap-3">
                    <div class="mt-3 w-50">
                        <label for="partner_id">Supplier<span class="text-danger">*</span></label>
                        <select name="partner_id" id="partner_id" class="form-select select2">
                            @foreach ($supplier as $s)
                                <option value="{{ $s->id }}">
                                    {{ $s->partner_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-3 w-50">
                        <label for="purchase_date">Tanggal Pembelian<span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error("purchase_date") is-invalid @enderror" name="purchase_date" id="purchase_date"
                            value="{{ Carbon\Carbon::today()->format('Y-m-d') }}">
                        <p class="invalid-feedback">Harap masukkan tanggal pembelian</p>
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <div class="mt-3 w-50">
                        <label for="purchase_deadline">Deadline/Tenggat Pembelian<span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error("purchase_deadline") is-invalid @enderror" name="purchase_deadline" id="purchase_deadline"
                            value="{{ Carbon\Carbon::today()->format('Y-m-d') }}">
                        <p class="invalid-feedback">Harap masukkan deadline/tenggat pembelian.</p>
                    </div>

                    <div class="mt-3 w-50">
                        <label for="fakeregister">SKU<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="_register" name="_register" placeholder="(Dibuat otomatis oleh sistem)"  value="{{ $purchase->register }}" disabled>
                            <input type="hidden" name="register" id="register" value="{{ $purchase->register }}">
                    </div>
                </div>

                <div class="mt-3">
                    <label>Status Pembelian<span class="text-danger">*</span></label>

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
                <h4>Daftar Barang<span class="text-danger">*</span></h4>

                <div class="overflow-x-auto mt-4">
                    <table class="w-100">
                        <thead>
                            <th class="border border-1 border-secondary">Barang</th>
                            <th class="border border-1 border-secondary">Jumlah</th>
                            <th class="border border-1 border-secondary">Aksi</th>
                        </thead>
                        <tbody id="isibody-registered">
                            @foreach($purchase->purchase_products as $purprod)
                                <tr>
                                    <td class="border border-1 border-secondary">
                                        <select name="products[]" class="form-select select2">
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" @if($purprod->product->id == $product->id) selected @endif>
                                                {{ $product->product_name }} - {{ $product->variant }} (Harga: {{ number_format($product->price, 0, ',', '.') }}, Stok:  {{ $product->stock }}, Diskon: {{ $product->discount }}%)
                                            </option>
                                        @endforeach
                                        </select>
                                    </td>
                                    <td class="border border-1 border-secondary">
                                        <input type="number" class="form-control qty-input" name="quantities[]" min="1" value="{{ $purprod->quantity }}">
                                    </td>
                                    <td class="border border-1 border-secondary"><button type="button" class="btn btn-danger remove-row-btn"><i class="bi bi-trash3"></i></button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="button" class="btn btn-primary" id="add-row-btn-registered">Tambah Data Baru</button>
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
                    newProdSel.append($('<option>').attr('value', prod.id).text(`${prod.product_name} - ${prod.variant} (Harga: ${formatCurrency(prod.price)}, Stok: ${[prod.stock]}, Diskon: ${prod.discount}%)`));
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

            $('#purchase_date').on('change', function(){
                const today = new Date();
                const todayDate = today.toISOString().split('T')[0];

                if($(this).val() == todayDate){
                    $('#_register').val('{{ $purchase->register }}');
                    $('#register').val('{{ $purchase->register }}');

                    return;
                }

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

                if(!inputError){
                    $('form').submit();
                }
            });
        });
    </script>
@endsection
