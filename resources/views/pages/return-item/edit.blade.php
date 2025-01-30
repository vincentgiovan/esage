@extends('layouts.main-admin')

@section("content")

    <x-container-middle>
        <div class="container rounded-4 p-5 bg-white border border-1 card mt-4">
            <h2>Edit Data Return Item</h2>

            <form method="POST" action="{{ route('returnitem-update', $return_item->id) }}" enctype="multipart/form-data">
                @csrf

                <div class="mt-3">
                    <label for="return_date">Tanggal Pengembalian</label>
                    <input type="date" class="form-control @error('return_date') is-invalid @enderror" name="return_date" id="return_date"  value="{{ old("return_date", Carbon\Carbon::today()->format('Y-m-d'))}}">
                    @error("return_date", $return_item->return_date)
                    <p class="text-danger">Harap masukkan nama tanggal pengembalian.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="project_id">Pilih Asal Proyek</label>
                    <select name="project_id" class="form-select @error('project_id') is-invalid @enderror" id="project_id">
                        @foreach ($projects as $proj)
                            <option value="{{ $proj->id }}" @if(old('project_id', $return_item->project->id) == $proj->id) selected @endif>{{ $proj->project_name }}</option>
                        @endforeach
                    </select>

                    @error("project_id")
                        <p class="text-danger">Harap pilih project asal barang yang ingin dikembalikan.</p>
                    @enderror
                </div>

                {{-- <div class="mt-3">
                    <label for="product">Produk yang akan dikembalikan</label>
                    <input type="text" name="product" class="form-control" id="product" value="{{ $return_item->product->product_name }}">
                </div> --}}

                {{-- <div class="mt-3">
                    <label for="quantity">Jumlah</label>
                    <input type="text" class="form-control @error('quantity') is-invalid @enderror" name="quantity" id="quantity" placeholder="Jumlah" value = "{{ old("quantity", $return_item->quantity)}}">
                    @error("quantity")
                    <p class="text-danger">Harap masukkan nilai minimal 1.</p>
                    @enderror
                </div> --}}

                <div class="mt-3">
                    <label for="status">Status</label>
                    <select name="status" class="form-select" id="status">
                        <option value="Ready to pickup" @if(old('status', $return_item->status) == "Ready to pickup") selected @endif>Siap diangkut</option>
                        <option value="Not ready yet" @if(old('status', $return_item->status) == "Not ready yet") selected @endif>Belum siap diangkut</option>
                    </select>
                </div>

                <div class="mt-3">
                    <label for="PIC">PIC</label>
                    <input type="text" class="form-control @error('PIC') is-invalid @enderror" name="PIC" id="PIC" placeholder="PIC" value = "{{ old('PIC', $return_item->PIC)}}">
                    @error("PIC")
                    <p class="text-danger">Harap masukkan nama PIC.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="driver">Supir</label>
                    <input type="text" class="form-control @error('driver') is-invalid @enderror" name="driver" id="driver" placeholder="Nama supir" value = "{{ old("driver", $return_item->driver)}}">
                    @error("driver")
                    <p class="text-danger">Harap masukkan nama driver.</p>
                    @enderror
                </div>

                {{-- <div class="mt-3">
                    <label for="image">Foto Barang</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image">
                    @error('image')
                        <p class="text-danger">Harap upload foto untuk barang yang ingin dikembalikan.</p>
                    @enderror
                </div>

                <img id="img-preview" class="w-25 mt-2" @if($return_item->foto) src="{{ Storage::url("app/public/" . $return_item->foto) }}" @endif> --}}

                <div class="mt-4">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Simpan Perubahan">
                </div>
            </form>
        </div>
    </x-container-middle>

    <script>
        // $(document).ready(function(){
        //     $("#image").on("change", function(){
        //         const oFReader = new FileReader();
        //         oFReader.readAsDataURL(image.files[0]);

        //         oFReader.onload = function(oFEvent){
        //             $("#img-preview").attr("src", oFEvent.target.result);
        //         }
        //     });
        // });
    </script>
@endsection
