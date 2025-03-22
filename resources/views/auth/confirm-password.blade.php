@extends('layouts.login-main-admin')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height:100vh;">
    <div class="row justify-content-center " id="login-container">
        <div class="col-md-6">
            <div class="card shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-secondary text-white fs-5 ">{{ __('Admin Login') }}</div>

                <div class="card-body p-4">
                    <div class="mb-4 text-sm text-gray-600">
                        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
</div>

                    {{-- Validation Errors --}}
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                                <form method="POST" action="{{ route('password.confirm') }}">
                                    @csrf

                                    {{-- Password --}}
                                    <div>
                                        <x-label for="password" :value="__('Password')" />

                                        <x-input id="password" class="block mt-1 w-full"
                                            type="password"
                                            name="password"
                                            required autocomplete="current-password" />
                                    </div>

                                    <div class="flex justify-end mt-4">
                                        <x-button class="btn btn-primary">
                                            {{ __('Confirm') }}
                                        </x-button>
                                    </div>
                                </form>
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
