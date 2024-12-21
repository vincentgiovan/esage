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
        {{-- <h1>Gaji Pegawai</h1>
        <hr>
        <br> --}}

        <!-- tabel list data-->
        <table style="width: 100%; border: 1px solid black;">
            <tr>
                <th>No</th>
                <th>Periode</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Total</th>
                <th>Ket.</th>
            </tr>

            @foreach($salaries as $s)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $s->start_period ? Carbon\Carbon::parse($s->start_period)->translatedFormat("d/m/Y") : "N/A" }} - {{ $s->end_period ? Carbon\Carbon::parse($s->end_period)->translatedFormat("d/m/Y") : "N/A" }}</td>
                    <td>{{ $s->employee->nama }}</td>
                    <td>{{ $s->employee->jabatan }}</td>
                    <td>{{ number_format($s->total, 2, ',', '.') }}</td>
                    <td></td>
                </tr>

                @php
                    $grouped = [];

                    foreach ($s->employee->attendances as $atd) {
                        $iae = false;

                        // Iterate over grouped by reference to allow modification
                        foreach ($grouped as &$g) {
                            if ($atd->project->project_name == $g['project']) {
                                $iae = true;

                                // Update values in the existing group
                                $g['vals']['normal'] += $atd->normal;
                                $g['vals']['lembur'] += $atd->jam_lembur;
                                $g['vals']['lembur_panjang'] += $atd->index_lembur_panjang;
                                $g['vals']['performa'] += $atd->performa;

                                $g['tots']['normal'] += $atd->normal * $atd->employee->pokok;
                                $g['tots']['lembur'] += $atd->jam_lembur * $atd->employee->lembur;
                                $g['tots']['lembur_panjang'] += $atd->index_lembur_panjang * $atd->employee->lembur_panjang;

                                break; // Exit the loop since we found the matching project
                            }
                        }
                        unset($g); // Unset reference to prevent unexpected behavior outside the loop

                        // If no existing group found, create a new group
                        if (!$iae) {
                            $val = [
                                'normal' => $atd->normal,
                                'lembur' => $atd->jam_lembur,
                                'lembur_panjang' => $atd->index_lembur_panjang,
                                'performa' => $atd->performa,
                            ];

                            $tot = [
                                'normal' => $atd->normal * $atd->employee->pokok,
                                'lembur' => $atd->jam_lembur * $atd->employee->lembur,
                                'lembur_panjang' => $atd->index_lembur_panjang * $atd->employee->lembur_panjang,
                            ];

                            $gv = [
                                "project" => $atd->project->project_name,
                                'vals' => $val,
                                'tots' => $tot,
                            ];

                            $grouped[] = $gv; // Add the new group to the grouped array
                        }
                    }
                @endphp

                @foreach($grouped as $grp)
                    <tr>
                        <td colspan="5">Normal: {{ $grp['vals']['normal'] }} jam ({{ $grp['project'] }})</td>
                        <td>{{ number_format($grp['tots']['normal'], 2, ',', '.') }}</td>
                    </tr>
                    @if($grp['vals']['lembur'] > 0)
                        <tr>
                            <td colspan="5">Lembur: {{ $grp['vals']['lembur'] }} jam ({{ $grp['project'] }})</td>
                            <td>{{ number_format($grp['tots']['lembur'], 2, ',', '.') }}</td>
                        </tr>
                    @endif
                    @if($grp['vals']['lembur_panjang'] > 0)
                        <tr>
                            <td colspan="5">Lembur Panjang: {{ $grp['vals']['lembur_panjang'] }} hari ({{ $grp['project'] }})</td>
                            <td>{{ number_format($grp['tots']['lembur_panjang'], 2, ',', '.') }}</td>
                        </tr>
                    @endif
                    @if($grp['vals']['performa'] > 0)
                        <tr>
                            <td colspan="5">Performa ({{ $atd->project->project_name }})</td>
                            <td>{{ number_format($grp['vals']['performa'], 2, ',', '.') }}</td>
                        </tr>
                    @endif
                @endforeach
            @endforeach
        </table>
    </body>
</html>

