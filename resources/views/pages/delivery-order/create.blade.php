@extends('layouts.main-admin')

@section("content")

    <div class="d-flex justify-content-center align-items-center" style="min-height:100vh">
    <div class="container border border-1 border-secondary rounded rounded-full p-5">
        <h2 class="text-center fw-bold">ニュー プロダクト</h2>
        <form method="POST" action="{{ route("product-store") }}">
{{-- @csrf kepake untuk token ,wajib --}}
            @csrf
            <div class="mt-3">
                <input type="text" class="form-control" name="product_name" placeholder="Nama Barang" value = "{{ old("product_name" ) }}">
                @error("product_name")
                <p style = "color: red; font-size: 10px;">{{$message }}</p>
                @enderror
            </div>
            <div class="mt-3">
                <input type="text" class="form-control" name="unit" placeholder="Unit"  value = "{{ old("unit") }}">
                @error("unit")
                <p style = "color: red; font-size: 10px;">{{$message }}</p>
                @enderror
            </div>
            {{-- membuat drop down untuk tabel --}}
            <div class="mt-3">
                {{-- <input type="text" class="form-control" name="status" placeholder="Status"  value = "{{ old("status") }}"> --}}
                <select name="status" class="form-select">
                    <option value="Ready">Ready</option>
                    <option value="Out Of Stock">Out Of Stock</option>
                </select>
                @error("status")
                <p style = "color: red; font-size: 10px;">{{$message }}</p>
                @enderror
            </div>
            <div class="mt-3">
                <input type="text" class="form-control" name="variant" placeholder="Variant"  value = "{{ old("variant") }}">
                @error("variant")
                <p style = "color: red; font-size: 10px;">{{$message }}</p>
                @enderror
            </div>
            <div class="mt-3">
                <input type="text" class="form-control" name="product_code" placeholder="Kode Produk"  value = "{{ old("product_code") }}">
                @error("product_code")
                <p style = "color: red; font-size: 10px;">{{$message }}</p>
                @enderror
            </div>
            <div class="mt-3">
                <input type="number" class="form-control" name="price" placeholder="Harga" value = "{{ old("price") }}">
                @error("price")
                <p style = "color: red; font-size: 10px;">{{$message }}</p>
                @enderror
            </div>
            <div class="mt-3">
                <input type="number" class="form-control" name="discount" placeholder="Diskon"  value = "{{ old("discount") }}">
                @error("discount")
                <p style = "color: red; font-size: 10px;">{{$message  }}</p>
                @enderror
            </div>
            <div class="mt-3">
                <input type="number" class="form-control" name="stock" placeholder="Stok"  value = "{{ old("stock") }}">
                @error("stock")
                <p style = "color: red; font-size: 10px;">{{$message  }}</p>
                @enderror
            </div>

            <div class="mt-3">
            @if (session()->has("passwordNotConfirmed"))
            <p class="text-success fw-bold">{{ session("passwordNotConfirmed") }}</p>

            @endif
            <input type="submit" class="btn btn-success px-3 py-1" id="susbtn">
            </div>
        </form>

        <!-- Bikin change button color on hover pake js -->
        {{-- <script>
            const susbtn = document.querySelector("#susbtn");
            susbtn.addEventListener("mouseover", () => {
                susbtn.classList.remove("btn-success");
                susbtn.classList.add("btn-secondary");
            });
            susbtn.addEventListener("mouseout", () => {
                susbtn.classList.remove("btn-secondary");
                susbtn.classList.add("btn-success");
            });
        </script> --}}
        <!-- FITUR KEREN bad ui design-->
        <script>
            const susbtn = document.querySelector("#susbtn");
            susbtn.addEventListener("mouseover", () => {
                if(susbtn.style.marginLeft == "200px" || susbtn.style.marginLeft == "600px" || susbtn.style.marginLeft == "1000px"){
                    let x = parseInt(Math.random() * 2);
                    if(x == 0){
                        susbtn.style.marginLeft = "0px";
                    } else if(x == 1) {
                        susbtn.style.marginLeft = "400px";
                    } else {
                        susbtn.style.marginLeft = "800px";
                    }

                } else {
                    let x = parseInt(Math.random() * 3);
                    if(x == 0){
                        susbtn.style.marginLeft = "200px";
                    } else if(x == 1) {
                        susbtn.style.marginLeft = "600px";
                    } else {
                        susbtn.style.marginLeft = "1000px";
                    }

                }

            });
        </script>

    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


@endsection
