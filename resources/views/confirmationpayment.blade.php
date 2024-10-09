@extends('layouts.mainlayouts')

@section('title','Konfirmasi Pembayaran')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <div class="container d-flex flex-column justify-content-center align-items-center mb-3 mt-3" style="text-align:center;">
        <div
         class="mt-2 mb-2">
            <h2>Detail Pembayaran</h2>
        </div>
        
        <div class="card col-lg-6 col-md-8 col-sm-12 p-4 shadow" style="text-align:left;">
            <div>
                <h3>Rincian Pembayaran</h3>
            </div>
            
            <div class="card mb-3" style="background:black; height: 2px;"></div>
            <div class="row">
                <div class="col-sm-4 col-md-3">
                        <p>No Pesanan</p>
                        <p>Nama Pelanggan</p>
                </div>
                <div class="col">
                    <p>: {{$orders->order_id}}</p>
                    <p>: {{Auth::user()->username}}</p>
                </div>
            </div>
            
            <div class="card mb-3" style="background:black; height: 2px;"></div>
            
            <div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Barang/Jasa</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($carts as $item)
                        <tr>
                            <td>{{$item->name}}</td>
                            <td>X {{$item->quantity}}</td>
                            <td>Rp.{{number_format($item->price)}}</td>
                        </tr>
                        @endforeach
                    </tbody>    

                    <tfoot>
                        <tr>
                            <th colspan="2" style="text-align:right;">Total Keseluruhan</th>
                            <th>Rp.{{number_format($orders->totalOrder)}}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="card mb-3" style="background:black; height: 2px;"></div>
            <form action="/paynow/{{$orders->order_id}}" method="post">
                @csrf
            
            <div class="mb-3">
                <label for="payment" class="fw-bold mb-3">Metode Pembayaran</label><br>
                <select name="payment" id="payment" style="width: 10rem; text-align: center; " id="">
                    @foreach($payments as $payments)
                    <option value="{{$payments->id}}">{{$payments->name}}</option>
                    @endforeach
                </select>

                    <input type="checkbox" class="btn-check" id="btncheck1" autocomplete="off">
                    <label class="btn btn-outline-success" for="btncheck1">BRI</label>

                    <input type="checkbox" class="btn-check" id="btncheck2" autocomplete="off">
                    <label class="btn btn-outline-primary" for="btncheck2">BCA</label>

                    <input type="checkbox" class="btn-check" id="btncheck3" autocomplete="off">
                    <label class="btn btn-outline-primary" for="btncheck3">Tunai</label>
            </div>
            
            <div class="card mb-3" style="background:black; height: 2px;"></div>

            <div class="d-flex flex-column justify-content-end align-items-end">
                <input type="hidden" name="quantity" value=" ">
                <button class="btn text-light" style="width:10rem; background: #072541; border-radius: 5px;">Bayar Sekarang</button>
            </div>
        </form>
    </div>
</div>

@endsection