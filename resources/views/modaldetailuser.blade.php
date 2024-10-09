    <div class="modal-header">
        <h1 class="modal-title fs-5" id="userDetailModalLabel">Detail User</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        
    <div class="card shadow p-3 mt-3">
        <div class="row">
            <div class="col-sm-5">
                <img src="{{$item->photo_profile != null ? asset('storage/photoprofile/'. $item->photo_profile) : asset('images/no-profile.png')}}" class="img-fluid block" alt="" style="max-width: 100%; max-height: 50vh;">
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label for="username">Nama Pengguna</label>
                    <input type="text" id="username" class="form-control" value="{{$item->username}}" disabled >
                </div>
                
                <div class="mb-3">
                    <label for="password">Kata Sandi</label>
                    <input type="password" id="password" class="form-control" value="" disabled >
                </div>
                
                <div class="mb-3">
                    <label for="phone">No HP</label>
                    <input type="text" id="phone" class="form-control" value="{{$item->phone}}" disabled >
                </div>
                
                <div class="mb-3">
                    <label for="address">Alamat</label>
                    <input type="text" id="address" class="form-control" value="{{$item->address}}" disabled >
                </div>

                <div class="mb-3">
                    <label for="staus">Status</label>
                    <input type="text" id="status" class="form-control" value="{{$item->status}}" disabled >
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondarySmall" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#userEditModal{{$item->slug}}"><i class="bi bi-save"></i> Perbarui</button>
        
    </div>