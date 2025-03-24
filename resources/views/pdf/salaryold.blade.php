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

    <body>
        <h3>Gaji Pegawai</h3>
        <hr>
        <br>

        {{-- tabel list data--}}
        <table style="width: 100%; border: 1px solid black;">
            <tr>
                <th class="border border-1 border-secondary">No</th>
                <th class="border border-1 border-secondary">Periode</th>
                <th class="border border-1 border-secondary">Nama</th>
                <th class="border border-1 border-secondary">Jabatan</th>
                <th class="border border-1 border-secondary">Total</th>
                <th class="border border-1 border-secondary">Ket.</th>
            </tr>

            @foreach($salaries as $i => $s)
                @php
                    $total_all_project = 0;
                    $grouped = [];

                    foreach ($s->employee->attendances->where('attendance_date', '>=', $s->start_period)->where('attendance_date', '<=', $s->end_period) as $atd) {
                        $projectName = $atd->project->project_name ?? 'Unknown';
                        if (!isset($grouped[$projectName])) {
                            $grouped[$projectName] = [
                                'vals' => ['normal' => 0, 'lembur' => 0, 'lembur_panjang' => 0, 'performa' => 0],
                                'tots' => ['normal' => 0, 'lembur' => 0, 'lembur_panjang' => 0],
                            ];
                        }

                        $grouped[$projectName]['vals']['normal'] += $atd->normal;
                        $grouped[$projectName]['vals']['lembur'] += $atd->jam_lembur;
                        $grouped[$projectName]['vals']['lembur_panjang'] += $atd->index_lembur_panjang;
                        $grouped[$projectName]['vals']['performa'] += $atd->performa;

                        $grouped[$projectName]['tots']['normal'] += ($atd->normal * $s->employee->pokok);
                        $grouped[$projectName]['tots']['lembur'] += ($atd->jam_lembur * $s->employee->lembur);
                        $grouped[$projectName]['tots']['lembur_panjang'] += ($atd->index_lembur_panjang * $s->employee->lembur_panjang);

                        // Add individual attendance contribution to total
                        $attendance_total = ($atd->normal * $s->employee->pokok) +
                                            ($atd->jam_lembur * $s->employee->lembur) +
                                            ($atd->index_lembur_panjang * $s->employee->lembur_panjang) +
                                            $atd->performa;

                        $total_all_project += $attendance_total;
                    }

                    // Kasbon
                    $kasubon = $s->employee->prepays->where('start_period', '>=', $s->start_period)->where('end_period', '<=', $s->end_period);

                    $total_kasbon = 0;
                    foreach($kasubon as $kb){
                        $total_kasbon += $kb->amount;
                    }

                    $total_all_project -= $total_kasbon;
                @endphp

                <tr>
                    <td class="border border-1 border-secondary" style="background-color: yellow;">{{ $loop->iteration }}</td>
                    <td class="border border-1 border-secondary">{{ $s->start_period ? Carbon\Carbon::parse($s->start_period)->translatedFormat("d/m/Y") : "N/A" }} - {{ $s->end_period ? Carbon\Carbon::parse($s->end_period)->translatedFormat("d/m/Y") : "N/A" }}</td>
                    <td class="border border-1 border-secondary">{{ $s->employee->nama }}</td>
                    <td class="border border-1 border-secondary">{{ $s->employee->jabatan }}</td>
                    <td class="border border-1 border-secondary" style="background-color: yellow;">{{ number_format($total_all_project, 2, ',', '.') }}</td>
                    <td class="border border-1 border-secondary"></td>
                </tr>

                @foreach($grouped as $project_name => $grp)
                    @if($grp['vals']['normal'] != 0)
                        <tr>
                            <td class="border border-1 border-secondary" colspan="5">Normal: {{ $grp['vals']['normal'] }} jam ({{ $project_name }})</td>
                            <td class="border border-1 border-secondary">{{ number_format($grp['tots']['normal'], 2, ',', '.') }}</td>
                        </tr>
                    @endif
                    @if($grp['vals']['lembur'] > 0)
                        <tr>
                            <td class="border border-1 border-secondary" colspan="5">Lembur: {{ $grp['vals']['lembur'] }} jam ({{ $project_name }})</td>
                            <td class="border border-1 border-secondary">{{ number_format($grp['tots']['lembur'], 2, ',', '.') }}</td>
                        </tr>
                    @endif
                    @if($grp['vals']['lembur_panjang'] > 0)
                        <tr>
                            <td class="border border-1 border-secondary" colspan="5">Lembur Panjang: {{ $grp['vals']['lembur_panjang'] }} hari ({{ $project_name }})</td>
                            <td class="border border-1 border-secondary">{{ number_format($grp['tots']['lembur_panjang'], 2, ',', '.') }}</td>
                        </tr>
                    @endif
                    @if($grp['vals']['performa'] > 0)
                        <tr>
                            <td class="border border-1 border-secondary" colspan="5">Performa ({{ $project_name }})</td>
                            <td class="border border-1 border-secondary">{{ number_format($grp['vals']['performa'], 2, ',', '.') }}</td>
                        </tr>
                    @endif
                @endforeach

                @foreach($kasubon as $ppay)
                    <tr>
                        <td class="border border-1 border-secondary" colspan="5">Potongan kasbon @if($ppay->remark)({{ $ppay->remark }})@endif</td>
                        <td class="border border-1 border-secondary">-{{ number_format($ppay->amount, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endforeach
        </table>
    </body>
</html>

