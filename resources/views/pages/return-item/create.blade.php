@extends('layouts.main-admin')

@section("content")

    <x-container-middle>
        <div class="container rounded-4 p-5 bg-white border border-1 card">
            <h2 class="text-center fw-bold">Return Barang Baru</h2>

            <form method="POST" action="{{ route('returnitem-store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mt-3">
                    <label for="project">Pilih Asal Proyek</label>
                    <select name="project" class="form-select" id="project">
                        <option disabled selected>Pick an item</option>
                        @foreach ($projects as $proj)
                            <option value="{{ $proj->id }}" @if(old('project') == $proj->id) selected @endif>{{ $proj->project_name }}</option>
                        @endforeach
                    </select>

                    @error("project")
                        <p style="color: red; font-size: 10px;">{{$message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="product">Produk yang akan dikembalikan</label>
                    <select name="product" class="form-select select2" id="product">
                        <option disabled selected>Pick an item</option>
                        @foreach ($products as $prod)
                            <option value="{{ $prod->id }}" @if(old('product') == $prod->id) selected @endif>{{ $prod->product_name }} - {{ $prod->variant }} (Harga: Rp {{ number_format($prod->price, 2, ',', '.') }}, Stok:  {{ $prod->stock }}, Diskon: {{ $prod->discount }}%) @if($prod->is_returned == 'yes'){{__('- Returned')}}@endif</option>
                        @endforeach
                    </select>
                    @error("product")
                    <p style = "color: red; font-size: 10px;">{{$message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="qty">Jumlah</label>
                    <input type="text" class="form-control" name="qty" id="qty" placeholder="Jumlah" value = "{{ old("qty")}}">
                    @error("qty")
                    <p style = "color: red; font-size: 10px;">{{$message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="status">Status</label>
                    <select name="status" class="form-select" id="status">
                        <option value="Ready to pickup" @if(old('status') == "Ready to pickup") selected @endif>Ready to pickup</option>
                        <option value="Not ready yet" @if(old('status') == "Not ready yet") selected @endif>Not ready yet</option>
                    </select>
                    @error("status")
                    <p style = "color: red; font-size: 10px;">{{$message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="PIC">PIC</label>
                    <input type="text" class="form-control" name="PIC" id="PIC" placeholder="PIC" value = "{{ old("PIC")}}">
                    @error("PIC")
                    <p style = "color: red; font-size: 10px;">{{$message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="image">Foto Barang</label>
                    <input type="file" class="form-control" name="image" id="image">
                    @error('image')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <img id="img-preview" class="w-25 mt-2">

                <div class="mt-3">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Simpan Data Baru">
                </div>
            </form>
        </div>
    </x-container-middle>

    <script>
        $(document).ready(function(){
            $("#image").on("change", function(){
                const oFReader = new FileReader();
                oFReader.readAsDataURL(image.files[0]);

                oFReader.onload = function(oFEvent){
                    $("#img-preview").attr("src", oFEvent.target.result);
                }
            });
        });
    </script>
@endsection
