@extends('layouts.main-admin')

@section('content')
    <x-container-middle>
        <div class="container bg-white rounded-4 py-4 px-5 border border-1 card mt-4">
            <h3>Laporan Presensi Baru</h3>
            <h6 class="fw-normal">Untuk proyek: {{ $project->project_name }}</h6>

            <form method="POST" action="{{ route('attendance-store-admin') }}">
                @csrf

                <input type="hidden" name="project" value="{{ $project->id }}">

                @php
                    $previousSaturday = Carbon\Carbon::now()->subWeeks(1)->previous(Carbon\Carbon::SATURDAY)->toDateString();
                    $previousFriday = Carbon\Carbon::now()->previous(Carbon\Carbon::FRIDAY)->toDateString();
                @endphp

                <div class="mt-4">Hari Masuk Kerja di Minggu Ini</div>
                <div class="d-flex flex-column">
                    <label for="setdayoff0" class="d-flex gap-2 align-items-center">
                        <input type="checkbox" class="setdayoffcb" id="setdayoff0" data-target="off-sat" checked> Sabtu
                    </label>
                    <label for="setdayoff1" class="d-flex gap-2 align-items-center">
                        <input type="checkbox" class="setdayoffcb" id="setdayoff1" data-target="off-sun" checked> Minggu
                    </label>
                    <label for="setdayoff2" class="d-flex gap-2 align-items-center">
                        <input type="checkbox" class="setdayoffcb" id="setdayoff2" data-target="off-mon" checked> Senin
                    </label>
                    <label for="setdayoff3" class="d-flex gap-2 align-items-center">
                        <input type="checkbox" class="setdayoffcb" id="setdayoff3" data-target="off-tue" checked> Selasa
                    </label>
                    <label for="setdayoff4" class="d-flex gap-2 align-items-center">
                        <input type="checkbox" class="setdayoffcb" id="setdayoff4" data-target="off-wed" checked> Rabu
                    </label>
                    <label for="setdayoff5" class="d-flex gap-2 align-items-center">
                        <input type="checkbox" class="setdayoffcb" id="setdayoff5" data-target="off-thu" checked> Kamis
                    </label>
                    <label for="setdayoff6" class="d-flex gap-2 align-items-center">
                        <input type="checkbox" class="setdayoffcb" id="setdayoff6" data-target="off-fri" checked> Jumat
                    </label>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <div class="w-50 d-flex flex-column">
                        <label for="start_date">Tanggal Awal Absensi</label>
                        <input type="date" class="form-control w-100 mt-1 @error('start_date') is-invalid @enderror" name="start_date" id="start_date" value="{{ old('start_date', $previousSaturday) }}">
                        @error('start_date')
                            <p class="text-danger">Harap pilih tanggal awal absensi.</p>
                        @enderror
                    </div>

                    <div class="w-50 d-flex flex-column">
                        <label for="end_date">Tanggal Akhir Absensi</label>
                        <input type="date" class="form-control w-100 mt-1 @error('end_date') is-invalid @enderror" name="end_date" id="end_date" value="{{ old('end_date', $previousFriday) }}">
                        @error('end_date')
                            <p class="text-danger">Harap pilih tanggal awal absensi.</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">Data Absensi</div>
                <div class="overflow-x-auto w-100 mt-2">
                    <table class="w-100">
                        <thead>
                            <tr>
                                <th rowspan="2">Pegawai</th>
                                <th rowspan="2">Kasbon</th>
                                <th colspan="7" class="text-center">Jam Kerja</th>
                                <th rowspan="2" class="text-center"></th>
                            </tr>
                            <tr>
                                <th class="text-center">Sabtu</th>
                                <th class="text-center">Minggu</th>
                                <th class="text-center">Senin</th>
                                <th class="text-center">Selasa</th>
                                <th class="text-center">Rabu</th>
                                <th class="text-center">Kamis</th>
                                <th class="text-center">Jumat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($project->employees as $e)
                                <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                                    <td>
                                        {{ $e->nama }}<br>({{ $e->jabatan }})
                                        <input type="hidden" name="employee[]" value="{{ $e->id }}">
                                    </td>
                                    <td style="width: 110px;">
                                        <input type="text" name="kasbon[{{ $e->id }}]" placeholder="Kasbon" class="w-100">
                                    </td>
                                    <td style="width: 110px;">
                                        <div class="d-flex flex-column gap-2 py-2 w-100">
                                            <input type="time" name="start_time[{{ $e->id }}][]" class="w-100" value="08:00">
                                            <input type="time" name="end_time[{{ $e->id }}][]" class="endtime-weekend w-100" value="16:00">
                                            <input type="text" name="performa[{{ $e->id }}][]" placeholder="Performa" class="w-100 performance">
                                            <label for="offcb{{ $e->id }}sat" class="d-flex gap-2 align-items-center w-100">
                                                <input type="checkbox" class="off-checkbox off-sat" id="offcb{{ $e->id }}sat"> Off
                                            </label>
                                        </div>
                                    </td>
                                    <td style="width: 110px;">
                                        <div class="d-flex flex-column gap-2 py-2 w-100">
                                            <input type="time" name="start_time[{{ $e->id }}][]" class="w-100" value="08:00">
                                            <input type="time" name="end_time[{{ $e->id }}][]" class="endtime-weekend w-100" value="16:00">
                                            <input type="text" name="performa[{{ $e->id }}][]" placeholder="Performa" class="w-100 performance">
                                            <label for="offcb{{ $e->id }}sun" class="d-flex gap-2 align-items-center w-100">
                                                <input type="checkbox" class="off-checkbox off-sun" id="offcb{{ $e->id }}sun"> Off
                                            </label>
                                        </div>
                                    </td>
                                    <td style="width: 110px;">
                                        <div class="d-flex flex-column gap-2 py-2 w-100">
                                            <input type="time" name="start_time[{{ $e->id }}][]" class="w-100" value="08:00">
                                            <input type="time" name="end_time[{{ $e->id }}][]" class="w-100" value="17:00">
                                            <input type="text" name="performa[{{ $e->id }}][]" placeholder="Performa" class="w-100 performance">
                                            <label for="offcb{{ $e->id }}mon" class="d-flex gap-2 align-items-center w-100">
                                                <input type="checkbox" class="off-checkbox off-mon" id="offcb{{ $e->id }}mon"> Off
                                            </label>
                                        </div>
                                    </td>
                                    <td style="width: 110px;">
                                        <div class="d-flex flex-column gap-2 py-2 w-100">
                                            <input type="time" name="start_time[{{ $e->id }}][]" class="w-100" value="08:00">
                                            <input type="time" name="end_time[{{ $e->id }}][]" class="w-100" value="17:00">
                                            <input type="text" name="performa[{{ $e->id }}][]" placeholder="Performa" class="w-100 performance">
                                            <label for="offcb{{ $e->id }}tue" class="d-flex gap-2 align-items-center w-100">
                                                <input type="checkbox" class="off-checkbox off-tue" id="offcb{{ $e->id }}tue"> Off
                                            </label>
                                        </div>
                                    </td>
                                    <td style="width: 110px;">
                                        <div class="d-flex flex-column gap-2 py-2 w-100">
                                            <input type="time" name="start_time[{{ $e->id }}][]" class="w-100" value="08:00">
                                            <input type="time" name="end_time[{{ $e->id }}][]" class="w-100" value="17:00">
                                            <input type="text" name="performa[{{ $e->id }}][]" placeholder="Performa" class="w-100 performance">
                                            <label for="offcb{{ $e->id }}wed" class="d-flex gap-2 align-items-center w-100">
                                                <input type="checkbox" class="off-checkbox off-wed" id="offcb{{ $e->id }}wed"> Off
                                            </label>
                                        </div>
                                    </td>
                                    <td style="width: 110px;">
                                        <div class="d-flex flex-column gap-2 py-2 w-100">
                                            <input type="time" name="start_time[{{ $e->id }}][]" class="w-100" value="08:00">
                                            <input type="time" name="end_time[{{ $e->id }}][]" class="w-100" value="17:00">
                                            <input type="text" name="performa[{{ $e->id }}][]" placeholder="Performa" class="w-100 performance">
                                            <label for="offcb{{ $e->id }}thu" class="d-flex gap-2 align-items-center w-100">
                                                <input type="checkbox" class="off-checkbox off-thu" id="offcb{{ $e->id }}thu"> Off
                                            </label>
                                        </div>
                                    </td>
                                    <td style="width: 110px;">
                                        <div class="d-flex flex-column gap-2 py-2 w-100">
                                            <input type="time" name="start_time[{{ $e->id }}][]" class="w-100" value="08:00">
                                            <input type="time" name="end_time[{{ $e->id }}][]" class="w-100" value="17:00">
                                            <input type="text" name="performa[{{ $e->id }}][]" placeholder="Performa" class="w-100 performance">
                                            <label for="offcb{{ $e->id }}fri" class="d-flex gap-2 align-items-center w-100">
                                                <input type="checkbox" class="off-checkbox off-fri" id="offcb{{ $e->id }}fri"> Off
                                            </label>
                                        </div>
                                    </td>
                                    <td style="width: 50px;">
                                        <div class="d-flex gap-2 w-100 justify-content-center">
                                            <button type="button" class="exclude-btn btn btn-danger" onclick="return confirm('Apakah anda yakin ingin menghapus pegawai ini dari daftar presensi?')"><i class="bi bi-trash3"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="w-100 d-flex flex-column mt-4">
                    <label for="remark">Remark</label>
                    <input type="text" class="form-control w-100 mt-1" name="remark" id="remark">
                </div>

                <button type="submit" class="btn btn-success mt-4">Unggah Data Presensi</button>
            </form>
        </div>
    </x-container-middle>

    <script>
        function toggleInputTime(targetted) {
            if ($(targetted).is(':checked')) {
                $(targetted).closest('td').find('input[type="time"], .performance').val('').hide();
            } else {
                const timeInputs = $(targetted).closest('td').find('input[type="time"]');

                timeInputs.eq(0).val('08:00');
                if (timeInputs.eq(1).hasClass('endtime-weekend')) {
                    timeInputs.eq(1).val('16:00');
                } else {
                    timeInputs.eq(1).val('17:00');
                }

                timeInputs.show();
                $(targetted).closest('td').find('.performance').show();
            }
        }

        $(document).ready(() => {
            // Toggling the target .off-checkbox when .setdayoffcb changes
            $('.setdayoffcb').on('change', function() {
                const target = $(`.${$(this).data('target')}`);

                if ($(this).is(':checked')) {
                    target.each(function(){
                        $(this).prop('checked', false);
                        toggleInputTime($(this));
                    });

                } else {
                    target.each(function(){
                        $(this).prop('checked', true);
                        toggleInputTime($(this));
                    });
                }
            });

            // Directly applying the toggleInputTime logic on .off-checkbox changes
            $('.off-checkbox').on('change', function() {
                toggleInputTime($(this));
            });

            $('.exclude-btn').click(function(){
                $(this).closest("tr").remove();
            });
        });

    </script>
@endsection
