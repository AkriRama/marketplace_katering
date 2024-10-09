    @if(count($deletedusers) > 0)
    <div class="modal-header">
        <h1 class="modal-title fs-5" id="userAddModalLabel">Daftar User Terhapus</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
        
    <div class="modal-body">
            
        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deletedusers as $item)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$item->username}}</td>
                    <td>
                        <a href="restoreuser/{{$item->slug}}"><i class="fas fa-trash-restore"></i> Pulihkan</a>
                        <a>|</a>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#userDeletePermanentModal{{$item->slug}}" >Hapus Permanen <i class="fa fa-trash" aria-hidden="true"></i></a>
                        
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>            
        
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondarySmall" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-success"><i class="bi bi-save"></i> Simpan</button>
    </div>
    
    @else
    <div class="modal-header">
        <h1 class="modal-title fs-5" id="userAddModalLabel">Daftar User Terhapus</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
        
    <div class="modal-body">
                    
        <div class="text-center">Tidak ada data user terhapus</div>
        
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondarySmall" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-success"><i class="bi bi-save"></i> Simpan</button>
    </div>


    @endif