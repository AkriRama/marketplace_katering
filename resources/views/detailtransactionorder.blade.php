    <div class="card shadow p-5" style="text-align:left; border-radius: 20px; height: 100%;">
            <div>
                <h3>Rincian Pembayaran</h3>
            </div>
            
            <div class="card mb-3" style="background:black; height: 2px;"></div>
            
            <div class="row" style="font-size: 12px;">
            <table class="table">
                <tr>
                    <th>No Pesanan</th>
                    <td>:</td>
                    <td>{{$order->order_id}}</td>

                    <th>Tanggal Pesanan</th>
                    <td>:</td>
                    <td>{{$order->created_at->format('d-m-Y')}}</td>
                </tr>
                
                <tr>
                    <th>Nama Pelanggan</th>
                    <td>:</td>
                    <td>{{Auth::user()->username}}</td>

                    <th>Waktu Pesanan</th>
                    <td>:</td>
                    <td>{{$order->created_at->format('H:i:s')}}</td>
                </tr>
            </table>
            
            </div>
            
            <div class="card mb-3" style="background:black; height: 2px;"></div>
            
            <div>
                <table class="table" style="font-size: 10px;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang/Jasa</th>
                            <th style="text-align:center;">Jumlah</th>
                            <th style="text-align:right;">Harga</th>
                            <th style="text-align:right;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $item)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$item->products->name}}</td>
                            <td style="text-align:center;">X {{$item->quantity}}</td>
                            <td style="text-align:right;">{{number_format($item->products->price, 0, ',', '.')}}</td>
                            <td style="text-align:right;">{{number_format($item->total, 0, ',', '.')}}</td>
                        </tr>
                        @endforeach
                    </tbody>    

                    <tfoot>
                        <tr>
                            <th colspan="4" style="text-align:right;">Total Keseluruhan</th>
                            <td style="text-align:right;">{{number_format($order->totalOrder, 0, ',', '.')}}</td>
                        </tr>
                        @if(isset($transactions) && isset($cash))
                        <tr>
                            <th colspan="4" style="text-align:right;">Tunai</th>
                            <td style="text-align:right;">{{number_format($cash, 0, ',', '.')}}</td>
                        </tr>
                        <tr>
                            <th colspan="4" style="text-align:right;">Kembalian</th>
                            <td style="text-align:right;">{{number_format($change, 0, ',', '.')}}</td>
                        </tr>
                        
                        @else
                        <tr>
                            <th colspan="4" style="text-align:right;">Tunai</th>
                            <th></th>
                        </tr>
                        <tr>
                            <th colspan="4" style="text-align:right;">Kembalian</th>
                            <th></th>
                        </tr>
                        @endif
                    </tfoot>
                </table>
            </div> 

    </div>

    <form action="exportOrderPDF">
        <div class="mt-3 d-flex justify-content-end">
            @if(isset($cash) && isset($change) && isset($transactions))
            <input hidden type="text" name="cash" value="{{$cash}}">
            <input hidden type="text" name="change" value="{{$change}}">
            <button class="btn btn-navySmall"><i class="bi bi-printer"></i> Cetak</button>
            @else
            <div style="max-width: 100%;">
                <button class="btn btn-secondary" disabled><i class="bi bi-printer"></i> Cetak</button>
            </div>

            @endif
        </div>
    </form>