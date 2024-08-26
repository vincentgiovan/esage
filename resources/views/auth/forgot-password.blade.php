@extends('layouts.login-main-admin')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height:100vh;">
    <div class="row justify-content-center " id="login-container">
        <div class="col-md-6">
            <div class="card shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-secondary text-white fs-5  ">{{ __('Forgot Password') }}</div>

                    <div class="align items-center ps-3 pe-2 ">
                            <x-auth-card class="">
                                <div class="mb-4 text-gray-600">
                                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                                </div>

                                <!-- Session Status -->
                                <x-auth-session-status class="mb-4" :status="session('status')" />

                                <!-- Validation Errors -->
                                <x-auth-validation-errors class="mb-4" :errors="$errors" />

                                <form method="POST" action="{{ route('password.email') }}">
                                    @csrf

                                    <!-- Email Address -->
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-envelope-fill"></i>
                                        <x-label for="email" :value="__('Email')" />

                                        <x-input id="email" class="block mt-1 w-full form-control border border-1 border-black" type="email" name="email" :value="old('email')" required autofocus />
                                    </div>

                                    <div class="flex items-center justify-end mt-4">
                                        <x-button class="btn btn-primary">
                                            {{ __('Email Password Reset Link') }}
                                        </x-button>
                                    </div>
                                </form>
                            </x-auth-card>
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
