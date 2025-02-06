@extends("layouts.main-admin")

@section("content")
    <x-container-middle>
        <div class="container bg-white rounded-4 py-4 px-5 border border-1 card mt-4">
            <h2>My Profile</h2>

            @if (session()->has('successEditProfile'))
                <p class="text-success fw-bold">{{ session('successEditProfile') }}</p>
            @endif

            <form method="POST" action="{{ route('profile-update') }}">
                @csrf

                <div class="mt-3">
                    <label for="name">Display Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Display Name"
                        value="{{ old('name', Auth::user()->name) }}">
                    @error('name')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="email">Email Address<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="email"
                        value="{{ old('email', Auth::user()->email) }}">
                    @error('email')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="text-danger fw-semibold">*Warning: Changing email will require you to verify the new email</div>

                <div class="mt-3 d-flex flex-column align-items-start">
                    <label for="password">Password</label>
                    <a href="{{ route('password.request') }}" class="btn btn-warning">Change Password</a>
                </div>

                <div class="mt-3">
                    <label for="role">Account Role</label>
                    @php
                        $role = "";
                        $role_id = Auth::user()->role->role_name;
                        switch($role_id){
                            case 1: $role = "Admin"; break;
                            case 2: $role = "User"; break;
                        }
                    @endphp
                    <input type="text" class="form-control" id="role" name="role" placeholder="Account"
                        value="{{ $role }}" disabled>
                    @error('role')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3 ">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Save Information">
                </div>
            </form>
        </div>
    </x-container-middle>
@endsection
