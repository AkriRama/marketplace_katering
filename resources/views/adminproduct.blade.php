@extends('layouts.mainlayoutsadmin')

@section('title', 'Barang')

@section('content')

<!-- Add jQuery from a CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Add jQuery UI from a CDN -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- Modal Tambah Barang -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">            
                @include('modaladdproduct')
            </div>
        </div>
    </div>
    
    <!-- Modal Edit Barang -->
    @foreach($products as $item)
    <div class="modal fade" id="productEditModal{{$item->slug}}" tabindex="-1" aria-labelledby="productEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            @include('modaleditproduct')
        </div>
    </div>
    </div>
    @endforeach
`
    <!-- Modal Notifikasi Hapus Permanen-->
    @foreach($deletedProducts as $item)
    <div class="modal fade" id="productDeletePermanentModal{{$item->slug}}" tabindex="-1" aria-labelledby="productDeletePermanentModalLabel" aria-hidden="true">
    <div class="modal-dialog model-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="productDeletePermanentModalLabel">Hapus Barang</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>        
            <div class="modal-body">
                <h5>Apakah kamu yakin ingin menghapus secara permanen Barang {{$item->name}} ?</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondarySmall" data-bs-dismiss="modal">Close</button>
                <a href="/forcedestroyproduct/{{$item->slug}}" class="btn btn-danger">Sure</a>
            </div>
        
        </div>
    </div>
    </div>
    @endforeach

    <!-- Modal Deleted Barang -->
    <div class="modal fade" id="productDeletedModal" tabindex="-1" aria-labelledby="productDeletedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                @include('modaldeletedproduct')
            </div>
        </div>
    </div>
    
    <!-- Modal Notifikasi Hapus-->
    @foreach($products as $item)
    <div class="modal fade" id="productDeleteModal{{$item->slug}}" tabindex="-1" aria-labelledby="productDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog model-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="productDeleteModalLabel">Hapus Kategori</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>        
            <div class="modal-body">
                <h5>Apakah anda yakin ingin menghapus barang {{$item->name}} ?</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondarySmall" data-bs-dismiss="modal">Close</button>
                <a href="/destroyproduct/{{$item->slug}}" class="btn btn-danger">Sure</a>
            </div>
        
        </div>
    </div>
    </div>
    @endforeach

<!-- <h1>Daftar Barang</h1> -->
    
    <div class="position-absolute" style="width: 50%;">
        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>

        @endif
    </div>

    <!-- Tombol tambah dan tombol lihat data terhapus -->
    <div class="mt-3 d-flex justify-content-end">
        <a href="#" data-bs-toggle="modal" data-bs-target="#productDeletedModal" class="btn btn-darkRed me-3"><i class="bi bi-trash"></i> Data Terhapus</a>
        <a href="#" class="btn btn-navySmall" data-bs-toggle="modal" data-bs-target="#productModal"><i class="bi bi-plus-square"></i> Tambah Data</a>
    </div>


    <div class="my-3">
        <table class="table w-100">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Cover</th>
                    <th>Kode Barang</th>
                    <th style="width: 150px;">Nama</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Diskon</th>
                    <th style="width: 350px;">Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $item)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>
                        <img src="{{$item->cover != null ? asset('storage/cover/'. $item->cover) : asset('images/no-image.png')}}" alt="" style="max-width: 3rem; max-height: 3rem;">
                    </td>
                    <td>{{$item->code_product}}</td>
                    <td>{{$item->name}}</td>
                    <td>{{number_format($item->price, 0, ',', '.')}}</td>
                    <td>{{$item->stock}}</td>
                    <td>{{$item->discount}}</td>
                    <td>{{$item->description}}</td>
                    <td>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#productEditModal{{$item->slug}}"><i class="bi bi-pencil"></i> Edit</a>
                        <a>|</a>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#productDeleteModal{{$item->slug}}">Hapus <i class="bi bi-trash3"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

     <!-- paginator -->
     {{ $products   ->links('pagination::paginator-bengkel') }}

     
    <script>
        var inputs = document.querySelectorAll('#stock, #price');

        inputs.forEach(function(input) {
            input.addEventListener('input', function(e) {
                // Replace semua karakter non-angka
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
                if (e.target.value.length > 8 ) {
                    e.target.value = e.target.value.slice(0, 8);
                }
            });
        });
    </script>   
    
    <script>
        var inputs = document.querySelectorAll('#discount');

        inputs.forEach(function(input) {
            input.addEventListener('input', function(e) {
                // Replace semua karakter non-angka
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
                if (e.target.value.length > 2 ) {
                    e.target.value = e.target.value.slice(0, 2);
                }
            });
        });
    </script>   

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        $(document).ready(function() {
        $('.multiple-select2').select2();
        });
    </script>


@endsection