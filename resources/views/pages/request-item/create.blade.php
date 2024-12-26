@extends('layouts.main-admin')

@section("content")

    <x-container-middle>
        <div class="container bg-white rounded-4 mt-4">

            <h2>Buat Request Baru</h2>

            <div>
                <div class="p-3 border border-2 rounded-4 mt-4">
                    <h5>Proyek Target</h5>
                    <hr>
                    <div class="mt-3">
                        <label for="request_date">Tanggal Request</label>
                        <input type="date" id="request_date" class="form-control" placeholder="Input request_date"/>
                        <p style="color: red; font-size: 10px;" id="err-request-date"></p>
                    </div>

                    <div class="mt-3">
                        <label for="select-project-dropdown">Nama Proyek</label>
                        <select name="project_name" class="form-select" id="select-project-dropdown">
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}{{-- $project->toJson() --}}" @if ($project->project_name == old("project_name")) selected @endif>{{ $project->project_name }} ({{ $project->location }}) (PIC :  {{ $project->PIC }})</option>
                            @endforeach
                        </select>
                        <p style="color: red; font-size: 10px;" id="err-project-name"></p>
                    </div>

                    <div class="mt-3">
                        <label for="PIC">PIC</label>
                        <input type="text" id="PIC" class="form-control" placeholder="Input PIC"/>
                        <p style="color: red; font-size: 10px;" id="err-pic"></p>
                    </div>

                    <div class="mt-3">
                        <label for="cart_notes">Catatan</label>
                        <textarea id="cart_notes" class="form-control" name="cart_notes" rows="4"></textarea>
                        <p style="color: red; font-size: 10px;" id="err-cart-notes"></p>
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
                        <p style="color: red; font-size: 10px;" id="err-product-name"></p>
                    </div>

                    <div class="mt-3">
                        <label for="quantity">Jumlah</label>
                        <input type="number" class="form-control" name="quantity" id="quantity"  placeholder="Quantity" value = "{{ old("quantity")}}">
                        <p style="color: red; font-size: 10px;" id="errQuantity"></p>
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
            {{-- @csrf kepake untuk token ,wajib --}}
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
            const input5 = document.getElementById("select-product-dropdown");
            const input6 = document.getElementById("quantity");

            // Targetkan elemen-elemen error message (buat nanti display error message)
            const errProductName = document.getElementById("err-product-name");
            const errQuantity = document.getElementById("errQuantity");

            // Hilangkan error message dan mark merah pada input dan error message sebelum validasi
            errProductName.innerText = "";
            errQuantity.innerText = "";
            input5.style.border = "none";
            input6.style.border = "none";

            // Validasi input
            let inputAman = true; // Status apakah sudah terjadi kesalahan input atau belum

            if(!input5.value){
                input5.style.border = "solid 1px red";
                errProductName.innerText = "Invalid input :3";

                inputAman = false;
            }

            if(!input6.value && input6.value <= 0){
                input6.style.border = "solid 1px red";
                errQuantity.innerText = "Invalid input :3";

                inputAman = false;
            }

            // Kalau misalkan ada 1 atau lebih input yang ga sesuai, jangan dilanjut
            if(!inputAman){
                return;
            }

            // Generate elemen <tr> dan <td> untuk membuat row tabel display
            const newRow = document.createElement("tr");
            const column1 = document.createElement("td");
            const column4 = document.createElement("td");
            const column5 = document.createElement("td");

            const converted = JSON.parse(input5.value); // value dari option yang dipilih itu konversi collection Laravel jadi JSON, tapi bentuknya masih teks, jadi perlu dikonversi ke format JSON beneran dulu biar lebih enak diolah
            column1.innerText = `${converted.product_name} (${converted.variant})`; // format teks yang tampil di kolom nama produk menjadi "nama_product (varian) dan tampilkan di row data baru di kolom nama produk"
            column4.innerText = input6.value; // ambil nilai dari input quantity dan tampilkan di kolom quantity

            // Buat tombol merah tong sampah buat nanti dipake buat hapus 1 row data
            const deleteButton = document.createElement("button");
            deleteButton.classList.add("btn", "btn-danger");
            deleteButton.setAttribute("type", "button");
            deleteButton.innerHTML = '<i class="bi bi-trash3"></i>';
            column5.appendChild(deleteButton); // display tombol merah di kolom action

            // Gabungkan semua kolom data menjadi 1 row data
            newRow.appendChild(column1);
            newRow.appendChild(column4);
            newRow.appendChild(column5);

            // Tambahkan row data baru ke tabel untuk di-display
            tbody.appendChild(newRow);

            // Generate hidden input untuk kirim data ke Laravel (bikin something like <input type="hidden" name="variabel_data[]" value="nilai_dari_input"> buat setiap input yang ada)
            const susInput = document.createElement("input");
            susInput.setAttribute("type", "hidden");
            susInput.setAttribute("name", "products[]");
            susInput.setAttribute("value", converted.id);

            const susInput3 = document.createElement("input");
            susInput3.setAttribute("type", "hidden");
            susInput3.setAttribute("name", "quantities[]");
            susInput3.setAttribute("value", input6.value);

            // Tambahkan semua hidden input ke form submit
            confirmationForm.appendChild(susInput);
            confirmationForm.appendChild(susInput3);

            // Kalau tombol merah diklik maka lakukan:
            deleteButton.addEventListener("click", function(){
                tbody.removeChild(newRow); // Hapus row data yang ada di tabel

                // Hilangkan hidden input-nya juga
                confirmationForm.removeChild(susInput);
                confirmationForm.removeChild(susInput3);
            });
        });

        $(confirmationForm).on("submit", function(e){
            e.preventDefault();

            const input1 = $("#request_date");
            const input2 = $("#select-project-dropdown");
            const input3 = $("#PIC");
            const input4 = $("#cart_notes");

            const errRequestDate = $("#err-request-date");
            const errProjectName = $("#err-project-name");
            const errPIC = $("#err-pic");
            const errCartNotes = $("#err-cart-notes");

            errRequestDate.innerText = "";
            errProjectName.innerText = "";
            errPIC.innerText = "";
            errCartNotes.innerText = "";
            $(input1).css("border", "none");
            $(input2).css("border", "none");
            $(input3).css("border", "none");
            $(input4).css("border", "none");

            // Validasi input
            let inputAman = true; // Status apakah sudah terjadi kesalahan input atau belum

            if(!$(input1).val()){
                $(input1).css("border", "solid 1px red");
                $(errRequestDate).text("Invalid input :3");

                inputAman = false;
            }

            if(!$(input2).val()){
                $(input2).css("border", "solid 1px red");
                $(errProjectName).text("Invalid input :3");

                inputAman = false;
            }

            if(!$(input3).val()){
                $(input3).css("border", "solid 1px red");
                $(errPIC).text("Invalid input :3");

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
                        "value": $(input1).val()
                    })
                ).append(
                    $("<input>").attr({
                        "type": "hidden",
                        "name": "project_id",
                        "value": $(input2).val()
                    })
                ).append(
                    $("<input>").attr({
                        "type": "hidden",
                        "name": "PIC",
                        "value": $(input3).val()
                    })
                ).append(
                    $("<input>").attr({
                        "type": "hidden",
                        "name": "notes",
                        "value": $(input4).val()
                    })
                );

            this.submit();
        });
    </script>

@endsection
