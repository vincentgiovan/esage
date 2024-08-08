@extends('layouts.main-admin')

@section('content')

<!-- tes123456789 -->
<div class="container">
    <h1>Account Management</h1>

    <!-- Add User Form -->
    <div class="card mb-4">
        <div class="card-header">
            Add New User
        </div>
        <div class="card-body">
            <form action="{{ route('account.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required value="{{ old("name") }}">
                    @error("name")
                        {{ $message }}
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required value="{{ old("email") }}">
                    @error("email")
                        {{ $message }}
                    @enderror
                </div>
                <div class="form-group">
                    <label for="role">Select Role</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="1">Admin</option>
                        <option value="2" selected>User</option>
                    </select>
                    @error("role")
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

                <button type="submit" class="btn btn-primary">Add User</button>
            </form>
        </div>
    </div>

    <!-- Users List -->
    <div class="card">
        <div class="card-header">
            Registered Users
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    @if(Auth::user()->id == $user->id)
                    @continue
                    @endif
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ ($user->role == 2)? "User" : "Admin" }}</td>
                        <td>
                            <form action="{{ route('account.destroy', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                            {{-- <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editUserModal-{{ $user->id }}">Edit</button> --}}
                            <a href="{{ route("account.show", $user->id) }}" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editUserModal-{{ $user->id }}">Edit</a>

                            {{-- <!-- Edit User Modal -->
                            <div class="modal fade" id="editUserModal-{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('account.update', $user->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="form-group">
                                                    <label for="name">Name</label>
                                                    <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                                                </div>
                                                <div class="input-group">
                                                    <input id="password" type="password" class="pe-5 form-control border border-secondary position-relative z-0 rounded @error('password') is-invalid @enderror" name="password"  autocomplete="current-password">
                                                    <div class="input-group-append position-absolute z-1 end-0">
                                                        <button type="button" class="btn" id="togglePassword">
                                                            <i class="bi bi-eye-fill" id="toggleIcon"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="password_confirmation">Confirm Password</label>
                                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
