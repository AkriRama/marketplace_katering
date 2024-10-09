    <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Kategori</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <form action="/editcategory/{{$item->slug}}" method="post">
    @csrf
    
    <div class="modal-body">
    
        <div>
            <label for="name" class="form-label">Nama</label>
            <input type="text" name="name" id="name" class="form-control" value="{{$item->name}}" placeholder="Nama Kategori">
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondarySmall" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-success"><i class="bi bi-save"></i> Perbarui</button>
    </div>
    </form>