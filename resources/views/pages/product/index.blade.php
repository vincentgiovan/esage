@extends('layouts.main-admin')

@section("content")

<br>
<h1>Warehouse Items</h1>
<br>

{{-- <h5>welcome back, {{ Auth::user()->name }}! </h5> --}}

    @if (session()->has("successAddProduct"))
            <p class="text-success fw-bold">{{ session("successAddProduct") }}</p>

    @elseif (session()->has("successEditProduct"))

            <p class="text-success fw-bold">{{ session("successEditProduct") }}</p>

    @elseif (session()->has("successDeleteProduct"))
            <p class="text-success fw-bold">{{ session("successDeleteProduct") }}</p>

    @endif

<a href="{{ route("product-create") }}" class="btn btn-primary text-white mb-3" style="font-size: 10pt">
    <i class="bi bi-plus-square"></i>
    Add New Purchase</a>
<br>
<!-- tabel list data-->

<table class="w-100">
    <tr>
        <th class="border border-1 border-dark ">Nomor</th>
        <th class="border border-1 border-dark ">Nama Produk </th>
        <th class="border border-1 border-dark ">SKU Produk </th>
        <th class="border border-1 border-dark ">Stok</th>
        <th class="border border-1 border-dark ">Harga</th>
        <th class="border border-1 border-dark ">Unit</th>
        <th class="border border-1 border-dark ">Variant</th>
        <th class="border border-1 border-dark ">Markup</th>
        <th class="border border-1 border-dark ">Status</th>
        <th class="border border-1 border-dark ">Action</th>
    </tr>

    @foreach ($products as $p)
        <tr>
            <td class="border border-1 border-dark " >{{ $loop->iteration }}</td>
            <td class="border border-1 border-dark " >{{ $p->product_name }}</td>
            <td class="border border-1 border-dark " >{{ $p->product_code }}</td>
            <td class="border border-1 border-dark " >{{ $p->stock }}</td>
            <td class="border border-1 border-dark " >Rp {{ $p->price }},00</td>
            <td class="border border-1 border-dark " >{{ $p->unit }}</td>
            <td class="border border-1 border-dark " >{{ $p->variant }}</td>
            <td class="border border-1 border-dark " >{{ $p->markup }}</td>
            <td class="border border-1 border-dark " >{{ $p->status }}</td>
            {{-- <td class="border border-1 border-dark " >{{ $p->user->name }}</td> --}}
            <td class="border border-1 border-dark " >
                <div class="d-flex gap-5 w-100 justify-content-center">
                <a href="{{ route("product-edit", $p->id ) }}" class="btn btn-warning text-white" style="font-size: 10pt">
                    <i class="bi bi-pencil"></i>
                    Edit Data</a>
                <form action="{{ route("product-destroy", $p->id ) }}" method="POST">
                    @csrf
                    <button class="btn btn-danger text-white" style="font-size: 10pt " onclick="return confirm('Do you want to delete this item?')">
                        <i class="bi bi-trash"></i>
                        Delete</button></form>
                </div>
            </td>

        </tr>

    @endforeach
</table>

@endsection
