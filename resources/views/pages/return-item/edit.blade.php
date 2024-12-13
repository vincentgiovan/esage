@extends('layouts.main-admin')

@section("content")

    <x-container-middle>
        <div class="container rounded-4 p-5 bg-white border border-1 card">
            <h2 class="text-center fw-bold">New Return Item</h2>

            <form method="POST" action="{{ route('returnitem-update', $return_item->id) }}" enctype="multipart/form-data">
                @csrf

                <div class="mt-3">
                    <label for="devor">Delivery Order</label>
                    <select class="form-select" id="devor" disabled>
                        <option disabled selected>Pick an item</option>
                        @foreach ($delivery_orders as $do)
                            <option value='@json([App\Models\DeliveryOrderProduct::where("delivery_order_id", $do->id)->get(), $do->id])' @if($do->id == $return_item->delivery_order_product->delivery_order_id) selected @endif>{{ $do->register }} (Date: {{ $do->delivery_date }}, Project: {{ $do->project->project_name }})</option>
                        @endforeach
                    </select>

                    @error("project_id")
                    <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="product">Produk yang akan dikembalikan</label>
                    <select name="product" class="form-select" id="product" disabled>
                        @forelse ($return_item->delivery_order_product->delivery_order->products as $p)
                            <option value="{{ $p->id }}" @if($return_item->delivery_order_product->product->id == $p->id) selected @endif>{{ $p->product_name }} (Variant: {{ $p->variant  }}, Harga: Rp {{ number_format($p->price, 2, ",", ".") }}, Diskon: {{ $p->discount }}%)</option>
                        @empty
                        @endforelse
                    </select>
                    @error("product")
                    <p style = "color: red; font-size: 10px;">{{$message }}</p>
                    @enderror
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
        const all_prod = @json(App\Models\Product::all());

        $(document).ready(function(){
            $("#image").on("change", function(){
                const oFReader = new FileReader();
                oFReader.readAsDataURL(image.files[0]);

                oFReader.onload = function(oFEvent){
                    $("#img-preview").attr("src", oFEvent.target.result);
                }
            });

            // $("#devor").change(function(){
            //     if($(this).val()){
            //         product_ids = [];

            //         for(let p of JSON.parse($(this).val())[0]){
            //             product_ids.push(p.product_id);
            //         }

            //         const product_list = all_prod.filter(item => product_ids.includes(item.id));
            //         const prod_dd = $("#product");
            //         prod_dd.html("");

            //         for(let pl of product_list){
            //             prod_dd.append($("<option>").attr("value", pl.id).text(`${pl.product_name}`));
            //         }
            //     }
            // });

            // $("form").on("submit", function(e){
            //     e.preventDefault();

            //     if($("#devor").val()){
            //         $(this).append($("<input>").attr({"type": "hidden", "name": "devor_id", "value": JSON.parse($("#devor").val())[1]}));
            //     }

            //     this.submit();
            // });
        });
    </script>
@endsection
