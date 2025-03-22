
@extends('layouts.main-admin')

@section("content")

    <x-container-middle>
        <div class="container bg-white py-4 px-5 rounded-4 border border-1 card mt-4">

            <h3>Tambah Barang ke Pengembalian</h3>
                <div>
                    <div class="mt-3">
                        <label for="product_id">Nama Produk</label>
                        <select class="form-select select2" name="_product_id" id="product_id" placeholder="Nama Barang">
                            @foreach ($products as $product)
                                <option value="{{ $product->toJson() }}" @if ($product->product_name == old("product_name")) selected @endif>{{ $product->product_name }} - {{ $product->variant }}  (Harga: Rp {{ number_format($product->price, 2, ',', '.') }}, Stok:  {{ $product->stock }}, Diskon: {{ $product->discount }}%)</option>
                            @endforeach
                        </select>
                        <p class="text-danger" id="errProductName"></p>
                    </div>

                    <div class="mt-3">
                        <label for="qty">Jumlah</label>
                        <input type="text" class="form-control" name="_qty" id="qty" placeholder="Qty"  value="0">
                        <p class="text-danger" id="errQty"></p>
                    </div>

                    <div class="mt-3">
                        <input type="button" id="addbutton" class="btn btn-primary px-3 py-1" value="Tambah">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-100 mt-4">
                        <thead>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </thead>
                        <tbody id="isibody">

                        </tbody>
                    </table>
                </div>

                <form method="POST" action="{{ route('returnitem-list-store', $return_item->id ) }}" class="mt-5" id="peon">

                    @csrf

                    <div class="mt-3">
                        <input type="submit" class="btn btn-success px-3 py-1" value="Simpan">
                    </div>

                </form>

        </div>
    </x-container-middle>

    <script>
        // function makeCapitalizedEachWordAndNoSpace(text){
        //     // Remove extra spaces, split into words, capitalize each, and join back together
        //     const processedText = text
        //         .trim() // Trim any leading/trailing spaces
        //         .split(/\s+/) // Split by any space characters
        //         .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()) // Capitalize each word
        //         .join(''); // Join without spaces

        //     return processedText;
        // }

        // Kalau tombol add data diklik maka lakukan:
        $('#addbutton').on("click", function(){
            // Targetkan tbody tabel (buat nanti display data)
            const tbody = $("#isibody");

            // Targetkan elemen-elemen input data purchase produk yang diperlukan (buat nanti diambil nilainya)
            const inpProdName = $("#product_id");
            const inpQty = $("#qty");

            // Targetkan elemen-elemen error message (buat nanti display error message)
            const errProductName = $("#errProductName");
            const errQty = $("#errQty");

            // Hilangkan error message dan mark merah pada input dan error message sebelum validasi
            errProductName.text('');
            errQty.text('');

            inpProdName.removeClass('is-invalid');
            inpQty.removeClass('is-invalid');

            // Validasi input
            let inputAman = true;

            // Kalo product name kosong maka mark merah input dan tampilkan error message
            if(!inpProdName.val()){
                inpProdName.addClass("is-invalid");
                errProductName.text("Harap masukkan nama barang.");

                inputAman = false;
            }

            // Kalo satuan kosong maka mark merah input dan tampilkan error message
            if(!inpQty.val() || inpQty.val() < 1){
                inpQty.addClass("is-invalid");
                errQty.text("Harap masukkan nilai minimal 1.");

                inputAman = false;
            }

            // Kalau misalkan ada 1 atau lebih input yang ga sesuai, jangan dilanjut
            if(!inputAman){
                return;
            }

            // Generate elemen <tr> dan <td> untuk membuat row tabel display
            const product = JSON.parse(inpProdName.val());

            const newRow = $("<tr>");
            const colProdName = $("<td>").text(product.product_name);
            const colQty = $("<td>").text(inpQty.val());
            const colAction = $("<td>");

            // Buat tombol merah tong sampah buat nanti dipake buat hapus 1 row data
            const deleteButton = $("<button>").addClass("btn btn-danger").attr("type", "button").html('<i class="bi bi-trash3"></i>');
            colAction.append(deleteButton); // display tombol merah di kolom action

            // Gabungkan semua kolom data menjadi 1 row data
            tbody.append(newRow.append(colProdName).append(colQty).append(colAction));

            // Generate hidden input untuk kirim data ke Laravel (bikin something like <input type="hidden" name="variabel_data[]" value="nilai_dari_input"> buat setiap input yang ada)
            const hidProdName = $("<input>").attr({"type": "hidden", "name": 'product_id[]', "value": product.id});
            const hidQty = $("<input>").attr({"type": "hidden", "name": 'qty[]', "value": inpQty.val()});

            $('form').append(hidProdName).append(hidQty);

            // Kalau tombol merah diklik maka lakukan:
            deleteButton.on("click", function(){
                newRow.remove(); // Hapus row data yang ada di tabel

                // Hilangkan hidden input-nya juga
                hidProdName.remove();
                hidQty.remove();
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
