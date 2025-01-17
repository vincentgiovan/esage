@extends('layouts.main-admin')

@section('content')
    <x-container-middle>
        <div class="container bg-white p-5 rounded-4 border border-1 card">

            <h2>Edit Data Pengiriman</h2>

            {{-- @csrf kepake untuk token ,wajib --}}

            <form method="POST" action="{{ route('deliveryorder-update', $delivery_order->id) }}" id="bikindevor">
                @csrf

                <div class="mt-3">
                    <label for="delivery_date">Tanggal Pengiriman</label>
                    <input type="date" class="form-control @error("delivery_date") is-invalid @enderror" id="delivery_date" name="delivery_date" placeholder="delivery_date"
                        value = "{{ old('delivery_date', $delivery_order->delivery_date) }}">

                    @error('delivery_date')
                        <p class="text-danger">Harap masukkan tanggal pengiriman</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="project_id">Proyek</label>
                    <select name="project_id" id="project_id" class="form-select">
                        @foreach ($projects as $pn)
                            <option value="{{ $pn->id }}" @if ($delivery_order->project_id == $pn->id) selected @endif>
                                {{ $pn->project_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-3">
                    <div class="mt-3">
                        <label for="fakeregister">SKU</label>
                        <input type="text" class="form-control" id="fakeregister" name="fakeregister" placeholder="Register" value="{{ $delivery_order->register }}"  disabled>
                    </div>
                </div>
                <div class="mt-3">
                    <label for="delivery_status">Status Pengiriman</label>
                    {{-- <input type="text" class="form-control" name="status" placeholder="Status"  value = "{{ old("status") }}"> --}}
                    <select name="delivery_status" id="delivery_status" class="form-select">
                        <option value="Complete">Selesai</option>
                        <option value="Incomplete">Belum selesai</option>
                    </select>
                </div>
                <div class="mt-3">
                    <label for="note">Catatan</label>
                    <input type="text" class="form-control" id="note" name="note" placeholder="Note"
                        value = "{{ old('note', $delivery_order->note) }}">
                </div>
                <div class="mt-4">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Simpan Perubahan">
                </div>
            </form>
        </div>
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
