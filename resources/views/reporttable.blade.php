        <table class="table table-bordered">
            <thead>
                <tr class="table table-dark">
                    <th>No</th>
                    <th>Tanggal Pesanan</th>
                    <th>Kode Pesanan</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
            </thead>
            
            @if(isset($transactions) && count($transactions) > 0)
            <tbody>
                @foreach($transactions as $item)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$item->created_at}}</td>
                    <td>{{$item->order_id}}</td>
                    <td></td>
                    <td>{{$item->name}}</td>
                    <td>{{$item->quantity}}</td>
                    <td>{{number_format($item->price, 0, ',', '.')}}</td>
                    <td style="text-align: right;">{{number_format($item->total, 0, ',', '.')}}</td>
                </tr>
                @endforeach
            </tbody>

            @else
            <tbody>
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data yang tersedia ditabel</td>
                </tr>
            </tbody>
            @endif

            <tfoot>
                <tr class="table table-dark">
                    <th colspan="7" style="text-align: right;">Total Keseluruhan</th>
                    <th style="text-align: right;">{{number_format($totalTransaction, 0, ',', '.')}}</th>
                </tr>
            </tfoot>
        
        </table>