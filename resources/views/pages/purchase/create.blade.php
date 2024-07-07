@extends('layouts.main-admin')

@section("content")

    <div class="d-flex justify-content-center align-items-center" style="min-height:100vh">
    <div class="container">

        <h2>Add Item</h2>

{{-- @csrf kepake untuk token ,wajib --}}

            <form method="POST" action="{{ route("purchase-store" ) }}" id="bikinpurchase">
            {{-- @csrf kepake untuk token ,wajib --}}
                @csrf
                {{-- <div class="mt-3">
                    <select name="product_name" class="form-select">
                        @foreach ($product_name as $s)
                            <option value="{{ $s }}" @if ($product->product_name == $s ) selected @endif>{{ $s }}</option>
                        @endforeach

                    </select>
                    @error("product_name")
                    <p style = "color: red; font-size: 10px;">{{$message }}</p>
                    @enderror
                </div> --}}
                <div class="mt-3">
                    <select name="partner_id" class="form-select">
                        @foreach ($supplier as $s)
                            <option value="{{ $s->id }}" @if ($supplier == old('partner_id') ) selected @endif>{{ $s->partner_name }}</option>
                        @endforeach

                    </select>
                    @error("supplier_id")
                    <p style = "color: red; font-size: 10px;">{{$message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <input type="text" class="form-control" name="purchase_deadline" id="purchase_deadline" onfocus="(this.type='date')"
                    onblur="(this.type='text')" placeholder="Purchase_deadine"  value = "{{ old("purchase_deadline") }}">
                    @error("purchase_deadline")
                    <p style = "color: red; font-size: 10px;">{{$message }}</p>
                    @enderror
                </div>
                <div class="mt-3">

                    <input type="text" class="form-control" name="fakeregister" placeholder="Register" disabled>
                </div>
                <div class="mt-3">
                    <input type="text" class="form-control" name="purchase_date" id="purchase_date" onfocus="(this.type='date')"
                    onblur="(this.type='text')" placeholder="Purchase Date" value = "{{ old("purchase_date")}}">
                    @error("purchase_date")
                    <p style = "color: red; font-size: 10px;">{{$message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <select name="purchase_status" class="form-select">
                        @foreach ($status as $st)
                            <option value="{{ $st }}" @if ($st == old('purchase_status') ) selected @endif>{{ $st }}</option>
                        @endforeach

                    </select>
                    @error("purchase_status")
                    <p style = "color: red; font-size: 10px;">{{$message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Add">
                </div>
            </form>
        </div>
    </div>

    <!-- Biar data register kekirim ke Laravel even its disabled, soalnya Laravel ga bakal nganggap input yang disabled -->
    <script>
        const purchaseForm = document.getElementById("bikinpurchase");
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


@endsection
