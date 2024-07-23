@extends('layouts.main-admin')

@section('content')
    <x-container-middle>
        <div class="container bg-white rounded-4 p-5">

            <h2>Add Item</h2>

            {{-- @csrf kepake untuk token ,wajib --}}

            <form method="POST" action="{{ route('purchase-store') }}" id="bikinpurchase">
                {{-- @csrf kepake untuk token ,wajib --}}
                @csrf

                <div class="mt-3">
                    <label for="partner_id">Supplier</label>
                    <select name="partner_id" id="partner_id" class="form-select">
                        @foreach ($supplier as $s)
                            <option value="{{ $s->id }}" @if ($supplier == old('partner_id')) selected @endif>
                                {{ $s->partner_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('partner_id')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="purchase_date">Purchase Date</label>
                    <input type="text" class="form-control" name="purchase_date" id="purchase_date"
                        onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="Purchase Date"
                        value="{{ old('purchase_date') }}">
                    @error('purchase_date')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="fakeregister">Register</label>
                    <input type="text" class="form-control" id="fakeregister" name="fakeregister" placeholder="Register"
                        disabled>
                </div>

                <div class="mt-3">
                    <label for="purchase_deadline">Purchase Deadline</label>
                    <input type="text" class="form-control" name="purchase_deadline" id="purchase_deadline"
                        onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="Purchase Deadline"
                        value="{{ old('purchase_deadline') }}">
                    @error('purchase_deadline')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="purchase_status">Purchase Status</label>
                    <select name="purchase_status" id="purchase_status" class="form-select">
                        @foreach ($status as $st)
                            <option value="{{ $st }}" @if ($st == old('purchase_status')) selected @endif>
                                {{ $st }}
                            </option>
                        @endforeach
                    </select>
                    @error('purchase_status')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Add">
                </div>
            </form>
        </div>
    </x-container-middle>

    <script>
        // Jika input purchase_date berubah, maka jalankan perintah berikut
        const purchase_date = document.getElementById("purchase_date");
        purchase_date.addEventListener("change", function() {

            // Ambil data dari Laravel
            const allpuchasedata = @json($purchases);

            // Hitung berapa data dengan purchase_date yang sama
            let n = 0;
            for (let purc of allpuchasedata) {
                if (purc.purchase_date == purchase_date.value) {
                    n++;
                }
            }

            // Formatting ulang data tanggal dari Y-M-D jadi DMY
            const [year, month, day] = purchase_date.value.split('-');
            const formattedDate = `${day}${month}${year}`;

            // Generate SKU dan masukkan hasilnya langsung ke input fakeregister (yang tampil di user)
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
