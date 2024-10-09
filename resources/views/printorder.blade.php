<style>
                        .vertical-align-middle {
                            display: flex;

}
                    </style>
                    <table class="table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th class="my-auto" style="text-align: left;"><img src="{{public_path('images/logo2.png')}}"alt="" style="max-height: 50px;"></th>
                                <td class="vertical-align-middle" style="font-size: 12px; text-align: center;">Jl. Merapi, Bandar Jaya <br> Bandar Lampung <br> No Telp. 0852-8182-6956</td>
                                <th class="my-auto" style="text-align: left;"><img src="{{public_path('images/Transparent.png')}}"alt="" style="max-height: 50px;"></th>
                                
                            </tr>
                        </thead>
                    </table>
                    
                    <div class="card" style="height:2px; background:black; margin-top: 10px; margin-bottom: 10px;"></div>
                    <div class="row mt-2">
                        <div class="col-6">
                            <table class="table table-borderless" style="font-size: 13px; width: 100%;">
                                <tr>
                                    <th style="text-align: left;">No Pemesanan</th>
                                    <td>:</td>
                                    <td>{{$order->order_id}}</td>
                                    <td></td>
                                    <th style="text-align: right;">Tanggal</th>
                                    <td>:</td>
                                    <td style="text-align: right;">{{$order->created_at->format('d-m-Y')}}</td>
                                </tr>
                                
                                <tr>
                                    <th style="text-align: left;">Petugas</th>
                                    <td>:</td>
                                    <td>{{Auth::user()->username}}</td>
                                    <td></td>
                                    <th style="text-align: right;">Waktu</th>
                                    <td>:</td>
                                    <td  style="text-align: right;">{{$order->created_at->format('H:i:s')}}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="card" style="height:2px; background:black; margin-top: 10px; margin-bottom: 10px;"></div>
                        <table class="table table-bordered"  style="font-size: 13px; width: 100%; border-collapse: separate; border-spacing: 0 10px;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th style="text-align: right;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $item)
                                <tr>
                                    <td style="text-align: center;">{{$loop->iteration}}</td>
                                    <td style="text-align: center;">{{$item->products->code_product}}</td>
                                    <td style="text-align: center;">{{$item->products->name}}</td>
                                    <td style="text-align: center;">{{$item->quantity}}</td>
                                    <td style="text-align: center;">{{number_format($item->products->price, 0, ',', '.')}}</td>
                                    <td style="text-align: right;">{{number_format($item->total, 0, ',', '.')}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            
                            <tfoot>
                                
                                <tr>
                                    <th colspan="6"><div class="card" style="height:2px; width: 100%; background:black; margin-top: 10px; margin-bottom: 10px;"></div></th>
                                </tr>
                                <tr>
                                    <th colspan="5" style="text-align: right;">Total Keseluruhan</th>
                                    <td style="text-align: right;">{{number_format($order->total, 0, ',', '.')}}</td>
                                </tr>
                                <tr>
                                    <th colspan="5" style="text-align: right;">Tunai</th>
                                    <td style="text-align: right;">{{number_format($cash, 0, ',', '.')}}</td>
                                </tr>
                                <tr>
                                    <th colspan="5" style="text-align: right;">Kembalian</th>
                                    <td style="text-align: right;">{{number_format($change, 0, ',', '.')}}</td>
                                </tr>
                            </tfoot>
                        </table>
    
            </div>
  