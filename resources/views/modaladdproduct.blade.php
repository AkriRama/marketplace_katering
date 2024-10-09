<div class="modal-header">
        <h1 class="modal-title fs-5" id="serviceAddModelLabel">Tambah Barang</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <form action="addproduct" method="post" enctype="multipart/form-data">
    @csrf
    
    <div class="modal-body">
            
        <div>
            <label for="code_product" class="form-label">Kode Barang</label>
            <input type="text" name="code_product" id="code_product" class="form-control" placeholder="Kode Barang" value="{{ old('code_product') }}">
        </div>
        
        <div class="mb-3">
            <label for="name" class="form-label">Nama Barang</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Nama Barang" value="{{ old('name') }}">
        </div>
        
        <div class="mb-3">
            <label for="price" class="form-label">Harga Barang</label>
            <input type="text" name="price" id="price" class="form-control" placeholder="Harga Barang" value="{{ old('price') }}">
        </div>
        
        <div class="mb-3">
            <label for="discount" class="form-label">Diskon Barang (%)</label>
            <input type="text" name="discount" id="discount" class="form-control" placeholder="Diskon Barang" value="{{ old('discount') }}">
        </div>
        
        <div class="mb-3">
            <label for="stock" class="form-label">Stok Barang</label>
            <input type="text" name="stock" id="stock" class="form-control" placeholder="Stok Barang" value="{{ old('stock') }}">
        </div>
        
        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <input type="text" name="description" id="description" class="form-control" placeholder="Deskripsi Barang" value="{{ old('description') }}">
        </div>
        
        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" name="image" id="image" class="form-control mb-3">
            <img id="image-preview" src="" alt="Preview Image" style="display: none; max-width: 200px; max-height: 200px"/>
        </div>

        <div>
            <label for="categories" class="form-label">Kategori</label>
            <select name="categories[]" id="categories" class="form-control multiple-select2" multiple>
                @foreach($categories as $item)
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


    <script>
        $(document).ready(function() {
        $('.multiple-select2').select2();
        });
    </script>   

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
