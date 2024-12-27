@extends('layouts.main-admin')

@section('content')
    <x-container-middle>
        <div class="container bg-white p-5 rounded-4 border border-1 card mt-4">
            <h2>Edit Data Pembelian</h2>

            <form method="POST" action="{{ route('purchase-edit', $purchase->id) }}" id="bikinpurchase">
                @csrf

                <div class="mt-3">
                    <label for="partner_id">Supplier</label>
                    <select name="partner_id" id="partner_id" class="form-select">
                        @foreach ($supplier as $s)
                            <option value="{{ $s->id }}" @if ($s->id == old('partner_id')) selected @endif>
                                {{ $s->partner_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-3">
                    <label for="purchase_date">Tanggal Pembelian</label>
                    <input type="date" class="form-control @error("purchase_date") is-invalid @enderror" name="purchase_date" id="purchase_date"
                        value = "{{ old('purchase_date', $purchase->purchase_date) }}">
                    @error('purchase_date')
                        <p class="text-danger">Harap masukkan tanggal pembelian.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="purchase_deadline">Tenggat Pembelian</label>
                    <input type="date" class="form-control @error("purchase_deadline") is-invalid @enderror" name="purchase_deadline" id="purchase_deadline"
                        value = "{{ old('purchase_deadline', $purchase->purchase_deadline) }}">
                    @error('purchase_deadline')
                        <p class="text-danger">Harap masukkan tenggat pembelian.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="fakeregister">SKU</label>
                    <input type="text" class="form-control" id="fakeregister" name="fakeregister" placeholder="Register"
                        disabled value="{{ $purchase->register }}">
                </div>

                <div class="mt-3">
                    <label for="purchase_status">Status Pembelian</label>
                    <select name="purchase_status" id="purchase_status" class="form-select">
                        <option value="Ordered" @if(old('purchase_status', $purchase->purchase_status) == 'Ordered') selected @endif>Telah dipesan</option>
                        <option value="Retrieved" @if(old('purchase_status', $purchase->purchase_status) == 'Retrieved') selected @endif>Diterima</option>
                    </select>
                </div>

                <div class="mt-4">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Simpan Perubahan">
                </div>
            </form>
        </div>
    </x-container-middle>

    <script>
        // Jika input delivery_date berubah, maka jalankan perintah berikut
        const purchase_date = document.getElementById("purchase_date");
        purchase_date.addEventListener("change", function() {

            // Ambil data dari Laravel
            const allpuchasedata = @json($purchases);

            // Hitung berapa data delivery order yang punya delivery_date yang sama
            let n = 0;
            for (let purc of allpuchasedata) {
                if (purc.purchase_date == purchase_date.value) {
                    n++;
                }
            }

            // Formatting ulang data tanggal dari Y-M-D jadi DMY
            const [year, month, day] = purchase_date.value.split('-');
            const formattedDate = `${day}${month}${year}`;

            // Generate SKU dan masukin hasilnya langsung ke input fakeregister (yang tampil di user)
            const generatedsku = "PU/" + formattedDate + "/" + (n + 1);
            const fakeregister = document.getElementById("fakeregister");
            fakeregister.value = generatedsku;
        });

        // Biar data register kekirim ke Laravel even its disabled, soalnya Laravel ga bakal nganggap input yang disabled
        const purchaseForm = document.getElementById("bikinpurchase");
        purchaseForm.addEventListener("submit", function(event) {
            event.preventDefault(); // Jangan submit dulu

            // Bikin hidden input buat dikirim ke Laravel
            const hiddenInput = document.createElement("input");
            hiddenInput.setAttribute("type", "hidden");
            hiddenInput.setAttribute("name", "register");

            // Ambil nilainya dari auto generated SKU
            const fakeRegister = document.querySelector('input[name="fakeregister"]');
            hiddenInput.setAttribute("value", fakeRegister.value);

            // Tambahin hidden input ke form
            purchaseForm.appendChild(hiddenInput);

            // Baru submit form-nya
            purchaseForm.submit();
        })
    </script>
@endsection
