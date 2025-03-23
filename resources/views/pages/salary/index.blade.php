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
                <form action="{{ route('salary-export') }}" method="post" target="_blank">
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
                    <th>Total</th>
                    <th></th>
                    <th>Aksi</th>
                </tr>

                @foreach($grouped_attendances as $emp_id => $attendances)
                    @php
                        $employee = App\Models\Employee::find(intval($emp_id));
                        $kasubon = $employee->prepays->where('prepay_date', '>=', $start_period)->where('prepay_date', '<=', $end_period);

                        $total_kasbon = 0;
                        foreach($kasubon as $k){
                            $total_kasbon += $k->amount;
                        }

                        $subtotals[$emp_id] -= $total_kasbon;

                        $iterasus = $loop->index;
                    @endphp

                    <tr style="background-color: @if($iterasus % 2 == 0) #E0E0E0 @else white @endif;">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ Carbon\Carbon::parse($start_period)->translatedFormat("d/m/Y") }} - {{ Carbon\Carbon::parse($end_period)->translatedFormat("d/m/Y") }}</td>
                        <td>{{ $employee->nama }}</td>
                        <td>{{ $employee->jabatan }}</td>
                        <td>{{ number_format($subtotals[$emp_id], 0, ',', '.') }}</td>
                        <td></td>
                        <td>
                            <button class="btn btn-success see-detail-btn" data-empid="{{ $emp_id }}">Rincian</button>
                        </td>
                    </tr>

                    @foreach($attendances->groupBy('project_id') as $proj_id => $gbp)
                        @php
                            $total_jam_normal = 0;
                            $total_jam_lembur = 0;
                            $total_kali_lembur_panjang = 0;

                            $total_gaji_normal = 0;
                            $total_gaji_lembur = 0;
                            $total_gaji_lembur_panjang = 0;
                            $total_performa = 0;
                        @endphp

                        @foreach($gbp as $proj_id => $atd)
                            @php
                                $project_name = $atd->project->project_name;

                                $total_jam_normal += $atd->normal;
                                $total_jam_lembur += $atd->jam_lembur;
                                $total_kali_lembur_panjang += $atd->index_lembur_panjang;

                                $total_gaji_normal += $atd->normal * $atd->employee->pokok;
                                $total_gaji_lembur += $atd->jam_lembur * $atd->employee->lembur;
                                $total_gaji_lembur_panjang += $atd->index_lembur_panjang * $atd->lembur_panjang;
                                $total_performa += $atd->performa;
                            @endphp
                        @endforeach

                        @if($total_jam_normal != 0)
                            <tr class="detail-area{{ $emp_id }}" style="display: none; background-color: @if($iterasus % 2 == 0) #E0E0E0 @else white @endif;">
                                <td class="py-2"></td>
                                <td class="py-2" colspan="4">Normal: {{ $total_jam_normal }} jam ({{ $project_name }})</td>
                                <td class="py-2">{{ number_format($total_gaji_normal, 0, ',', '.') }}</td>
                                <td class="py-2"></td>
                            </tr>
                        @endif
                        @if($total_jam_lembur > 0)
                            <tr class="detail-area{{ $emp_id }}" style="display: none; background-color: @if($iterasus % 2 == 0) #E0E0E0 @else white @endif;">
                                <td class="py-2"></td>
                                <td class="py-2" colspan="4">Lembur: {{ $total_jam_lembur }} jam ({{ $project_name }})</td>
                                <td class="py-2">{{ number_format($total_gaji_lembur, 0, ',', '.') }}</td>
                                <td class="py-2"></td>
                            </tr>
                        @endif
                        @if($total_kali_lembur_panjang > 0)
                            <tr class="detail-area{{ $emp_id }}" style="display: none; background-color: @if($iterasus % 2 == 0) #E0E0E0 @else white @endif;">
                                <td class="py-2"></td>
                                <td class="py-2" colspan="4">Lembur Panjang: {{ $total_kali_lembur_panjang }} hari ({{ $project_name }})</td>
                                <td class="py-2">{{ number_format($total_gaji_lembur_panjang, 0, ',', '.') }}</td>
                                <td class="py-2"></td>
                            </tr>
                        @endif
                        @if($total_performa > 0)
                            <tr class="detail-area{{ $emp_id }}" style="display: none; background-color: @if($iterasus % 2 == 0) #E0E0E0 @else white @endif;">
                                <td class="py-2"></td>
                                <td class="py-2" colspan="4">Performa ({{ $project_name }})</td>
                                <td class="py-2">{{ number_format($total_performa, 0, ',', '.') }}</td>
                                <td class="py-2"></td>
                            </tr>
                        @endif
                    @endforeach

                    @foreach($kasubon as $ppay)
                        <tr class="detail-area{{ $emp_id }}" style="display: none; background-color: @if($iterasus % 2 == 0) #E0E0E0 @else white @endif;">
                            <td class="py-2"></td>
                            <td class="py-2" colspan="4">Potongan kasbon @if($ppay->remark)({{ $ppay->remark }})@endif</td>
                            <td class="py-2">-{{ number_format($ppay->amount, 0, ',', '.') }}</td>
                            <td class="py-2"></td>
                        </tr>
                    @endforeach
                @endforeach

                {{-- @foreach ($attendances as $a)
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
                        <td>
                            <div class="d-flex gap-2 w-100">
                                <a href="{{ route('salary-edit', $a->id) }}" class="btn btn-warning text-white"
                                    style="font-size: 10pt; background-color: rgb(197, 167, 0);">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </td>

                    </tr>
                @endforeach --}}
            </table>
        </div>
    </x-container>

    <script>
        $(document).ready(() => {
            $('.see-detail-btn').on('click', function(){
                $(this).closest('tbody').find(`.detail-area${$(this).data('empid')}`).toggle();
            });

            $("form").on("submit", function(e){
                e.preventDefault();

                $(this).append($("<input>").attr({"type": "hidden", "name": "from", "value": $("#filter-start-date").val()}));
                $(this).append($("<input>").attr({"type": "hidden", "name": "until", "value": $("#filter-end-date").val()}));

                this.submit();
            });
        });

    </script>

@endsection
