<div class="p-2"style="height: 100%; width: 100%;">

<div class="card shadow p-5" style="text-align:left; border-radius: 20px; height: 100%;">
        <div>
            <h3>Rincian Pembelian</h3>
        </div>
        
        <div class="card mb-3" style="background:black; height: 2px;"></div>
        
        <div class="row" style="font-size: 12px;">
        <table class="table">
            <tr>
                <th>No Pembelian</th>
                <td>:</td>
                <td>{{$purchases->first()->purchase_id}}</td>

                <th>Tanggal Pembelian</th>
                <td>:</td>
                <td>{{$purchases->first()->created_at->format('d-m-Y')}}</td>
            </tr>
            
            <tr>
                <th>Nama Penerima</th>
                <td>:</td>
                <td>{{$users->username}}</td>

                <th>Waktu Pembelian</th>
                <td>:</td>
                <td>{{$purchases->first()->created_at->format('H:i:s')}}</td>
            </tr>
        </table>
        
        </div>
        
        <div class="card mb-3" style="background:black; height: 2px;"></div>
        
        <div>
            <table class="table" style="font-size: 10px;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th style="text-align:center;">Jumlah</th>
                        <th style="text-align:right;">Harga</th>
                        <th style="text-align:right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($item as $items)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$items->name}}</td>
                        <td style="text-align:center;">X {{$item->quantity}}</td>
                        <td style="text-align:right;">{{number_format($items->price, 0, ',', '.')}}</td>
                        <td style="text-align:right;">{{number_format($items->total, 0, ',', '.')}}</td>
                    </tr>
                    @endforeach
                </tbody>    

                <tfoot>
                    <tr>
                        <th colspan="4" style="text-align:right;">Total Keseluruhan</th>
                        <td style="text-align:right;">{{number_format($purchases->first()->total, 0, ',', '.')}}</td>
                    </tr>
                </tfoot>
            </table>
        </div> 

</div>

<div class="mt-3 d-flex justify-content-end">
    <button class="btn btn-navySmall"><i class="bi bi-printer"></i> print</button>
</div>

</div>