@extends("layouts.main-admin")

@section("content")
    <x-container>
        <br>
        <h2>Gaji Pegawai</h2>
        <hr>

        @if (session()->has('successEditSalary'))
            <p class="text-success fw-bold">{{ session('successEditSalary') }}</p>
        @elseif (session()->has('successAutoGenerateSalary'))
            <p class="text-success fw-bold">{{ session('successAutoGenerateSalary') }}</p>
        @endif

        <div class="d-flex w-100 justify-content-between mt-2 align-items-center">
            <form action="{{ route('salary-autocreate') }}" method="post">
                @csrf
                <button class="btn btn-primary" type="submit">Generasi Data Gaji</button>

                @php
                    $lastDataGeneration = Carbon\Carbon::parse(
                        Illuminate\Support\Facades\DB::table('salaries')->max('created_at')
                    )->startOfDay();

                    $ableToGenerateUntil = Carbon\Carbon::now()
                        ->previous('Friday')
                        ->startOfDay();

                    $diff = $lastDataGeneration->diffInDays($ableToGenerateUntil);
                @endphp

                <div class="mt-3 fst-italic">Tanggal terakhir generasi data gaji: <strong>{{ Carbon\Carbon::parse($lastDataGeneration)->format('d M Y') }}</strong></div>
                @if($diff > 0)
                    <div class="fst-italic">Bisa menggenerasi data gaji terbaru hingga tanggal: <strong>{{ Carbon\Carbon::parse($ableToGenerateUntil)->format('d M Y') }}</strong></div>
                @else
                    <div class="fst-italic">Saat ini belum bisa menggenerasi data gaji terbaru, harap tunggu hingga tanggal: <strong>{{ Carbon\Carbon::parse(Carbon\Carbon::now()->next('Friday'))->format('d M Y') }}</strong></div>
                @endif
            </form>

            <form action="{{ route('salary-index') }}" class="d-flex flex-column align-items-end">
                <label for="">Tampilkan Data Hanya pada Periode Tanggal:</label>
                <div class="d-flex gap-3 align-items-center mt-1">
                    <input type="date" name="from" class="form-control" id="filter-start-date" value="{{ request('from') }}">
                    <div class="">-</div>
                    <input type="date" name="until" class="form-control" id="filter-end-date" value="{{ request('until') }}">
                </div>
                <button type="submit" class="btn btn-primary px-4 mt-3">Filter Data</button>
            </form>
        </div>


        <div class="overflow-x-auto mt-3">
            <table class="w-100">
                <tr>
                    <th>No</th>
                    <th>Periode</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Total</th>
                    <th>Keterangan</th>
                    <th>Actions</th>
                </tr>

                @foreach ($salaries as $s)
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $s->start_period ? Carbon\Carbon::parse($s->start_period)->format("d M Y") : "N/A" }} - {{ $s->end_period ? Carbon\Carbon::parse($s->end_period)->format("d M Y") : "N/A" }}</td>
                        <td>{{ $s->employee->nama }}</td>
                        <td>{{ $s->employee->jabatan }}</td>
                        <td>Rp {{ number_format($s->total, 2, ",", ".") }}</td>
                        <td>{{ $s->keterangan ?? "N/A" }}</td>
                        <td>
                            <div class="d-flex gap-2 w-100">
                                <a href="{{ route('salary-edit', $s->id) }}" class="btn btn-warning text-white"
                                    style="font-size: 10pt; background-color: rgb(197, 167, 0);">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </td>

                    </tr>
                @endforeach
            </table>
        </div>
    </x-container>

@endsection
