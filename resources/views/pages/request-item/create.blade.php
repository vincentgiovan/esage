@extends('layouts.main-admin')

@section("content")

    <x-container-middle>
        <div class="container bg-white rounded-4 mt-4 p-4">

            <h3>Buat Request Baru</h3>

            <div>
                <div class="p-3 border border-2 rounded-4 mt-4">
                    <h5>Proyek Target</h5>
                    <hr>
                    <div class="mt-3">
                        <label for="request_date">Tanggal Request</label>
                        <input type="date" id="request_date" class="form-control" placeholder="Input request_date"/>
                        <p class="text-danger" id="err-request-date"></p>
                    </div>

                    <div class="mt-3">
                        <label for="select-project-dropdown">Nama Proyek</label>
                        <select name="project_id" class="form-select @error('project_id') is-invalid @enderror" id="select-project-dropdown">
                            <option disabled selected>Pilih proyek</option>
                            @forelse (Auth::user()->employee_data->projects ?? [] as $proj)
                                <option value="{{ $proj->id }}" @if(old('project_id') == $proj->id) selected @endif>{{ $proj->project_name }} (PIC: {{ $proj->PIC }})</option>
                            @empty
                            @endforelse
                        </select>

                        <p class="text-danger" id="err-project-name"></p>
                    </div>

                    <div class="mt-3">
                        <label for="PIC">PIC</label>
                        <input type="text" id="PIC" class="form-control" placeholder="Input PIC"/>
                        <p class="text-danger" id="err-pic"></p>
                    </div>

                    <div class="mt-3">
                        <label for="cart_notes">Catatan</label>
                        <textarea id="cart_notes" class="form-control" name="cart_notes" rows="4"></textarea>
                        <p class="text-danger" id="err-cart-notes"></p>
                    </div>
                </div>

                <div class="p-3 border border-2 rounded-4 mt-4" >
                    <h5>Daftar Barang</h5>
                    <hr>
                    <div class="mt-3">
                        <label for="select-product-dropdown">Nama Barang</label>
                        <select name="product_name" class="form-select select2" id="select-product-dropdown">
                            @foreach ($products as $product)
                                <option value="{{ $product->toJson() }}" @if ($product->product_name == old("product_name")) selected @endif>{{ $product->product_name }} - {{ $product->variant }} (Harga: Rp {{ number_format($product->price, 2, ',', '.') }}, Stok:  {{ $product->stock }}, Diskon: {{ $product->discount }}%) @if($product->is_returned == 'yes'){{__('- Returned')}}@endif</option>
                            @endforeach
                        </select>
                        <p class="text-danger" id="err-product-name"></p>
                    </div>

                    <div class="mt-3">
                        <label for="quantity">Jumlah</label>
                        <input type="number" class="form-control" name="quantity" id="quantity"  placeholder="Quantity" value = "{{ old("quantity")}}">
                        <p class="text-danger" id="errQuantity"></p>
                    </div>

                    <div class="mt-3">
                        <input type="button" id="addbutton" class="btn btn-primary px-3 py-1" value="Tambah">
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-100 mt-4">
                            <thead>
                                <th>Nama Barang & Variant</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                            </thead>
                            <tbody id="isibody">

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <form method="POST" action="{{ route('requestitem-store') }}" class="mt-5" id="peon">

                @csrf

                <div class="mt-3">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Buat Request">
                </div>
                @error("prices")
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <br>
                @error("quantities")
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </form>
        </div>
    </x-container-middle>

    <script>
        // Targetkan form buat submit data
        const confirmationForm = document.getElementById("peon");

        // Targetkan tombol add data
        const addbutton = document.getElementById("addbutton");

        // Kalau tombol add data diklik maka lakukan:
        addbutton.addEventListener("click", function(){
            // Targetkan tbody tabel (buat nanti display data)
            const tbody = document.getElementById("isibody");

            // Targetkan elemen-elemen input data purchase produk yang diperlukan (buat nanti diambil nilainya)
            const inpProduct = document.getElementById("select-product-dropdown");
            const inpQty = document.getElementById("quantity");

            // Targetkan elemen-elemen error message (buat nanti display error message)
            const errProductName = document.getElementById("err-product-name");
            const errQuantity = document.getElementById("errQuantity");

            // Hilangkan error message dan mark merah pada input dan error message sebelum validasi
            errProductName.innerText = "";
            errQuantity.innerText = "";
            inpProduct.classList.remove('is-invalid');
            inpQty.classList.remove('is-invalid');
            $(inpQty).removeClass('is-invalid');

            // Validasi input
            let inputAman = true; // Status apakah sudah terjadi kesalahan input atau belum

            if(!inpProduct.value){
                inpProduct.classList.add('is-invalid');
                errProductName.innerText = "Invalid input :3";

                inputAman = false;
            }

            if(!inpQty.value || inpQty.value < 1){
                inpQty.classList.add('is-invalid');
                $(inpQty).addClass('is-invalid');
                errQuantity.innerText = "Harap masukkan nilai minimal 1.";

                inputAman = false;
            }

            // Kalau misalkan ada 1 atau lebih input yang ga sesuai, jangan dilanjut
            if(!inputAman){
                return;
            }

            // Generate elemen <tr> dan <td> untuk membuat row tabel display
            const newRow = document.createElement("tr");
            const column1 = document.createElement("td");
            const column2 = document.createElement("td");
            const column3 = document.createElement("td");

            const converted = JSON.parse(inpProduct.value); // value dari option yang dipilih itu konversi collection Laravel jadi JSON, tapi bentuknya masih teks, jadi perlu dikonversi ke format JSON beneran dulu biar lebih enak diolah
            column1.innerText = `${converted.product_name} (${converted.variant})`; // format teks yang tampil di kolom nama produk menjadi "nama_product (varian) dan tampilkan di row data baru di kolom nama produk"
            column2.innerText = inpQty.value; // ambil nilai dari input quantity dan tampilkan di kolom quantity

            // Buat tombol merah tong sampah buat nanti dipake buat hapus 1 row data
            const deleteButton = document.createElement("button");
            deleteButton.classList.add("btn", "btn-danger");
            deleteButton.setAttribute("type", "button");
            deleteButton.innerHTML = '<i class="bi bi-trash3"></i>';
            column3.appendChild(deleteButton); // display tombol merah di kolom action

            // Gabungkan semua kolom data menjadi 1 row data
            newRow.appendChild(column1);
            newRow.appendChild(column2);
            newRow.appendChild(column3);

            // Tambahkan row data baru ke tabel untuk di-display
            tbody.appendChild(newRow);

            // Generate hidden input untuk kirim data ke Laravel (bikin something like <input type="hidden" name="variabel_data[]" value="nilai_dari_input"> buat setiap input yang ada)
            const susInpProd = document.createElement("input");
            susInpProd.setAttribute("type", "hidden");
            susInpProd.setAttribute("name", "products[]");
            susInpProd.setAttribute("value", converted.id);

            const susInpQty = document.createElement("input");
            susInpQty.setAttribute("type", "hidden");
            susInpQty.setAttribute("name", "quantities[]");
            susInpQty.setAttribute("value", inpQty.value);

            // Tambahkan semua hidden input ke form submit
            confirmationForm.appendChild(susInpProd);
            confirmationForm.appendChild(susInpQty);

            // Kalau tombol merah diklik maka lakukan:
            deleteButton.addEventListener("click", function(){
                tbody.removeChild(newRow); // Hapus row data yang ada di tabel

                // Hilangkan hidden input-nya juga
                confirmationForm.removeChild(susInpProd);
                confirmationForm.removeChild(susInpQty);
            });
        });

        $(confirmationForm).on("submit", function(e){
            e.preventDefault();

            if($('#isibody').find('tr').length > 0){
                this.submit();
            }
            else {
                alert('Anda belum memasukkan data sama sekali.');

                return false;
            }

            const inpReqDate = $("#request_date");
            const inpProj = $("#select-project-dropdown");
            const inpPIC = $("#PIC");
            const inpNotes = $("#cart_notes");

            const errRequestDate = $("#err-request-date");
            const errProjectName = $("#err-project-name");
            const errPIC = $("#err-pic");
            const errCartNotes = $("#err-cart-notes");

            errRequestDate.innerText = "";
            errProjectName.innerText = "";
            errPIC.innerText = "";
            errCartNotes.innerText = "";
            $(inpReqDate).removeClass('is-invalid');
            $(inpPIC).removeClass('is-invalid');
            $(inpNotes).removeClass('is-invalid');

            // Validasi input
            let inputAman = true; // Status apakah sudah terjadi kesalahan input atau belum

            if(!$(inpReqDate).val()){
                $(inpReqDate).addClass('is-invalid');
                $(errRequestDate).text("Harap masukkan tanggal request.");

                inputAman = false;
            }

            if(!$(inpProj).val()){
                $(inpProj).addClass('is-invalid');
                $(errProjectName).text("Harap pilih proyek.");

                inputAman = false;
            }

            if(!$(inpPIC).val()){
                $(inpPIC).addClass('is-invalid');
                $(errPIC).text("Harap masukkan PIC request.");

                inputAman = false;
            }

            if(!inputAman){
                return;
            }

            $(confirmationForm)
                .append(
                    $("<input>").attr({
                        "type": "hidden",
                        "name": "request_date",
                        "value": $(inpReqDate).val()
                    })
                ).append(
                    $("<input>").attr({
                        "type": "hidden",
                        "name": "project_id",
                        "value": $(inpProj).val()
                    })
                ).append(
                    $("<input>").attr({
                        "type": "hidden",
                        "name": "PIC",
                        "value": $(inpPIC).val()
                    })
                ).append(
                    $("<input>").attr({
                        "type": "hidden",
                        "name": "notes",
                        "value": $(inpNotes).val()
                    })
                );

            this.submit();
        });
    </script>

@endsection
