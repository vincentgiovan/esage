@extends('layouts.main-admin')

@section("content")

    <x-container-middle>
        <div class="container bg-white py-4 px-5 rounded-4 border border-1 card mt-4">
            <h2>Tambah Foto ke Pengembalian</h2>

            <form method="POST" action="{{ route('returnitem-image-store', $return_item->id ) }}" class="mt-2" enctype="multipart/form-data">
                @csrf

                <div>
                    <label>Pilih Foto Barang (Bisa lebih dari 1)</label>
                    <input type="file" class="form-control image" name="image[]" multiple />
                    <p class="text-danger" id="errProductName"></p>
                </div>

                <!-- Container to hold multiple image previews -->
                <div id="preview-container" class="d-flex flex-wrap mt-2 gap-2"></div>

                <div class="mt-3">
                    <input type="submit" class="btn btn-success px-3 py-1" value="Simpan">
                </div>
            </form>
        </div>
    </x-container-middle>

    <script>
        $(document).ready(function(){
            $(".image").on("change", function(){
                let files = this.files;
                let previewContainer = $("#preview-container");

                // Clear previous previews
                previewContainer.empty();

                if (files) {
                    $.each(files, function(index, file){
                        let reader = new FileReader();

                        reader.onload = function(e) {
                            let imgElement = $("<img>")
                                .attr("src", e.target.result)
                                .css({"width": "400px", "height": "300px", "object-fit": "cover", "border-radius": "8px"});

                            previewContainer.append(imgElement);
                        };

                        reader.readAsDataURL(file);
                    });
                }
            });
        });
    </script>

@endsection
