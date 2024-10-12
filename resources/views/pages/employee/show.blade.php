@extends("layouts.main-admin")

@section("content")
    <x-container>
        <br>
        <h1>Sage Employees</h1>

        <table class="w-100">
            <tr>
                <th class="border border-1 border-secondary w-25">Nama</th>
                <td class="border border-1 border-secondary">{{ $employee->user->name }}</td>
            </tr>
            <tr>
                <th class="border border-1 border-secondary w-25">NIK</th>
                <td class="border border-1 border-secondary">{{ $employee->NIK }}</td>
            </tr>
        </table>
    </x-container>
@endsection
