@extends('layouts.main-admin')

@section("content")

    <div class="d-flex justify-content-center align-items-center" style="min-height:100vh">
    <div class="container">

        <h2>Edit Item</h2>

{{-- @csrf kepake untuk token ,wajib --}}

            <form method="POST" action="{{ route("delivery-order-edit",$deliver_order->id ) }}">
                {{-- @csrf kepake untuk token ,wajib --}}
                            @csrf
                            <div class="mt-3">
                                <select name="product_name" class="form-select">
                                    @foreach ($product_name as $pn)
                                        <option value="{{ $pn }}" @if ($product->product_name == $pn ) selected @endif>{{ $pn }}</option>
                                    @endforeach

                                </select>
                                @error("product_name")
                                <p style = "color: red; font-size: 10px;">{{$message }}</p>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <select name="delivery_date" class="form-select">
                                    @foreach ($delivery_date as $dd)
                                        <option value="{{ $dd }}" @if ($product->delivery_date == $dd ) selected @endif>{{ $dd }}</option>
                                    @endforeach

                                </select>
                                @error("delivery_date")
                                <p style = "color: red; font-size: 10px;">{{$message }}</p>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <select name="project_name" class="form-select">
                                    @foreach ($project_name as $pn)
                                        <option value="{{ $pn }}" @if ($product->project_name == $pn ) selected @endif>{{ $pn }}</option>
                                    @endforeach

                                </select>
                                @error("project_name")
                                <p style = "color: red; font-size: 10px;">{{$message }}</p>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <input type="text" class="form-control" name="register" placeholder="Register"  value = "{{ old("register", $product->register) }}">
                                @error("register")
                                <p style = "color: red; font-size: 10px;">{{$message }}</p>
                                @enderror
                            </div>
                            <div class="mt-3">
                                <input type="text" class="form-control" name="note" placeholder="Note" value = "{{ old("note" , $product->note)}}">
                                @error("note")
                                <p style = "color: red; font-size: 10px;">{{$message }}</p>
                                @enderror
                            </div>
                            <div class="mt-3">
                            <input type="submit" class="btn btn-success px-3 py-1" value="Edit">
                            </div>
                        </form>
    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


@endsection
