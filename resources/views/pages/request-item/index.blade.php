@extends("layouts.main-admin")

@section("content")
    <x-container>

        <br>
        <h3>Request Barang</h3>
        <hr>

        @if (session()->has('successAddRequest'))
            <p class="text-success fw-bold">{{ session('successAddRequest') }}</p>
        @elseif (session()->has('successEditRequest'))
            <p class="text-success fw-bold">{{ session('successEditRequest') }}</p>
        @elseif (session()->has('successDeleteRequest'))
            <p class="text-success fw-bold">{{ session('successDeleteRequest') }}</p>
        @elseif (session()->has('successUpdateStatus'))
            <p class="text-success fw-bold">{{ session('successUpdateStatus') }}</p>
        @endif

        @if(in_array(Auth::user()->role->role_name, ['project_manager', 'master']))
            <a href="{{ route('requestitem-create') }}" class="btn btn-primary text-white" style="font-size: 10pt">
                <i class="bi bi-plus-square"></i>
                Buat Request Baru
            </a>
        @endif

        <div class="d-flex w-100 justify-content-between align-items-center mt-3">
            <div class="d-flex" style="gap: 1px;">
                <a href="{{ route('requestitem-index', ['content' => 'awaiting']) }}" class="btn" style="border-radius: 0; width: 200px; @if(!request('content') || request('content') == 'awaiting') border-bottom: 3px solid rgb(59, 59, 59); @else border-bottom: none; @endif">Menunggu Review</a>
                <a href="{{ route('requestitem-index', ['content' => 'unawaiting']) }}" class="btn" style="border-radius: 0; width: 200px; @if(request('content') == 'unawaiting') border-bottom: 3px solid rgb(59, 59, 59); @else border-bottom: none; @endif">Telah Di-Review</a>
            </div>
            @if(!request('content') || request('content') == 'awaiting')
                <div class="d-flex w-100 justify-content-end">
                    Memperlihatkan {{ $awaiting_requests->firstItem() }} - {{ $awaiting_requests->lastItem()  }} dari {{ $awaiting_requests->total() }} item
                </div>
            @endif
            @if(request('content') == 'unawaiting')
                <div class="d-flex w-100 justify-content-end">
                    Memperlihatkan {{ $unawaiting_requests->firstItem() }} - {{ $unawaiting_requests->lastItem()  }} dari {{ $unawaiting_requests->total() }} item
                </div>
            @endif
        </div>

        @if(!request('content') || request('content') == 'awaiting')
            <div class="overflow-x-auto mt-3">
                <table class="w-100">
                    <tr>
                        <th class="border border-1 border-secondary">No</th>
                        <th class="border border-1 border-secondary">Tanggal Request</th>
                        <th class="border border-1 border-secondary">Nama Proyek</th>
                        <th class="border border-1 border-secondary">Lokasi Proyek</th>
                        <th class="border border-1 border-secondary">PIC</th>
                        <th class="border border-1 border-secondary">Status</th>
                        <th class="border border-1 border-secondary">Catatan</th>
                        <th class="border border-1 border-secondary">Aksi</th>
                    </tr>

                    @foreach ($awaiting_requests as $r)
                        <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                            <td class="border border-1 border-secondary">{{ $loop->iteration }}</td>
                            <td class="border border-1 border-secondary">{{ Carbon\Carbon::parse($r->request_date)->translatedFormat("d M Y") }}</td>
                            <td class="border border-1 border-secondary">{{ $r->project->project_name }}</td>
                            <td class="border border-1 border-secondary">{{ $r->project->location }}</td>
                            <td class="border border-1 border-secondary">{{ $r->PIC }}</td>
                            <td class="border border-1 border-secondary" class="fw-bold @if($r->status == 'approved') text-success @elseif($r->status == 'rejected') text-danger @else text-primary @endif">{{ ucwords($r->status) }}</td>
                            <td class="border border-1 border-secondary">{{ $r->notes }}</td>
                            <td class="border border-1 border-secondary">
                                <div class="d-flex gap-2 w-100">
                                    <a href="{{ route('requestitem-show', $r->id) }}" class="btn btn-success text-white"
                                        style="font-size: 10pt;">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    @if(in_array(Auth::user()->role->role_name, ['project_manager']))
                                        <a href="{{ route('requestitem-edit', $r->id) }}" class="btn btn-warning text-white"
                                            style="font-size: 10pt; background-color: rgb(197, 167, 0);">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ route('requestitem-destroy', $r->id) }}" method="post">
                                            @csrf
                                            <button onclick="return confirm('This request will be deleted, are you sure?')" type="submit" class="btn btn-danger"><i class="bi bi-trash3"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </td>

                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="mt-4">
                {{ $awaiting_requests->links() }}
            </div>
        @endif

        @if(request('content') == 'unawaiting')
            <div class="overflow-x-auto mt-3">
                <table class="w-100">
                    <tr>
                        <th class="border border-1 border-secondary">No</th>
                        <th class="border border-1 border-secondary">Tanggal Request</th>
                        <th class="border border-1 border-secondary">Nama Proyek</th>
                        <th class="border border-1 border-secondary">Lokasi Proyek</th>
                        <th class="border border-1 border-secondary">PIC</th>
                        <th class="border border-1 border-secondary">Status</th>
                        <th class="border border-1 border-secondary">Catatan</th>
                        <th class="border border-1 border-secondary">Aksi</th>
                    </tr>

                    @foreach ($unawaiting_requests as $r)
                        <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                            <td class="border border-1 border-secondary">{{ $loop->iteration }}</td>
                            <td class="border border-1 border-secondary">{{ Carbon\Carbon::parse($r->request_date)->translatedFormat("d M Y") }}</td>
                            <td class="border border-1 border-secondary">{{ $r->project->project_name }}</td>
                            <td class="border border-1 border-secondary">{{ $r->project->location }}</td>
                            <td class="border border-1 border-secondary">{{ $r->PIC }}</td>
                            <td class="border border-1 border-secondary" class="fw-bold @if($r->status == 'approved') text-success @elseif($r->status == 'rejected') text-danger @else text-primary @endif">{{ ucwords($r->status) }}</td>
                            <td class="border border-1 border-secondary">{{ $r->notes }}</td>
                            <td class="border border-1 border-secondary">
                                <div class="d-flex gap-2 w-100">
                                    <a href="{{ route('requestitem-show', $r->id) }}" class="btn btn-success text-white"
                                        style="font-size: 10pt;">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </td>

                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="mt-4">
                {{ $unawaiting_requests->links() }}
            </div>
        @endif
    </x-container>

@endsection
