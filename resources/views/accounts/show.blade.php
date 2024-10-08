@extends('layouts.main-admin')

@section('content')
<div class="container">
    <h1>Account Management</h1>

    <!-- Add User Form -->
    <div class="card mb-4">
        <div class="card-header">
            Edit User
        </div>
        <div class="card-body">
            <form action="{{ route('account.update', $user->id) }}" method="POST">
                @method("put")
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
                    @error("name")
                        {{ $message }}
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}">
                    @error("email")
                        {{ $message }}
                    @enderror
                </div>
                <div class="form-group d-flex flex-column mt-3">
                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                    <div class="w-100">
                        <div class="input-group">
                            <input id="password" type="password" class="pe-5 form-control position-relative z-0 rounded @error('password') is-invalid @enderror" name="password"  autocomplete="current-password">
                            <div class="input-group-append position-absolute z-1 end-0">
                                <button type="button" class="btn" id="togglePassword">
                                    <i class="bi bi-eye-fill" id="toggleIcon"></i>
                                </button>
                            </div>
                        </div>

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group d-flex flex-column mt-3">
                    <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">{{ __('Password Confirmation') }}</label>

                    <div class="w-100">
                        <div class="input-group">
                            <input id="password_confirmation" type="password" class="pe-5 form-control position-relative z-0 rounded @error('password_confirmation') is-invalid @enderror" name="password_confirmation"  autocomplete="current-password_confirmation">
                            <div class="input-group-append position-absolute z-1 end-0">
                                <button type="button" class="btn" id="togglePassword2">
                                    <i class="bi bi-eye-fill" id="toggleIcon2"></i>
                                </button>
                            </div>
                        </div>

                        @error('password_confirmation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Edit User</button>
            </form>
        </div>
    </div>
</div>

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
