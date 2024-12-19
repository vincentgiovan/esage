@extends('layouts.main-admin')

@section("content")

    <x-container-middle>
        <div class="container rounded-4 p-5 bg-white border border-1 card">
            <h2 class="text-center fw-bold">New Return Item</h2>

            <form method="POST" action="{{ route('returnitem-update', $return_item->id) }}" enctype="multipart/form-data">
                @csrf

                <div class="mt-3">
                    <label for="project">Pilih Asal Proyek</label>
                    <input type="text" name="project" class="form-control" id="project" value="{{ $return_item->project->project_name }}" disabled>
                </div>

                <div class="mt-3">
                    <label for="product">Produk yang akan dikembalikan</label>
                    <input type="text" name="product" class="form-control" id="product" value="{{ $return_item->product->product_name }}" disabled>
                </div>

                <div class="mt-3">
                    <label for="quantity">Jumlah</label>
                    <input type="text" class="form-control" name="quantity" id="quantity" placeholder="Jumlah" value = "{{ old("quantity", $return_item->quantity)}}">
                    @error("quantity")
                    <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="status">Status</label>
                    <select name="status" class="form-select" id="status">
                        <option value="Ready to pickup" @if(old('status', $return_item->status) == "Ready to pickup") selected @endif>Ready to pickup</option>
                        <option value="Not ready yet" @if(old('status', $return_item->status) == "Not ready yet") selected @endif>Not ready yet</option>
                    </select>
                    @error("status")
                    <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="PIC">PIC</label>
                    <input type="text" class="form-control" name="PIC" id="PIC" placeholder="PIC" value = "{{ old('PIC', $return_item->PIC)}}">
                    @error("PIC")
                    <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="image">Foto Barang</label>
                    <input type="file" class="form-control" name="image" id="image">
                    @error('image')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <img id="img-preview" class="w-25 mt-2" @if($return_item->foto) src="{{ Storage::url("app/public/" . $return_item->foto) }}" @endif>

                <div class="mt-3">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Simpan Perubahan">
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
