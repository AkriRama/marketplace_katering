@extends('layouts.mainlayoutsadmin')

@section('title','Pengeluaran')

@section('content')

    <!-- Modal Tambah Pengeluaran -->
    <div class="modal fade" id="expensesModal" tabindex="-1" aria-labelledby="expensesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Pengeluaran</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="addexpenses" method="post">
        @csrf

        <div class="modal-body">


                <div class="mb-3">
                    <label for="name" class="form-label">Nama Pengeluaran</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Nama Pengeluaran">
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <input type="text" name="description" id="description" class="form-control" placeholder="Deskripsi">
                </div>

                <div class="mb-3">
                    <label for="expense" class="form-label">Total</label>
                    <input type="text" name="expense" id="expense" class="form-control" placeholder="Total Pengeluaran">
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
    
    <!-- Modal Edit Pengeluaran -->
    @foreach($expenses as $item)
    <div class="modal fade" id="expensesEditModal{{$item->slug}}" tabindex="-1" aria-labelledby="expensesEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="expensesModalLabel">Edit Pengeluaran</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="editexpenses/{{$item->slug}}" method="post">
        @csrf

        <div class="modal-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <div class="mb-3">
                    <label for="name" class="form-label">Nama Pengeluaran</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $item->name }}" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <input type="text" name="description" id="description" class="form-control" value="{{ $item->description }}">
                </div>

                <div class="mb-3">
                    <label for="expense" class="form-label">Total</label>
                    <input type="text" name="expense" id="expense" class="form-control" value="{{ $item->expense }}">
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
    @endforeach

        <!-- Modal Notifikasi Hapus Permanen-->
        @foreach($deletedExpenses as $item)
    <div class="modal fade" id="expensesDeletePermanentModal{{$item->slug}}" tabindex="-1" aria-labelledby="expensesDeletePermanentModalLabel" aria-hidden="true">
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
    <div class="modal fade" id="expensesDeletedModal" tabindex="-1" aria-labelledby="expensesDeletedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            @include('modaldeletedexpenses')
            </div>
        </div>
    </div>

    <!-- Modal Notifikasi Hapus-->
    @foreach($expenses as $item)
    <div class="modal fade" id="expensesDeleteModal{{$item->slug}}" tabindex="-1" aria-labelledby="expensesDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog model-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="expensesDeleteModalLabel">Hapus Data Pengeluaran</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
            <div class="modal-body">
                <h5>Apakah anda yakin ingin menghapus data pengeluaran {{$item->name}} ?</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondarySmall" data-bs-dismiss="modal">Close</button>
                <a href="/destroyexpenses/{{$item->slug}}" class="btn btn-danger">Sure</a>
            </div>

        </div>
    </div>
    </div>
    @endforeach

    <!-- <h1>Daftar Pengeluaran</h1> -->

    <div class="position-absolute" style="width: 50%;">
        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>

        @endif
    </div>

    <div class="mt-3 d-flex justify-content-end">
        <a href="deletedcategory" data-bs-toggle="modal" data-bs-target="#expensesDeletedModal" class="btn btn-darkRed me-3"><i class="bi bi-trash"></i> Data Terhapus</a>
        <a href="#" class="btn btn-navySmall" data-bs-toggle="modal" data-bs-target="#expensesModal"><i class="bi bi-plus-square"></i> Tambah Data</a>
    </div>


   <div class="my-3">
        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Total Pengeluaran</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenses as $item)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$item->name}}</td>
                    <td>{{$item->description}}</td>
                    <td>{{number_format($item->expense, 0, ',', '.')}}</td>
                    <td>
                        <!-- <a href="editexpenses/{{$item->slug}}"><i class="bi bi-pencil"></i> Edit</a> -->
                        <a href="#" data-bs-toggle="modal" data-bs-target="#expensesEditModal{{$item->slug}}"><i class="bi bi-pencil"></i> Edit</a>
                        <a style="decoration: none">|</a>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#expensesDeleteModal{{$item->slug}}">Hapus <i class="bi bi-trash3"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection 
