@extends('layouts.main-admin')

@section('bcd')
    <span>> <a href="#">Create</a></span>
@endsection

@section('content')
    <x-container-middle>
        <div class="container bg-white rounded-4 p-5 mt-4 border border-1 card">
            <h2>Buat Akun Baru</h2>
            <form action="{{ route('account.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="name">Nama</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old("name") }}">
                    @error("name")
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old("email") }}">
                    @error("email")
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="role">Pilih Role</label>
                    <select class="form-control text-black" id="role" name="role">
                        <option value="1">Admin</option>
                        <option value="2" selected>User</option>
                    </select>
                    @error("role")
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group mb-3 row">
                    <label for="password">Password</label>

                    <div class="input-group w-100">
                        <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" aria-describedby="togglePassword">
                        <button class="btn border border-2" type="button" id="togglePassword">
                            <i class="bi bi-eye-fill" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="invalid-feedback d-block" role="alert">
                            {{ $message }}
                        </span>
                    @enderror

                </div>

                <div class="form-group mb-4 row">
                    <label for="password_confirmation">Konfirmasi Password</label>

                    <div class="input-group w-100">
                        <input id="password_confirmation" type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" aria-describedby="togglePassword">
                        <button class="btn border border-2" type="button" id="togglePassword2">
                            <i class="bi bi-eye-fill" id="toggleIcon2"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <span class="invalid-feedback d-block" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="employee">Hubungkan dengan Pegawai (jika akun dibuat untuk pegawai, bisa diatur nanti)</label>
                    <select class="form-control select2 text-black" id="employee" name="employee">
                        <option selected disabled>Pilih Pegawai</option>
                        @foreach($employees as $e)
                            <option value="{{ $e->id }}">{{ $e->nama }}</option>
                        @endforeach
                    </select>
                    @error("employee")
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary mt-3">Buat Akun</button>
            </form>
        </div>
    </x-container-middle>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function (e) {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('toggleIcon');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            passwordIcon.classList.toggle('bi-eye-fill');
            passwordIcon.classList.toggle('bi-eye-slash-fill');
        });

        document.getElementById('togglePassword2').addEventListener('click', function (e) {
            const passwordInput = document.getElementById('password_confirmation');
            const passwordIcon = document.getElementById('toggleIcon2');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            passwordIcon.classList.toggle('bi-eye-fill');
            passwordIcon.classList.toggle('bi-eye-slash-fill');
        });
    </script>
@endsection
