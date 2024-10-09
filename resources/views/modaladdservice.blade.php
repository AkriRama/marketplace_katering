    <div class="modal-header">
        <h1 class="modal-title fs-5" id="serviceAddModelLabel">Tambah Barang</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <form action="addservice" method="post" enctype="multipart/form-data">
    @csrf
    
    <div class="modal-body">
            
            
    <div class="mb-3">
                <label for="name" class="form-label">Nama Jasa</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
            </div>
            
            <div class="mb-3">
                <label for="price" class="form-label">Harga Jasa</label>
                <input type="text" name="price" id="price" class="form-control" value="{{ old('price') }}">
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <input type="text" name="description" id="description" class="form-control" placeholder="Deskripsi Barang" value="{{ old('description') }}">
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" name="image" id="image" class="form-control mb-3">
                <img id="image-preview" src="" alt="Preview Image" style="display: none; max-width: 200px; max-height: 200px;" />
            </div>

            <div>
                <label for="products" class="form-label">Barang</label>
                <select name="products[]" id="products" class="form-control multiple-select2" multiple>
                    @foreach($products as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach
                </select>
            </div>
        
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondarySmall" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-success"><i class="bi bi-save"></i> Simpan</button>
    </div>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#image').change(function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#image-preview').attr('src', e.target.result);
                        $('#image-preview').show();
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#image-preview').attr('src', '');
                    $('#image-preview').hide();
                }
            });
        });
    </script>
