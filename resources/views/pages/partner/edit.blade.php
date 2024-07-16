@extends('layouts.main-admin')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="min-height:100vh">
        <div class="container">

            <h2>Edit Item</h2>

            {{-- @csrf kepake untuk token ,wajib --}}

            <form method="POST" action="{{ route('partner-update', $partner->id) }}">
                {{-- @csrf kepake untuk token ,wajib --}}
                @csrf
                <div class="mt-3">
                    <input type="text" class="form-control" name="partner_name" placeholder="Nama Partner"
                        value = "{{ old('partner_name', $partner->partner_name) }}">
                    @error('partner_name')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <input type="text" class="form-control" name="role" placeholder="Role"
                        value = "{{ old('role', $partner->role) }}">
                    @error('role')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <input type="text" class="form-control" name="remark" placeholder="Remark"
                        value = "{{ old('remark', $partner->remark) }}">
                    @error('remark')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <input type="text" class="form-control" name="address" placeholder="Alamat"
                        value = "{{ old('address', $partner->address) }}">
                    @error('address')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <input type="text" class="form-control" name="contact" placeholder="Kontak"
                        value = "{{ old('contact', $partner->contact) }}">
                    @error('contact')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <input type="text" class="form-control" name="phone" placeholder="No Telp"
                        value = "{{ old('phone', $partner->phone) }}">
                    @error('phone')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <input type="text" class="form-control" name="fax" placeholder="Fax"
                        value = "{{ old('fax', $partner->fax) }}">
                    @error('fax')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <input type="email" class="form-control" name="email" placeholder="Email"
                        value = "{{ old('email', $partner->email) }}">
                    @error('email')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
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

            <script></script>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
@endsection
