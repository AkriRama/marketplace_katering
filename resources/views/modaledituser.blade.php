    <div class="modal-header">
        <h1 class="modal-title fs-5" id="userEditModalLabel">Edit User</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <form action="/edituser/{{$item->slug}}" method="post"  enctype="multipart/form-data">
    @csrf
    <div class="modal-body">

    <div class="card shadow p-3 mt-3">
        <div class="row">
            <div class="col-sm-5">
                <img src="{{$item->photo_profile != null ? asset('storage/photoprofile/'. $item->photo_profile) : asset('images/no-profile.png')}}" class="img-fluid block" alt="" style="max-width: 100%; max-height: 50vh;">
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label for="username">Nama</label>
                    <input type="text" id="username" name="username" class="form-control" value="{{$item->username}}">
                </div>
                
                
                <div class="mb-3">
                    <label for="phone">No HP</label>
                    <input type="text" id="phone" name="phone" class="form-control" value="{{$item->phone}}">
                </div>
                
                <div class="mb-3">
                    <label for="address">Alamat</label>
                    <input type="text" id="address" name="address" class="form-control" value="{{$item->address}}">
                </div>

                <div class="mb-3">
                    <label for="staus">Status</label>
                    <input type="text" id="status" class="form-control" value="{{$item->status}}" >
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondarySmall" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-success" class="form-control" type="submit"><i class="bi bi-save"></i> Simpan</button>
    </div>
    </form>