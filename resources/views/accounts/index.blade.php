@extends('layouts.main-admin')

@section('content')
    <x-container>
        <h2 class="my-4">Kelola Akun</h2>
        <hr class="mt-2">

        <!-- Users List -->
        @if (session()->has('successCreateAccount'))
            <p class="text-success fw-bold">{{ session('successCreateAccount') }}</p>
        @elseif (session()->has('successEditAccount'))
            <p class="text-success fw-bold">{{ session('successEditAccount') }}</p>
        @elseif (session()->has('successDeleteAccount'))
            <p class="text-success fw-bold">{{ session('successDeleteAccount') }}</p>
        @endif


            <a href="{{ route('account.create') }}" class="btn btn-primary"><i class="bi bi-plus-square"></i> Buat Akun Baru</a>


        <div class="w-full overflow-x-auto mt-3">
            <table class="w-100">
                <thead>
                    <tr>
                        <th style="background: rgb(199, 199, 199)">Nama</th>
                        <th style="background: rgb(199, 199, 199)">Email</th>
                        <th style="background: rgb(199, 199, 199)">Role</th>
                        <th style="background: rgb(199, 199, 199)">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users->except([Auth::user()->id]) as $user)
                        <tr style="background: @if($loop->iteration % 2 == 0) #E0E0E0 @else white @endif;">
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ ($user->role == 2)? "User" : "Admin" }}</td>
                            <td>
                                <div class="d-flex w-100 align-items-center gap-2">
                                    <a href="{{ route("account.show", $user->id) }}" class="btn text-white" data-toggle="modal" data-target="#editUserModal-{{ $user->id }}" style="background-color: rgb(197, 167, 0);"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('account.destroy', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure want to delete this account?');"><i class="bi bi-trash3"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-container>
@endsection
