@extends('layouts.main-admin')

@section('content')
    <x-container-middle>
        <div class="container bg-white rounded-4 p-5 mt-4 border border-1 card">
            <h3>Tambah Partner Baru</h3>
            <form method="POST" action="{{ route('partner-store') }}">

                @csrf
                <div class="mt-3">
                    <label for="partner_name">Nama Partner</label>
                    <input type="text" class="form-control @error('partner_name') is-invalid @enderror" name="partner_name" id="partner_name" placeholder="Nama Partner"
                        value = "{{ old('partner_name') }}">
                    @error('partner_name')
                        <p class="text-danger">Harap masukkan nama partner.</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="role">Role Partner</label>
                    <input type="text" class="form-control @error('role') is-invalid @enderror" name="role" id="role" placeholder="Role"
                        value = "{{ old('role') }}">
                    @error('role')
                        <p class="text-danger">Harap masukkan role/tipe partner.</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="address">Alamat</label>
                    <input type="text" class="form-control" name="address" id="address" placeholder="Alamat"
                        value = "{{ old('address') }}">
                </div>
                <div class="mt-3">
                    <label for="contact">Kontak</label>
                    <input type="text" class="form-control @error('contact') is-invalid @enderror" name="contact" id="contact" placeholder="Kontak"
                        value = "{{ old('contact') }}">
                    @error('contact')
                        <p class="text-danger">Harap masukkan format nomor yang benar.</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="phone">Telepon</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" id="phone" placeholder="No Telp"
                        value = "{{ old('phone') }}">
                    @error('phone')
                        <p class="text-danger">Harap masukkan format nomor yang benar.</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="fax">Fax</label>
                    <input type="text" class="form-control @error('fax') is-invalid @enderror" name="fax" id="fax"  placeholder="Fax"
                        value = "{{ old('fax') }}">
                    @error('fax')
                        <p class="text-danger">Harap masukkan format nomor yang benar.</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email"
                        value = "{{ old('email') }}">
                    @error('email')
                        <p class="text-danger">Harap masukkan email dengan format yang benar.</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <label for="tempo">Tempo</label>
                    <input type="text" class="form-control" name="tempo" placeholder="Tempo"
                        value = "{{ old('tempo') }}">
                </div>
                <div class="mt-3">
                    <label for="remark">Remark</label>
                    <input type="text" class="form-control" name="remark" id="remark" placeholder="Remark"
                        value = "{{ old('remark') }}">
                </div>

                <div class="mt-4">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Simpan Data Baru">
                </div>
            </form>

        </div>
    </x-container-middle>
@endsection
