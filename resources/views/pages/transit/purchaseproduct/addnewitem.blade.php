
@extends('layouts.main-admin')

@section("content")

    <x-container-middle>
        <div class="container bg-white py-4 px-5 rounded-4 border border-1 card mt-4">

            <h2>Tambah Barang ke Pembelian dan Data Barang</h2>
                <div>
                    <div class="mt-3">
                        <label for="product_name">Nama Produk</label>
                        <input type="text" class="form-control" name="product_name" id="product_name" placeholder="Nama Barang" value = "{{ old("product_name" ) }}">
                        <p class="text-danger" id="errProductName"></p>
                    </div>
                    <div class="mt-3">
                        <div class="unit">Satuan</div>
                        <input type="text" class="form-control" name="unit" id="unit" placeholder="Unit"  value = "{{ old("unit") }}">
                        <p class="text-danger" id="errUnit"></p>
                    </div>
                    <div class="mt-3">
                        <div class="status">Status</div>
                        <select name="status" class="form-select" id="status">
                            <option value="Ready">Tersedia</option>
                            <option value="Out Of Stock">Stok kosong</option>
                        </select>
                        <p class="text-danger" id="errStatus"></p>
                    </div>
                    <div class="mt-3">
                        <label for="variant">Variant</label>
                        <input type="text" class="form-control" name="variant"  id="variant" placeholder="Variant"  value = "{{ old("variant") }}">
                        <p class="text-danger" id="errVariant"></p>
                    </div>
                    <div class="mt-3">
                        <label for="fake_product_code">SKU</label>
                        <input type="text" class="form-control" name="fake_product_code" id="fake_product_code" placeholder="(Dibuat otomatis oleh sistem)" disabled>
                        <p class="text-danger" id="errProductCode"></p>
                    </div>
                    <div class="mt-3">
                        <label for="price">Harga</label>
                        <input type="number" class="form-control" name="price" id="price" placeholder="Harga" value = "{{ old("price") }}">
                        <p class="text-danger" id="errPrice"></p>
                    </div>
                    <div class="mt-3">
                        <label for="markup">Markup</label>
                        <input type="number" class="form-control" name="markup" id="markup" placeholder="Markup"  value = "{{ old("markup") }}">
                        <p class="text-danger" id="errMarkup"></p>
                    </div>
                    <div class="mt-3">
                        <label for="stock">Stok</label>
                        <input type="number" class="form-control" name="stock"  id="stock" placeholder="Stok"  value = "{{ old("stock") }}">
                        <p class="text-danger" id="errStock"></p>
                    </div>
                    <div class="mt-3">
                        <label for="discount">Diskon</label>
                        <input type="number" class="form-control" name="discount"  id="discount" placeholder="Diskon"  value = "{{ old("discount") }}">
                        <p class="text-danger" id="errDiscount"></p>
                    </div>

                    <div class="mt-3">
                        <input type="button" id="addbutton" class="btn btn-primary px-3 py-1" value="Tambah">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-100 mt-4">
                        <thead>
                            <th>Nama Barang</th>
                            <th>Unit</th>
                            <th>Status</th>
                            <th>Variant</th>
                            <th>Kode Produk</th>
                            <th>Harga</th>
                            <th>Mark Up</th>
                            <th>Stok</th>
                            <th>Diskon</th>
                            <th>Aksi</th>
                        </thead>
                        <tbody id="isibody">

                        </tbody>
                    </table>
                </div>

                <form method="POST" action="{{ route("purchaseproduct-store2", $purchase->id ) }}" class="mt-5" id="peon">
                {{-- @csrf kepake untuk token ,wajib --}}
                    @csrf

                    <div class="mt-3">
                        <input type="submit" class="btn btn-success px-3 py-1" value="Simpan">
                    </div>
                    @error("product_name")
                        <span class="text-danger">{{ $message }}</span><br>
                    @enderror
                    @error("unit")
                        <span class="text-danger">{{ $message }}</span><br>
                    @enderror
                    @error("status")
                        <span class="text-danger">{{ $message }}</span><br>
                    @enderror
                    @error("variant")
                        <span class="text-danger">{{ $message }}</span><br>
                    @enderror
                    @error("product_code")
                        <span class="text-danger">{{ $message }}</span><br>
                    @enderror
                    @error("price")
                        <span class="text-danger">{{ $message }}</span><br>
                    @enderror
                    @error("markup")
                        <span class="text-danger">{{ $message }}</span><br>
                    @enderror
                    @error("stock")
                        <span class="text-danger">{{ $message }}</span><br>
                    @enderror
                    @error("discount")
                        <span class="text-danger">{{ $message }}</span><br>
                    @enderror

                </form>

        </div>
    </x-container-middle>

    <script>
        function makeCapitalizedEachWordAndNoSpace(text){
            // Remove extra spaces, split into words, capitalize each, and join back together
            const processedText = text
                .trim() // Trim any leading/trailing spaces
                .split(/\s+/) // Split by any space characters
                .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()) // Capitalize each word
                .join(''); // Join without spaces

            return processedText;
        }

        $("#product_name, #variant").change(function(){
            const inputProductName = $("#product_name").val();
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

        // $("#folm").on("submit", function(event){
        //     event.preventDefault();

        //     $(this).append($("<input>").attr({"type":"hidden", "name": "product_code", "value": $("#fake_product_code").val()}));

        //     this.submit();
        // });

        // Targetkan form buat submit data
        const confirmationForm = document.getElementById("peon");

        // Targetkan tombol add data
        const addbutton = document.getElementById("addbutton");

        // Kalau tombol add data diklik maka lakukan:
        addbutton.addEventListener("click", function(){
            // Targetkan tbody tabel (buat nanti display data)
            const tbody = document.getElementById("isibody");

            // Targetkan elemen-elemen input data purchase produk yang diperlukan (buat nanti diambil nilainya)
            const input1 = document.getElementById("product_name");
            const input2 = document.getElementById("unit");
            const input3 = document.getElementById("status");
            const input4 = document.getElementById("variant");
            const input5 = document.getElementById("fake_product_code");
            const input6 = document.getElementById("price");
            const input7 = document.getElementById("markup");
            const input8 = document.getElementById("stock");
            const input9 = document.getElementById("discount");

            // Targetkan elemen-elemen error message (buat nanti display error message)
            const errProductName = document.getElementById("errProductName");
            const errUnit = document.getElementById("errUnit");
            const errStatus = document.getElementById("errStatus");
            const errVariant = document.getElementById("errVariant");
            const errProductCode = document.getElementById("errProductCode");
            const errPrice = document.getElementById("errPrice");
            const errMarkup = document.getElementById("errMarkup");
            const errStock = document.getElementById("errStock");
            const errDiscount = document.getElementById("errDiscount");

            // Hilangkan error message dan mark merah pada input dan error message sebelum validasi
            errProductName.innerText = "";
            errUnit.innerText = "";
            errStatus.innerText = "";
            errVariant.innerText = "";
            errProductCode.innerText = "";
            errPrice.innerText = "";
            errMarkup.innerText = "";
            errStock.innerText = "";
            errDiscount.innerText = "";

            input1.classList.remove("is-invalid");
            input2.classList.remove("is-invalid");
            input3.classList.remove("is-invalid");
            input4.classList.remove("is-invalid");
            input5.classList.remove("is-invalid");
            input6.classList.remove("is-invalid");
            input7.classList.remove("is-invalid");
            input8.classList.remove("is-invalid");
            input9.classList.remove("is-invalid");

            // Validasi input
            let inputAman = true;

            // Kalo product name kosong maka mark merah input dan tampilkan error message
            if(!input1.value){
                input1.classList.add("is-invalid");
                errProductName.innerText = "Harap masukkan nama barang.";

                inputAman = false;
            }

            // Kalo satuan kosong maka mark merah input dan tampilkan error message
            if(!input2.value){
                input2.classList.add("is-invalid");
                errUnit.innerText = "Harap masukkan satuan/unit barang.";

                inputAman = false;
            }

            // Kalo varian kosong maka mark merah input dan tampilkan error message
            if(!input4.value){
                input4.classList.add("is-invalid");
                errVariant.innerText = "Harap masukkan varian barang.";

                inputAman = false;
            }

            // Kalo harga kosong atau di bawah 1 maka mark merah input dan tampilkan error message
            if(!input6.value && input6.value < 1){
                input6.classList.add("is-invalid");
                errPrice.innerText = "Harap masukkan nilai minimal 1.";

                inputAman = false;
            }

            // Kalo markup kosong atau di bawah 1 maka mark merah input dan tampilkan error message
            if(input7.value && input7.value < 1){
                input7.classList.add("is-invalid");
                errMarkup.innerText = "Harap masukkan nilai minimal 1.";

                inputAman = false;
            }

            // Kalo stock kosong atau di bawah 1 maka mark merah input dan tampilkan error message
            if(!input8.value && input8.value < 1){
                input8.classList.add("is-invalid");
                errStock.innerText = "Harap masukkan nilai minimal 1.";

                inputAman = false;
            }

            // Kalo diskon kosong maka mark merah input dan tampilkan error message
            if(!input9.value){
                input9.classList.add("is-invalid");
                errDiscount.innerText = "Harap masukkan nilai minimal 0.";

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
            const column4 = document.createElement("td");
            const column5 = document.createElement("td");
            const column6 = document.createElement("td");
            const column7 = document.createElement("td");
            const column8 = document.createElement("td");
            const column9 = document.createElement("td");
            const column10 = document.createElement("td");

            // Untuk setiap kolom yang dibentuk masukkan data dari setiap input yang sesuai
            column1.innerText = input1.value; // Misalnya kolom paling kiri yang pertama diisi sama nilai dari input 1 which is product name
            column2.innerText = input2.value; // Then kolom kedua diisi sama nilai dari unit barang
            column3.innerText = input3.value; // Then kolom ketiga diisi sama nilai dari status
            column4.innerText = input4.value; // dan seterusnya ...
            column5.innerText = input5.value;
            column6.innerText = input6.value;
            column7.innerText = input7.value;
            column8.innerText = input8.value;
            column9.innerText = input9.value;

            // Buat tombol merah tong sampah buat nanti dipake buat hapus 1 row data
            const deleteButton = document.createElement("button");
            deleteButton.classList.add("btn", "btn-danger");
            deleteButton.setAttribute("type", "button");
            deleteButton.innerText = "Remove";
            column10.appendChild(deleteButton); // display tombol merah di kolom action

            // Gabungkan semua kolom data menjadi 1 row data
            newRow.appendChild(column1);
            newRow.appendChild(column2);
            newRow.appendChild(column3);
            newRow.appendChild(column4);
            newRow.appendChild(column5);
            newRow.appendChild(column6);
            newRow.appendChild(column7);
            newRow.appendChild(column8);
            newRow.appendChild(column9);
            newRow.appendChild(column10);

            // Tambahkan row data baru ke tabel untuk di-display
            tbody.appendChild(newRow);

            // Generate hidden input untuk kirim data ke Laravel (bikin something like <input type="hidden" name="variabel_data[]" value="nilai_dari_input"> buat setiap input yang ada)
            const susInput1 = document.createElement("input");
            susInput1.setAttribute("type", "hidden");
            susInput1.setAttribute("name", "product_name[]");
            susInput1.setAttribute("value", input1.value);

            const susInput2 = document.createElement("input");
            susInput2.setAttribute("type", "hidden");
            susInput2.setAttribute("name", "unit[]");
            susInput2.setAttribute("value", input2.value);

            const susInput3 = document.createElement("input");
            susInput3.setAttribute("type", "hidden");
            susInput3.setAttribute("name", "status[]");
            susInput3.setAttribute("value", input3.value);

            const susInput4 = document.createElement("input");
            susInput4.setAttribute("type", "hidden");
            susInput4.setAttribute("name", "variant[]");
            susInput4.setAttribute("value", input4.value);

            const susInput5 = document.createElement("input");
            susInput5.setAttribute("type", "hidden");
            susInput5.setAttribute("name", "product_code[]");
            susInput5.setAttribute("value", input5.value);

            const susInput6 = document.createElement("input");
            susInput6.setAttribute("type", "hidden");
            susInput6.setAttribute("name", "price[]");
            susInput6.setAttribute("value", input6.value);

            const susInput7 = document.createElement("input");
            susInput7.setAttribute("type", "hidden");
            susInput7.setAttribute("name", "markup[]");
            susInput7.setAttribute("value", input7.value);

            const susInput8 = document.createElement("input");
            susInput8.setAttribute("type", "hidden");
            susInput8.setAttribute("name", "stock[]");
            susInput8.setAttribute("value", input8.value);

            const susInput9 = document.createElement("input");
            susInput9.setAttribute("type", "hidden");
            susInput9.setAttribute("name", "discount[]");
            susInput9.setAttribute("value", input9.value);

            // Tambahkan semua hidden input ke form submit
            confirmationForm.appendChild(susInput1);
            confirmationForm.appendChild(susInput2);
            confirmationForm.appendChild(susInput3);
            confirmationForm.appendChild(susInput4);
            confirmationForm.appendChild(susInput5);
            confirmationForm.appendChild(susInput6);
            confirmationForm.appendChild(susInput7);
            confirmationForm.appendChild(susInput8);
            confirmationForm.appendChild(susInput9);

            // Kalau tombol merah diklik maka lakukan:
            deleteButton.addEventListener("click", function(){
                tbody.removeChild(newRow); // Hapus row data yang ada di tabel

                // Hilangkan hidden input-nya juga
                confirmationForm.removeChild(susInput1);
                confirmationForm.removeChild(susInput2);
                confirmationForm.removeChild(susInput3);
                confirmationForm.removeChild(susInput4);
                confirmationForm.removeChild(susInput5);
                confirmationForm.removeChild(susInput6);
                confirmationForm.removeChild(susInput7);
                confirmationForm.removeChild(susInput8);
                confirmationForm.appendChild(susInput9);
            });
        });
    </script>

@endsection
