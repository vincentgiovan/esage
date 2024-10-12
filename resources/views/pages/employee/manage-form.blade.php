@extends("layouts.main-admin")

@section("content")
    <x-container>
        <br>
        <h1>Sage Employees - Manage Form</h1>

        @if (session()->has('successAddPosition'))
            <p class="text-success fw-bold">{{ session('successAddPosition') }}</p>
        @elseif (session()->has('successAddSpeciality'))
            <p class="text-success fw-bold">{{ session('successAddSpeciality') }}</p>
        @elseif (session()->has('successEditPosition'))
            <p class="text-success fw-bold">{{ session('successEditPosition') }}</p>
        @elseif (session()->has('successEditSpeciality'))
            <p class="text-success fw-bold">{{ session('successEditSpeciality') }}</p>
        @elseif (session()->has('successDeletePosition'))
            <p class="text-success fw-bold">{{ session('successDeletePosition') }}</p>
        @elseif (session()->has('successDeleteSpeciality'))
            <p class="text-success fw-bold">{{ session('successDeleteSpeciality') }}</p>
        @endif

        <div class="d-flex justify-content-between align-items-center mt-4">
            <h3>Jabatan</h3>
            <form action="{{ route('employee-manageform-addposition') }}" method="post" class="d-flex gap-2 items-stretch">
                @csrf
                <input type="text" class="form-control" name="position_name" placeholder="New Position">
                <button type="submit" class="btn btn-primary" style="width: 100px"><i class="bi bi-plus-lg"></i> Add</button>
            </form>
        </div>
        <div class="overflow-x-auto mt-3">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary ">#</th>
                    <th class="border border-1 border-secondary ">Position Name</th>
                    <th class="border border-1 border-secondary ">Availability</th>
                    <th class="border border-1 border-secondary ">Actions</th>
                </tr>

                @foreach ($positions as $p)
                    <tr>
                        <td class="border border-1 border-secondary " style="width: 80px;">{{ $loop->iteration }}</td>
                        <td class="border border-1 border-secondary ">{{ $p->position_name }}</td>
                        <td class="border border-1 border-secondary " style="width: 300px;">
                            <form action="{{ route('employee-manageform-editposition', $p->id) }}" method="post" class="d-flex gap-2 justify-content-center">
                                @csrf
                                <select name="status" class="form-select text-black" style="width: 200px;">
                                    <option value="on" @if($p->status == "on") selected @endif>On</option>
                                    <option value="off" @if($p->status == "off") selected @endif>Off</option>
                                </select>
                                <button type="submit" class="btn btn-warning" style="display: none;">Save</button>
                            </form>
                        </td>
                        <td class="border border-1 border-secondary ">
                            <div class="d-flex gap-2 w-100 justify-content-center">
                                <form action="{{ route('employee-manageform-deleteposition', $p->id) }}" method="post" class="d-flex gap-2 w-100 justify-content-center">
                                    @csrf
                                    <button type="submit" class="btn btn-danger"><i class="bi bi-trash3"></i></button>
                                </form>
                            </div>
                        </td>

                    </tr>
                @endforeach
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-5">
            <h3>Keahlian</h3>
            <form action="{{ route('employee-manageform-addspeciality') }}" method="post" class="d-flex gap-2 items-stretch">
                @csrf
                <input type="text" class="form-control" name="speciality_name" placeholder="New Speciality">
                <button type="submit" class="btn btn-primary" style="width: 100px"><i class="bi bi-plus-lg"></i> Add</button>
            </form>
        </div>
        <div class="overflow-x-auto mt-3">
            <table class="w-100">
                <tr>
                    <th class="border border-1 border-secondary ">#</th>
                    <th class="border border-1 border-secondary ">Speciality Name</th>
                    <th class="border border-1 border-secondary ">Availability</th>
                    <th class="border border-1 border-secondary ">Actions</th>
                </tr>

                @foreach ($specialities as $s)
                    <tr>
                        <td class="border border-1 border-secondary " style="width: 80px;">{{ $loop->iteration }}</td>
                        <td class="border border-1 border-secondary ">{{ $s->speciality_name }}</td>
                        <td class="border border-1 border-secondary " style="width: 300px;">
                            <form action="{{ route('employee-manageform-editspeciality', $s->id) }}" method="post" class="d-flex gap-2 justify-content-center">
                                @csrf
                                <select name="status" class="form-select text-black" style="width: 200px;">
                                    <option value="on" @if($s->status == "on") selected @endif>On</option>
                                    <option value="off" @if($s->status == "off") selected @endif>Off</option>
                                </select>
                                <button type="submit" class="btn btn-warning" style="display: none;">Save</button>
                            </form>
                        </td>
                        <td class="border border-1 border-secondary ">
                            <form action="{{ route('employee-manageform-deletespeciality', $s->id) }}" method="post" class="d-flex gap-2 w-100 justify-content-center">
                                @csrf
                                <button type="submit" class="btn btn-danger"><i class="bi bi-trash3"></i></button>
                            </form>
                        </td>

                    </tr>
                @endforeach
            </table>
        </div>
    </x-container>

    <script>
        $("select").change(function(){
            $(this).next().show();
        });
    </script>

@endsection
