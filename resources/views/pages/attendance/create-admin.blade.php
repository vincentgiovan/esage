@extends('layouts.main-admin')

@section('content')
    <x-container-middle>
        <div class="container bg-white rounded-4 py-4 px-5 border border-1 card mt-4">
            <h3>Laporan Presensi Baru</h3>
            <h6 class="fw-normal">Untuk proyek: {{ $project->project_name }}</h6>

            <form method="POST" action="{{ route('attendance-store-admin') }}">
                @csrf

                <input type="hidden" name="project_id" value="{{ $project->id }}">

                @php
                    $previousFriday = Carbon\Carbon::now()->previous(Carbon\Carbon::FRIDAY);
                    $previousSaturday = $previousFriday->copy()->previous(Carbon\Carbon::SATURDAY);

                    $sunday = $previousSaturday->copy()->addDay();
                    $monday = $previousFriday->copy()->addDay();
                    $tuesday = $monday->copy()->addDay();
                    $wednesday = $tuesday->copy()->addDay();
                    $thursday = $wednesday->copy()->addDay();
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
                        <label for="start_date">Tanggal Awal Absensi<span class="text-danger">*</span></label>
                        <input type="date" class="form-control w-100 mt-1 @error('start_date') is-invalid @enderror" name="start_date" id="start_date" value="{{ old('start_date', $previousSaturday->format('Y-m-d')) }}">
                        <p class="text-danger invalid-feedback">Harap pilih tanggal awal absensi, pastikan tanggal awal kurang dari tanggal akhir.</p>
                    </div>

                    <div class="w-50 d-flex flex-column">
                        <label for="end_date">Tanggal Akhir Absensi<span class="text-danger">*</span></label>
                        <input type="date" class="form-control w-100 mt-1 @error('end_date') is-invalid @enderror" name="end_date" id="end_date" value="{{ old('end_date', $previousFriday->format('Y-m-d')) }}">
                        <p class="text-danger invalid-feedback">Harap pilih tanggal akhir absensi.</p>
                    </div>
                </div>

                <div class="mt-4">Data Absensi</div>
                <div class="overflow-auto w-100 mt-2" style="max-height: 70vh;" id="table-container">
                    <table class="w-100">
                        <thead class="position-sticky top-0 z-2">
                            <tr>
                                <th class="border border-1 border-secondary" rowspan="2">Pegawai</th>
                                <th class="border border-1 border-secondary" colspan="7">Jam Kerja</th>
                                <th class="border border-1 border-secondary" rowspan="2"></th>
                            </tr>
                            <tr>
                                <th class="border border-1 border-secondary">Sabtu<br><span id="date-sat">{{ $previousSaturday->format('d/m/Y') }}</span></th>
                                <th class="border border-1 border-secondary">Minggu<br><span id="date-sun">{{ $sunday->format('d/m/Y') }}</span></th>
                                <th class="border border-1 border-secondary">Senin<br><span id="date-mon">{{ $monday->format('d/m/Y') }}</span></th>
                                <th class="border border-1 border-secondary">Selasa<br><span id="date-tue">{{ $tuesday->format('d/m/Y') }}</span></th>
                                <th class="border border-1 border-secondary">Rabu<br><span id="date-wed">{{ $wednesday->format('d/m/Y') }}</span></th>
                                <th class="border border-1 border-secondary">Kamis<br><span id="date-thu">{{ $thursday->format('d/m/Y') }}</span></th>
                                <th class="border border-1 border-secondary">Jumat<br><span id="date-fri">{{ $previousFriday->format('d/m/Y') }}</span></th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td class="border border-1 border-secondary select-employee">
                                    <select name="employee[]" class="select2 form-select">
                                        @forelse($project->employees as $e)
                                            <option value="{{ $e->id }}">{{ $e->nama }} ({{ $e->jabatan }})</option>
                                        @empty
                                            <option disabled selected>Belum ada karyawan yang ditugaskan di proyek ini</option>
                                        @endforelse
                                    </select>
                                </td>
                                <td class="border border-1 border-secondary" style="width: 125px;">
                                    <div class="d-flex flex-column gap-2 py-2 w-100">
                                        <div class="input_area">
                                            <label>Normal<span class="text-danger">*</span></label>
                                            <input type="text" name="normal[0][]" class="w-100 normal" value="1">

                                            <label>Lembur<span class="text-danger">*</span></label>
                                            <input type="text" name="lembur[0][]" class="w-100 lembur" value="0">

                                            <label>L. Panjang<span class="text-danger">*</span></label>
                                            <input type="text" name="lembur_panjang[0][]" class="w-100 lembur_panjang" value="0">

                                            <label>Performa</label>
                                            <input type="text" name="performa[0][]" placeholder="Performa" class="w-100 performance">
                                        </div>

                                        <label for="offcbsat" class="mt-2 d-flex gap-2 align-items-center w-100">
                                            <input type="checkbox" class="off-checkbox off-sat" id="offcbsat"> Off
                                        </label>
                                        <label for="anoprojcbsat" class="d-flex gap-2 align-items-center w-100">
                                            <input type="checkbox" class="otherproj-checkbox" id="anoprojcbsat"> Proyek lain
                                        </label>
                                    </div>
                                </td>
                                <td class="border border-1 border-secondary" style="width: 125px;">
                                    <div class="d-flex flex-column gap-2 py-2 w-100">
                                        <div class="input_area">
                                            <label>Normal<span class="text-danger">*</span></label>
                                            <input type="text" name="normal[0][]" class="w-100 normal" value="1">

                                            <label>Lembur<span class="text-danger">*</span></label>
                                            <input type="text" name="lembur[0][]" class="w-100 lembur" value="0">

                                            <label>L. Panjang<span class="text-danger">*</span></label>
                                            <input type="text" name="lembur_panjang[0][]" class="w-100 lembur_panjang" value="0">

                                            <label>Performa</label>
                                            <input type="text" name="performa[0][]" placeholder="Performa" class="w-100 performance">
                                        </div>

                                        <label for="offcbsun" class="mt-2 d-flex gap-2 align-items-center w-100">
                                            <input type="checkbox" class="off-checkbox off-sun" id="offcbsun"> Off
                                        </label>
                                        <label for="anoprojcbsun" class="d-flex gap-2 align-items-center w-100">
                                            <input type="checkbox" class="otherproj-checkbox" id="anoprojcbsun"> Proyek lain
                                        </label>
                                    </div>
                                </td>
                                <td class="border border-1 border-secondary" style="width: 125px;">
                                    <div class="d-flex flex-column gap-2 py-2 w-100">
                                        <div class="input_area">
                                            <label>Normal<span class="text-danger">*</span></label>
                                            <input type="text" name="normal[0][]" class="w-100 normal" value="1">

                                            <label>Lembur<span class="text-danger">*</span></label>
                                            <input type="text" name="lembur[0][]" class="w-100 lembur" value="0">

                                            <label>L. Panjang<span class="text-danger">*</span></label>
                                            <input type="text" name="lembur_panjang[0][]" class="w-100 lembur_panjang" value="0">

                                            <label>Performa</label>
                                            <input type="text" name="performa[0][]" placeholder="Performa" class="w-100 performance">
                                        </div>

                                        <label for="offcbmon" class="mt-2 d-flex gap-2 align-items-center w-100">
                                            <input type="checkbox" class="off-checkbox off-mon" id="offcbmon"> Off
                                        </label>
                                        <label for="anoprojcbmon" class="d-flex gap-2 align-items-center w-100">
                                            <input type="checkbox" class="otherproj-checkbox" id="anoprojcbmon"> Proyek lain
                                        </label>
                                    </div>
                                </td>
                                <td class="border border-1 border-secondary" style="width: 125px;">
                                    <div class="d-flex flex-column gap-2 py-2 w-100">
                                        <div class="input_area">
                                            <label>Normal<span class="text-danger">*</span></label>
                                            <input type="text" name="normal[0][]" class="w-100 normal" value="1">

                                            <label>Lembur<span class="text-danger">*</span></label>
                                            <input type="text" name="lembur[0][]" class="w-100 lembur" value="0">

                                            <label>L. Panjang<span class="text-danger">*</span></label>
                                            <input type="text" name="lembur_panjang[0][]" class="w-100 lembur_panjang" value="0">

                                            <label>Performa</label>
                                            <input type="text" name="performa[0][]" placeholder="Performa" class="w-100 performance">
                                        </div>

                                        <label for="offcbtue" class="mt-2 d-flex gap-2 align-items-center w-100">
                                            <input type="checkbox" class="off-checkbox off-tue" id="offcbtue"> Off
                                        </label>
                                        <label for="anoprojcbtue" class="d-flex gap-2 align-items-center w-100">
                                            <input type="checkbox" class="otherproj-checkbox" id="anoprojcbtue"> Proyek lain
                                        </label>
                                    </div>
                                </td>
                                <td class="border border-1 border-secondary" style="width: 125px;">
                                    <div class="d-flex flex-column gap-2 py-2 w-100">
                                        <div class="input_area">
                                            <label>Normal<span class="text-danger">*</span></label>
                                            <input type="text" name="normal[0][]" class="w-100 normal" value="1">

                                            <label>Lembur<span class="text-danger">*</span></label>
                                            <input type="text" name="lembur[0][]" class="w-100 lembur" value="0">

                                            <label>L. Panjang<span class="text-danger">*</span></label>
                                            <input type="text" name="lembur_panjang[0][]" class="w-100 lembur_panjang" value="0">

                                            <label>Performa</label>
                                            <input type="text" name="performa[0][]" placeholder="Performa" class="w-100 performance">
                                        </div>

                                        <label for="offcbwed" class="mt-2 d-flex gap-2 align-items-center w-100">
                                            <input type="checkbox" class="off-checkbox off-wed" id="offcbwed"> Off
                                        </label>
                                        <label for="anoprojcbwed" class="d-flex gap-2 align-items-center w-100">
                                            <input type="checkbox" class="otherproj-checkbox" id="anoprojcbwed"> Proyek lain
                                        </label>
                                    </div>
                                </td>
                                <td class="border border-1 border-secondary" style="width: 125px;">
                                    <div class="d-flex flex-column gap-2 py-2 w-100">
                                        <div class="input_area">
                                            <label>Normal<span class="text-danger">*</span></label>
                                            <input type="text" name="normal[0][]" class="w-100 normal" value="1">

                                            <label>Lembur<span class="text-danger">*</span></label>
                                            <input type="text" name="lembur[0][]" class="w-100 lembur" value="0">

                                            <label>L. Panjang<span class="text-danger">*</span></label>
                                            <input type="text" name="lembur_panjang[0][]" class="w-100 lembur_panjang" value="0">

                                            <label>Performa</label>
                                            <input type="text" name="performa[0][]" placeholder="Performa" class="w-100 performance">
                                        </div>

                                        <label for="offcbthu" class="mt-2 d-flex gap-2 align-items-center w-100">
                                            <input type="checkbox" class="off-checkbox off-thu" id="offcbthu"> Off
                                        </label>
                                        <label for="anoprojcbthu" class="d-flex gap-2 align-items-center w-100">
                                            <input type="checkbox" class="otherproj-checkbox" id="anoprojcbthu"> Proyek lain
                                        </label>
                                    </div>
                                </td>
                                <td class="border border-1 border-secondary" style="width: 125px;">
                                    <div class="d-flex flex-column gap-2 py-2 w-100">
                                        <div class="input_area">
                                            <label>Normal<span class="text-danger">*</span></label>
                                            <input type="text" name="normal[0][]" class="w-100 normal" value="1">

                                            <label>Lembur<span class="text-danger">*</span></label>
                                            <input type="text" name="lembur[0][]" class="w-100 lembur" value="0">

                                            <label>L. Panjang<span class="text-danger">*</span></label>
                                            <input type="text" name="lembur_panjang[0][]" class="w-100 lembur_panjang" value="0">

                                            <label>Performa</label>
                                            <input type="text" name="performa[0][]" placeholder="Performa" class="w-100 performance">
                                        </div>

                                        <label for="offcbfri" class="mt-2 d-flex gap-2 align-items-center w-100">
                                            <input type="checkbox" class="off-checkbox off-fri" id="offcbfri"> Off
                                        </label>
                                        <label for="anoprojcbfri" class="d-flex gap-2 align-items-center w-100">
                                            <input type="checkbox" class="otherproj-checkbox" id="anoprojcbfri"> Proyek lain
                                        </label>
                                    </div>
                                </td>
                                <td class="border border-1 border-secondary" style="width: 50px;">
                                    <div class="d-flex gap-2 w-100 justify-content-center">
                                        <button type="button" class="exclude-btn btn btn-danger"><i class="bi bi-trash3"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 w-100 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="add-data-btn">Tambah Data</button>
                </div>

                <div class="w-100 d-flex flex-column mt-4">
                    <label for="remark">Remark</label>
                    <input type="text" class="form-control w-100 mt-1" name="remark" id="remark" placeholder="Masukkan keterangan">
                </div>

                <button type="button" class="btn btn-success mt-4" id="submit-attendance-btn">Unggah Data Presensi</button>
            </form>

            {{-- @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif --}}

        </div>
    </x-container-middle>

    <script>
        let incrementus = 0;

        function toggleInputTime(targetted) {
            if ($(targetted).is(':checked')) {
                $(targetted).closest('td').find('input').val('');
                $(targetted).closest('td').find('.input_area').hide();
            } else {
                $(targetted).closest('td').find('.normal').val(1);
                $(targetted).closest('td').find('.lembur').val(0);
                $(targetted).closest('td').find('.lembur_panjang').val(0);
                $(targetted).closest('td').find('.performa').val('');
                $(targetted).closest('td').find('.input_area').show();
            }
        }

        function setTHDate(startDate) {
            const baseDate = new Date(startDate);
            const dayIds = ['sat', 'sun', 'mon', 'tue', 'wed', 'thu', 'fri'];

            dayIds.forEach((id, index) => {
                const tempDate = new Date(baseDate);
                tempDate.setDate(baseDate.getDate() + index);

                // Format as dd/mm/yyyy
                const day = String(tempDate.getDate()).padStart(2, '0');
                const month = String(tempDate.getMonth() + 1).padStart(2, '0');
                const year = tempDate.getFullYear();

                const formattedDate = `${day}/${month}/${year}`;

                $(`#date-${id}`).text(formattedDate); // e.g., #date-sun
            });
        }

        const employees = @json($project->employees);

        $(document).ready(() => {
            $('#add-data-btn').on('click', function(){
                incrementus++;
                const newRow = $('tbody').find('tr').first().clone();

                newRow.find('input[name*="normal"]').each(function(){
                    const oldName = $(this).attr('name');
                    $(this).attr('name', oldName.replace(/\[(\d+)\]/, `[${incrementus}]`));
                });
                newRow.find('input[name*="lembur"]').each(function(){
                    const oldName = $(this).attr('name');
                    $(this).attr('name', oldName.replace(/\[(\d+)\]/, `[${incrementus}]`));
                });
                newRow.find('input[name*="lembur_panjang"]').each(function(){
                    const oldName = $(this).attr('name');
                    $(this).attr('name', oldName.replace(/\[(\d+)\]/, `[${incrementus}]`));
                });
                newRow.find('input[name*="performa"]').each(function(){
                    const oldName = $(this).attr('name');
                    $(this).attr('name', oldName.replace(/\[(\d+)\]/, `[${incrementus}]`));
                });

                newRow.find('input[type="checkbox"]').each(function(){
                    const newId = `${$(this).attr('id')}_${incrementus}`;
                    $(this).attr('id', newId);
                    $(this).parent().attr('for', newId);
                });

                newRow.find('.select-employee').html('');
                const newSelect = $('<select>').addClass('select2 from-select').attr('name', 'employee[]');
                employees.forEach(employee => {
                    newSelect.append($('<option>').attr('value', employee.id).text(`${employee.nama} (${employee.jabatan})`));
                });
                newRow.find('.select-employee').append(newSelect);

                $('tbody').append(newRow);

                reinitializeselect2();

                const container = $('#table-container');
                container.scrollTop(container[0].scrollHeight);
            });

            // Toggling the target .off-checkbox when .setdayoffcb changes
            $(document).on('change', '.setdayoffcb', function() {
                const target = $(`.${$(this).data('target')}`);
                if(target.closest('div').find('.otherproj-checkbox').is(':checked')){
                    target.closest('div').find('.otherproj-checkbox').prop('checked', false);
                }

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
            $(document).on('change', '.off-checkbox, .otherproj-checkbox', function() {
                if($(this).is(':checked')){
                    if($(this).hasClass('off-checkbox')){
                        $(this).closest('div').find('.otherproj-checkbox').prop('checked', false);
                    }
                    else {
                        $(this).closest('div').find('.off-checkbox').prop('checked', false);
                    }
                }

                toggleInputTime($(this));
            });

            $(document).on('click', '.exclude-btn', function(){
                if(confirm('Apakah anda yakin ignin menghapus item ini dari data presensi?')){
                    if($('tbody').find('tr').length > 1){
                        $(this).closest("tr").remove();
                    }
                    else {
                        alert('Data presensi tidak boleh kosong!');
                    }
                }
            });

            $('#submit-attendance-btn').on('click', function(){
                let invalid = false;

                $('#start_date, #end_date').removeClass('is-invalid')

                if(!$('#start_date').val()){
                    $('#start_date').addClass('is-invalid');
                    invalid = true;
                }

                if(!$('#end_date').val()){
                    $('#end_date').addClass('is-invalid');
                    invalid = true;
                }

                if($('#start_date').val() > $('#end_date').val()){
                    $('#start_date').addClass('is-invalid');
                    $('#end_date').addClass('is-invalid');
                    invalid = true;
                }

                if(!invalid){
                    $('form').submit();
                }
            });

            $('#start_date').on('change', function() {
                const startDate = new Date($(this).val());

                // Clone the date to avoid modifying the original
                const endDate = new Date(startDate);
                endDate.setDate(endDate.getDate() + 6);

                // Format as YYYY-MM-DD
                const formattedEndDate = endDate.toISOString().split('T')[0];

                $('#end_date').val(formattedEndDate);

                setTHDate($(this).val());
            });

            $('#end_date').on('change', function() {
                const endDate = new Date($(this).val());

                // Clone the endDate to calculate the startDate
                const startDate = new Date(endDate);
                startDate.setDate(endDate.getDate() - 6);

                // Format as YYYY-MM-DD
                const formattedStartDate = startDate.toISOString().split('T')[0];

                $('#start_date').val(formattedStartDate);

                setTHDate($('#start_date').val());
            });

        });

    </script>
@endsection
