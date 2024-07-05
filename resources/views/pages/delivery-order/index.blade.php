@extends('layouts.main-admin')

@section("content")

<br>
<h1>Delivery Orders</h1>
<hr>
<br>

{{-- <h5>welcome back, {{ Auth::user()->name }}! </h5> --}}

    @if (session()->has("successAddProduct"))
            <p class="text-success fw-bold">{{ session("successAddProduct") }}</p>

    @elseif (session()->has("successEditProduct"))

            <p class="text-success fw-bold">{{ session("successEditProduct") }}</p>

    @elseif (session()->has("successDeleteProduct"))
            <p class="text-success fw-bold">{{ session("successDeleteProduct") }}</p>

    @endif

<a href="{{ route("deliveryorder-create") }}" class="btn btn-primary text-white mb-3" style="font-size: 10pt">
    <i class="bi bi-plus-square"></i>
    Add New Delivery</a>
<br>
<!-- tabel list data-->

<table class="w-100">
    <tr>
        <th class="border border-1 border-dark ">Nomor</th>
        <th class="border border-1 border-dark ">Delivery Date</th>
        <th class="border border-1 border-dark ">Project</th>
        <th class="border border-1 border-dark ">Register</th>
        <th class="border border-1 border-dark ">Delivery Status</th>
        <th class="border border-1 border-dark ">Note</th>
        <th class="border border-1 border-dark ">Action</th>
    </tr>

    @foreach ($deliveryorders as $p)
        <tr>
            <td class="border border-1 border-dark " >{{ $loop->iteration }}</td>
            <td class="border border-1 border-dark " >{{ $p->delivery_date }}</td>
            <td class="border border-1 border-dark " >{{ $p->project->project_name }}</td>
            <td class="border border-1 border-dark " >{{ $p->register }}</td>
            <td class="border border-1 border-dark " >{{ $p->delivery_status }}</td>
            <td class="border border-1 border-dark " >{{ $p->note }}</td>
            {{-- <td class="border border-1 border-dark " >{{ $p->user->name }}</td> --}}
            <td class="border border-1 border-dark" >
                <div class="d-flex gap-5 w-100 justify-content-center">
                <a href="{{ route("deliveryorder-edit", $p->id ) }}" class="btn btn-warning text-white" style="font-size: 10pt">
                    <i class="bi bi-pencil"></i>
                    Edit Data</a>
                <form action="{{ route("deliveryorder-destroy", $p->id ) }}" method="POST">
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
