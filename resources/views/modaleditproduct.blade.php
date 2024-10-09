<div class="modal-header">
        <h1 class="modal-title fs-5" id="productEditModalLabel">Edit Barang</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <form action="/editproduct/{{$item->slug}}" method="post" enctype="multipart/form-data">
    @csrf
    
    <div class="modal-body">
    
            
        <div class="mb-3">
            <label for="code_product" class="form-label">Kode Barang</label>
            <input type="text" name="code_product" id="code_product" class="form-control" value="{{$item->code_product}}" placeholder="Kode Barang">
        </div class="mb-3">

        <div>
            <label for="name" class="form-label">Nama</label>
            <input type="text" name="name" id="name" class="form-control" value="{{$item->name}}" placeholder="Nama Barang">
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Harga Barang</label>
            <input type="text" name="price" id="price" class="form-control" value="{{$item->price}}" placeholder="Harga Barang">
        </div>
        
        <div class="mb-3">
            <label for="discount" class="form-label">Diskon Barang (%)</label>
            <input type="text" name="discount" id="discount" class="form-control" value="{{$item->discount}}" placeholder="Diskon Barang">
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">Stok Barang</label>
            <input type="text" name="stock" id="stock" class="form-control" value="{{$item->stock}}" placeholder="Stok Barang">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <input type="text" name="description" id="descriptionname" class="form-control" value="{{$item->description}}" placeholder="Deskripsi">
        </div>
        
        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" name="image" id="image" accept="image/*" onchange="previewImage(this)" class="form-control mb-3">
            <input type="hidden" name="old_image" value="{{ $item->cover }}">
            <img id="image-preview" src="{{$item->cover != null ? asset('storage/cover/'. $item->cover) : asset('images/no-image.png')}}" alt="Preview Gambar" style="max-width: 200px; max-height: 200px">

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
        <button class="btn btn-success"><i class="bi bi-save"></i> Perbarui</button>
    </div>
    </form>

    <script>
        $(document).ready(function() {
        $('.multiple-select2').select2();
        });
    </script>

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
