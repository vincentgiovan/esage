@extends('layouts.main-admin')

@section('content')
    <x-container-middle>
        <div class="container bg-white rounded-4 p-5 mt-4 border border-1 card">
            <h2>Edit Data Akun</h2>
            <form action="{{ route('account.update', $user->id) }}" method="POST">
                @method('put')
                @csrf
                <div class="form-group mb-3">
                    <label for="name">Nama</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old("name", $user->name) }}">
                    @error("name")
                        <p class="text-danger" role="alert">
                            Harap masukkan nama.
                        </p>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old("email", $user->email) }}">
                    @error("email")
                        <p class="text-danger" role="alert">
                            Harap masukkan email dengan format yang benar.
                        </p>
                    @enderror
                </div>
                <div class="form-group mb-3">
                    <label for="role">Pilih Role</label>
                    <select class="form-control text-black" id="role" name="role">
                        <option value="admin" @if(old('role', $user->role) == '1') selected @endif>Admin</option>
                        <option value="user" @if(old('role', $user->role) == '2') selected @endif>User</option>
                    </select>
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
                        <p class="text-danger d-block" role="alert">
                            Harap masukkan password (minimal 8 karakter) dan pastikan konfirmasi password sama.
                        </p>
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
                        <p class="text-danger d-block" role="alert">
                            Harap masukkan password (minimal 8 karakter) dan pastikan konfirmasi password sama.
                        </p>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="employee">Hubungkan dengan Pegawai (jika akun dibuat untuk pegawai, bisa diatur nanti)</label>
                    <select class="form-control select2 text-black" id="employee" name="employee">
                        <option selected disabled>Pilih Pegawai</option>
                        @foreach($employees as $e)
                            <option value="{{ $e->id }}" @if(old('employee') == $e->id || ($user->employee_data && $user->employee_data->id == $e->id)) selected @endif>{{ $e->nama }}</option>
                        @endforeach
                    </select>
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
