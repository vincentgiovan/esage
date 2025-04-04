@extends("layouts.main-admin")

@section("content")
    <x-container>
        <br>

        <div class="d-flex w-100 justify-content-between align-items-center">
            <h3>Kasbon Pegawai: {{ $employee->nama }} - {{ $employee->jabatan }}</h3>
            {{-- <div class="position-relative d-flex flex-column align-items-end">
                <button class="btn btn-secondary" type="button" id="dd-toggler">
                    <i class="bi bi-file-earmark-arrow-up"></i> Export
                </button>
                <div class="bg-white rounded-lg position-absolute z-2 border border-1" id="dd-menu" style="display: none; top: 40px;">
                    <form action="{{ route('salary-export') }}" method="post" target="_blank">
                        @csrf
                        <button type="submit" class="dropdown-item border border-1 py-2 px-3">Export (PDF)</button>
                    </form>
                </div>
            </div> --}}
        </div>
        <script>
            $(document).ready(() => {
                $("#dd-toggler").click(function(){
                    $("#dd-menu").toggle();
                });
            });
        </script>
        <hr>

        @if (session()->has('successEditPrepay'))
            <p class="text-success fw-bold">{{ session('successEditPrepay') }}</p>
        @elseif (session()->has('successAddPrepay'))
            <p class="text-success fw-bold">{{ session('successAddPrepay') }}</p>
        @elseif (session()->has('successGeneratePrepays'))
            <p class="text-success fw-bold">{{ session('successGeneratePrepays') }}</p>
        @endif

        {{-- <div class="d-flex justify-content-between align-items-end w-100">
            <form action="{{ route('prepay-index') }}" class="d-flex align-items-end gap-2">
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
        </div> --}}

        <div class="d-flex justify-content-between align-items-end w-100">
            <div class="d-flex" style="gap: 1px;">
                <a href="{{ route('prepay-index', ['emp_id' => $employee->id, 'content' => 'summary']) }}" class="btn" style="border-radius: 0; width: 200px; @if(!request('content') || request('content') == 'summary') border-bottom: 3px solid rgb(59, 59, 59); @else border-bottom: none; @endif">Ringkasan</a>
                <a href="{{ route('prepay-index', ['emp_id' => $employee->id, 'content' => 'log']) }}" class="btn" style="border-radius: 0; width: 200px; @if(request('content') == 'log') border-bottom: 3px solid rgb(59, 59, 59); @else border-bottom: none; @endif">Log Pemotongan</a>
            </div>
            <div class="d-flex justify-content-end">
                Memperlihatkan {{ $prepays->firstItem() }} - {{ $prepays->lastItem()  }} dari {{ $prepays->total() }} item
            </div>
        </div>

        @if(!request('content') || request('content') == 'summary')
            <div class="mt-4">
                <a href="{{ route('prepay-create', $employee->id) }}" class="btn btn-primary"><i class="bi bi-plus-square"></i> Tambah Kasbon Baru</a>
            </div>

            <div class="overflow-x-auto mt-3">
                <table class="w-100">
                    <tr>
                        <th class="border border-1 border-secondary">No</th>
                        <th class="border border-1 border-secondary">Tanggal Pembuatan</th>
                        <th class="border border-1 border-secondary">Nilai Awal</th>
                        <th class="border border-1 border-secondary">Saldo Saat Ini</th>
                        <th class="border border-1 border-secondary">Potongan<br>Per Periode</th>
                        <th class="border border-1 border-secondary">Pemotongan<br>Otomatis</th>
                        <th class="border border-1 border-secondary">Keperluan</th>
                        <th class="border border-1 border-secondary">Aksi</th>
                    </tr>

                    @foreach($prepays as $ppay)
                        <tr style="background-color: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                            <td class="border border-1 border-secondary">{{ ($loop->index + 1) + ((request('page') ?? 1) - 1) * 30 }}</td>
                            <td class="border border-1 border-secondary">{{ Carbon\Carbon::parse($ppay->prepay_date)->translatedFormat('d F Y') }}</td>
                            <td class="border border-1 border-secondary">Rp {{ number_format($ppay->init_amount, 2, ',', '.') }}</td>
                            <td class="border border-1 border-secondary">Rp {{ number_format($ppay->curr_amount, 2, ',', '.') }}</td>
                            <td class="border border-1 border-secondary">Rp {{ number_format($ppay->cut_amount, 2, ',', '.') }}</td>
                            <td class="border border-1 border-secondary">
                                @if($ppay->enable_auto_cut == 'yes')
                                    <i class="bi bi-check-circle-fill fs-4" style="color: green"></i>
                                @else
                                    <i class="bi bi-x-circle-fill fs-4" style="color: red"></i>
                                @endif
                            </td>
                            <td class="border border-1 border-secondary">{{ $ppay->remark }}</td>
                            <td class="border border-1 border-secondary">
                                <div class="d-flex gap-2 w-100">
                                    <a href="{{ route('prepay-edit', [$employee->id, $ppay->id]) }}" class="btn btn-warning text-white"
                                        style="font-size: 10pt; background-color: rgb(197, 167, 0);">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('prepay-destroy', [$employee->id, $ppay->id]) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-danger text-white" style="font-size: 10pt "
                                            onclick="return confirm('Do you want to delete this item?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>

            <div class="mt-4">
                {{ $prepays->links() }}
            </div>
        @endif

        @if(request('content') == 'log')
            <table class="w-100 mt-4">
                <tr>
                    <th class="border border-1 border-secondary">No</th>
                    <th class="border border-1 border-secondary">Periode</th>
                    <th class="border border-1 border-secondary">Saldo Awal</th>
                    <th class="border border-1 border-secondary">Pemotongan</th>
                    <th class="border border-1 border-secondary">Sisa Saldo</th>
                    <th class="border border-1 border-secondary">Keperluan Kasbon</th>
                </tr>

                @foreach($prepay_cuts as $ppc)
                    <tr style="background-color: @if($loop->index % 2 == 1) #E0E0E0 @else white @endif;">
                        <td class="border border-1 border-secondary">{{ $loop->iteration }}</td>
                        <td class="border border-1 border-secondary">{{ Carbon\Carbon::parse($ppc->start_period)->format('d/m/Y') }} - {{ Carbon\Carbon::parse($ppc->end_period)->format('d/m/Y') }}</td>
                        <td class="border border-1 border-secondary">Rp {{ number_format($ppc->init_amount, 2, ',', '.') }}</td>
                        <td class="border border-1 border-secondary">Rp {{ number_format($ppc->cut_amount, 2, ',', '.') }}</td>
                        <td class="border border-1 border-secondary">Rp {{ number_format($ppc->remaining_amount, 2, ',', '.') }}</td>
                        <td class="border border-1 border-secondary">{{ $ppc->prepay->remark }}</td>
                    </tr>
                @endforeach
            </table>
        @endif
    </x-container>

    <script>


    </script>

@endsection
