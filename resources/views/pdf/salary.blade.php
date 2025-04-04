<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>

        <style>
            table {
                border-collapse: collapse;
                width: 100%;
            }
            th, td {
                border: 1px solid black;
                padding: 8px;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
            }
        </style>
    </head>

    {{-- tabel list data--}}
    <body>
        <h3>Gaji Pegawai</h3>
        <hr>
        <br>

        <table style="width: 100%; border: 1px solid black;">
            <tr>
                <th class="border border-1 border-secondary">No</th>
                <th class="border border-1 border-secondary">Periode</th>
                <th class="border border-1 border-secondary">Nama</th>
                <th class="border border-1 border-secondary">Jabatan</th>
                <th class="border border-1 border-secondary">Total</th>
                <th class="border border-1 border-secondary">Ket.</th>
            </tr>

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

                <tr>
                    <td class="border border-1 border-secondary" style="background-color: yellow;">{{ $loop->iteration }}</td>
                    <td class="border border-1 border-secondary">{{ Carbon\Carbon::parse($start_period)->translatedFormat("d/m/Y") }} - {{ Carbon\Carbon::parse($end_period)->translatedFormat("d/m/Y") }}</td>
                    <td class="border border-1 border-secondary">{{ $employee->nama }}</td>
                    <td class="border border-1 border-secondary">{{ $employee->jabatan }}</td>
                    <td class="border border-1 border-secondary" style="background-color: yellow;">{{ number_format($subtotals[$emp_id], 2, ',', '.') }}</td>
                    <td class="border border-1 border-secondary"></td>
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
                        <tr>
                            <td class="border border-1 border-secondary" colspan="5">Normal: {{ $total_jam_normal }} jam ({{ $project_name }})</td>
                            <td class="border border-1 border-secondary">{{ number_format($total_gaji_normal, 2, ',', '.') }}</td>
                        </tr>
                    @endif
                    @if($total_jam_lembur > 0)
                        <tr>
                            <td class="border border-1 border-secondary" colspan="5">Lembur: {{ $total_jam_lembur }} jam ({{ $project_name }})</td>
                            <td class="border border-1 border-secondary">{{ number_format($total_gaji_lembur, 2, ',', '.') }}</td>
                        </tr>
                    @endif
                    @if($total_kali_lembur_panjang > 0)
                        <tr>
                            <td class="border border-1 border-secondary" colspan="5">Lembur Panjang: {{ $total_kali_lembur_panjang }} hari ({{ $project_name }})</td>
                            <td class="border border-1 border-secondary">{{ number_format($total_gaji_lembur_panjang, 2, ',', '.') }}</td>
                        </tr>
                    @endif
                    @if($total_performa > 0)
                        <tr>
                            <td class="border border-1 border-secondary" colspan="5">Performa ({{ $project_name }})</td>
                            <td class="border border-1 border-secondary">{{ number_format($total_performa, 2, ',', '.') }}</td>
                        </tr>
                    @endif
                @endforeach

                @foreach($prepay_cuts as $ppc)
                    <tr>
                        <td class="border border-1 border-secondary" class="py-2" colspan="5">Potongan kasbon untuk {{ $ppc->prepay->remark }} (Sisa saldo: {{ number_format($ppc->remaining_amount, 2, ',', '.') }})</td>
                        <td class="border border-1 border-secondary" class="py-2">- {{ number_format($ppc->cut_amount, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endforeach
        </table>
    </body>
</html>

