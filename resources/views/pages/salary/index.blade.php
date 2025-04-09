@extends("layouts.main-admin")

@section("content")
    <x-container>
        <br>

        <div class="d-flex w-100 justify-content-between align-items-center">
            <h3>Gaji Pegawai</h3>
            <div class="position-relative d-flex flex-column align-items-end">
                <button class="btn btn-secondary" type="button" id="dd-toggler">
                    <i class="bi bi-file-earmark-arrow-up"></i> Export
                </button>
                <div class="bg-white rounded-lg position-absolute z-2 border border-1" id="dd-menu" style="display: none; top: 40px;">
                    <form action="{{ route('salary-export') }}" method="post" target="_blank">
                        @csrf
                        <input type="hidden" name="from" value="{{ request('from') }}">
                        <input type="hidden" name="until" value="{{ request('until') }}">
                        <input type="hidden" name="employee" value="{{ request('employee') }}">
                        <button type="submit" class="dropdown-item border border-1 py-2 px-3">Export (PDF)</button>
                    </form>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(() => {
                $("#dd-toggler").click(function(){
                    $("#dd-menu").toggle();
                });
            });
        </script>
        <hr>

        @if (session()->has('successEditSalary'))
            <p class="text-success fw-bold">{{ session('successEditSalary') }}</p>
        @elseif (session()->has('successAutoGenerateSalary'))
            <p class="text-success fw-bold">{{ session('successAutoGenerateSalary') }}</p>
        @endif

        <div class="d-flex justify-content-between align-items-end w-100">
            <form action="{{ route('salary-index') }}" class="d-flex align-items-end gap-2">
                <div class="d-flex flex-column">
                    <label for="">Filter Tanggal</label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="date" class="form-control" name="from" value="{{ request('from') }}">
                        <div class="">s/d</div>
                        <input type="date" class="form-control" name="until" value="{{ request('until') }}">
                    </div>
                </div>
                <div class="d-flex flex-column ms-3">
                    <label for="">Filter Karyawan</label>
                    <input type="text" class="form-control" name="employee" placeholder="Nama karyawan" value="{{ request('employee') }}">
                </div>
                <button type="submit" class="btn btn-primary ms-2"><i class="bi bi-search"></i></button>
            </form>
            <div class="d-flex justify-content-end">
                Memperlihatkan {{ $grouped_attendances->firstItem() }} - {{ $grouped_attendances->lastItem()  }} dari {{ $grouped_attendances->total() }} item
            </div>
        </div>

        <div class="overflow-x-auto mt-3">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary">No</th>
                    <th class="border border-1 border-secondary">Tanggal</th>
                    <th class="border border-1 border-secondary">Nama</th>
                    <th class="border border-1 border-secondary">Jabatan</th>
                    <th class="border border-1 border-secondary">Total</th>
                    <th class="border border-1 border-secondary">Parsial</th>
                    <th class="border border-1 border-secondary">Aksi</th>
                </tr>

                @php
                    $iterasus = 0;
                @endphp

                @foreach($grouped_attendances as $emp_id => $attendances)
                    @php
                        $employee = App\Models\Employee::find(intval($emp_id));
                        $kasubon = $employee->prepays()->pluck('id')->toArray();
                        $prepay_cuts = App\Models\PrepayCut::whereIn('prepay_id', $kasubon)->where('start_period', '>=', request('from'))->where('end_period', '<=', request('until'))->get();

                        $total_kasbon = 0;
                        foreach($prepay_cuts as $ppay_cut){
                            $total_kasbon += $ppay_cut->cut_amount;
                        }

                        $subtotals[$emp_id] -= $total_kasbon;
                    @endphp

                    <tr style="background-color: @if($iterasus % 2 == 1) #E0E0E0 @else white @endif;">
                        <td class="border border-1 border-secondary">{{ ($loop->index + 1) + ((request('page') ?? 1) - 1) * 30 }}</td>
                        <td class="border border-1 border-secondary">{{ Carbon\Carbon::parse($start_period)->translatedFormat("d/m/Y") }} - {{ Carbon\Carbon::parse($end_period)->translatedFormat("d/m/Y") }}</td>
                        <td class="border border-1 border-secondary">{{ $employee->nama }}</td>
                        <td class="border border-1 border-secondary">{{ $employee->jabatan }}</td>
                        <td class="border border-1 border-secondary">{{ number_format($subtotals[$emp_id], 0, ',', '.') }}</td>
                        <td class="border border-1 border-secondary"></td>
                        <td class="border border-1 border-secondary">
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
                                $total_gaji_lembur_panjang += $atd->index_lembur_panjang * $atd->employee->lembur_panjang;
                                $total_performa += $atd->performa;
                            @endphp
                        @endforeach

                        @if($total_jam_normal != 0)
                            <tr class="detail-area{{ $emp_id }}" style="display: none; background-color: @if($iterasus % 2 == 1) #E0E0E0 @else white @endif;">
                                <td class="border border-1 border-secondary" class="py-2"></td>
                                <td class="border border-1 border-secondary" class="py-2" colspan="4">Normal: {{ $total_jam_normal }} jam ({{ $project_name }})</td>
                                <td class="border border-1 border-secondary" class="py-2">{{ number_format($total_gaji_normal, 0, ',', '.') }}</td>
                                <td class="border border-1 border-secondary" class="py-2"></td>
                            </tr>
                        @endif

                        @if($total_jam_lembur > 0)
                            <tr class="detail-area{{ $emp_id }}" style="display: none; background-color: @if($iterasus % 2 == 1) #E0E0E0 @else white @endif;">
                                <td class="border border-1 border-secondary" class="py-2"></td>
                                <td class="border border-1 border-secondary" class="py-2" colspan="4">Lembur: {{ $total_jam_lembur }} jam ({{ $project_name }})</td>
                                <td class="border border-1 border-secondary" class="py-2">{{ number_format($total_gaji_lembur, 0, ',', '.') }}</td>
                                <td class="border border-1 border-secondary" class="py-2"></td>
                            </tr>
                        @endif

                        @if($total_kali_lembur_panjang > 0)
                            <tr class="detail-area{{ $emp_id }}" style="display: none; background-color: @if($iterasus % 2 == 1) #E0E0E0 @else white @endif;">
                                <td class="border border-1 border-secondary" class="py-2"></td>
                                <td class="border border-1 border-secondary" class="py-2" colspan="4">Lembur Panjang: {{ $total_kali_lembur_panjang }} hari ({{ $project_name }})</td>
                                <td class="border border-1 border-secondary" class="py-2">{{ number_format($total_gaji_lembur_panjang, 0, ',', '.') }}</td>
                                <td class="border border-1 border-secondary" class="py-2"></td>
                            </tr>
                        @endif

                        @if($total_performa > 0)
                            <tr class="detail-area{{ $emp_id }}" style="display: none; background-color: @if($iterasus % 2 == 1) #E0E0E0 @else white @endif;">
                                <td class="border border-1 border-secondary" class="py-2"></td>
                                <td class="border border-1 border-secondary" class="py-2" colspan="4">Performa ({{ $project_name }})</td>
                                <td class="border border-1 border-secondary" class="py-2">{{ number_format($total_performa, 0, ',', '.') }}</td>
                                <td class="border border-1 border-secondary" class="py-2"></td>
                            </tr>
                        @endif
                    @endforeach

                    @foreach($prepay_cuts as $ppc)
                        <tr class="detail-area{{ $emp_id }}" style="display: none; background-color: @if($iterasus % 2 == 1) #E0E0E0 @else white @endif;">
                            <td class="border border-1 border-secondary" class="py-2"></td>
                            <td class="border border-1 border-secondary" class="py-2" colspan="4">Potongan kasbon untuk {{ $ppc->prepay->remark }} (Sisa saldo: {{ number_format($ppc->remaining_amount, 0, ',', '.') }})</td>
                            <td class="border border-1 border-secondary" class="py-2">- {{ number_format($ppc->cut_amount, 0, ',', '.') }}</td>
                            <td class="border border-1 border-secondary" class="py-2"></td>
                        </tr>
                    @endforeach

                    @php
                        $iterasus++;
                    @endphp
                @endforeach
            </table>
        </div>

        <div class="mt-4">
            {{ $grouped_attendances->links() }}
        </div>
    </x-container>

    <script>
        $(document).ready(() => {
            $('.see-detail-btn').on('click', function(){
                $(this).closest('tbody').find(`.detail-area${$(this).data('empid')}`).toggle();
            });
        });

    </script>

@endsection
