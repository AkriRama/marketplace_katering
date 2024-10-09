@if(count($deletedExpenses) > 0)
    <div class="modal-header">
        <h1 class="modal-title fs-5">Data Pengeluaran Terhapus</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
        
    <div class="modal-body">
            
        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Pengeluaran</th>
                    <th>Deskripsi</th>
                    <th>Total Pengeluaran</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deletedExpenses as $item)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$item->name}}</td>
                    <td>{{$item->description}}</td>
                    <td>{{number_format($item->expense, 0, ',', '.')}}</td>
                    <td>
                        <a href="restoreexpenses/{{$item->slug}}"><i class="fas fa-trash-restore"></i> Pulihkan</a>
                        <a>|</a>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#expensesDeletePermanentModal{{$item->slug}}" >Hapus Permanen <i class="fa fa-trash" aria-hidden="true"></i></a>
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
        <h1 class="modal-title fs-5">Data Pengeluaran Terhapus</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
        
    <div class="modal-body">
        
        <div class="text-center">Tidak ada data pengeluaran terhapus</div>      
        
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondarySmall" data-bs-dismiss="modal">Close</button>
        
    </div>

    @endif