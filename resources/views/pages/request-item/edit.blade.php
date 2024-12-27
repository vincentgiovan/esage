@extends('layouts.main-admin')

@section("content")

    <x-container-middle>
        <div class="container bg-white rounded-4 mt-4">

            <h2>Edit Request Items</h2>

            <div>
                <div class="p-3 border border-2 rounded-4 mt-4">
                    <h5>Proyek Target</h5>
                    <hr>
                    <div class="mt-3">
                        <label for="request_date">Tanggal Request</label>
                        <input type="date" id="request_date" class="form-control" placeholder="Input request_date" value="{{ old('request_date', $request_item->request_date) }}"/>
                        <p class="text-danger" id="err-request-date"></p>
                    </div>

                    <div class="mt-3">
                        <label for="select-project-dropdown">Nama Proyek</label>
                        <select name="project_name" class="form-select" id="select-project-dropdown">
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}{{-- $project->toJson() --}}" @if ($project->project_name == old("project_name", $request_item->project->project_name)) selected @endif>{{ $project->project_name }} ({{ $project->location }}) (PIC :  {{ $project->PIC }})</option>
                            @endforeach
                        </select>
                        <p class="text-danger" id="err-project-name"></p>
                    </div>

                    <div class="mt-3">
                        <label for="PIC">PIC</label>
                        <input type="text" id="PIC" class="form-control" placeholder="Input PIC" value="{{ old('PIC', $request_item->PIC) }}"/>
                        <p class="text-danger" id="err-pic"></p>
                    </div>

                    <div class="mt-3">
                        <label for="cart_notes">Catatan</label>
                        <textarea id="cart_notes" class="form-control" name="cart_notes" rows="4">{{ $request_item->notes }}</textarea>
                        <p class="text-danger" id="err-cart-notes"></p>
                    </div>
                </div>

                <div class="p-3 border border-2 rounded-4 mt-4" >
                    <h5>Product List</h5>
                    <hr>
                    <div class="mt-3">
                        <label for="select-product-dropdown">Nama Barang</label>
                        <select name="product_name" class="form-select select2" id="select-product-dropdown">
                            @foreach ($products as $product)
                                <option value="{{ $product->toJson() }}" @if ($product->product_name == old("product_name")) selected @endif>{{ $product->product_name }} ({{ $product->variant }}) (Stok :  {{ $product->stock }})</option>
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
                                <th>Nama Barang & Varian</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                            </thead>
                            <tbody id="isibody">
                                @foreach($rip as $r)
                                    <tr>
                                        <td>{{ $r->product->product_name }}</td>
                                        <td>{{ $r->quantity }}</td>
                                        <td><button class="btn btn-danger remove-row" data-target="{{ $r->id }}"><i class="bi bi-trash3"></i></button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <form method="POST" action="{{ route('requestitem-update', $request_item->id) }}" class="mt-5" id="peon">
            {{-- @csrf kepake untuk token ,wajib --}}
                @csrf

                @foreach($rip as $r)
                    <input type="hidden" name="products[]" id="{{ $r->id }}" value="{{ $r->product->id }}">
                    <input type="hidden" name="quantities[]" value="{{ $r->quantity }}">
                @endforeach

                <div class="mt-3">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Simpan Perubahan">
                </div>
            </form>
        </div>
    </x-container-middle>

    <script>
        $(document).ready(() => {
            $(".remove-row").click(function(){
                $(this).closest("tr").remove();
                const hiddenProduct = $(`input[type="hidden"][name*="products"][id="${$(this).data('target')}"]`);
                $(hiddenProduct).next().remove();
                $(hiddenProduct).remove();
            });

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
        });
    </script>

@endsection
