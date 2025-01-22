@extends("layouts.main-admin")

@section("content")
    <x-container>
        <br>
        <h2>Data Pegawai</h2>
        <hr>

        @if (session()->has('successAddPrepay'))
            <p class="text-success fw-bold">{{ session('successAddPrepay') }}</p>
        @elseif (session()->has('successEditPrepay'))
            <p class="text-success fw-bold">{{ session('successEditPrepay') }}</p>
        @elseif (session()->has('successDeletePrepay'))
            <p class="text-success fw-bold">{{ session('successDeletePrepay') }}</p>
        @endif

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
                        <div class="form-group mb-3 w-100" >
                            <label for="c_start_period">Periode Kasbon</label>
                            <input type="date" name="c_start_period" class="form-control">

                            @error('c_start_period')
                                <p class="text-danger">Harap masukkan tanggal awal periode kasbon.</p>
                            @enderror
                        </div>

                        <div class="form-group mb-3 w-100" >
                            <label for="c_end_period">Periode Kasbon</label>
                            <input type="date" name="c_end_period" class="form-control">

                            @error('c_end_period')
                                <p class="text-danger">Harap masukkan tanggal akhir periode kasbon.</p>
                            @enderror
                        </div>

                        <div class="form-group mb-3 w-100" >
                            <label for="c_amount">Jumlah</label>
                            <input type="text" name="c_amount" class="form-control" placeholder="Masukkan nominal">

                            @error('c_amount')
                                <p class="text-danger">Harap masukkan jumlah nominal kasbon.</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group mb-3 w-100">
                        <label for="c_remark">Keterangan</label>
                        <input type="text" name="c_remark" class="form-control w-100" placeholder="Tambah keterangan">
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
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>

                @forelse ($employee->prepays()->orderBy('prepay_date')->get() as $kasbon)
                    <tr style="background: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td style="width: 50px;">{{ $loop->iteration }}</td>
                        <td style="width: 500px;">
                            <div class="w-100 d-flex gap-3 align-items-center">
                                {{ Carbon\Carbon::parse($kasbon->prepay_date)->translatedFormat('d F Y') }}
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
                                <form action="{{ route('prepay-destroy', [$employee->id, $kasbon->id]) }}" method="post"
                                    style="font-size: 10pt;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Data kasbon ini akan dihapus, apakah anda yakin ingin melanjutkan?')"><i class="bi bi-trash3"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <tr style="display: none;">
                        <td class="p-5" colspan="5">
                            <form action="{{ route('prepay-update', [$employee->id, $kasbon->id]) }}" method="post" class="w-100 d-flex flex-column align-items-start" id="prepayedit-{{ $kasbon->id }}">
                                @csrf
                                <div class="w-100 d-flex gap-3">
                                    <div class="form-group mb-3 w-100">
                                        <label for="e_start_period">Periode Awal Kasbon</label>
                                        <input type="date" name="e_start_period" class="form-control" value="{{ old("e_start_period", $kasbon->start_period) }}">

                                        @error('e_start_period')
                                            <p class="text-danger">Harap masukkan tanggal awal periode kasbon.</p>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3 w-100">
                                        <label for="e_end_period">Periode Akhir Kasbon</label>
                                        <input type="date" name="e_end_period" class="form-control" value="{{ old("e_end_period", $kasbon->end_period) }}">

                                        @error('e_end_period')
                                            <p class="text-danger">Harap masukkan tanggal akhir periode kasbon.</p>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3 w-100">
                                        <label for="e_amount">Jumlah</label>
                                        <input type="text" name="e_amount" class="form-control" placeholder="Masukkan nominal" value="{{ old("e_amount", $kasbon->amount) }}">

                                        @error('e_amount')
                                            <p class="text-danger">Harap masukkan jumlah nominal kasbon.</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group mb-3 w-100">
                                    <label for="e_remark">Keterangan</label>
                                    <input type="text" name="e_remark" class="form-control w-100" placeholder="Tambah keterangan" value="{{ old('e_remark', $kasbon->remark) }}">
                                </div>

                                <button type="submit" class="btn btn-primary">Simpan perubahan</button>
                            </form>
                        </td>
                    </tr>
                @empty
                @endforelse
            </table>
        </div>
    </x-container>

    <script>
        const createFormError = @json(session('create_form_visible'));
        const editFormError = @json(session('edit_form_visible'));
        const editFormId = @json(session('edit_form_id'));

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

            if(editFormError){
                $(`#prepayedit-${editFormId}`).closest('tr').show();

                // Scroll to the form
                $(`#prepayedit-${editFormId}`)[0].scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }

            $('.edit-prepay-btn').click(function(){
                $(this).closest('tr').next().toggle();
            });
        });

    </script>
@endsection

