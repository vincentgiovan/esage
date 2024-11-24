@extends('layouts.login-main-admin')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height:100vh;">
    <div class="row justify-content-center" id="login-container">
        <div class="col-md-6">
            <div class="card shadow-sm rounded-4 overflow-hidden border-0">
                <div class="card-header bg-secondary text-white fs-5 ">{{ __('Admin Login') }}</div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}">
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
                                    <button class="btn border border-2" type="button" id="togglePassword">
                                        <i class="bi bi-eye-fill" id="toggleIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="block mt-4">
                            <label for="remember_me" class="inline-flex items-center">
                                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                            </label>
                        </div>

                        <div class="d-flex justify-content-end">
                            @if (Route::has('password.request'))
                                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif
                        </div>
                        <div class="form-group row mb-0 mt-4">
                            <div class="d-flex w-100 justify-content-center">
                                <button type="submit" class="btn btn-primary col-md-6">
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
    let detectedIP = "";
    let detectedLocation = "";
    let detectedDevice = "";
    let detectedOS = "";

    document.getElementById('togglePassword').addEventListener('click', function (e) {
        const passwordInput = document.getElementById('password');
        const passwordIcon = document.getElementById('toggleIcon');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        passwordIcon.classList.toggle('bi-eye-fill');
        passwordIcon.classList.toggle('bi-eye-slash-fill');
    });

    // Fetch user's real IP and other details from ip-api.com
    fetch('http://ip-api.com/json')
        .then(response => response.json())
        .then(data => {
            console.log('IP:', data.query); // Output: Mobile, Tablet, Desktop, or Unknown
            console.log('City:', data.city); // Output: Platform name (e.g., 'Win32', 'MacIntel', 'Linux')
            console.log('Region:', data.regionName); // Output: Mobile, Tablet, Desktop, or Unknown
            console.log('Country:', data.country); // Output: Platform name (e.g., 'Win32', 'MacIntel', 'Linux')

            detectedIP = data.query;
            detectedLocation = `${data.city}, ${data.regionName}, ${data.country}`;
        })
        .catch(error => console.error('Error fetching IP data:', error));

    const deviceType = (() => {
        const ua = navigator.userAgent;

        // Check if the device is mobile (Android, iPhone, iPad, iPod)
        if (/Mobile|Android|iP(hone|od|ad)/.test(ua)) {
            return 'Mobile';
        }
        // Check if the device is a tablet
        else if (/Tablet/.test(ua)) {
            return 'Tablet';
        }
        // Check for common desktop OS (Windows, Mac, Linux)
        else if (/Windows|Mac|Linux/.test(navigator.platform)) {
            return 'Desktop';
        }
        // If it doesn't match any of the above, return 'Unknown'
        else {
            return 'Unknown';
        }
    })();

    const os = navigator.platform; // Get the platform (e.g., 'Win32', 'MacIntel', 'Linux')

    detectedDevice = deviceType;
    detectedOS = os;

    console.log('Device Type:', deviceType); // Output: Mobile, Tablet, Desktop, or Unknown
    console.log('OS:', os); // Output: Platform name (e.g., 'Win32', 'MacIntel', 'Linux')

    $("form").on("submit", function(e){
        e.preventDefault();

        $(this).append($("<input>").attr({"type": "hidden", "name": "IP", "value": detectedIP}));
        $(this).append($("<input>").attr({"type": "hidden", "name": "location", "value": detectedLocation}));

        $(this).append($("<input>").attr({"type": "hidden", "name": "OS", "value": detectedOS}));
        $(this).append($("<input>").attr({"type": "hidden", "name": "device", "value": detectedDevice}));

        this.submit();
    });
</script>
@endsection
