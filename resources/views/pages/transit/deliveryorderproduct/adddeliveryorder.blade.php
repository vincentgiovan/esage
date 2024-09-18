
@extends('layouts.main-admin')

@section("content")

    <x-container-middle>
        <div class="container bg-white rounded-4 p-5 border border-1 card">
            <h2>Add Product To Delivery Order</h2>
            <div>
                <div class="mt-3">
                    <label for="select-product-dropdown">Nama Produk</label>
                    <select name="product_name" class="form-select" id="select-product-dropdown">
                        @foreach ($products as $product)
                            <option value="{{ $product->toJson() }}" @if ($product->product_name == old("product_name")) selected @endif>{{ $product->product_name }} - {{ $product->variant }} (Harga: Rp {{ number_format($product->price, 2, ',', '.') }}, Stok:  {{ $product->stock }})</option>
                        @endforeach
                    </select>
                    <p style = "color: red; font-size: 10px;"></p>
                </div>

                <div class="mt-3">
                    <label for="quantity">Jumlah</label>
                    <input type="number" class="form-control" name="quantity" id="quantity"  placeholder="Quantity" value = "{{ old("quantity")}}">
                    <p style = "color: red; font-size: 10px;" id="errQuantity"></p>
                </div>

                <div class="mt-3">
                    <input type="button" id="addbutton" class="btn btn-primary px-3 py-1" value="Add Items">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-100 mt-4">
                    <thead>
                        <th>Nama Barang & Variant</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </thead>
                    <tbody id="isibody">

                    </tbody>
                </table>
            </div>

            <form method="POST" action="{{ route("deliveryorderproduct-store1", $deliveryorder->id ) }}" class="mt-5" id="peon">
                {{-- @csrf kepake untuk token ,wajib --}}
                @csrf

                <div class="mt-3">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Proceed">
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
            const input4 = document.getElementById("quantity");

            // Targetkan elemen-elemen error message (buat nanti display error message)
            const errQuantity = document.getElementById("errQuantity");

            const converted = JSON.parse(input1.value); // value dari option yang dipilih itu konversi collection Laravel jadi JSON, tapi bentuknya masih teks, jadi perlu dikonversi ke format JSON beneran dulu biar lebih enak diolah

            // Hilangkan error message dan mark merah pada input dan error message sebelum validasi
            errQuantity.innerText = "";
            input4.style.border = "none";

            // Validasi input
            let inputAman = true; // Status apakah sudah terjadi kesalahan input atau belum

            // Kalau input quantity kosong atau nilainya di bawah 1 maka mark merah input dan tampilkan pesan error
            if(!input4.value || input4.value < 1 || input4.value > converted.stock){
                input4.style.border = "solid 1px red";
                errQuantity.innerText = "Invalid input";

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

            column1.innerText = `${converted.product_name} - ${converted.variant} (Harga: ${formatNumber(converted.price)}, Stok: ${converted.stock})`; // format teks yang tampil di kolom nama produk menjadi "nama_product (varian) dan tampilkan di row data baru di kolom nama produk"
            column4.innerText = input4.value; // ambil nilai dari input quantity dan tampilkan di kolom quantity

            // Buat tombol merah tong sampah buat nanti dipake buat hapus 1 row data
            const deleteButton = document.createElement("button");
            deleteButton.classList.add("btn", "btn-danger");
            deleteButton.setAttribute("type", "button");
            deleteButton.innerText = "Remove";
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
            susInput3.setAttribute("value", input4.value);

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
    </script>

@endsection
