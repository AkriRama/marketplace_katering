@extends('layouts.mainlayoutsadmin')

@section('title', 'Pembelian Barang')

@section('content')


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
    .modal-backdrop {
    opacity: 0.5 !important;
}
</style>

    <!-- Modal Tambah Barang -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Barang</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="addproduct" method="post" enctype="multipart/form-data">
            @csrf
        
        <div class="modal-body">
            
            
                
                <div>
                    <label for="code_product" class="form-label">Kode Barang</label>
                    <input type="text" name="code_product" id="code_product" class="form-control" placeholder="Kode Barang" value="{{ old('code_product') }}">
                </div>
                
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Barang</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Nama Barang" value="{{ old('name') }}">
                </div>
                
                <div class="mb-3">
                    <label for="price" class="form-label">Harga Barang</label>
                    <input type="text" name="price" id="price" class="form-control" placeholder="Harga Barang" value="{{ old('price') }}">
                </div>
                
                <div class="mb-3">
                    <label for="stock" class="form-label">Stok Barang</label>
                    <input type="text" name="stock" id="stock" class="form-control" placeholder="Stok Barang" value="{{ old('stock') }}">
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <input type="text" name="description" id="description" class="form-control" placeholder="Deskripsi Barang" value="{{ old('description') }}">
                </div>
                
                <div class="mb-3">
                    <label for="image" class="form-label">Image</label>
                    <input type="file" name="image" id="image" class="form-control">
                </div>

                <div>
                    <label for="categories" class="form-label">Kategori</label>
                    <select name="categories[]" id="categories" class="form-control multiple-select2" multiple>
                        @foreach($categories as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondarySmall" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-success"><i class="bi bi-save"></i> Simpan</button>
            </div>
        </form>
        </div>
    </div>
    </div>

    <!-- Modal Daftar/Cari Barang -->
    <div class="modal fade" id="exampleModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Cari Barang</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="">
                <div class="row mb-3 ">
                    <div class="col-6 my-auto" style="vertical-align: middle;">
                        <a href="#" class="btn btn-navySmall" data-bs-toggle="modal" data-bs-target="#addProductModal"><i class="bi bi-plus-square"></i> Tambah Data</a>
                    </div>
                    
                    <div class="col-6 d-flex justify-content-end">
                        <form action="" id="searchForm">

                        <div class="input-group flex-nowrap">
                            <!-- <span class="input-group-text" id="addon-wrapping">@</span>
                            <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="addon-wrapping"> -->
                            
                            <label for="search" class="input-group-text" style="border: 0; background: transparent;">Cari : </label>
                            <input type="text" class="form-control" name="search">
                        </div>
    
                            <!-- <label for="search" class="form-label">Cari : </label> -->
                            
                            <input type="hidden" name="from_modal" value="1">
                            <button hidden type="submit"></button>
                        </form>
                    </div>
                </div>
            <table id="searchResults" class="table table-bordered" style="font-size: 15px;">
                <thead class="table table-dark align-middle"  style="text-align: center;">
                    <tr>
                        <th>Kode Barang</th>
                        <th>Name</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($product as $item)
                    <tr>
                        <td>{{ $item->code_product }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>{{ $item->stock }}</td>
                        <td class="d-flex justify-content-center">
                            <form action="">
                                <input hidden type="text" name="code_product" value="{{$item->code_product}}">
                                <button type="submit" class="btn btn-navy" style="font-size: 10px;"><i class="bi bi-check2"></i> Pilih</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    
                </tbody>
            </table>
        </div>
        </div>
        <div class="modal-footer">
            <div style="width: 100%; height: 100%;">
                {{ $product->links('pagination::paginator-bengkel') }}
            </div>
        </div>
        </div>
    </div>
    </div>

    <!-- Modal Notifikasi Pembelian Barang -> (Iya/Tidak) -->
    <div class="modal fade" id="modalTransaction" tabindex="-1" aria-labelledby="modalTransaction" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Transaksi</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            Apakah Anda yakin ingin menyelesaikan transaksi ini?
        </div>
        <div class="modal-footer d-flex justify-content-center">
            
            <form @if(isset($cash)) action="paidpurchase/{{$order->purchase_id}}" method="post" @endif>
            @csrf
                <button type="submit" class="btn btn-navy">Yakin</button>
            </form>

            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
        </div>
        </div>
    </div>
    </div>
    

    <!-- Notifikasi Alert status/message -->
    <div class="position-relative d-flex justify-content-center" style="z-index: 99999;">
        <div class="position-absolute top-0">

            @if(session('status'))
            <div class="alert alert-danger" id="alertMessage">
                {{ session('status') }}
            </div>
            @elseif(session('message'))
            <div class="alert alert-success" id="alertMessage">
                {{ session('message') }}
            </div>
            @endif
        </div>
    </div>

    <div class="row">

        <!-- Pembelian Barang -->
        <div class="col-lg-5 col-12 position-relative" style="height: 100%;  font-size: 15px;">
    
            <div class="card p-5 shadow mb-1 fw-bold" style="border-radius: 30px 30px 0px 0px ; height: 100%;">
            
            
                <form action="">

                    <div class="row mb-2">
                        <div class="col-4">
                            <label for="code_product" class="fw-bold">Kode Barang</label>
                        </div>
                        <div class="col-8">

                            <div class="form-group input-group">
                                <input type="text" name="code_product" id="code_product" @if(isset($products)) value="{{ $products->code_product}}" @elseif(isset($purchases)) disabled @endif class="form-control submit"  onkeypress="return enterAsSubmit(event)">
                                
                                <div class="input-group-prepend">
                                    
                                    <span @if(empty($purchases)) class="input-group-text text-white btn" @else class="input-group-text text-white btn disabled" @endif data-bs-toggle="modal" data-bs-target="#exampleModal" style="border-radius: 0 3px 3px 0; background: #072541; height: 100%;">
                                        <i class="bi bi-search"></i>
                                    </span>
                                </div>
                            </div>

                            <button hidden type="submit">Cari</button>
                        </div>
                    </div>
        
                    <div class="row mb-2">
                        <div class="col-4">
                            <label for="name" class="fw-bold">Nama Barang</label>
                        </div>
                        <div class="col-8">
                            <input type="text" name="name" class="form-control" @if(isset($products)) value="{{ $products->name}}" @endif style="width: 100%;" disabled>
                        </div>
                    </div>
                    
                    <div class="row mb-2">
                        <div class="col-4">
                            <label for="price" class="fw-bold">Harga</label>
                        </div>
                        <div class="col-8">
                            <input type="text" name="price" class="form-control" @if(isset($products)) value="Rp{{number_format($products->price, 0, ',', '.')}}" @endif style="width: 100%;" disabled>
                        </div>
                    </div>
                    
                    <div class="row mb-2">
                        <div class="col-4">
                            <label for="quantity" class="fw-bold">Jumlah</label>
                        </div>
                        
                        <div class="col-8">

                            <div class="input-group">                            
                                <input type="text" name="quantity" class="form-control" id="numericInput" @if(isset($quantity)) value="{{$quantity}}" @elseif(isset($products)) value="0" @else disabled  @endif>
                                
                                <div class="input-group-append">
                                    <div class="btn-group-vertical" style="width: 40px;">
                                    
                                    <button @if(isset($products)) class="btn btn-navy py-0" @else class="btn btn-secondary py-0" disabled @endif style="font-size: 12px; border-top-left-radius: 0; border-bottom: 0; --bs-border-opacity: 0.1;" onclick="increase()" ><i class="fas fa-angle-up"></i></button>
                                    <button @if(isset($products)) class="btn btn-navy py-0" @else class="btn btn-secondary py-0" disabled @endif style="font-size: 12px; border-bottom-left-radius: 0; margin: 0;" onclick="decrease()"><i class="fas fa-angle-down"></i></button>
                                    </div>
                                </div>  
                            </div>

                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-4">
                            <label for="total" class="fw-bold">Total Harga</label>
                        </div>
                        <div class="col-8">
                            <input type="text" name="total" class="form-control" @if(isset($quantity)) value="Rp{{number_format($total, 0, ',', '.')}}" @endif style="width: 100%;" disabled>
                        </div>
                    </div>

                </form>

                <form @if(isset($products) ) action="/addPurchase/{{$products->slug}}" method="post" @endif>
                @csrf
            
                    <div>
                        <input type="hidden" name="quantity" @if(isset($quantity)) value="{{$quantity}}" @endif>
                        <button type="submit" @if(isset($quantity)) class="btn btn-navy mt-3" @else class="btn btn-secondary mt-3" disabled @endif><i class="bi bi-plus"></i> Tambah</button>
                    </div>
                </form>
                
            </div>

            <div class="card p-5 shadow" style="border-radius: 0px 0px 30px 30px ; height: 100%;">
            
                <form action="">
                
                <div class="row mb-3">
                    <div class="col-sm-3 col-lg-3">
                        <label for="cash" class="fw-bold">Tunai</label>
                        </div>
                        <div class="col-sm-9 col-lg-9">
                            <input type="text" name="cash" id="numericInput2"  @if(isset($cash)) value="{{$cash}}" @endif class="submit form-control" style="width: 100%;">
                            <button hidden type="submit"></button>
                        </div>
                    </div>
                    
                    
                    <div class="row mb-3">
                        <div class="col-sm-3 col-lg-3">
                            <label for="change" class="fw-bold">Kembalian</label>
                        </div>
                        <div class="col-sm-9 col-lg-9">
                            <input type="text" name="change" class="form-control" @if(isset($cash)) value="{{number_format($change, 0, ',', '.')}}" @endif style="width: 100%;" disabled>
                        </div>
                    </div>

                </form> 


                <!-- Button trigger modal -->
                <button type="button" @if(isset($cash)) class="btn btn-navy mt-3" @else class="btn btn-secondary mt-3" disabled @endif data-bs-toggle="modal" data-bs-target="#modalTransaction"><i class="bi bi-credit-card"></i> Bayar</button>

            </div>

        </div>

        <div class="col-2 my-auto">
            <!-- Button Pesanan Baru -->
            <form action="newPurchase" style="width: 100%;">
                <button class="btn btn-navy mb-3"><i class="bi bi-plus-square"></i> Pembelian Baru</button>
            </form>
        </div>

        @if(isset($orders) && isset($order))
        
            @include('detailtransactionpurchases')

        @endif

    </div>



<script>
    var inputs = document.querySelectorAll('#numericInput, #numericInput2');

    inputs.forEach(function(input) {
        input.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
            if (e.target.value.length > 8 ) {
                e.target.value = e.target.value.slice(0, 8);
            }
        });
    });
</script>   

<script>
    function increase() {
    let quantityInput = document.getElementById("numericInput");
    let currentValue = parseInt(quantityInput.value, 10);
    quantityInput.value = currentValue + 1;
}

function decrease() {
    let quantityInput = document.getElementById("numericInput");
    let currentValue = parseInt(quantityInput.value, 10);
    if (currentValue > 0) {  
        quantityInput.value = currentValue - 1;
    }
}

</script>

<script>
    var alertTimeout = 2000;
    var alertElement = document.getElementById('alertMessage');
    if (alertElement) {
        setTimeout(function() {
            alertElement.style.display = 'none'; 
        }, alertTimeout);
    }
</script>


<script>
    $(document).ready(function() {

$(document).on('click', '.pagination a', function(e) {
    e.preventDefault();
    let url = $(this).attr('href');

    $.get(url, function(data) {

        $('#exampleModal .modal-body').html($(data).find('#exampleModal .modal-body').html());
        $('#exampleModal .modal-footer').html($(data).find('#exampleModal .modal-footer').html());
    });

});

});

</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        @if(session('from_modal'))
            var myModal = new bootstrap.Modal(document.getElementById('exampleModal'), {});
            myModal.show();
            @php
                session()->forget('from_modal');
            @endphp
        @endif
    });
</script>
@endsection