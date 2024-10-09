@extends('layouts.mainlayouts')

@section('title','Detail Transaction')

@section('content')

<style>

    .card-img-top {
        max-width: 50%;
        max-height: 100%;
    }

    .custom-card {
        
    }

    .card-custom-reverse {
        --bs-bg-opacity: .15;
        border-radius: 0px 0px 10px 10px;
        display: flex;
        flex-direction: column;
        justify-content: center; 
        align-items: bottom;
        padding: 20px;
        height: 3rem; 
        background: #173551;
    }

    /* .card-custom5 {
        height:20rem:
        border-radius: 20px 20px 20px 20px;
    }

    .custom-checkbox {
        position: relative;
    }

    .custom-checkbox input[type="checkbox"] {
        display: none;
    }

    .custom-checkbox label {
        padding-left: 25px;
        position: relative;
        cursor: pointer;
    }

    .custom-checkbox label:before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 20px;
        height: 20px;
        border: 1px solid #ccc;
        background-color: #fff;
    } */

    /* .custom-checkbox input[type="checkbox"]:checked + label:after {
        content: '\2713';  Unicode character for a checkmark
        position: absolute;
        left: 5px;
        top: 2px;
        color: #000;
        font-weight: bold;
    } */
</style>

<div class="container mt-5 mb-5 col-sm-12 col-md-8 col-lg-6 col-12">
    
    <div class="mt-3">
        @if(session('status'))
            <div class="alert alert-danger">
                {{ session('status') }}
            </div>

        @endif
    </div>

    @if($cart && count($cart) > 0)
    <div class="btn"></div>
    <input type="checkbox" id="select-all"/> Select All
    @foreach($cart as $item)
    <div class="card mb-3 p-2 shadow-sm"> 
        <div class="row">
            <div class="col-sm-1 col-2 my-auto ms-2">
                <div class="btn" aria-label="Basic checkbox toggle button group">
                    <input  type="checkbox" class="item-checkbox" data-price="{{$item->price}}" autocomplete="off" style="width: 25px; height: 25px;">
                </div>
            </div>

            <div class="col-sm-4 col-md-4 col-lg-4 col-5 d-flex flex-column justify-content-center" style="min-height:100px;">
                <img src="{{ $item->cover != null ? asset('storage/cover/'. $item->cover) : asset('images/no-image.png')}}" class="card-img-top" alt="" >
            </div>
            
            <div class="col-sm-4 col-4" style="margin-left: -15%;">
                <h6>{{$item->name}}</h6>
                <p>Rp.{{ number_format($item->price) }}</p>
                <p>X {{$item->quantity}}</p>
                <p id="totalprice">Rp.{{ number_format($item->total) }}</p>
                
            </div>

            @if($item->product_service == "service")
                <div class="col-sm-4 d-flex justify-content-end" style="margin:auto; height:8rem;">
                    <a href="cartdeleteService/{{$item->service_id}}" class="btn btn-transparent d-flex flex-column justify-content-center shadow-sm" style="--bs-text-opacity: 1; text-align:center;">Hapus</a>
                </div>
            @else
                <div class="col-sm-4 d-flex justify-content-end" style="margin:auto; height:8rem;">
                    <a href="cartdelete/{{$item->product_id}}" class="btn btn-transparent d-flex flex-column justify-content-center shadow-sm" style="--bs-text-opacity: 1; text-align:center;">Hapus</a>
                </div>
            @endif
        </div>
    </div>
    @endforeach
    
    <form action="checkout/{{$orders->order_id}}" method="post">
        @csrf
        
        <div class="card card-custom-reverse text-white" style="">
            <div class="row">
                <div class="col-sm-4 col-4 d-flex justify-content-center flex-column align-items-center">
                    <div class="text-center fw-bold fs-5">Total Harga</div>
                </div>
                
                <div class="col-sm-4 col-4 d-flex justify-content-center flex-column" >
                    <div id="total">Rp{{ number_format($orders->totalOrder, 0, ',', '.')}}</div>
                </div>
                
                <div class="col-sm-4 col-4 d-flex justify-content-end my-auto">
                    <button class="btn bg-light fw-bold" type="submit" id="checkoutButton">Checkout</button>
                </div>
            </div>
        </div>
        
    </form>

    @else
    <div class="card d-flex align-items-center" style="border: 0;">
        <h1>Keranjang Kosong</h1>
        <p class="fs-6">Silahkan cari barang yang dibutuhkan</p>
        <div class=""><i class="bi bi-cart-x" style="font-size: 100px;"></i></div>
        <div>
            <a href="product" class="btn btn-navy">Cari Barang</a>
        </div>
    </div>
    @endif

</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.increase').click(function() {
            let id = $(this).data('id');
            let input = $('#quantity_' + id);
            input.val(parseInt(input.val()) + 1);
            // Anda dapat menambahkan logika AJAX di sini untuk memperbarui kuantitas di database
        });

        $('.decrease').click(function() {
            let id = $(this).data('id');
            let input = $('#quantity_' + id);
            if (parseInt(input.val()) > 1) { // Pastikan kuantitas tidak kurang dari 1
                input.val(parseInt(input.val()) - 1);
                // Anda dapat menambahkan logika AJAX di sini untuk memperbarui kuantitas di database
            }
        });
    });
</script>

<script>
$(document).ready(function() {
    // Ketika kotak centang master diklik
    $('#select-all').click(function() {
        // Jika kotak centang master dicentang
        if ($(this).prop('checked')) {
            $('.item-checkbox').prop('checked', true);  // Centang semua kotak centang individual
        } else {
            $('.item-checkbox').prop('checked', false); // Hapus centang dari semua kotak centang individual
        }
    });

    // Opsi tambahan: Jika salah satu kotak centang individual tidak dicentang, batalkan pilihan kotak centang master
    $('.item-checkbox').click(function() {
                let checkboxes = document.querySelectorAll('.item-checkbox');
                let totalElement = document.getElementById('total');
            
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        // let currentTotal = parseInt(totalElement.innerText.replace('Rp.', ''));
                        let currentTotal = document.getElementById('totalprice');
                        let itemPrice = parseInt(this.getAttribute('data-price'));
                if ($('.item-checkbox:checked').length != $('.item-checkbox').length) {
                    $('#select-all').prop('checked', false);
                    currentTotal += itemPrice;
                } else {
                    $('#select-all').prop('checked', true);
                    currentTotal -= itemPrice;
                }
                totalElement.innerText = 'Rp.' + currentTotal.toFixed(3);
            });
        });  
    });
});
</script>

<script>
    $(document).ready(function() {
        $('.increase').click(function() {
            let id = $(this).data('id');
            let input = $('#quantity_' + id);
            input.val(parseInt(input.val()) + 1);
            // Anda dapat menambahkan logika AJAX di sini untuk memperbarui kuantitas di database
        });

        $('.decrease').click(function() {
            let id = $(this).data('id');
            let input = $('#quantity_' + id);
            if (parseInt(input.val()) > 1) { // Pastikan kuantitas tidak kurang dari 1
                input.val(parseInt(input.val()) - 1);
                // Anda dapat menambahkan logika AJAX di sini untuk memperbarui kuantitas di database
            }
        });
    });
</script>





@endsection