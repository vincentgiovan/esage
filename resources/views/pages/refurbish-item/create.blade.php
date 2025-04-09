@extends('layouts.main-admin')

@section('content')
    <x-container-middle>
        <div class="container bg-white rounded-4 p-5 mt-4 border border-1 card">
            <h3>Rekondisi Barang</h3>

            <form method="POST" action="{{ route('refurbishitem-store') }}">
                @csrf

                <div class="mt-3">
                    <label for="product_id">Nama Barang<span class="text-danger">*</span></label>
                    <select name="product_id" class="form-select select2" >
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @if ($product->product_name == old("product_id")) selected @endif>{{ $product->product_name }} - {{ $product->variant }} (Harga: {{ number_format($product->price, 0, ',', '.') }}, Stok:  {{ $product->stock }}, Diskon: {{ $product->discount }}%) @if($product->is_returned == 'yes'){{__('- Returned')}}@endif</option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <p class="text-danger">Harap masukkan produk.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="refurbish_date">Tanggal Rekondisi<span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('refurbish_date') is-invalid @enderror" name="refurbish_date" id="refurbish_date"  value="{{ old('refurbish_date') }}">
                    @error('refurbish_date')
                        <p class="text-danger">Harap masukkan tanggal rekondisi.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="qty">Jumlah<span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('qty') is-invalid @enderror" name="qty" id="qty" placeholder="Jumlah"
                        value="0">
                    @error('qty')
                        <p class="text-danger">Harap masukkan nilai minimal 1.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="notes">Catatan</label>
                    <input type="text" class="form-control" name="notes" id="notes" placeholder="Tambahkan catatan" value="{{ old('notes') }}">
                </div>

                <div class="mt-4">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Simpan Data Baru">
                </div>
            </form>

        </div>
    </x-container-middle>
@endsection
