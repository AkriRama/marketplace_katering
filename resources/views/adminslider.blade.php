@extends('layouts.mainlayoutsadmin')

@section('title','Slider')

@section('content')
    <!-- Modal Tambah Slider -->
    <div class="modal fade" id="sliderAddModal" tabindex="-1" aria-labelledby="sliderAddModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            @include('modaladdslider')
        </form>
        </div>
    </div>
    </div>
    
    <!-- Modal Edit Slider -->
    @foreach($sliders as $item)
    <div class="modal fade" id="sliderEditModal{{$item->slug}}" tabindex="-1" aria-labelledby="sliderEditModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            @include('modaleditslider')
        </form>
        </div>
    </div>
    </div>
    @endforeach

    <!-- Modal Notifikasi Hapus Permanen-->
    @foreach($deletedSliders as $item)
    <div class="modal fade" id="sliderDeletePermanentModal{{$item->slug}}" tabindex="-1" aria-labelledby="sliderDeletePermanentModalLabel" aria-hidden="true">
    <div class="modal-dialog model-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="sliderDeletePermanentModalLabel">Hapus Kategori</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>        
            <div class="modal-body">
                <h5>Apakah kamu yakin ingin menghapus secara permanen slider {{$item->name}} ?</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondarySmall" data-bs-dismiss="modal">Close</button>
                <a href="/forcedestroyslider/{{$item->slug}}" class="btn btn-danger">Sure</a>
            </div>
        
        </div>
    </div>
    </div>
    @endforeach

    <!-- Modal Deleted Slider -->
    <div class="modal fade" id="sliderDeletedModal" tabindex="-1" aria-labelledby="sliderDeletedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                @include('modaldeletedslider')
            </div>
        </div>
    </div>

    <!-- Modal Notifikasi Hapus-->
    @foreach($sliders as $item)
    <div class="modal fade" id="sliderDeleteModal{{$item->slug}}" tabindex="-1" aria-labelledby="sliderDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog model-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="sliderDeleteModalLabel">Hapus Slider</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>        
            <div class="modal-body">
                <h5>Are you sure to delete slider {{$item->name}} ?</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondarySmall" data-bs-dismiss="modal">Close</button>
                <a href="/destroyslider/{{$item->slug}}" class="btn btn-danger">Sure</a>
            </div>
        
        </div>
    </div>
    </div>
    @endforeach

    <div class="mt-3 d-flex justify-content-end">
        <a href="#" class="btn btn-darkRed me-3" data-bs-toggle="modal" data-bs-target="#sliderDeletedModal"><i class="bi bi-trash"></i> Data Terhapus</a>
        <a href="addslider" class="btn btn-navySmall" data-bs-toggle="modal" data-bs-target="#sliderAddModal"><i class="bi bi-plus-square"></i> Tambah Slider</a>
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
                    <th>Nama</th>
                    <th>Slider</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sliders as $item)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$item->name}}</td>
                    <td>
                        <img src="{{$item->cover != null ? asset('storage/slider/'. $item->cover) : asset('images/no-image.png')}}" alt="" style="max-width: 3rem; max-height: 3rem;">
                    </td>
                    <td>{{$item->description}}</td>
                    <td>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#sliderEditModal{{$item->slug}}"><i class="bi bi-pencil"></i> Edit</a>
                        <a>|</a>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#sliderDeleteModal{{$item->slug}}">Hapus <i class="bi bi-trash3"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
