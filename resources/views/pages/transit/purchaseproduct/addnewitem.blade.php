
@extends('layouts.main-admin')

@section("content")

    <div class="d-flex justify-content-center align-items-center" style="min-height:100vh">
    <div class="container">

        <h2>Add New Purchase</h2>
            <div>
                <div class="mt-3">
                    <input type="text" class="form-control" name="product_name" id="product_name" placeholder="Nama Barang" value = "{{ old("product_name" ) }}">
                    <p style = "color: red; font-size: 10px;" id="errProductName"></p>
                </div>
                <div class="mt-3">
                    <input type="text" class="form-control" name="unit" id="unit" placeholder="Unit"  value = "{{ old("unit") }}">
                    <p style = "color: red; font-size: 10px;" id="errUnit"></p>
                </div>
                <div class="mt-3">
                    <select name="status" class="form-select" id="status">
                        <option value="Ready">Ready</option>
                        <option value="Out Of Stock">Out Of Stock</option>
                    </select>
                    <p style = "color: red; font-size: 10px;" id="errStatus"></p>
                </div>
                <div class="mt-3">
                    <input type="text" class="form-control" name="variant"  id="variant" placeholder="Variant"  value = "{{ old("variant") }}">
                    <p style = "color: red; font-size: 10px;" id="errVariant"></p>
                </div>
                <div class="mt-3">
                    <input type="text" class="form-control" name="product_code" id="product_code" placeholder="Kode Produk"  value = "{{ old("product_code") }}">
                    <p style = "color: red; font-size: 10px;" id="errProductCode"></p>
                </div>
                <div class="mt-3">
                    <input type="number" class="form-control" name="price" id="price" placeholder="Harga" value = "{{ old("price") }}">
                    <p style = "color: red; font-size: 10px;" id="errPrice"></p>
                </div>
                <div class="mt-3">
                    <input type="number" class="form-control" name="markup" id="markup" placeholder="Markup"  value = "{{ old("markup") }}">
                    <p style = "color: red; font-size: 10px;" id="errMarkup"></p>
                </div>
                <div class="mt-3">
                    <input type="number" class="form-control" name="stock"  id="stock" placeholder="Stok"  value = "{{ old("stock") }}">
                    <p style = "color: red; font-size: 10px;" id="errS.  tock"></p>
                </div>

                <div class="mt-3">
                    <input type="button" id="addbutton" class="btn btn-primary px-3 py-1" value="Add Items">
                </div>
            </div>

            <table class="w-100 mt-4">
                <thead>
                    <th>Nama Barang</th>
                    <th>Unit</th>
                    <th>Status</th>
                    <th>Variant</th>
                    <th>Kode Produk</th>
                    <th>Harga</th>
                    <th>Mark Up</th>
                    <th>Stok</th>

                </thead>
                <tbody id="isibody">

                </tbody>
            </table>

            <form method="POST" action="{{ route("purchaseproduct-store2", $purchase->id ) }}" class="mt-5" id="peon">
            {{-- @csrf kepake untuk token ,wajib --}}
                @csrf

                <div class="mt-3">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Proceed">
                </div>
                @error("product_name")
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <br>
                @error("unit")
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <br>
                @error("status")
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <br>
                @error("variant")
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <br>
                @error("product_code")
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <br>
                @error("price")
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <br>
                @error("markup")
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <br>
                @error("stock")
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
            const input1 = document.getElementById("product_name");
            const input2 = document.getElementById("unit");
            const input3 = document.getElementById("status");
            const input4 = document.getElementById("variant");
            const input5 = document.getElementById("product_code");
            const input6 = document.getElementById("price");
            const input7 = document.getElementById("markup");
            const input8 = document.getElementById("stock");

            const errProductName = document.getElementById("errProductName");
            const errUnit = document.getElementById("errUnit");
            const errStatus = document.getElementById("errStatus");
            const errVariant = document.getElementById("errVariant");
            const errProductCode = document.getElementById("errProductCode");
            const errPrice = document.getElementById("errPrice");
            const errMarkup = document.getElementById("errMarkup");
            const errStock = document.getElementById("errStock");

            errProductName.innerText = "";
            errUnit.innerText = "";
            errStatus.innerText = "";
            errVariant.innerText = "";
            errProductCode.innerText = "";
            errPrice.innerText = "";
            errMarkup.innerText = "";
            errStock.innerText = "";
            input1.style.border = "none";
            input2.style.border = "none";
            input3.style.border = "none";
            input4.style.border = "none";
            input5.style.border = "none";
            input6.style.border = "none";
            input7.style.border = "none";
            input8.style.border = "none";

            let inputAman = true;

            if(!input1.value){
                input1.style.border = "solid 1px red";
                errProductName.innerText = "Invalid input";

                inputAman = false;
            }

            if(!input2.value){
                input2.style.border = "solid 1px red";
                errUnit.innerText = "Invalid input";

                inputAman = false;
            }

            if(!input4.value){
                input4.style.border = "solid 1px red";
                errVariant.innerText = "Invalid input";

                inputAman = false;
            }

            if(!input5.value){
                input5.style.border = "solid 1px red";
                errProductCode.innerText = "Invalid input";

                inputAman = false;
            }

            if(!input6.value && input6.value < 1){
                input6.style.border = "solid 1px red";
                errPrice.innerText = "Invalid input";

                inputAman = false;
            }

            if(input7.value && input7.value < 1){
                input7.style.border = "solid 1px red";
                errMarkup.innerText = "Invalid input";

                inputAman = false;
            }

            if(!input8.value && input8.value < 1){
                input8.style.border = "solid 1px red";
                errStock.innerText = "Invalid input";

                inputAman = false;
            }


            if(!inputAman){
                return;
            }

            const newRow = document.createElement("tr");
            const column1 = document.createElement("td");
            const column2 = document.createElement("td");
            const column3 = document.createElement("td");
            const column4 = document.createElement("td");
            const column5 = document.createElement("td");
            const column6 = document.createElement("td");
            const column7 = document.createElement("td");
            const column8 = document.createElement("td");
            const column9 = document.createElement("td");

            column1.innerText = input1.value;
            column2.innerText = input2.value;
            column3.innerText = input3.value;
            column4.innerText = input4.value;
            column5.innerText = input5.value;
            column6.innerText = input6.value;
            column7.innerText = input7.value;
            column8.innerText = input8.value;

            const deleteButton = document.createElement("button");
            deleteButton.classList.add("btn", "btn-danger");
            deleteButton.setAttribute("type", "button");
            deleteButton.innerText = "Remove";
            column9.appendChild(deleteButton);

            newRow.appendChild(column1);
            newRow.appendChild(column2);
            newRow.appendChild(column3);
            newRow.appendChild(column4);
            newRow.appendChild(column5);
            newRow.appendChild(column6);
            newRow.appendChild(column7);
            newRow.appendChild(column8);
            newRow.appendChild(column9);
            tbody.appendChild(newRow);

            const susInput1 = document.createElement("input");
            susInput1.setAttribute("type", "hidden");
            susInput1.setAttribute("name", "product_name[]");
            susInput1.setAttribute("value", input1.value);

            const susInput2 = document.createElement("input");
            susInput2.setAttribute("type", "hidden");
            susInput2.setAttribute("name", "unit[]");
            susInput2.setAttribute("value", input2.value);

            const susInput3 = document.createElement("input");
            susInput3.setAttribute("type", "hidden");
            susInput3.setAttribute("name", "status[]");
            susInput3.setAttribute("value", input3.value);

            const susInput4 = document.createElement("input");
            susInput4.setAttribute("type", "hidden");
            susInput4.setAttribute("name", "variant[]");
            susInput4.setAttribute("value", input4.value);

            const susInput5 = document.createElement("input");
            susInput5.setAttribute("type", "hidden");
            susInput5.setAttribute("name", "product_code[]");
            susInput5.setAttribute("value", input5.value);

            const susInput6 = document.createElement("input");
            susInput6.setAttribute("type", "hidden");
            susInput6.setAttribute("name", "price[]");
            susInput6.setAttribute("value", input6.value);

            const susInput7 = document.createElement("input");
            susInput7.setAttribute("type", "hidden");
            susInput7.setAttribute("name", "markup[]");
            susInput7.setAttribute("value", input7.value);

            const susInput8 = document.createElement("input");
            susInput8.setAttribute("type", "hidden");
            susInput8.setAttribute("name", "stock[]");
            susInput8.setAttribute("value", input8.value);

            confirmationForm.appendChild(susInput1);
            confirmationForm.appendChild(susInput2);
            confirmationForm.appendChild(susInput3);
            confirmationForm.appendChild(susInput4);
            confirmationForm.appendChild(susInput5);
            confirmationForm.appendChild(susInput6);
            confirmationForm.appendChild(susInput7);
            confirmationForm.appendChild(susInput8);

            deleteButton.addEventListener("click", function(){
                tbody.removeChild(newRow);
                confirmationForm.removeChild(susInput1);
                confirmationForm.removeChild(susInput2);
                confirmationForm.removeChild(susInput3);
                confirmationForm.removeChild(susInput4);
                confirmationForm.removeChild(susInput5);
                confirmationForm.removeChild(susInput6);
                confirmationForm.removeChild(susInput7);
                confirmationForm.removeChild(susInput8);
            });
        });
    </script>



@endsection
