@extends('layouts.mainlayoutsadmin')

@section('title','Dashboard')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    
    .card-data-service {
        border:solid;
        padding: 20px 30px 31px;
        background-color: #FF5F00;
        color: #fff;
        position: relative;
    }
    
    .card-data-product {
        border:solid;
        padding: 20px 30px 31px;
        background-color: #1B03A3;
        color: #fff;
        position: relative;
    }
    
    .card-data-user {
        border:solid;
        padding: 20px 30px 31px;
        background-color: #FFA500;
        color: #fff;
        position: relative;
    }
    
    .card-data-transaction {
        border:solid;
        padding: 20px 30px 31px;
        background-color: #008000;
        color: #fff;
        position: relative;
    }

    .card-data-info {
        background-color: rgba(0, 0, 0, 0.2); 
        color: rgba(255, 255, 255, 1);
        width: 100%;
        text-align: center;
        left: 0;
        bottom: 0;
        position: absolute;
        font-size: 14px;
    }
    
    .card-data-info a {
        
        color: #fff;
        text-decoration: none;
        
    }
    .card-data-service, .card-data-product, .card-data-user, .card-data-transaction{
        font-size: 40px;
    }

    .card-desc {
        font-size: 20px;
    }

    .card-count {
        font-size: 25px;
    }
</style>
        <div class="row">
            <div class="col-3 mb-3">
                <div class="card-data-product">
                    <div class="row">
                        <div class="col-6">
                            <i class="bi bi-box-seam-fill"></i>
                        </div>
                        <div class="col-6 d-flex flex-column justify-content-center align-items-end">
                            <div class="card-desc">Barang</div>
                        <div class="card-count">{{$product_count}}</div>
                    </div>
                    
                    <div class="card-data-info">
                        <a href="/adminproduct">
                            Detail <i class="bi bi-arrow-right-circle"></i>
                        </a>    
                    </div>
                </div>
                
            </div>
        </div>
 
        <div class="col-3">
            <div class="card-data-transaction">
                <div class="row">
                    <div class="col-6">
                        <i class="bi bi-bag-check-fill"></i>
                    </div>
                    <div class="col-6 d-flex flex-column justify-content-center align-items-end">
                        <div class="card-desc">Transaksi</div>
                        <div class="card-count">{{$transaction_count}}</div>
                    </div>
                </div>

                <div class="card-data-info">
                    <a href="/admintransaction">
                        Detail <i class="bi bi-arrow-right-circle"></i>
                    </a>    
                </div>
            </div>
        </div>
    
    <div class="container mx-auto mt-2"  style="width: 90%">

        <div class="p-5 bg-white rounded shadow">
            {!! $chart->container() !!}
        </div>

    </div>

    <script src="{{ $chart->cdn() }}"></script>

    {{ $chart->script() }}    
</div>

@endsection