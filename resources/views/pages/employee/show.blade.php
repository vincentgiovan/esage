@extends("layouts.main-admin")

@section("content")
    <x-container>
        <br>
        <h2>Data Pegawai</h2>
        <hr>

        <h5 class="mt-4">Data Utama</h5>
        <table class="w-100 mt-2">
            <tr>
                <th class="border border-1 border-secondary w-25">Nama</th>
                <td class="border border-1 border-secondary">{{ $employee->nama }}</td>
            </tr>
            <tr>
                <th class="border border-1 border-secondary w-25">NIK</th>
                <td class="border border-1 border-secondary">{{ $employee->NIK ?? "N/A" }}</td>
            </tr>
            <tr>
                <th class="border border-1 border-secondary w-25">Foto KTP</th>
                <td class="border border-1 border-secondary">
                    @if($employee->foto_ktp)
                        <img class="w-50" src="{{ Storage::url("app/public/" . $employee->foto_ktp) }}">
                    @else
                        N/A
                    @endif
                </td>
            </tr>
            <tr>
                <th class="border border-1 border-secondary w-25">Kalkulasi Gaji</th>
                <td class="border border-1 border-secondary">{{ ($employee->kalkulasi_gaji == "on")? "Ya" : "Tidak" }}</td>
            </tr>
            <tr>
                <th class="border border-1 border-secondary w-25">Jabatan</th>
                <td class="border border-1 border-secondary">{{ $employee->jabatan ?? "N/A" }}</td>
            </tr>
            <tr>
                <th class="border border-1 border-secondary w-25">Keahlian</th>
                <td class="border border-1 border-secondary">
                    @if(count(unserialize($employee->keahlian)) > 0)
                        <ul>
                            @foreach(unserialize($employee->keahlian) as $sp)
                                <li>{{ $sp }}</li>
                            @endforeach
                        </ul>
                    @else
                        N/A
                    @endif
                </td>
            </tr>
            <tr>
                <th class="border border-1 border-secondary w-25">Pokok</th>
                <td class="border border-1 border-secondary">{{ __("Rp " . number_format($employee->pokok, 2, ',', '.')) ?? "N/A" }}</td>
            </tr>
            <tr>
                <th class="border border-1 border-secondary w-25">Lembur</th>
                <td class="border border-1 border-secondary">{{ __("Rp " . number_format($employee->lembur, 2, ',', '.')) ?? "N/A" }}</td>
            </tr>
            <tr>
                <th class="border border-1 border-secondary w-25">Lembur Panjang</th>
                <td class="border border-1 border-secondary">{{ __("Rp " . number_format($employee->lembur_panjang, 2, ',', '.')) ?? "N/A" }}</td>
            </tr>
            <tr>
                <th class="border border-1 border-secondary w-25">Performa</th>
                <td class="border border-1 border-secondary">{{ __("Rp " . number_format($employee->performa, 2, ',', '.')) ?? "N/A" }}</td>
            </tr>
            {{-- <tr>
                <th class="border border-1 border-secondary w-25">Kasbon</th>
                <td class="border border-1 border-secondary">{{ __("Rp " . number_format($employee->kasbon, 2, ',', '.')) ?? "N/A" }}</td>
            </tr>
            <tr>
                <th class="border border-1 border-secondary w-25">Payroll</th>
                <td class="border border-1 border-secondary">{{ ($employee->payroll == "on")? "Ya" : "Tidak" }}</td>
            </tr> --}}
            <tr>
                <th class="border border-1 border-secondary w-25">Masuk</th>
                <td class="border border-1 border-secondary">{{ $employee->masuk ? Carbon\Carbon::parse($employee->masuk)->translatedFormat('d F Y') : "N/A" }}</td>
            </tr>
            <tr>
                <th class="border border-1 border-secondary w-25">Keluar</th>
                <td class="border border-1 border-secondary">{{ $employee->keluar ? Carbon\Carbon::parse($employee->keluar)->translatedFormat('d F Y') : "N/A" }}</td>
            </tr>
            <tr>
                <th class="border border-1 border-secondary w-25">Keterangan</th>
                <td class="border border-1 border-secondary">{{ $employee->keterangan ?? "N/A" }}</td>
            </tr>
            <tr>
                <th class="border border-1 border-secondary w-25">Status</th>
                <td class="border border-1 border-secondary">{{ $employee->status ? ucwords($employee->status) : "N/A" }}</td>
            </tr>
        </table>

        <h5 class="mt-4">Kasbon</h5>
        <div class="card mb-4">
            <div class="card-header">
                <button class="w-100 h-100 btn d-flex justify-content-between" type="button" id="add-new-prepay-btn">Tambah Kasbon Baru <i class="bi bi-chevron-down"></i></button>
            </div>

            <div class="card-body" id="add-new-prepay" style="display: none;">
                <form action="{{ route('prepay-store', $employee->id) }}" method="post" class="w-100 d-flex flex-column align-items-start" id="new-prepay-form">
                    @csrf
                    <div class="w-100 d-flex gap-3">
                        <div class="form-group mb-3" style="width: 30%;">
                            <label for="start_period">Periode Kasbon</label>
                            <input type="date" name="start_period" class="form-control">

                            @error('start_period')
                                <p class="text-danger">Harap masukkan tanggal awal periode kasbon.</p>
                            @enderror
                        </div>

                        <div class="form-group mb-3" style="width: 30%;">
                            <label for="end_period">Periode Kasbon</label>
                            <input type="date" name="end_period" class="form-control">

                            @error('end_period')
                                <p class="text-danger">Harap masukkan tanggal akhir periode kasbon.</p>
                            @enderror
                        </div>

                        <div class="form-group mb-3" style="width: 30%;">
                            <label for="amount">Jumlah</label>
                            <input type="text" name="amount" class="form-control" placeholder="Masukkan nominal">

                            @error('amount')
                                <p class="text-danger">Harap masukkan jumlah nominal kasbon.</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group mb-3 w-100">
                        <label for="remark">Keterangan</label>
                        <input type="text" name="remark" class="form-control w-100" placeholder="Tambah keterangan">
                    </div>

                    <button type="submit" class="btn btn-primary">Konfirmasi dan Lanjut</button>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto w-100 mt-2">
            @csrf
            <table class="w-100">
                <tr>
                    <th>No</th>
                    <th>Periode</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                    <th>Actions</th>
                </tr>

                @foreach ($employee->prepays()->orderBy('start_period')->get() as $kasbon)
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td style="width: 50px;">{{ $loop->iteration }}</td>
                        <td style="width: 500px;">
                            <div class="w-100 d-flex gap-3 align-items-center">
                                {{ Carbon\Carbon::parse($kasbon->start_period)->translatedFormat('d F Y') }} - {{ Carbon\Carbon::parse($kasbon->end_period)->translatedFormat('d F Y') }}
                            </div>
                        </td>
                        <td style="width: 300px;">Rp {{ number_format($kasbon->amount, 2, ",", ".") }}</td>
                        <td>{{ $kasbon->remark ?? 'N/A' }}</td>
                        <td>
                            <div class="d-flex gap-2 w-100">
                                <button type="button" class="btn edit-prepay-btn text-white"
                                    style="font-size: 10pt; background-color: rgb(197, 167, 0);">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <a href="{{ route('prepay-destroy', [$employee->id, $kasbon->id]) }}" class="btn btn-danger text-white"
                                    style="font-size: 10pt;">
                                    <i class="bi bi-trash3"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr style="display: none;">
                        <td class="py-4" style="width: 50px;"></td>
                        <td class="py-4" style="width: 500px;">
                            <div class="w-100 d-flex gap-3 align-items-center">
                                <input type="date" name="start_period" class="form-control" value="{{ Carbon\Carbon::parse($kasbon->start_period)->format('Y-m-d') }}"> - <input type="date" name="end_period" class="form-control" value="{{ Carbon\Carbon::parse($kasbon->end_period)->format('Y-m-d') }}">
                            </div>
                        </td>
                        <td class="py-4" style="width: 300px;">
                            <input type="text" name="amount" class="form-control" value="{{ $kasbon->amount }}">
                        </td>
                        <td class="py-4">
                            <input type="text" name="remark" class="form-control" value="{{ $kasbon->remark }}">
                        </td>
                        <td class="py-4">
                            <form action="{{ route('prepay-update', [$employee->id, $kasbon->id]) }}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </x-container>

    <script>
        const createFormError = @json(session('form_visible'));

        $(document).ready(() => {
            // Toggle form visibility
            $("#add-new-prepay-btn").click(() => {
                $("#add-new-prepay").slideToggle();
            });

            if (createFormError){
                // Make the form visible
                $('#new-prepay-form').parent().show();

                // Scroll to the form
                $('#new-prepay-form')[0].scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }

            $('.edit-prepay-btn').click(function(){
                $(this).closest('tr').next().toggle();
            });

            $('.edit-prepay');
        });

    </script>
@endsection

