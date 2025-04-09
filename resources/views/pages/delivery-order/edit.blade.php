@extends('layouts.main-admin')

@section("content")
    <x-container class="mt-4">
        <form method="POST" action="{{ route("deliveryorder-update", $delivery_order->id ) }}" id="bikindevor">
            @csrf

            <h3>Edit Pengiriman</h3>

            {{-- Data Pengiriman --}}
            <div class="container rounded-4 p-4 bg-white border border-1 card mt-4">
                <h4>Data Pengiriman</h4>
                <div>
                    <div class="d-flex gap-3">
                        <div class="mt-3 w-50">
                            <label for="delivery_date">Tanggal Pengiriman<span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error("delivery_date") is-invalid @enderror" id="delivery_date" name="delivery_date" placeholder="delivery_date"  value="{{ $delivery_order->delivery_date }}">
                            <p class="invalid-feedback">Harap masukkan tanggal pengiriman</p>
                        </div>

                        <div class="mt-3 w-50">
                            <label for="project_id">Proyek<span class="text-danger">*</span></label>
                            <select name="project_id" class="form-select select2" id="project_id">
                                @foreach ($projects as $pn)
                                    <option value="{{ $pn->id}}" @if($delivery_order->project_id == $pn->id) selected @endif>{{ $pn->project_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <div class="mt-3 w-50">
                            <label for="_register">SKU<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="_register" name="_register" placeholder="(Dibuat otomatis oleh sistem)"  value="{{ __($delivery_order->register) }}" disabled>
                            <input type="hidden" name="register" id="register" value="{{ __($delivery_order->register) }}">
                        </div>
                        <div class="mt-3 w-50"></div>
                    </div>

                    <div class="mt-3">
                        <label>Status Pengiriman<span class="text-danger">*</span></label>

                        <div class="d-flex gap-3">
                            <div class="d-flex gap-2 rounded-3 py-2">
                                <input class="form-check-input" type="radio" name="delivery_status" id="delivery_status1" value="Complete" @if($delivery_order->delivery_status == "Complete") checked @endif checked>
                                <label class="form-check-label" for="delivery_status1">Selesai</label>
                            </div>
                            <div class="d-flex gap-2 rounded-3 py-2">
                                <input class="form-check-input" type="radio" name="delivery_status" id="delivery_status2" value="Incomplete" @if($delivery_order->delivery_status == "Incomplete") checked @endif>
                                <label class="form-check-label" for="delivery_status2">Belum Selesai</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="note">Catatan</label>
                        <input type="text" class="form-control" name="note" id="note" placeholder="Note" value="{{ $delivery_order->note }}">
                    </div>
                </div>
            </div>

            {{-- Cart pengiriman --}}
            <div class="container bg-white rounded-4 p-4 border border-1 card mt-4">
                <h4>Daftar Barang<span class="text-danger">*</span></h4>

                <div class="overflow-x-auto">
                    <table class="w-100 mt-4">
                        <thead>
                            <th class="border border-1 border-secondary">Barang</th>
                            <th class="border border-1 border-secondary">Jumlah</th>
                            <th class="border border-1 border-secondary">Aksi</th>
                        </thead>
                        <tbody id="isibody">
                            @foreach($delivery_order->delivery_order_products as $dop)
                                <tr>
                                    <td class="border border-1 border-secondary">
                                        <select name="products[]" class="form-select select2">
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" @if ($product->id == $dop->product->id) selected @endif>
                                                {{ $product->product_name }} - {{ $product->variant }} (Harga: {{ number_format($product->price, 0, ',', '.') }}, Stok:  {{ $product->stock }}, Diskon: {{ $product->discount }}%) [Tgl beli: {{ Carbon\Carbon::parse($product->ordering_date)->format('d/m/Y') }}]
                                            </option>
                                        @endforeach
                                        </select>
                                    </td>
                                    <td class="border border-1 border-secondary">
                                        <input type="number" class="form-control qty-input" name="quantities[]" min="1" value="{{ $dop->quantity }}">
                                    </td>
                                    <td class="border border-1 border-secondary"><button type="button" class="btn btn-danger remove-row-btn"><i class="bi bi-trash3"></i></button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <p class="invalid-feedback">Harap pilih nama barang</p>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="button" class="btn btn-primary" id="add-row-btn">Tambah Data Baru</button>
                    </div>
                </div>

                <div>
                    <button type="button" class="btn btn-success px-3 py-1" id="submit-btn">Simpan</button>
                </div>
            </div>

            <ul class="text-danger fw-bold mt-3">
                @if($errors->any())
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                @endif
            </ul>
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

        // Ambil data dari Laravel
        const alldeliveryorderdata = @json($delivery_orders);
        const allproducts = @json($products);

        $(document).on('click', '.remove-row-btn', function(){
            if($('#isibody').find('tr').length > 1 && confirm('Apakah anda yakin ingin menghapus item ini?')){
                $(this).closest('tr').remove();
            }
        });

        $(document).on('change', '.qty-input', function(){
            if($(this).val() < 1){
                $(this).val(1);
            }
        });

        $(document).ready(() => {
            $('#add-row-btn').on('click', function(){
                const newProdSel = $('<select>').attr('name', 'products[]').addClass('form-select select2');
                allproducts.forEach(prod => {
                    newProdSel.append($('<option>').attr('value', prod.id).text(`${prod.product_name} - ${prod.variant} (Harga: ${formatCurrency(prod.price)}, Stok: ${[prod.stock]}, Diskon: ${prod.discount}%) [Tgl beli: ${formatDate(prod.ordering_date)}]`));
                });

                $('#isibody').append(
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

            $('#delivery_date').on('change', function(){
                const today = new Date();
                const todayDate = today.toISOString().split('T')[0];

                if($(this).val() == todayDate){
                    $('#_register').val('{{ $delivery_order->register }}');
                    $('#register').val('{{ $delivery_order->register }}');

                    return;
                }

                // Hitung berapa data delivery order yang punya delivery_date yang sama
                let n = 0;
                for(let delord of alldeliveryorderdata) {
                    if(delord.delivery_date == $(this).val()){
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
                const inpDeliveryDate = $('#delivery_date');

                let inputError = false;

                if(!inpDeliveryDate.val()){
                    inpDeliveryDate.addClass('is-invalid');
                    inputError = true;
                }

                if(!inputError){
                    $('form').submit();
                }
            });
        });

    </script>
@endsection
