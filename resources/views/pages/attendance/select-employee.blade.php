@extends('layouts.main-admin')

@section('content')
    <x-container-middle>
        <div class="container bg-white rounded-4 py-4 px-5 border border-1 card mt-4">
            <form action="{{ route('attendance-precreate-continue') }}" method="post">
                @csrf
                <h3>Laporan Presensi Baru</h3>

                <div class="form-group mt-3">
                    <label for="project">Absensi dibuat untuk proyek</label>
                    <select class="form-select select2" id="project" name="project">
                        <option disabled selected>Pilih Proyek</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                        @endforeach
                    </select>
                </div>

                <label class="mt-4">Silakan pilih karyawan yang ingin dimasukkan ke dalam presensi:</label>
                <div id="employee-selection" class="d-flex w-100 flex-column align-items-start mt-2">
                    <i>- Belum ada proyek yang dipilih -</i>
                </div>

                <div class="mt-4">
                    <button class="btn btn-primary">Konfirmasi & Lanjut</button>
                </div>
            </form>
        </div>
    </x-container-middle>

    <script>
        $(document).ready(() => {
            $('#project').on('change', function(){
                const allProjects = @json($projects);

                const selectedProject = allProjects.filter(proj => {
                    return proj.id == $(this).val();
                });

                $('#employee-selection').empty();

                // console.log(selectedProject[0].employees);

                selectedProject[0].employees.forEach(employee => {
                    $('#employee-selection').append(
                        $('<label>').attr('for', `cbe${employee.id}`).addClass('mb-2 d-flex gap-2').append(
                            $('<input>').attr({'type': 'checkbox', 'id': `cbe${employee.id}`}).data('employee_id', employee.id).addClass('cb')
                        ).append(
                            $('<span>').text(employee.nama)
                        )
                    );
                });
            });

            $('form').on('submit', function(e){
                e.preventDefault();

                $('.cb').each(function(){
                    if($(this).is(':checked')){
                        $('form').append($('<input>').attr({'type': 'hidden', 'name': 'employee[]', 'value': $(this).data('employee_id')}))
                    }
                });

                this.submit();
            });
        });
    </script>
@endsection
