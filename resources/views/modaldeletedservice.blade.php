    @if(count($deletedServices) > 0 )
    <div class="modal-header">
        <h1 class="modal-title fs-5">Pelayanan Terhapus</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
        
    <div class="modal-body">
            
    <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Cover</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deletedServices as $item)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>
                    <img src="{{$item->cover != null ? asset('storage/coverService/'. $item->cover) : asset('images/no-image.png')}}" alt="" style="max-width: 5rem; max-height: 5rem;">
                    </td>
                    <td>{{$item->name}}</td>
                    <td>{{number_format($item->price, 0, ',', '.')}}</td>
                    <td>{{$item->description}}</td>
                    <td>
                        <a href="restoreservice/{{$item->slug}}"><i class="fas fa-trash-restore"></i> Pulihkan</a>
                        <a>|</a>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#serviceDeletePermanentModal{{$item->slug}}">Hapus Permanen <i class="fa fa-trash" aria-hidden="true"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>     
        
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondarySmall" data-bs-dismiss="modal">Close</button>
        
    </div>
    
    @else
    <div class="modal-header">
        <h1 class="modal-title fs-5">Pelayanan Terhapus</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
        
    <div class="modal-body">
        
        <div class="text-center">Tidak ada data pelayanan terhapus</div>      
        
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondarySmall" data-bs-dismiss="modal">Close</button>
        
    </div>

    @endif