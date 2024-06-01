@extends('layouts.main-admin')

@section("content")

    <div class="d-flex justify-content-center align-items-center" style="min-height:100vh">
    <div class="container">

        <h2>Edit Project</h2>

{{-- @csrf kepake untuk token ,wajib --}}

<form method="POST" action="{{ route("project-update", $project->id) }}">
    {{-- @csrf kepake untuk token ,wajib --}}
                @csrf
                <div class="mt-3">
                    <input type="text" class="form-control" name="project_name" placeholder="Nama Project" value = "{{ old("project_name", $project->project_name ) }}">
                    @error("project_name")
                    <p style = "color: red; font-size: 10px;">{{$message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <input type="text" class="form-control" name="location" placeholder="Location"  value = "{{ old("location", $project->location) }}">
                    @error("location")
                    <p style = "color: red; font-size: 10px;">{{$message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <input type="text" class="form-control" name="PIC" placeholder="PIC Name"  value = "{{ old("PIC", $project->PIC) }}">
                    @error("PIC")
                    <p style = "color: red; font-size: 10px;">{{$message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <input type="text" class="form-control" name="address" placeholder="Alamat"  value = "{{ old("address", $project->address) }}">
                    @error("address")
                    <p style = "color: red; font-size: 10px;">{{$message }}</p>
                    @enderror
                </div>

                <div class="mt-3 ">
                <input type="submit" class="btn btn-success px-3 py-1" value="Edit">
                </div>
            </form>

            <script>

            </script>
    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


@endsection
