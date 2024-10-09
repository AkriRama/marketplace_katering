@extends('layouts.mainlayoutsadmin')

@section('title','Tambah Barang')

@section('content')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- <h1>Tambah Barang Baru</h1> -->

    <div class="mt-5 w-50">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="addproduct" method="post" enctype="multipart/form-data">
            @csrf
            
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

            <div class="mt-3">
                <button class="btn btn-success"><i class="bi bi-save"></i> Simpan</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
        $('.multiple-select2').select2();
        });
    </script>
@endsection  