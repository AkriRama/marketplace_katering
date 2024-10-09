@extends('layouts.mainlayoutsadmin')

@section('title','Edit Barang')

@section('content')

    <h1>Edit Barang</h1>

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

        <form action="/editproduct/{{$product->slug}}" method="post" enctype="multipart/form-data"  >
            @csrf
            
            <div class="mb-3">
                <label for="code_product" class="form-label">Kode Barang</label>
                <input type="text" name="code_product" id="code_product" class="form-control" value="{{$product->code_product}}" placeholder="Kode Barang">
            </div class="mb-3">

            <div>
                <label for="name" class="form-label">Nama</label>
                <input type="text" name="name" id="name" class="form-control" value="{{$product->name}}" placeholder="Nama Barang">
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Harga Barang</label>
                <input type="text" name="price" id="price" class="form-control" value="{{$product->price}}" placeholder="Harga Barang">
            </div>

            <div class="mb-3">
                <label for="stock" class="form-label">Stok Barang</label>
                <input type="text" name="stock" id="stock" class="form-control" value="{{$product->stock}}" placeholder="Stok Barang">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <input type="text" name="description" id="descriptionname" class="form-control" value="{{$product->description}}" placeholder="Deskripsi">
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
                <button class="btn btn-success">Perbarui</button>
            </div>
        </form>
    </div>

@endsection  