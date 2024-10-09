<div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Kategori</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <form action="/editslider/{{$item->slug}}" method="post" enctype="multipart/form-data">
    @csrf
    
    <div class="modal-body">
            
            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" name="name" id="name" class="form-control" value="{{$item->name}}">
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Slider</label>
                <input type="file" name="image" id="image" class="form-control mb-3">
                <input type="hidden" name="old_image" value="{{ $item->cover }}">
                <img id="image-preview" src="{{$item->cover != null ? asset('storage/slider/'. $item->cover) : asset('images/no-image.png')}}" alt="Preview Gambar" style="max-width: 200px; max-height: 200px">

            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <input type="text" name="description" id="description" class="form-control" value="{{$item->description}}">
            </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondarySmall" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-success"><i class="bi bi-save"></i> Perbarui</button>
    </div>
    </form>

    <script>
        function previewImage(input) {
            var preview = document.getElementById('image-preview');
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = "{{ asset('storage/cover/'. $item->cover) }}";
            }
        }
    </script>
