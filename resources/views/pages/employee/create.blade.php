@extends('layouts.main-admin')

@section('bcd')
    <span>> <a href="#">Edit</a></span>
@endsection

@section('content')
    <x-container-middle>
        <div class="container bg-white py-4 px-5 rounded-4 mt-4 border border-1 card">
            <h2>Data Pegawai Baru</h2>

            <form method="POST" action="{{ route('employee-store') }}" id="folm" enctype="multipart/form-data">
                @csrf

                <div class="mt-3">
                    <label for="nama">Nama</label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" id="nama" placeholder="Nama Pegawai"
                        value="{{ old('nama') }}">
                    @error('nama')
                        <p class="text-danger">Harap masukkan nama karyawan.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="NIK">NIK</label>
                    <input type="text" class="form-control @error('NIK') is-invalid @enderror" name="NIK" id="NIK" placeholder="NIK"
                        value="{{ old('NIK') }}">
                    @error('NIK')
                        <p class="text-danger">Harap masukkan NIK karyawan.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="image">Foto KTP</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image">
                    @error('image')
                        <p class="text-danger">Harap upload foto KTP karyawan.</p>
                    @enderror
                </div>

                <img id="img-preview" class="w-25 mt-2">

                <div class="mt-3">
                    <label>Kalkulasi Gaji</label>

                    <div class="d-flex gap-3">
                        <div class="d-flex gap-2 rounded-3 py-2">
                            <input class="form-check-input" type="radio" name="kalkulasi_gaji" id="flexRadioDefault1" value="on" @if(old('kalkulasi_gaji') == "on") checked @endif checked>
                            <label class="form-check-label" for="flexRadioDefault1">
                                Ya
                            </label>
                        </div>
                        <div class="d-flex gap-2 rounded-3 py-2">
                            <input class="form-check-input" type="radio" name="kalkulasi_gaji" id="flexRadioDefault2" value="off" @if(old('kalkulasi_gaji') == "off") checked @endif>
                            <label class="form-check-label" for="flexRadioDefault2">
                                Tidak
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <label for="jabatan">Jabatan</label>
                    <select class="form-select text-black" name="jabatan" id="jabatan">
                        @foreach($positions as $p)
                            @if($p->status == "on")
                                <option value="{{ $p->id }}" @if(old('jabatan') == $p->id) selected @endif>{{ $p->position_name }}</option>
                            @endif
                        @endforeach
                    </select>
                    @error('jabatan')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label>Keahlian</label>

                    <div class="d-flex flex-wrap gap-3">
                        @foreach($specialities as $s)
                            @if($s->status == "on")
                                <div class="d-flex gap-2 rounded-3 border border-2 py-2 px-3">
                                    <input type="checkbox" class="form-check-input border border-2" id="scb-{{ $s->id }}" name="selected_speciality[]" @if(old('specialities.'.$loop->index) == "on") checked @endif>
                                    <label class="form-check-label" for="scb-{{ $s->id }}">{{ $s->speciality_name }}</label>
                                </div>
                            @endif
                        @endforeach

                    </div>

                    @error('keahlian[]')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="pokok">Pokok</label>
                    <input type="text" class="form-control @error('pokok') is-invalid @enderror" name="pokok" id="pokok" placeholder="Pokok"
                        value="{{ old('pokok') }}">
                    @error('pokok')
                        <p class="text-danger">Harap masukkan nominal pokok karyawan.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="lembur">Lembur</label>
                    <input type="text" class="form-control @error('lembur') is-invalid @enderror" name="lembur" id="lembur" placeholder="Lembur"
                        value="{{ old('lembur') }}">
                    @error('lembur')
                        <p class="text-danger">Harap masukkan nominal lembur karyawan.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="lembur_panjang">Lembur Panjang</label>
                    <input type="text" class="form-control @error('lembur_panjang') is-invalid @enderror" name="lembur_panjang" id="lembur_panjang" placeholder="Lembur Panjang"
                        value="{{ old('lembur_panjang') }}">
                    @error('lembur_panjang')
                        <p class="text-danger">Harap masukkan nominal lembur panjang karyawan.</p>
                    @enderror
                </div>

                {{-- <div class="mt-3">
                    <label>Payroll</label>

                    <div class="d-flex gap-3">
                        <div class="d-flex gap-2 rounded-3 py-2">
                            <input class="form-check-input" type="radio" name="payroll" id="flexRadioDefault3" value="on" @if(old('payroll') == "on") checked @endif>
                            <label class="form-check-label" for="flexRadioDefault3">
                                Ya
                            </label>
                        </div>
                        <div class="d-flex gap-2 rounded-3 py-2">
                            <input class="form-check-input" type="radio" name="payroll" id="flexRadioDefault4" value="off" @if(old('payroll') == "off") checked @endif>
                            <label class="form-check-label" for="flexRadioDefault4">
                                Tidak
                            </label>
                        </div>
                    </div>
                </div> --}}

                <div class="mt-3">
                    <label for="masuk">Masuk</label>
                    <input type="date" class="form-control" name="masuk" id="masuk"
                        value="{{ old('masuk') }}">
                </div>

                <div class="mt-3">
                    <label for="keluar">Keluar</label>
                    <input type="date" class="form-control" name="keluar" id="keluar"
                        value="{{ old('keluar') }}">
                </div>

                <div class="mt-3">
                    <label for="keterangan">Keterangan</label>
                    <input type="text" class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan"
                        value="{{ old('keterangan') }}">
                </div>

                <div class="mt-4">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Simpan Data Baru">
                </div>
            </form>
        </div>
    </x-container-middle>

    <script>
        $("#image").on("change", function(){
			const oFReader = new FileReader();
			oFReader.readAsDataURL(image.files[0]);

			oFReader.onload = function(oFEvent){
				$("#img-preview").attr("src", oFEvent.target.result);
			}
		});

        function retrieveCBV(){
            cbval = [];
            $('input[type="checkbox"]').each(function(){
                if($(this).is(":checked")){
                    cbval.push("on");
                } else {
                    cbval.push("off");
                }
            });

            return cbval;
        }

        $("#folm").on("submit", function(e){
            e.preventDefault();

            cbv = retrieveCBV();

            for(let cb of cbv){
                $(this).append($("<input>").attr({"type": "hidden", "value": cb, "name": "specialities[]"}));
            }

            this.submit();
        });
    </script>
@endsection
