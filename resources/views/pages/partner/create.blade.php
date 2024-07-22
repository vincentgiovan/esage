@extends('layouts.main-admin')

@section('content')
    <x-container-middle>
        <div class="container bg-white rounded-4 p-5">
            <h2 class="text-center fw-bold">Insert Partner</h2>
            <form method="POST" action="{{ route('partner-store') }}">
                {{-- @csrf kepake untuk token ,wajib --}}
                @csrf
                <div class="mt-3">
                    <label for="partner_name">Nama Partner</label>
                    <input type="text" class="form-control" name="partner_name" id="partner_name" placeholder="Nama Partner"
                        value = "{{ old('partner_name') }}">
                    @error('partner_name')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="role">Tipe Partner</label>
                    <input type="text" class="form-control" name="role" id="role" placeholder="Role"
                        value = "{{ old('role') }}">
                    @error('role')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="remark">Remark</label>
                    <input type="text" class="form-control" name="remark" id="remark" placeholder="Remark"
                        value = "{{ old('remark') }}">
                    @error('remark')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="address">Alamat</label>
                    <input type="text" class="form-control" name="address" id="address" placeholder="Alamat"
                        value = "{{ old('address') }}">
                    @error('address')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="contact">Kontak</label>
                    <input type="text" class="form-control" name="contact" id="contact" placeholder="Kontak"
                        value = "{{ old('contact') }}">
                    @error('contact')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="phone">Telepon</label>
                    <input type="text" class="form-control" name="phone" id="phone" placeholder="No Telp"
                        value = "{{ old('phone') }}">
                    @error('phone')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="fax">Fax</label>
                    <input type="text" class="form-control" name="fax" id="fax"  placeholder="Fax"
                        value = "{{ old('fax') }}">
                    @error('fax')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" placeholder="Email"
                        value = "{{ old('email') }}">
                    @error('email')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="tempo">Tempo</label>
                    <input type="text" class="form-control" name="tempo" placeholder="Tempo"
                        value = "{{ old('tempo') }}">
                    @error('tempo')
                        <p style = "color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    @if (session()->has('passwordNotConfirmed'))
                        <p class="text-success fw-bold">{{ session('passwordNotConfirmed') }}</p>
                    @endif
                    <input type="submit" class="btn btn-success px-3 py-1" value="Add">
                </div>
            </form>

        </div>
    </x-container-middle>
@endsection
