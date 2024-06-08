@extends('layouts.main-admin')

@section("content")

<br>
<h1>Sage Partners </h1>
<br>


{{-- <h5>welcome back, {{ Auth::user()->name }}! </h5> --}}

    @if (session()->has("successAddPartner"))
            <p class="text-success fw-bold">{{ session("successAddPartner") }}</p>

    @elseif (session()->has("successEditPartner"))

            <p class="text-success fw-bold">{{ session("successEditPartner") }}</p>

    @elseif (session()->has("successDeletePartner"))
            <p class="text-success fw-bold">{{ session("successDeletePartner") }}</p>

    @endif

<a href="{{ route("partner-create") }}" class="btn btn-primary text-white mb-3" style="font-size: 10pt">
    <i class="bi bi-plus-square"></i>
    Add New Data</a>
<br>
<!-- tabel list data-->

<table class="w-100">
    <tr>
        <th class="border border-1 border-dark ">Nomor</th>
        <th class="border border-1 border-dark ">Nama Partner</th>
        <th class="border border-1 border-dark ">Partner Role</th>
        <th class="border border-1 border-dark ">Remark</th>
        <th class="border border-1 border-dark ">Address</th>
        <th class="border border-1 border-dark ">Contact</th>
        <th class="border border-1 border-dark ">Phone</th>
        <th class="border border-1 border-dark ">Fax</th>
        <th class="border border-1 border-dark ">Email</th>
        <th class="border border-1 border-dark ">Tempo</th>
        <th class="border border-1 border-dark " >Action</th>
    </tr>

    @foreach ($partners as $p)
        <tr>
            <td class="border border-1 border-dark " >{{ $loop->iteration }}</td>
            <td class="border border-1 border-dark " >{{ $p->partner_name }}</td>
            <td class="border border-1 border-dark " >{{ $p->role }}</td>
            <td class="border border-1 border-dark " >{{ $p->remark }}</td>
            <td class="border border-1 border-dark " >{{ $p->address }}</td>
            <td class="border border-1 border-dark " >{{ $p->contact }}</td>
            <td class="border border-1 border-dark " >{{ $p->phone }}</td>
            <td class="border border-1 border-dark " >{{ $p->fax }}</td>
            <td class="border border-1 border-dark " >{{ $p->email }}</td>
            <td class="border border-1 border-dark " >{{ $p->tempo }}</td>
            {{-- <td class="border border-1 border-dark " >{{ $p->user->name }}</td> --}}
            <td class="border border-1 border-dark " >
                <div class="d-flex gap-5 w-100 justify-content-center">
                <a href="{{ route("partner-edit", $p->id ) }}" class="btn btn-warning text-white" style="font-size: 10pt">
                    <i class="bi bi-pencil"></i>
                    Edit Data</a>
                <form action="{{ route("partner-destroy", $p->id ) }}" method="POST">
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
