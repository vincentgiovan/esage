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


            <div class="d-flex justify-content-between align-items-end w-100">
                <div class="d-flex flex-column">
                    <label for="">Tampilkan Data untuk Periode Tanggal:</label>
                    <div class="d-flex gap-3 align-items-center mt-1">
                        <input type="date" class="form-control" id="filter-start-date" value="{{ request('from') }}">
                        <div class="">-</div>
                        <input type="date" class="form-control" id="filter-end-date" value="{{ request('until') }}">
                        <form action="{{ route('salary-index') }}">
                            <button type="submit" class="btn btn-primary px-4">Konfirmasi</button>
                        </form>
                    </div>
                </div>
                <form action="{{ route('salary-export') }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-primary px-4">Export PDF</button>
                </form>
            </div>

        <div class="overflow-x-auto mt-3">
            <table class="w-100">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Proyek</th>
                    <th>Subtotal</th>
                    <th>Kasbon</th>
                    {{-- <th>Keterangan</th> --}}
                    <th>Aksi</th>
                </tr>

                @foreach ($attendances as $a)
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $a->attendance_date ? Carbon\Carbon::parse($a->attendance_date)->translatedFormat("d M Y") : "N/A" }}</td>
                        <td>{{ $a->employee->nama }}</td>
                        <td>{{ $a->employee->jabatan }}</td>
                        <td>{{ $a->project->project_name }}</td>
                        <td>
                            @if($subtotals[$loop->iteration - 1] != "N/A")
                                Rp {{ number_format($subtotals[$loop->iteration - 1], 2, ",", ".") }}
                            @else
                                {{ $subtotals[$loop->iteration - 1] }}
                            @endif
                        </td>
                        <td>
                            @php
                                $ppamount = 0;

                                $prepay = $a->employee->prepays->where('prepay_date', $a->attendance_date)->first();
                                if($prepay){
                                    $ppamount = $prepay->amount;
                                }

                                echo 'Rp ' . number_format($ppamount, 2, ",", ".");
                            @endphp
                        </td>
                        {{-- <td>{{ $a->keterangan ?? "N/A" }}</td> --}}
                        <td>
                            <div class="d-flex gap-2 w-100">
                                <a href="{{ route('salary-edit', $a->id) }}" class="btn btn-warning text-white"
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
