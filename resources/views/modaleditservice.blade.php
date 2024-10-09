    <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Kategori</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <form action="editservice/{{$item->slug}}" method="post" enctype="multipart/form-data">
    @csrf
    
    <div class="modal-body">
    
        <div class="mb-3">
            <label for="name" class="form-label">Nama Jasa</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $item->name }}">
        </div>
        
        <div class="mb-3">
            <label for="price" class="form-label">Harga Jasa</label>
            <input type="text" name="price" id="price" class="form-control" value="{{ $item->price }}">
        </div>
        
        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <input type="text" name="description" id="description" class="form-control" value="{{ $item->description }}">
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" name="image" id="image" class="form-control mb-3">
            <input type="hidden" name="old_image" value="{{ $item->cover }}">
            <img id="image-preview" src="{{$item->cover != null ? asset('storage/coverService/'. $item->cover) : asset('images/no-image.png')}}" alt="Preview Gambar" style="max-width: 200px; max-height: 200px">
        </div>

        <div>
            <label for="products" class="form-label">Kategori</label>
            <select name="products[]" id="products" class="form-control multiple-select2" multiple>
                @foreach($products as $items)
                    <option value="{{$item->id}}">{{$items->name}}</option>
                @endforeach
            </select>
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondarySmall" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-success"><i class="bi bi-save"></i> Perbarui</button>
    </div>
    </form>