@extends('layouts.mainlayoutsadmin')

@section('title','Pelanggan')

@section('content')

<style>
    img {
        max-width: 5rem;
        max-height: 5rem;
    }
</style>

    <!-- Modal Tambah User -->
    <div class="modal fade" id="userAddModal" tabindex="-1" aria-labelledby="userAddModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            @include('modaladduser')
            </div>
        </div>
    </div>

    <!-- Modal Edit User -->
    @foreach($users as $item)
    <div class="modal fade" id="userEditModal{{$item->slug}}" tabindex="-1" aria-labelledby="userEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            @include('modaledituser')
        </div>
    </div>
    </div>
    @endforeach

    @foreach($users as $item)
    <!-- Modal Detail User -->
    <div class="modal fade" id="userModal{{ $item->slug }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            @include('modaldetailuser')
        </div>
    </div>
    </div>
    @endforeach
    
    <!-- Modal Notifikasi Hapus Permanen-->
    @foreach($deletedusers as $item)
    <div class="modal fade" id="userDeletePermanentModal{{$item->slug}}" tabindex="-1" aria-labelledby="userDeletePermanentModalLabel" aria-hidden="true">
    <div class="modal-dialog model-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="userDeletePermanentModalLabel">Hapus Kategori</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>        
            <div class="modal-body">
                <h5>Apakah kamu yakin ingin menghapus secara permanen User {{$item->username}} ?</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondarySmall" data-bs-dismiss="modal">Close</button>
                <a href="/forcedestroyuser/{{$item->slug}}" class="btn btn-danger">Sure</a>
            </div>
        
        </div>
    </div>
    </div>
    @endforeach
    
    <!-- Modal Deleted User -->
    <div class="modal fade" id="userDeletedModal" tabindex="-1" aria-labelledby="userDeletedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                @include('modaldeleteduser')
            </div>
        </div>
    </div>
    
    <!-- Modal Notifikasi Hapus-->
    @foreach($users as $item)
    <div class="modal fade" id="userDeleteModal{{$item->slug}}" tabindex="-1" aria-labelledby="userDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog model-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="userDeleteModalLabel">Hapus User</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>        
            <div class="modal-body">
                <h5>Apakah anda yakin ingin menghapus user {{$item->username}} ?</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondarySmall" data-bs-dismiss="modal">Close</button>
                <a href="/destroyuser/{{$item->slug}}" class="btn btn-danger">Sure</a>
            </div>
        
        </div>
    </div>
    </div>
    @endforeach


    <div class="mt-3 d-flex justify-content-end">
        <a href="#" class="btn btn-darkRed me-3" data-bs-toggle="modal" data-bs-target="#userDeletedModal"><i class="bi bi-trash"></i> Data Terhapus</a>
        <a href="#" class="btn btn-navySmall"  data-bs-toggle="modal" data-bs-target="#userAddModal"><i class="bi bi-plus-square"></i> Tambah User</a>
    </div>

    <div class="mt-3">
        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @elseif(session('message'))
            <div class="alert alert-danger">
                {{ session('message') }}
            </div>
            
        @endif
    </div>

    <div class="my-3 table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Foto Profil</th>
                    <th>Nama Pengguna</th>
                     <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><img src="{{ $item->photo_profile != null ? asset('storage/photoprofile/'. $item->photo_profile) : asset('images/no-profile.png')}}" alt=""></td>
                    <td>{{ $item->username }}</td>
                    <td>
                    
                    <div>
                    @if($item->status == "inactive")
                        <a href="changeactive/{{$item->slug}}" class="btn btn-red w-100">Tidak Aktif</a>
                    @elseif($item->status == "active")
                        <a href="changeinactive/{{$item->slug}}" class="btn btn-success w-100">Aktif</a>
                    @endif
                    </div>    
                        
                    </td>
                        <td>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#userModal{{ $item->slug }}"><i class="bi bi-card-list"></i> Detail</a>
                        <a>|</a>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#userDeleteModal{{ $item->slug }}">Hapus <i class="bi bi-trash3"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection