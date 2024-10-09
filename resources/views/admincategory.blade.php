@extends('layouts.mainlayoutsadmin')

@section('title','Category')

@section('content')

    <!-- Modal Tambah Kategori -->
    <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Kategori</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="addcategory" method="post">
        @csrf
        
        <div class="modal-body">
            
            
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Kategori</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Nama Kategori">
                </div>
                
                <div class="mt-3">
                    
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
    
    <!-- Modal Edit Kategori -->
    @foreach($categories as $item)
    <div class="modal fade" id="categoryEditModal{{$item->slug}}" tabindex="-1" aria-labelledby="categoryEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            @include('modaleditcategory')
        </div>
    </div>
    </div>
    @endforeach
`
    <!-- Modal Notifikasi Hapus Permanen-->
    @foreach($deletedCategories as $item)
    <div class="modal fade" id="categoryDeletePermanentModal{{$item->slug}}" tabindex="-1" aria-labelledby="categoryDeletePermanentModalLabel" aria-hidden="true">
    <div class="modal-dialog model-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="categoryDeletePermanentModalLabel">Hapus Kategori</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>        
            <div class="modal-body">
                <h5>Apakah kamu yakin ingin menghapus secara permanen Kategori {{$item->name}} ?</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondarySmall" data-bs-dismiss="modal">Close</button>
                <a href="/forcedestroycategory/{{$item->slug}}" class="btn btn-danger">Sure</a>
            </div>
        
        </div>
    </div>
    </div>
    @endforeach

    <!-- Modal Deleted Kategori -->
    <div class="modal fade" id="categoryDeletedModal" tabindex="-1" aria-labelledby="categoryDeletedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                @include('modaldeletedcategory')
            </div>
        </div>
    </div>
    
    <!-- Modal Notifikasi Hapus-->
    @foreach($categories as $item)
    <div class="modal fade" id="categoryDeleteModal{{$item->slug}}" tabindex="-1" aria-labelledby="categoryDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog model-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="categoryDeleteModalLabel">Hapus Kategori</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>        
            <div class="modal-body">
                <h5>Are you sure to delete category {{$item->name}} ?</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondarySmall" data-bs-dismiss="modal">Close</button>
                <a href="/destroycategory/{{$item->slug}}" class="btn btn-danger">Sure</a>
            </div>
        
        </div>
    </div>
    </div>
    @endforeach

    <!-- <h1>Daftar Kategori</h1> -->
    
    <div class="position-absolute" style="width: 50%;">
        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>

        @endif
    </div>

    <!-- Tombol tambah dan tombol lihat data terhapus -->
    <div class="mt-3 d-flex justify-content-end">
        <a href="#" data-bs-toggle="modal" data-bs-target="#categoryDeletedModal" class="btn btn-darkRed me-3"><i class="bi bi-trash"></i> Data Terhapus</a>
        <a href="#" class="btn btn-navySmall" data-bs-toggle="modal" data-bs-target="#categoryModal"><i class="bi bi-plus-square"></i> Tambah Data</a>
    </div>


    <div class="my-3">
        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $item)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$item->name}}</td>
                    <td>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#categoryEditModal{{$item->slug}}"><i class="bi bi-pencil"></i> Edit</a>
                        <a>|</a>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#categoryDeleteModal{{$item->slug}}">Hapus <i class="bi bi-trash3"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
