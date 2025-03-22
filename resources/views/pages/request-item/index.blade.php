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

        <p class="fw-bold fs-6 mt-4">Masih Menunggu Review dari Accounting Admin</p>
        <div class="overflow-x-auto mt-3">
            <table class="w-100">
                <tr>
                    <th>No</th>
                    <th>Tanggal Request</th>
                    <th>Nama Proyek</th>
                    <th>Lokasi Proyek</th>
                    <th>PIC</th>
                    <th>Status</th>
                    <th>Catatan</th>
                    <th>Aksi</th>
                </tr>

                @foreach ($awaiting_requests as $r)
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ Carbon\Carbon::parse($r->request_date)->translatedFormat("d M Y") }}</td>
                        <td>{{ $r->project->project_name }}</td>
                        <td>{{ $r->project->location }}</td>
                        <td>{{ $r->PIC }}</td>
                        <td class="fw-bold @if($r->status == 'approved') text-success @elseif($r->status == 'rejected') text-danger @else text-primary @endif">{{ ucwords($r->status) }}</td>
                        <td>{{ $r->notes }}</td>
                        <td>
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


        <p class="fw-bold fs-6 mt-4">Sudah Mendapatkan Review dari Accounting Admin</p>
        <div class="overflow-x-auto mt-3">
            <table class="w-100">
                <tr>
                    <th>No</th>
                    <th>Tanggal Request</th>
                    <th>Nama Proyek</th>
                    <th>Lokasi Proyek</th>
                    <th>PIC</th>
                    <th>Status</th>
                    <th>Catatan</th>
                    <th>Aksi</th>
                </tr>

                @foreach ($unawaiting_requests as $r)
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ Carbon\Carbon::parse($r->request_date)->translatedFormat("d M Y") }}</td>
                        <td>{{ $r->project->project_name }}</td>
                        <td>{{ $r->project->location }}</td>
                        <td>{{ $r->PIC }}</td>
                        <td class="fw-bold @if($r->status == 'approved') text-success @elseif($r->status == 'rejected') text-danger @else text-primary @endif">{{ ucwords($r->status) }}</td>
                        <td>{{ $r->notes }}</td>
                        <td>
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
    </x-container>

@endsection
