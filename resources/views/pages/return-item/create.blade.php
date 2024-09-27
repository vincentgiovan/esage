@extends('layouts.main-admin')

@section("content")

    <x-container-middle>
        <div class="container rounded-4 p-5 bg-white border border-1 card">
            <h2 class="text-center fw-bold">Create New Order</h2>
            <form method="POST" action="{{ route("deliveryorder-store"{{-- ,$delivery_order->id--}} ) }}" id="bikindevor">
                @csrf
                <div class="mt-3">
                    <label for="delivery_date">Tanggal Delivery</label>
                    <input type="date" class="form-control" id="delivery_date" name="delivery_date" placeholder="delivery_date"  value = "{{ old("delivery_date") }}">

                    @error("delivery_date")
                    <p style = "color: red; font-size: 10px;">{{$message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="project_id">Proyek</label>
                    <select name="project_id" class=
                    @extends('layouts.main-admin')

                    @section("content")

                        <x-container-middle>
                            <div class="container bg-white rounded-4 p-5 border border-1 card">
                                <h2>Add Product To Return</h2>
                                <div>
                                    <div class="mt-3">
                                        <label for="select-product-dropdown">Nama Produk</label>
                                        <select name="product_name" class="form-select" id="select-product-dropdown">
                                            @foreach ($products as $product)
                                                <option value="{{ $product->toJson() }}" @if ($product->product_name == old("product_name")) selected @endif>{{ $product->product_name }} ({{ $product->variant }}) (Stok :  {{ $product->stock }})</option>
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

                                column1.innerText = `${converted.product_name} (${converted.variant})`; // format teks yang tampil di kolom nama produk menjadi "nama_product (varian) dan tampilkan di row data baru di kolom nama produk"
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
                    "form-select" id="project_id">
                        @foreach ($projects as $pn)
                            <option value="{{ $pn->id}}">{{ $pn->project_name }}</option>
                        @endforeach

                    </select>
                    @error("project_id")
                    <p style = "color: red; font-size: 10px;">{{$message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="fakeregister">SKU</label>
                    <input type="text" class="form-control" id="fakeregister" name="fakeregister" placeholder="Register"  disabled>
                </div>
                <div class="mt-3">
                    {{-- <input type="text" class="form-control" name="status" placeholder="Status"  value = "{{ old("status") }}"> --}}
                    <label for="delivery_status">Status Delivery</label>
                    <select name="delivery_status" class="form-select" id="delivery_status">
                        <option value="Complete">Complete</option>
                        <option value="Incomplete">Incomplete</option>
                    </select>
                    @error("delivery_status")
                    <p style = "color: red; font-size: 10px;">{{$message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="note">Catatan</label>
                    <input type="text" class="form-control" name="note" id="note" placeholder="Note" value = "{{ old("note")}}">
                    @error("note")
                    <p style = "color: red; font-size: 10px;">{{$message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <input type="submit" class="btn btn-success px-3 py-1" value="add">
                </div>
            </form>
        </div>


        <!-- Bikin change button color on hover pake js -->
        {{-- <script>
            const susbtn = document.querySelector("#susbtn");
            susbtn.addEventListener("mouseover", () => {
                susbtn.classList.remove("btn-success");
                susbtn.classList.add("btn-secondary");
            });
            susbtn.addEventListener("mouseout", () => {
                susbtn.classList.remove("btn-secondary");
                susbtn.classList.add("btn-success");
            });
        </script> --}}
    </x-container-middle>

    <script>
        // Jika input delivery_date berubah, maka jalankan perintah berikut
        const delivery_date = document.getElementById("delivery_date");
        delivery_date.addEventListener("change", function(){

            // Ambil data dari Laravel
            const alldeliveryorderdata = @json($delivery_orders);

            // Hitung berapa data delivery order yang punya delivery_date yang sama
            let n = 0;
            for(let delord of alldeliveryorderdata) {
                if(delord.delivery_date == delivery_date.value){
                    n++;
                }
            }

            // Formatting ulang data tanggal dari Y-M-D jadi DMY
            const [year, month, day] = delivery_date.value.split('-');
            const formattedDate = `${day}${month}${year}`;

            // Generate SKU dan masukin hasilnya langsung ke input fakeregister (yang tampil di user)
            const generatedsku = "DO/"+ formattedDate +"/"+ (n + 1);
            const fakeregister = document.getElementById("fakeregister");
            fakeregister.value = generatedsku;
        });

        // Jika form bikindevor di-submit, jalankan perintah berikut
        const purchaseForm = document.getElementById("bikindevor");
        purchaseForm.addEventListener("submit", function(event){
            // Cegah form buat submit
            event.preventDefault();

            // Ambil nilai SKU dari input fakeregister
            const fakeRegister = document.querySelector('input[name="fakeregister"]');

            // Bikin elemen input tersembunyi buat bantu kirim data ke Laravel (karena by default-nya Laravel ga nganggap disabled input) dan nilainya diambil dari fakeregister
            const hiddenInput = document.createElement("input");
            hiddenInput.setAttribute("type", "hidden");
            hiddenInput.setAttribute("name", "register");
            hiddenInput.setAttribute("value", fakeRegister.value);

            purchaseForm.appendChild(hiddenInput);

            // Baru submit form-nya
            purchaseForm.submit();
        })
    </script>
@endsection
