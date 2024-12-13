@extends('layouts.main-admin')

@section('content')
    <x-container-middle>
        <div class="container bg-white rounded-4 p-5 border border-1 card">
            <h2 class="text-center fw-bold">Add Attendance Data</h2>
            <form method="POST" action="{{ route('attendance-store') }}">
                @csrf

                <div class="mt-3">
                    <label for="attendance_date">Tanggal</label>
                    <input type="date" class="form-control @error('attendance_date') is-invalid @enderror" id="attendance_date" name="attendance_date" placeholder="attendance_date"
                        value="{{ old('attendance_date') }}">
                    @error('attendance_date')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="employee_id">Employee Name</label>
                    <select type="text" class="form-select text-black @error('employee_id') is-invalid @enderror" id="employee_id" name="employee_id">
                        <option selected disabled>Select an employee</option>
                        @foreach ($employees as $e)
                            <option value="{{ $e->id }}" @if(old("employee_id") == $e->id) selected @endif>{{ $e->nama }}</option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    <label for="project_id">Project Name</label>
                    <select type="text" class="form-select text-black @error('project_id') is-invalid @enderror" id="project_id" name="project_id">
                        <option selected disabled>Select a project</option>
                        @foreach ($projects as $p)
                            <option value="{{ $p->id }}" @if(old("project_id") == $p->id) selected @endif>{{ $p->project_name }}</option>
                        @endforeach
                    </select>
                    @error('project_id')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="d-flex w-100 gap-4 mt-3">
                    <div class="w-50">
                        <label for="normal">Normal</label>
                        <input type="text" class="form-control @error('normal') is-invalid @enderror" id="normal" name="normal" placeholder="Input normal"
                            value="{{ old('normal') }}">
                        @error('normal')
                            <p style="color: red; font-size: 10px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-50">
                        <label for="pokok">Pokok</label>
                        <input type="text" class="form-control @error('pokok') is-invalid @enderror" id="pokok" disabled>
                        @error('pokok')
                            <p style="color: red; font-size: 10px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="d-flex w-100 gap-4 mt-3">
                    <div class="w-50">
                        <label for="jam_lembur">Jam Lembur</label>
                        <input type="text" class="form-control @error('jam_lembur') is-invalid @enderror" id="jam_lembur" name="jam_lembur" placeholder="Input jam lembur"
                            value="{{ old('jam_lembur') }}">
                        @error('jam_lembur')
                            <p style="color: red; font-size: 10px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-50">
                        <label for="lembur">Lembur</label>
                        <input type="text" class="form-control @error('lembur') is-invalid @enderror" id="lembur" disabled>
                        @error('lembur')
                            <p style="color: red; font-size: 10px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="d-flex w-100 gap-4 mt-3">
                    <div class="w-50">
                        <label for="index_lembur_panjang">Index Lembur Panjang</label>
                        <input type="text" class="form-control @error('index_lembur_panjang') is-invalid @enderror" id="index_lembur_panjang" name="index_lembur_panjang" placeholder="Input Index lembur panjang"
                            value="{{ old('index_lembur_panjang') }}">
                        @error('index_lembur_panjang')
                            <p style="color: red; font-size: 10px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-50">
                        <label for="lembur_panjang">Lembur Panjang</label>
                        <input type="text" class="form-control @error('lembur_panjang') is-invalid @enderror" id="lembur_panjang" disabled>
                        @error('lembur_panjang')
                            <p style="color: red; font-size: 10px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="d-flex w-100 gap-4 mt-3">
                    <div class="w-50">
                        <label for="index_performa">Index Performa</label>
                        <input type="text" class="form-control @error('index_performa') is-invalid @enderror" id="index_performa" name="index_performa" placeholder="Input index performa"
                            value="{{ old('index_performa') }}">
                        @error('index_performa')
                            <p style="color: red; font-size: 10px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-50">
                        <label for="performa">Performa</label>
                        <input type="text" class="form-control @error('performa') is-invalid @enderror" id="performa" disabled>
                        @error('performa')
                            <p style="color: red; font-size: 10px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-3">
                    <label for="remark">Remark</label>
                    <input type="text" class="form-control @error('remark') is-invalid @enderror" id="remark" name="remark" placeholder="Input remark"
                        value="{{ old('remark') }}">
                    @error('remark')
                        <p style="color: red; font-size: 10px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-3">
                    @if (session()->has('passwordNotConfirmed'))
                        <p class="text-success fw-bold">{{ session('passwordNotConfirmed') }}</p>
                    @endif
                    <input type="submit" class="btn btn-success px-3 py-1" value="Simpan Data Baru">
                </div>
            </form>
        </div>
    </x-container-middle>

    <script>
        $(document).ready(() => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    console.log("Location allowed.");
                },
                function(error) {
                    console.error("Error getting location:", error);
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }

            const all_employees = @json($employees);

            $("#employee_id").change(function(){
                const targetted = all_employees.find(item => item.id == $(this).val());

                $("#pokok").val(targetted.pokok);
                $("#lembur").val(targetted.lembur);
                $("#lembur_panjang").val(targetted.lembur_panjang);
                $("#performa").val(targetted.performa);
            });

            $("form").on("submit", function(e){
                e.preventDefault();

                // Create a Promise to handle geolocation
                const locationPromise = new Promise((resolve, reject) => {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            const lat = position.coords.latitude;
                            const lon = position.coords.longitude;

                            $("form").append($("<input>").attr({"type": "hidden", "name": "latitude", "value": lat}));
                            $("form").append($("<input>").attr({"type": "hidden", "name": "longitude", "value": lon}));

                            resolve(); // Location success
                        },
                        function(error) {
                            console.error("Error getting location:", error);
                            alert("Error occurred while retrieving location.");
                            reject(); // Location failed
                        },
                        {
                            enableHighAccuracy: true,  // Request high accuracy
                            timeout: 10000,            // Timeout after 10 seconds
                            maximumAge: 0              // Don't use cached location data
                        });
                    } else {
                        alert("Geolocation is not supported by this browser.");
                        reject(); // Geolocation not supported
                    }
                });

                // Handle the location promise
                locationPromise.then(() => {
                    // Proceed with form submission if location is allowed
                    this.submit();
                }).catch(() => {
                    alert("Please allow location access in this web app.");
                });
            });
        });
    </script>


@endsection
