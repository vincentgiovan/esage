@extends('layouts.main-admin')



@section("content")

    <div class="d-flex justify-content-center align-items-center" style="min-height:100vh">
    <div class="container border border-1 border-secondary rounded rounded-full p-5">
        <h2 class="text-center fw-bold">Create New Order</h2>
        <form method="POST" action="{{ route("deliveryorder-store"{{-- ,$delivery_order->id--}} ) }}" id="bikindevor">
                        @csrf
            {{-- <div class="mt-3">
                <select name="product_id" class="form-select">
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" >{{ $product->product_name }}</option>
                    @endforeach

                </select>
                @error("product_id")
                <p style = "color: red; font-size: 10px;">{{$message }}</p>
                @enderror
            </div> --}}
            <div class="mt-3">
                <input type="date" class="form-control" name="delivery_date" placeholder="delivery_date"  value = "{{ old("delivery_date") }}">

                @error("delivery_date")
                <p style = "color: red; font-size: 10px;">{{$message }}</p>
                @enderror
            </div>
            <div class="mt-3">
                <select name="project_id" class="form-select">
                    @foreach ($projects as $pn)
                        <option value="{{ $pn->id}}">{{ $pn->project_name }}</option>
                    @endforeach

                </select>
                @error("project_id")
                <p style = "color: red; font-size: 10px;">{{$message }}</p>
                @enderror
            </div>
            <div class="mt-3">
                @php
                        $today_date = date("dmY");
                        $n = App\Models\DeliveryOrder::where("delivery_date", date("Y-m-d"))->get()->count();
                        $generatedSKU = "DO/" . $today_date . "/" . ($n + 1);
                    @endphp
                <input type="text" class="form-control" name="fakeregister" placeholder="Register"  value = "{{ $generatedSKU }}" disabled>
            </div>
            <div class="mt-3">
                {{-- <input type="text" class="form-control" name="status" placeholder="Status"  value = "{{ old("status") }}"> --}}
                <select name="delivery_status" class="form-select">
                    <option value="Complete">Complete</option>
                    <option value="Incomplete">Incomplete</option>
                </select>
                @error("delivery_status")
                <p style = "color: red; font-size: 10px;">{{$message }}</p>
                @enderror
            </div>
            <div class="mt-3">
                <input type="text" class="form-control" name="note" placeholder="Note" value = "{{ old("note")}}">
                @error("note")
                <p style = "color: red; font-size: 10px;">{{$message }}</p>
                @enderror
            </div>
            <div class="mt-3">
                <input type="submit" class="btn btn-success px-3 py-1" value="add">
            </div>
        </form>
    </div>


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
    </div>

    <script>
        const purchaseForm = document.getElementById("bikindevor");
        purchaseForm.addEventListener("submit", function(event){
            event.preventDefault();
            const hiddenInput = document.createElement("input");
            hiddenInput.setAttribute("type", "hidden");
            hiddenInput.setAttribute("name", "register");

            const fakeRegister = document.querySelector('input[name="fakeregister"]');

            hiddenInput.setAttribute("value", fakeRegister.value);

            purchaseForm.appendChild(hiddenInput);

            purchaseForm.submit();
        })
    </script>
@endsection
