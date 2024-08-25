@extends('layouts.main-admin')

@section('content')
    <x-container-middle>
        <div class="container bg-white p-5 rounded-4 border border-1 card">

            <h2>Edit Item</h2>

            {{-- @csrf kepake untuk token ,wajib --}}

            <form method="POST" action="{{ route('purchase-edit', $purchase->id) }}" id="bikinpurchase">
                {{-- @csrf kepake untuk token ,wajib --}}
                @csrf
                {{-- <div class="mt-3">
                    <select name="product_name" class="form-select">
                        @foreach ($product_name as $s)
                            <option value="{{ $s }}" @if ($product->product_name == $s) selected @endif>{{ $s }}</option>
                        @endforeach

                    </select>
                    @error('product_name')
                    <p style = "color: red; font-size: 10px;">{{$message }}</p>
                    @enderror
                </div> --}}
                <div class="mt-3">
                    <label for="partner_id">Nama Partner</label>
                    <select name="partner_id" id="partner_id" class="form-select">
                        @foreach ($supplier as $s)
                            <option value="{{ $s->id }}" @if ($s->id == old('partner_id')) selected @endif>
                                {{ $s->partner_name }}</option>
                        @endforeach

                    </select>
                    @error('supplier_id')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="purchase_deadline">Deadline Pembelian</label>
                    <input type="text" class="form-control" name="purchase_deadline" id="purchase_deadline"
                        onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="Purchase_deadine"
                        value = "{{ old('purchase_deadline', $purchase->purchase_deadline) }}">
                    @error('purchase_deadline')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="fakeregister">SKU</label>
                    <input type="text" class="form-control" id="fakeregister" name="fakeregister" placeholder="Register"
                        disabled value="{{ $purchase->register }}">
                </div>
                <div class="mt-3">
                    <label for="purchase_date">Tanggal Pembelian</label>
                    <input type="text" class="form-control" name="purchase_date" id="purchase_date"
                        onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="Purchase Date"
                        value = "{{ old('purchase_date', $purchase->purchase_date) }}">
                    @error('purchase_date')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="purchase_status">Status</label>
                    <select name="purchase_status" id="purchase_status" class="form-select">
                        @foreach ($status as $st)
                            <option value="{{ $st }}" @if ($st == old('purchase_status')) selected @endif>
                                {{ $st }}</option>
                        @endforeach

                    </select>
                    @error('purchase_status')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Edit">
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
