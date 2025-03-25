@extends("layouts.main-admin")

@section("content")
    <x-container>
        <br>
        <h3>Gaji Pegawai</h3>
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

                <div class="mt-3 fst-italic">Tanggal terakhir generasi data gaji: <strong>{{ Carbon\Carbon::parse($lastDataGeneration)->translatedFormat('d M Y') }}</strong></div>
                @if($diff > 0)
                    <div class="fst-italic">Bisa menggenerasi data gaji terbaru hingga tanggal: <strong>{{ Carbon\Carbon::parse($ableToGenerateUntil)->translatedFormat('d M Y') }}</strong></div>
                @else
                    <div class="fst-italic">Saat ini belum bisa menggenerasi data gaji terbaru, harap tunggu hingga tanggal: <strong>{{ Carbon\Carbon::parse(Carbon\Carbon::now()->next('Friday'))->translatedFormat('d M Y') }}</strong></div>
                @endif
            </form>

            <div class="d-flex flex-column align-items-end">
                <label for="">Tampilkan Data Hanya pada Periode Tanggal:</label>
                <div class="d-flex gap-3 align-items-center mt-1">
                    <input type="date" class="form-control" id="filter-start-date" value="{{ request('from') }}">
                    <div class="">-</div>
                    <input type="date" class="form-control" id="filter-end-date" value="{{ request('until') }}">
                </div>
                <div class="d-flex gap-3">
                    <form action="{{ route('salary-export') }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-primary px-4 mt-3">Export PDF</button>
                    </form>
                    <form action="{{ route('salary-index') }}">
                        <button type="submit" class="btn btn-primary px-4 mt-3">Filter Data</button>
                    </form>
                </div>
            </div>
        </div>


        <div class="overflow-x-auto mt-3">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary">No</th>
                    <th class="border border-1 border-secondary">Periode</th>
                    <th class="border border-1 border-secondary">Nama</th>
                    <th class="border border-1 border-secondary">Jabatan</th>
                    <th class="border border-1 border-secondary">Subtotal</th>
                    <th class="border border-1 border-secondary">Kasbon</th>
                    <th class="border border-1 border-secondary">Keterangan</th>
                    <th class="border border-1 border-secondary">Aksi</th>
                </tr>

                @foreach ($salaries as $s)
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td class="border border-1 border-secondary">{{ $loop->iteration }}</td>
                        <td class="border border-1 border-secondary">{{ $s->start_period ? Carbon\Carbon::parse($s->start_period)->translatedFormat("d M Y") : "N/A" }} - {{ $s->end_period ? Carbon\Carbon::parse($s->end_period)->translatedFormat("d M Y") : "N/A" }}</td>
                        <td class="border border-1 border-secondary">{{ $s->employee->nama }}</td>
                        <td class="border border-1 border-secondary">{{ $s->employee->jabatan }}</td>
                        <td class="border border-1 border-secondary">Rp {{ number_format($s->total, 2, ",", ".") }}</td>
                        <td class="border border-1 border-secondary">
                            @php
                                $total_kasbon = 0;
                                foreach($s->employee->prepays->where('start_period', '>=', $s->start_period)->where('end_period', '<=', $s->end_period) as $ppay){
                                    $total_kasbon += $ppay->amount;
                                }

                                echo number_format($total_kasbon, 2, ',', '.');
                            @endphp
                        </td>
                        <td class="border border-1 border-secondary">{{ $s->keterangan ?? "N/A" }}</td>
                        <td class="border border-1 border-secondary">
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

    <script>
        $("form").on("submit", function(e){
            e.preventDefault();

            $(this).append($("<input>").attr({"type": "hidden", "name": "from", "value": $("#filter-start-date").val()}));
            $(this).append($("<input>").attr({"type": "hidden", "name": "until", "value": $("#filter-end-date").val()}));

            this.submit();
        });
    </script>

@endsection
