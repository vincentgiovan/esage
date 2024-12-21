@extends("layouts.main-admin")

@section("content")
    <x-container>

        <br>
        <h2>Request Barang</h2>
        <hr>
        <br>

        @if (session()->has('successAddRequest'))
            <p class="text-success fw-bold">{{ session('successAddRequest') }}</p>
        @elseif (session()->has('successEditRequest'))
            <p class="text-success fw-bold">{{ session('successEditRequest') }}</p>
        @elseif (session()->has('successDeleteRequest'))
            <p class="text-success fw-bold">{{ session('successDeleteRequest') }}</p>
        @endif

        <a href="{{ route('requestitem-create') }}" class="btn btn-primary text-white mb-3" style="font-size: 10pt">
            <i class="bi bi-plus-square"></i>
            Add New Request</a>

        <div class="overflow-x-auto mt-3">
            <table class="w-100">
                <tr>
                    <th>No</th>
                    <th>Request Date</th>
                    <th>Project Name</th>
                    <th>Project Location</th>
                    <th>PIC</th>
                    <th>Notes</th>
                    <th>Actions</th>
                </tr>

                @foreach ($requests as $r)
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ Carbon\Carbon::parse($r->request_date)->translatedFormat("d M Y") }}</td>
                        <td>{{ $r->project->project_name }}</td>
                        <td>{{ $r->project->location }}</td>
                        <td>{{ $r->PIC }}</td>
                        <td>{{ $r->notes }}</td>
                        <td>
                            <div class="d-flex gap-2 w-100">
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
