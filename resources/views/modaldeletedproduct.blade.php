    @if(count($deletedProducts) > 0)
    <div class="modal-header">
        <h1 class="modal-title fs-5">Data Barang Terhapus</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
        
    <div class="modal-body">
            
        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Cover</th>
                    <th>Kode Barang</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deletedProducts as $item)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>
                    <img src="{{$item->cover != null ? asset('storage/cover/'. $item->cover) : asset('images/no-image.png')}}" alt="" style="max-width: 3rem; max-height: 3rem;">    
                    </td>
                    <td>{{$item->code_product}}</td>
                    <td>{{$item->name}}</td>
                    <td>{{number_format($item->price, 0, ',', '.')}}</td>
                    <td>{{$item->stock}}</td>
                    <td>{{$item->description}}</td>
                    <td>
                        <a href="restoreproduct/{{$item->slug}}"><i class="fas fa-trash-restore"></i> Pulihkan</a>
                        <a>|</a>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#productDeletePermanentModal{{$item->slug}}" >Hapus Permanen <i class="fa fa-trash" aria-hidden="true"></i></a>
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
        <h1 class="modal-title fs-5">Barang Terhapus</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
        
    <div class="modal-body">
        
        <div class="text-center">Tidak ada data barang terhapus</div>      
        
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondarySmall" data-bs-dismiss="modal">Close</button>
        
    </div>

    @endif