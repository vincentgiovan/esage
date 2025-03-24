@extends('layouts.main-admin')

@section('content')
    <x-container>
        <h3 class="my-4">Kelola Akun</h3>
        <hr class="mt-2">

        {{-- Users List --}}
        @if (session()->has('successCreateAccount'))
            <p class="text-success fw-bold">{{ session('successCreateAccount') }}</p>
        @elseif (session()->has('successEditAccount'))
            <p class="text-success fw-bold">{{ session('successEditAccount') }}</p>
        @elseif (session()->has('successDeleteAccount'))
            <p class="text-success fw-bold">{{ session('successDeleteAccount') }}</p>
        @endif


        <div class="d-flex w-100 justify-content-between">
            <div class="d-flex gap-2 ">
                <form action="{{ route('account.index') }}" class="d-flex gap-2">
                    <div class="position-relative">
                    <input type="text" name="search" placeholder="Cari akun..." value="{{ request('search') }}" class="form-control border border-1 border-secondary pe-5" style="width: 300px;">
                        <a href="{{ route('account.index') }}" class="btn position-absolute top-0 end-0"><i class="bi bi-x-lg"></i></a>
                    </div>
                    <button class="btn btn-primary"><i class="bi bi-search"></i></button>
                </form>
            </div>
            <a href="{{ route('account.create') }}" class="btn btn-primary"><i class="bi bi-plus-square"></i> Buat Akun Baru</a>
        </div>

        <br>

        <div class="d-flex w-100 justify-content-end">
            Memperlihatkan {{ $users->firstItem() }} - {{ $users->lastItem()  }} dari {{ $users->total() }} item
        </div>

        <div class="w-full overflow-x-auto mt-3">
            <table class="w-100">
                <thead>
                    <tr>
                        <th class="border border-1 border-secondary" style="background: rgb(199, 199, 199)">Nama</th>
                        <th class="border border-1 border-secondary" style="background: rgb(199, 199, 199)">Email</th>
                        <th class="border border-1 border-secondary" style="background: rgb(199, 199, 199)">Role</th>
                        <th class="border border-1 border-secondary" style="background: rgb(199, 199, 199)">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users->except([Auth::user()->id]) as $user)
                        <tr style="background: @if($loop->iteration % 2 == 0) #E0E0E0 @else white @endif;">
                            <td class="border border-1 border-secondary">{{ $user->name }}</td>
                            <td class="border border-1 border-secondary">{{ $user->email }}</td>
                            <td class="border border-1 border-secondary">{{ ucwords($user->role->role_name) }}</td>
                            <td class="border border-1 border-secondary">
                                <div class="d-flex w-100 align-items-center gap-2">
                                    <a href="{{ route("account.show", $user->id) }}" class="btn text-white" data-toggle="modal" data-target="#editUserModal-{{ $user->id }}" style="background-color: rgb(197, 167, 0);"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('account.destroy', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah anda yakin ingin menghapus akun {{ $user->name }} ({{ $user->id }})?');"><i class="bi bi-trash3"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </x-container>
@endsection
