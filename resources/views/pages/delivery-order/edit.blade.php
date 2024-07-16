@extends('layouts.main-admin')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="min-height:100vh">
        <div class="container">

            <h2>Edit Item</h2>

            {{-- @csrf kepake untuk token ,wajib --}}

            <form method="POST" action="{{ route('deliveryorder-update', $delivery_order->id) }}">
                @csrf
                {{-- <div class="mt-3">
                <select name="product_id" class="form-select">
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" @if ($delivery_order->product_id == $product->id) selected @endif >{{ $product->product_name }}</option>
                @endforeach

                </select>
                @error('product_id')
                <p style = "color: red; font-size: 10px;">{{$message }}</p>
                @enderror
                </div> --}}
                <div class="mt-3">
                    <input type="date" class="form-control" name="delivery_date" placeholder="delivery_date"
                        value = "{{ old('delivery_date', $delivery_order->delivery_date) }}">

                    @error('delivery_date')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <select name="project_id" class="form-select">
                        @foreach ($projects as $pn)
                            <option value="{{ $pn->id }}" @if ($delivery_order->project_id == $pn->id) selected @endif>
                                {{ $pn->project_name }}</option>
                        @endforeach
                    </select>
                    @error('project_id')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <input type="text" class="form-control" name="register" placeholder="Register"
                        value = "{{ old('register', $delivery_order->register) }}">
                    @error('register')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    {{-- <input type="text" class="form-control" name="status" placeholder="Status"  value = "{{ old("status") }}"> --}}
                    <select name="delivery_status" class="form-select">
                        <option value="Complete">Complete</option>
                        <option value="Incomplete">Incomplete</option>
                    </select>
                    @error('delivery_status')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <input type="text" class="form-control" name="note" placeholder="Note"
                        value = "{{ old('note', $delivery_order->note) }}">
                    @error('note')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Edit">
                </div>
            </form>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
@endsection
