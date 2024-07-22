@extends('layouts.main-admin')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="min-height:100vh">
        <div class="container">

            <h2>Edit Item</h2>

            {{-- @csrf kepake untuk token ,wajib --}}

            <form method="POST" action="{{ route('deliveryorder-update', $delivery_order->id) }}" id="bikindevor">
                @csrf
                {{-- <div class="mt-3">
                <select name="product_id" class="form-select">
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" @if ($delivery_order->product_id == $product->id) selected @endif >{{ $product->product_name }}</option>
                @endforeach

                </select>
                @error('product_id')
                <p style = "color: red; font-size: 10px;">{{$message }}</p>
                @enderror
                </div> --}}
                <div class="mt-3">
                    <input type="date" class="form-control" id="delivery_date" name="delivery_date" placeholder="delivery_date"
                        value = "{{ old('delivery_date', $delivery_order->delivery_date) }}">

                    @error('delivery_date')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <select name="project_id" class="form-select">
                        @foreach ($projects as $pn)
                            <option value="{{ $pn->id }}" @if ($delivery_order->project_id == $pn->id) selected @endif>
                                {{ $pn->project_name }}</option>
                        @endforeach
                    </select>
                    @error('project_id')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <div class="mt-3">
                        <input type="text" class="form-control" id="fakeregister" name="fakeregister" placeholder="Register" value="{{ $delivery_order->register }}"  disabled>
                    </div>
                </div>
                <div class="mt-3">
                    {{-- <input type="text" class="form-control" name="status" placeholder="Status"  value = "{{ old("status") }}"> --}}
                    <select name="delivery_status" class="form-select">
                        <option value="Complete">Complete</option>
                        <option value="Incomplete">Incomplete</option>
                    </select>
                    @error('delivery_status')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <input type="text" class="form-control" name="note" placeholder="Note"
                        value = "{{ old('note', $delivery_order->note) }}">
                    @error('note')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Edit">
                </div>
            </form>
        </div>
    </div>

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
            const generatedsku = "DO/"+ formattedDate +"/"+ (n+1);
            const fakeregister = document.getElementById("fakeregister");
            fakeregister.value = generatedsku;
        });

        // Jika form bikindevor di-submit, jalankan perintah berikut
        const daForm = document.getElementById("bikindevor");
        daForm.addEventListener("submit", function(event){
            // Cegah form buat submit
            event.preventDefault();

            // Ambil nilai SKU dari input fakeregister
            const fakeRegister = document.querySelector('input[name="fakeregister"]');

            // Bikin elemen input tersembunyi buat bantu kirim data ke Laravel (karena by default-nya Laravel ga nganggap disabled input) dan nilainya diambil dari fakeregister
            const hiddenInput = document.createElement("input");
            hiddenInput.setAttribute("type", "hidden");
            hiddenInput.setAttribute("name", "register");
            hiddenInput.setAttribute("value", fakeRegister.value);

            daForm.appendChild(hiddenInput);

            // Baru submit form-nya
            daForm.submit();
        })
    </script>

@endsection
