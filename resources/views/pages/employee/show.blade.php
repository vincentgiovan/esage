@extends("layouts.main-admin")

@section("content")
    <x-container>
        <br>
        <h3>Data Pegawai</h3>
        <hr>

        @if (session()->has('successAddPrepay'))
            <p class="text-success fw-bold">{{ session('successAddPrepay') }}</p>
        @elseif (session()->has('successEditPrepay'))
            <p class="text-success fw-bold">{{ session('successEditPrepay') }}</p>
        @elseif (session()->has('successDeletePrepay'))
            <p class="text-success fw-bold">{{ session('successDeletePrepay') }}</p>
        @endif

        <table class="w-100 mt-4">
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
            {{-- <tr>
                <th class="border border-1 border-secondary w-25">Performa</th>
                <td class="border border-1 border-secondary">{{ __("Rp " . number_format($employee->performa, 2, ',', '.')) ?? "N/A" }}</td>
            </tr> --}}
            {{-- <tr>
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
            <tr>
                <th class="border border-1 border-secondary w-25">Kasbon</th>
                <td class="border border-1 border-secondary">{{ __("Rp " . number_format($employee->kasbon, 2, ',', '.')) ?? "N/A" }}</td>
            </tr>
        </table>
    </x-container>
@endsection

