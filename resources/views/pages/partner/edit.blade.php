@extends('layouts.main-admin')

@section('content')
    <x-container-middle>
        <div class="container bg-white p-5 rounded-4 mt-4">

            <h2>Edit Item</h2>

            {{-- @csrf kepake untuk token ,wajib --}}

            <form method="POST" action="{{ route('partner-update', $partner->id) }}">
                {{-- @csrf kepake untuk token ,wajib --}}
                @csrf
                <div class="mt-3">
                    <label for="partner_name">Nama Partner</label>
                    <input type="text" class="form-control" name="partner_name" placeholder="Nama Partner"
                        value = "{{ old('partner_name', $partner->partner_name) }}">
                    @error('partner_name')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="role">Role</label>
                    <input type="text" class="form-control" name="role" placeholder="Role"
                        value = "{{ old('role', $partner->role) }}">
                    @error('role')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="remark">Remark</label>
                    <input type="text" class="form-control" name="remark" placeholder="Remark"
                        value = "{{ old('remark', $partner->remark) }}">
                    @error('remark')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="address">Alamat</label>
                    <input type="text" class="form-control" name="address" placeholder="Alamat"
                        value = "{{ old('address', $partner->address) }}">
                    @error('address')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="contact">Kontak</label>
                    <input type="text" class="form-control" name="contact" placeholder="Kontak"
                        value = "{{ old('contact', $partner->contact) }}">
                    @error('contact')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="phone">No Telp</label>
                    <input type="text" class="form-control" name="phone" placeholder="No Telp"
                        value = "{{ old('phone', $partner->phone) }}">
                    @error('phone')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="fax">Fax</label>
                    <input type="text" class="form-control" name="fax" placeholder="Fax"
                        value = "{{ old('fax', $partner->fax) }}">
                    @error('fax')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" placeholder="Email"
                        value = "{{ old('email', $partner->email) }}">
                    @error('email')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="tempo">Tempo</label>
                    <input type="text" class="form-control" name="tempo" placeholder="Tempo"
                        value = "{{ old('tempo', $partner->tempo) }}">
                    @error('tempo')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3 ">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Edit">
                </div>
            </form>
        </div>
    </x-container-middle>

@endsection
