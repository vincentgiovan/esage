@extends('layouts.main-admin')

@section('content')
    <x-container-middle>
        <div class="container bg-white rounded-4 p-5 mt-4 border border-1 card">
            <h3>Rekondisi Barang</h3>

            <form method="POST" action="{{ route('refurbishitem-update', $refurbish_item->id) }}">
                @csrf

                <div class="mt-3">
                    <label for="product_id">Nama Barang</label>
                    <select name="product_id" class="form-select select2" disabled>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @if ($product->product_name == old("product_id")) selected @endif>{{ $product->product_name }} - {{ $product->variant }} (Harga: Rp {{ number_format($product->price, 2, ',', '.') }}, Stok:  {{ $product->stock }}, Diskon: {{ $product->discount }}%) @if($product->is_returned == 'yes'){{__('- Returned')}}@endif</option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <p class="text-danger">Harap masukkan produk.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="refurbish_date">Tanggal Rekondisi</label>
                    <input type="date" class="form-control @error('refurbish_date') is-invalid @enderror" name="refurbish_date" id="refurbish_date"  value="{{ old('refurbish_date', $refurbish_item->refurbish_date) }}">
                    @error('refurbish_date')
                        <p class="text-danger">Harap masukkan tanggal rekondisi.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="qty">Jumlah</label>
                    <input type="text" class="form-control" name="qty" id="qty" placeholder="Jumlah"
                        value = "{{ old('qty', $refurbish_item->qty) }}">
                    @error('qty')
                        <p class="text-danger">Harap masukkan nilai minimal 0.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="notes">Catatan</label>
                    <input type="text" class="form-control" name="notes" id="notes" placeholder="Tambahkan catatan" value="{{ old('notes', $refurbish_item->notes) }}">
                </div>

                <div class="mt-4">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Simpan Data Baru">
                </div>
            </form>

        </div>
    </x-container-middle>
@endsection
