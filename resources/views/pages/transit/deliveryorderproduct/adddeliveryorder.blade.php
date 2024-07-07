
@extends('layouts.main-admin')

@section("content")

    <div class="d-flex justify-content-center align-items-center" style="min-height:100vh">
    <div class="container">

        <h2>Add Product To Delivery Order</h2>
            <div>
                <div class="mt-3">
                    <select name="product_name" class="form-select" id="select-product-dropdown">
                        @foreach ($products as $product)
                            <option value="{{ $product->toJson() }}" @if ($product->product_name == old("product_name")) selected @endif>{{ $product->product_name }} ({{ $product->variant }}) (Stok :  {{ $product->stock }})</option>
                        @endforeach
                    </select>
                    <p style = "color: red; font-size: 10px;"></p>
                </div>

                <div class="mt-3">
                    <input type="number" class="form-control" name="quantity" id="quantity"  placeholder="Quantity" value = "{{ old("quantity")}}">
                    <p style = "color: red; font-size: 10px;" id="errQuantity"></p>
                </div>

                    <div class="mt-3">
                        <input type="button" id="addbutton" class="btn btn-primary px-3 py-1" value="Add Items">
                    </div>
            </div>

            <table class="w-100 mt-4">
                <thead>
                    <th>Nama Barang & Variant</th>
                    <th>Quantity</th>
                    <th>Action</th>
                </thead>
                <tbody id="isibody">

                </tbody>
            </table>

            <form method="POST" action="{{ route("deliveryorderproduct-store1", $deliveryorder->id ) }}" class="mt-5" id="peon">
            {{-- @csrf kepake untuk token ,wajib --}}
                @csrf

                <div class="mt-3">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Proceed">
                </div>
                @error("prices")
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <br>
                @error("quantities")
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </form>
        </div>
    </div>

    <script>
        const confirmationForm = document.getElementById("peon");
        const addbutton = document.getElementById("addbutton");

        addbutton.addEventListener("click", function(){
            const tbody = document.getElementById("isibody");
            const input1 = document.getElementById("select-product-dropdown");

            const input4 = document.getElementById("quantity");


            const errQuantity = document.getElementById("errQuantity");


            errQuantity.innerText = "";

            input4.style.border = "none";

            let inputAman = true;




            if(!input4.value && input4.value < 1){
                input4.style.border = "solid 1px red";
                errQuantity.innerText = "Invalid input :3";

                inputAman = false;
            }

            if(!inputAman){
                return;
            }

            const newRow = document.createElement("tr");
            const column1 = document.createElement("td");

            const column4 = document.createElement("td");
            const column5 = document.createElement("td");

            const converted = JSON.parse(input1.value);
            column1.innerText = `${converted.product_name} (${converted.variant})`;

            column4.innerText = input4.value;

            const deleteButton = document.createElement("button");
            deleteButton.classList.add("btn", "btn-danger");
            deleteButton.setAttribute("type", "button");
            deleteButton.innerText = "Remove";
            column5.appendChild(deleteButton);

            newRow.appendChild(column1);

            newRow.appendChild(column4);
            newRow.appendChild(column5);
            tbody.appendChild(newRow);

            const susInput = document.createElement("input");
            susInput.setAttribute("type", "hidden");
            susInput.setAttribute("name", "products[]");
            susInput.setAttribute("value", converted.id);



            const susInput3 = document.createElement("input");
            susInput3.setAttribute("type", "hidden");
            susInput3.setAttribute("name", "quantities[]");
            susInput3.setAttribute("value", input4.value);



            confirmationForm.appendChild(susInput);

            confirmationForm.appendChild(susInput3);


            deleteButton.addEventListener("click", function(){
                tbody.removeChild(newRow);
                confirmationForm.removeChild(susInput);

                confirmationForm.removeChild(susInput3);

            });
        });
    </script>



@endsection
