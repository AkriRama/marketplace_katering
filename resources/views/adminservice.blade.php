@extends('layouts.mainlayoutsadmin')

@section('title','Jasa')

@section('content')

    <!-- Modal Tambah Pelayanan -->
    <div class="modal fade" id="serviceAddModal" tabindex="-1" aria-labelledby="serviceAddModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            @include('modaladdservice')
        </div>
    </div>
    </div>

    <!-- Modal Edit Pelayanan -->
    @foreach($services as $item)
    <div class="modal fade" id="serviceEditModal{{$item->slug}}" tabindex="-1" aria-labelledby="serviceEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            @include('modaleditservice')
        </div>
    </div>
    </div>
    @endforeach

    <!-- Modal Notifikasi Hapus Permanen-->
    @foreach($deletedServices as $item)
    <div class="modal fade" id="serviceDeletePermanentModal{{$item->slug}}" tabindex="-1" aria-labelledby="serviceDeletePermanentModalLabel" aria-hidden="true">
    <div class="modal-dialog model-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="serviceDeletePermanentModalLabel">Hapus Pelayanan Permanen</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>        
            <div class="modal-body">
                <h5>Apakah kamu yakin ingin menghapus secara permanen Pelayanan {{$item->name}} ?</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondarySmall" data-bs-dismiss="modal">Close</button>
                <a href="/forcedestroyservice/{{$item->slug}}" class="btn btn-danger">Sure</a>
            </div>
        
        </div>
    </div>
    </div>
    @endforeach
    
    <!-- Modal Deleted Kategori -->
    <div class="modal fade" id="serviceDeletedModal" tabindex="-1" aria-labelledby="serviceDeletedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                @include('modaldeletedservice')
            </div>
        </div>
    </div>

    <!-- Modal Notifikasi Hapus-->
    @foreach($services as $item)
    <div class="modal fade" id="serviceDeleteModal{{$item->slug}}" tabindex="-1" aria-labelledby="serviceDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog model-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="serviceDeleteModalLabel">Hapus Pelayanan</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>        
            <div class="modal-body">
                <h5>Apakah anda yakin ingin menghapus pelayanan {{$item->name}} ?</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondarySmall" data-bs-dismiss="modal">Close</button>
                <a href="/destroyservice/{{$item->slug}}" class="btn btn-danger">Sure</a>
            </div>
        
        </div>
    </div>
    </div>
    @endforeach
    
    <div class="mt-3 d-flex justify-content-end">
        <a href="#" class="btn btn-darkRed me-3" data-bs-toggle="modal" data-bs-target="#serviceDeletedModal"><i class="bi bi-trash"></i> Data Terhapus</a>
        <a href="addservice" class="btn btn-navySmall"><i class="bi bi-plus-square"></i> Tambah Data</a>
    </div>

    <div class="mt-3">
        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>

        @endif
    </div>

    <div class="my-3">
        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Cover</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th style="width: 200px;">Daftar Barang</th>
                    <th style="width: 350px;">Deskripsi</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($services as $item)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>
                        <img src="{{$item->cover != null ? asset('storage/coverService/'. $item->cover) : asset('images/no-image.png')}}" alt="" style="max-width: 5rem; max-height: 5rem;">
                    </td>
                    <td>{{$item->name}}</td>
                    <td>Rp{{number_format($item->price, 0, ',', '.')}}</td>
                    <td>
                        @foreach($item->products as $products)
                            {{$loop->iteration}}. {{$products->name}} <br>
                        @endforeach
                    </td>
                    <td>{{$item->description}}</td>
                    <td style="width:10rem;">
                        <div>
                            @if($item->status == 'not available')
                            <a href="available/{{$item->slug}}" class="btn btn-secondary" style="width: 8rem;">Tidak Tersedia</a>
                            @elseif($item->status == 'available')
                            <a href="notavailable/{{$item->slug}}" class="btn btn-primary" style="width: 8rem;">Tersedia</a>
                            @endif
                        </div>
                    </td>
                    <td>
                        <a href="editservice/{{$item->slug}}"><i class="bi bi-pencil"></i> Edit</a>
                        <a>|</a>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#serviceDeleteModal{{$item->slug}}">Hapus <i class="bi bi-trash3"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection