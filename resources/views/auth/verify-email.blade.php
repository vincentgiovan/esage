@extends('layouts.login-main-admin')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height:100vh;">
    <div class="row justify-content-center w-75">
        <div class="col-md-6">
            <div class="card shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-secondary text-white fs-5 ">{{ __('Account Email Verification') }}</div>

                <div class="card-body p-4">

                    <div class="mb-4 text-sm text-gray-600">
                        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                    </div>

                    @if (session('status') == 'verification-link-sent')
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                        </div>
                    @endif

                    <div class="mt-4 flex items-center justify-between">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf

                            <div class="flex items-center justify-end mt-4 ">
                                <button class="btn btn-secondary text-white">
                                    {{('Resend Verification Email') }}
                                </button>
                            </div>
                        </form>
<br>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button type="submit" class=" btn btn-primary ">
                                {{('Log Out') }}
                            </button>
                        </form>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




