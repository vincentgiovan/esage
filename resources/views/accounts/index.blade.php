@extends('layouts.main-admin')

@section('content')

<!-- tes123456789 -->
<x-container>
    <h1 class="my-4">Account Management</h1>
    <hr class="mt-2">
    <br>

    <!-- Add User Form -->
    <div class="card mb-4">
        <div class="card-header">
            <button class="w-100 h-100 btn text-start" type="button" id="add-new-user-btn">Add New User <i class="bi bi-chevron-down"></i></button>
        </div>

        <div class="card-body" id="add-user-form" style="display: none;">
            <form action="{{ route('account.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="name">Name</label>
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
                    <label for="role">Select Role</label>
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

                {{-- <div class="form-group mb-3 d-flex flex-column mt-3">
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
                    </div>

                    <input id="password" type="password" class="pe-5 form-control position-relative z-0 rounded @error('password') is-invalid @enderror" name="password"  autocomplete="current-password">

                    @error('password')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div> --}}
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
                    <label for="password_confirmation">Password Confirmation</label>

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

                <button type="submit" class="btn btn-primary">Add User</button>
            </form>
        </div>
    </div>

    <!-- Users List -->
    <div class="card mt-5">
        <div class="card-header">
            Registered Users
        </div>
        <div class="card-body">

            @if (session()->has('successCreateAccount'))
                <p class="text-success fw-bold">{{ session('successCreateAccount') }}</p>
            @elseif (session()->has('successEditAccount'))
                <p class="text-success fw-bold">{{ session('successEditAccount') }}</p>
            @elseif (session()->has('successDeleteAccount'))
                <p class="text-success fw-bold">{{ session('successDeleteAccount') }}</p>
            @endif

            <div class="w-full overflow-x-auto">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="border border-1 border-secondary" style="background: rgb(199, 199, 199)">Name</th>
                            <th class="border border-1 border-secondary" style="background: rgb(199, 199, 199)">Email</th>
                            <th class="border border-1 border-secondary" style="background: rgb(199, 199, 199)">Role</th>
                            <th class="border border-1 border-secondary" style="background: rgb(199, 199, 199)">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        @if(Auth::user()->id == $user->id)
                            @continue
                        @endif
                        <tr>
                            <td class="border border-1 border-secondary">{{ $user->name }}</td>
                            <td class="border border-1 border-secondary">{{ $user->email }}</td>
                            <td class="border border-1 border-secondary">{{ ($user->role == 2)? "User" : "Admin" }}</td>
                            <td class="border border-1 border-secondary">
                                <div class="d-flex w-100 align-items-center gap-3">
                                    <form action="{{ route('account.destroy', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure want to delete this account?');"><i class="bi bi-trash3"></i> Delete</button>
                                    </form>
                                    {{-- <button class="btn btn-warning" data-toggle="modal" data-target="#editUserModal-{{ $user->id }}">Edit</button> --}}
                                    <a href="{{ route("account.show", $user->id) }}" class="btn text-white" data-toggle="modal" data-target="#editUserModal-{{ $user->id }}" style="background-color: rgb(197, 167, 0);"><i class="bi bi-pencil"></i> Edit</a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-container>

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

    $(document).ready(() => {
        $("#add-new-user-btn").click(() => {
            $("#add-user-form").slideToggle();
        })
    });

</script>
@endsection
