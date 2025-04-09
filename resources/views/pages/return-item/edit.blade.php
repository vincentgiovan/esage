@extends('layouts.main-admin')

@section("content")

    <x-container-middle>
        <div class="container rounded-4 p-5 bg-white border border-1 card mt-4">
            <h3>Edit Data Return Item</h3>

            <form method="POST" action="{{ route('returnitem-update', $return_item->id) }}" enctype="multipart/form-data">
                @csrf

                <div class="mt-3">
                    <label for="return_date">Tanggal Pengembalian<span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('return_date') is-invalid @enderror" name="return_date" id="return_date"  value="{{ old("return_date", Carbon\Carbon::today()->format('Y-m-d'))}}">
                    @error("return_date", $return_item->return_date)
                    <p class="text-danger">Harap masukkan nama tanggal pengembalian.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="project_id">Pilih Asal Proyek<span class="text-danger">*</span></label>
                    <select name="project_id" class="form-select @error('project_id') is-invalid @enderror" id="project_id">
                        <option disabled selected>Pilih proyek</option>
                        @forelse (Auth::user()->employee_data->projects ?? [] as $proj)
                            <option value="{{ $proj->id }}" @if(old('project_id', $return_item->project->id) == $proj->id) selected @endif>{{ $proj->project_name }} (PIC: {{ $proj->PIC }})</option>
                        @empty
                        @endforelse
                    </select>

                    @error("project_id")
                        <p class="text-danger">Harap pilih project asal barang yang ingin dikembalikan.</p>
                    @enderror
                </div>

                {{-- <div class="mt-3">
                    <label for="status">Status<span class="text-danger">*</span></label>
                    <select name="status" class="form-select" id="status">
                        <option value="Ready to pickup" @if(old('status', $return_item->status) == "Ready to pickup") selected @endif>Siap diangkut</option>
                        <option value="Not ready yet" @if(old('status', $return_item->status) == "Not ready yet") selected @endif>Belum siap diangkut</option>
                    </select>
                </div> --}}

                <div class="mt-3">
                    <label>Status<span class="text-danger">*</span></label>

                    <div class="d-flex gap-3">
                        <div class="d-flex gap-2 rounded-3 py-2">
                            <input class="form-check-input" type="radio" name="status" id="status1" value="Ready to pickup" @if(old('status', $return_item->status) == "Ready to pickup") checked @endif checked>
                            <label class="form-check-label" for="status1">Siap diangkut</label>
                        </div>
                        <div class="d-flex gap-2 rounded-3 py-2">
                            <input class="form-check-input" type="radio" name="status" id="status2" value="Not ready yet" @if(old('status', $return_item->status) == "Not ready yet") checked @endif>
                            <label class="form-check-label" for="status2">Belum siap diangkut</label>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <label for="PIC">PIC<span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('PIC') is-invalid @enderror" name="PIC" id="PIC" placeholder="PIC" value="{{ old('PIC', $return_item->PIC)}}">
                    @error("PIC")
                    <p class="text-danger">Harap masukkan nama PIC.</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="driver">Supir<span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('driver') is-invalid @enderror" name="driver" id="driver" placeholder="Nama supir" value="{{ old("driver", $return_item->driver)}}">
                    @error("driver")
                    <p class="text-danger">Harap masukkan nama driver.</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Simpan Perubahan">
                </div>
            </form>
        </div>
    </x-container-middle>

    <script>
        // $(document).ready(function(){
        //     $("#image").on("change", function(){
        //         const oFReader = new FileReader();
        //         oFReader.readAsDataURL(image.files[0]);

        //         oFReader.onload = function(oFEvent){
        //             $("#img-preview").attr("src", oFEvent.target.result);
        //         }
        //     });
        // });
    </script>
@endsection
