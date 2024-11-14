@extends("layouts.main-admin")

@section("content")
    <x-container>

        <br>
        <h1>Request Items</h1>
        <br>

        @if (session()->has('successAddRequest'))
            <p class="text-success fw-bold">{{ session('successAddRequest') }}</p>
        @elseif (session()->has('successEditRequest'))
            <p class="text-success fw-bold">{{ session('successEditRequest') }}</p>
        @elseif (session()->has('successDeleteRequest'))
            <p class="text-success fw-bold">{{ session('successDeleteRequest') }}</p>
        @endif

        @can("admin")
            <a href="{{ route('requestitem-create') }}" class="btn btn-primary text-white mb-3" style="font-size: 10pt">
                <i class="bi bi-plus-square"></i>
                Add New Request</a>
        @endcan

        <div class="overflow-x-auto mt-3">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary ">#</th>
                    <th class="border border-1 border-secondary ">Request Date</th>
                    <th class="border border-1 border-secondary ">Project Name</th>
                    <th class="border border-1 border-secondary ">Project Location</th>
                    <th class="border border-1 border-secondary ">PIC</th>
                    <th class="border border-1 border-secondary ">Notes</th>
                    <th class="border border-1 border-secondary ">Actions</th>
                </tr>

                @foreach ($requests as $r)
                    <tr>
                        <td class="border border-1 border-secondary ">{{ $loop->iteration }}</td>
                        <td class="border border-1 border-secondary ">{{ $r->request_date }}</td>
                        <td class="border border-1 border-secondary ">{{ $r->project->project_name }}</td>
                        <td class="border border-1 border-secondary ">{{ $r->project->location }}</td>
                        <td class="border border-1 border-secondary ">{{ $r->PIC }}</td>
                        <td class="border border-1 border-secondary ">{{ $r->notes }}</td>
                        <td class="border border-1 border-secondary ">
                            <div class="d-flex gap-2 w-100 justify-content-center">
                                <a href="{{ route('requestitem-show', $r->id) }}" class="btn btn-success text-white"
                                    style="font-size: 10pt;">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('requestitem-edit', $r->id) }}" class="btn btn-warning text-white"
                                    style="font-size: 10pt; background-color: rgb(197, 167, 0);">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('requestitem-destroy', $r->id) }}" method="post">
                                    @csrf
                                    <button onclick="return confirm('This request will be deleted, are you sure?')" type="submit" class="btn btn-danger"><i class="bi bi-trash3"></i></button>
                                </form>
                            </div>
                        </td>

                    </tr>
                @endforeach
            </table>
        </div>
    </x-container>

@endsection
