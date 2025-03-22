
@extends('layouts.main-admin')

@section("content")

    <x-container-middle>
        <div class="container border border-1 card py-4 px-5 mt-4">

            <h2>Tambah Barang ke Pembelian</h2>
                <div>
                    <div class="mt-3 ">
                        <label for="select-product-dropdown">Nama Produk</label>
                        <select name="product_name" class="form-select select2" id="select-product-dropdown">
                            @foreach ($products as $product)
                                <option value="{{ $product->toJson() }}" @if ($product->product_name == old("product_name")) selected @endif>{{ $product->product_name }} - {{ $product->variant }}  (Harga: Rp {{ number_format($product->price, 2, ',', '.') }}, Stok:  {{ $product->stock }}, Diskon: {{ $product->discount }}%)</option>
                            @endforeach
                        </select>
                        <p class="text-danger"></p>
                    </div>

                    <div class="mt-3">
                        <label for="price">Harga</label>
                        <input type="number" class="form-control" name="price" id="price"  placeholder="Price"  value="0">
                        <p class="text-danger" id="errPrice"></p>
                    </div>

                    <div class="mt-3">
                        <label for="discount">Diskon</label>
                        <input type="text" class="form-control" name="discount"  id="discount" placeholder="Diskon" value="0">
                        <p class="text-danger" id="errDiscount"></p>
                    </div>

                    <div class="mt-3">
                        <label for="quantity">Jumlah</label>
                        <input type="number" class="form-control" name="quantity" id="quantity"  placeholder="Quantity" value="0">
                        <p class="text-danger" id="errQuantity"></p>
                    </div>

                    <div class="mt-3">
                        <input type="button" id="addbutton" class="btn btn-primary px-3 py-1" value="Tambah">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-100 mt-4">
                        <thead>
                            <th>Nama Barang & Varian</th>
                            <th>Price</th>
                            <th>Diskon</th>
                            <th>Quantity</th>
                            <th>Aksi</th>
                        </thead>
                        <tbody id="isibody">

                        </tbody>
                    </table>
                </div>

                <form method="POST" action="{{ route("purchaseproduct-store1", $purchase->id ) }}" class="mt-5" id="peon">
                {{-- @csrf kepake untuk token ,wajib --}}
                    @csrf

                    <button class="btn btn-success" type="submit">Simpan</button>
                </form>

        </div>
    </x-container-middle>

    <script>
        function formatNumber(number) {
            return new Intl.NumberFormat('de-DE', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }).format(number);
        }

        // Targetkan form buat submit data
        const confirmationForm = document.getElementById("peon");

        // Targetkan tombol add data
        const addbutton = document.getElementById("addbutton");

        // Kalau tombol add data diklik maka lakukan:
        addbutton.addEventListener("click", function(){
            // Targetkan tbody tabel (buat nanti display data)
            const tbody = document.getElementById("isibody");

            // Targetkan elemen-elemen input data purchase produk yang diperlukan (buat nanti diambil nilainya)
            const input1 = document.getElementById("select-product-dropdown");
            const input2 = document.getElementById("price");
            const input3 = document.getElementById("discount");
            const input4 = document.getElementById("quantity");

            // Targetkan elemen-elemen error message (buat nanti display error message)
            const errPrice = document.getElementById("errPrice");
            const errDiscount = document.getElementById("errDiscount");
            const errQuantity = document.getElementById("errQuantity");

            // Hilangkan error message dan mark merah pada input dan error message sebelum validasi
            errPrice.innerText = "";
            errQuantity.innerText = "";
            input2.classList.remove("is-invalid");
            input4.classList.remove("is-invalid");

            // Validasi input
            let inputAman = true; // Status apakah sudah terjadi kesalahan input atau belum

            // Kalau input price kosong atau nilainya di bawah 1 maka mark merah input dan tampilkan pesan error
            if(!input2.value || input2.value < 1){
                input2.classList.add("is-invalid");
                errPrice.innerText = "Harap masukkan nilai minimal 1.";

                inputAman = false;
            }

            // Kalau input diskon kosong maka mark merah input dan tampilkan pesan error
            if(!input3.value || input3.value < 0){
                input3.classList.add("is-invalid");
                errDiscount.innerText = "Harap masukkan nilai minimal 0. Gunakan tanda titik untuk desimal.";
            }

            // Kalau input quantity kosong atau nilainya di bawah 1 maka mark merah input dan tampilkan pesan error
            if(!input4.value || input4.value < 1){
                input4.classList.add("is-invalid");
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
            const column4 = document.createElement("td");
            const column5 = document.createElement("td");

            const converted = JSON.parse(input1.value); // value dari option yang dipilih itu konversi collection Laravel jadi JSON, tapi bentuknya masih teks, jadi perlu dikonversi ke format JSON beneran dulu biar lebih enak diolah
            column1.innerText = `${converted.product_name} - ${converted.variant} (Harga: Rp ${formatNumber(converted.price)}, Stok: ${converted.stock}, Diskon: ${converted.discount}%)`; // format teks yang tampil di kolom nama produk menjadi "nama_product (varian) dan tampilkan di row data baru di kolom nama produk"
            column2.innerText = input2.value; // ambil nilai dari input price dan tampilkan di kolom harga
            column3.innerText = input3.value; // ambil nilai dari input diskon dan tampilkan di kolom diskon
            column4.innerText = input4.value; // ambil nilai dari input quantity dan tampilkan di kolom quantity

            // Buat tombol merah tong sampah buat nanti dipake buat hapus 1 row data
            const deleteButton = document.createElement("button");
            deleteButton.classList.add("btn", "btn-danger");
            deleteButton.setAttribute("type", "button");
            deleteButton.innerHTML = '<i class="bi bi-trash3"></i>';
            column5.appendChild(deleteButton); // display tombol merah di kolom action

            // Gabungkan semua kolom data menjadi 1 row data
            newRow.appendChild(column1);
            newRow.appendChild(column2);
            newRow.appendChild(column3);
            newRow.appendChild(column4);
            newRow.appendChild(column5);

            // Tambahkan row data baru ke tabel untuk di-display
            tbody.appendChild(newRow);

            // Generate hidden input untuk kirim data ke Laravel (bikin something like <input type="hidden" name="variabel_data[]" value="nilai_dari_input"> buat setiap input yang ada)
            const susInput = document.createElement("input");
            susInput.setAttribute("type", "hidden");
            susInput.setAttribute("name", "products[]");
            susInput.setAttribute("value", converted.id);

            const susInput2 = document.createElement("input");
            susInput2.setAttribute("type", "hidden");
            susInput2.setAttribute("name", "discounts[]");
            susInput2.setAttribute("value", input3.value);

            const susInput3 = document.createElement("input");
            susInput3.setAttribute("type", "hidden");
            susInput3.setAttribute("name", "quantities[]");
            susInput3.setAttribute("value", input4.value);

            const susInput4 = document.createElement("input");
            susInput4.setAttribute("type", "hidden");
            susInput4.setAttribute("name", "prices[]");
            susInput4.setAttribute("value", input2.value);

            // Tambahkan semua hidden input ke form submit
            confirmationForm.appendChild(susInput);
            confirmationForm.appendChild(susInput2);
            confirmationForm.appendChild(susInput3);
            confirmationForm.appendChild(susInput4);

            // Kalau tombol merah diklik maka lakukan:
            deleteButton.addEventListener("click", function(){
                tbody.removeChild(newRow); // Hapus row data yang ada di tabel

                // Hilangkan hidden input-nya juga
                confirmationForm.removeChild(susInput);
                confirmationForm.removeChild(susInput2);
                confirmationForm.removeChild(susInput3);
                confirmationForm.removeChild(susInput4);
            });
        });

        $('#peon').on('submit', function(){
            if($('#isibody').find('tr').length > 0){
                this.submit();
            }
            else {
                alert('Anda belum memasukkan data sama sekali.');

                return false;
            }
        });
    </script>



@endsection
