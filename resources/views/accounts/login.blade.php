@extends('layouts.login-main-admin')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height:100vh;">
    <div class="row justify-content-center w-75">
        <div class="col-md-6">
            <div class="card shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-secondary text-white fs-5 ">{{ __('Admin Login') }}</div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('checkLogin') }}">
                        @csrf

                        <div class="form-group row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-right">
                                <i class="bi bi-envelope-fill"></i> {{ __('E-Mail Address') }}
                            </label>

                            <div class="col-md-8">
                                <input id="email" type="text" class="form-control border border-2 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autofocus>
                                @error('email')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <label for="password" class="col-md-4 col-form-label text-md-right">
                                <i class="bi bi-lock-fill"></i> {{ __('Password') }}
                            </label>

                            <div class="col-md-8">
                                <div class="input-group">
                                    <input id="password" type="password" name="password" class="form-control border border-2 @error('password') is-invalid @enderror" aria-describedby="togglePassword">
                                    {{-- <button class="btn border border-2" type="button" id="togglePassword">
                                        <i class="bi bi-eye-fill" id="toggleIcon"></i>
                                    </button> --}}
                                </div>
                                @error('password')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <br>
                        {{-- <div class="form-group row mb-4 ">
                            <div class="col-md-8 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input border border-2" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div> --}}

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                @if (session()->has('failMessage'))
                                    <p class="text-danger fw-bold">{{ session('failMessage') }}</p>
                                @endif
                                <button type="submit" class="btn btn-primary w-100">
                                    {{ __('Login') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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
</script>
@endsection
