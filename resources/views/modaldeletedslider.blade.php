    @if(count($deletedSliders) > 0)
    <div class="modal-header">
        <h1 class="modal-title fs-5">Slider Terhapus</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
        
    <div class="modal-body">
            
        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Slider</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deletedSliders as $item)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$item->name}}</td>
                    <td><img src="{{$item->cover != null ? asset('storage/slider/'. $item->cover) : asset('images/no-image.png')}}" alt="" style="max-width: 3rem; max-height: 3rem;"></td>
                    <td>{{$item->description}}</td>
                    <td>
                        <a href="restoreslider/{{$item->slug}}"><i class="fas fa-trash-restore"></i> Pulihkan</a>
                        <a>|</a>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#sliderDeletePermanentModal{{$item->slug}}" >Hapus Permanen <i class="fa fa-trash" aria-hidden="true"></i></a>
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
        <h1 class="modal-title fs-5">Slider Terhapus</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
        
    <div class="modal-body">
        
        <div class="text-center">Tidak ada data slider terhapus</div>      
        
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondarySmall" data-bs-dismiss="modal">Close</button>
        
    </div>

    @endif