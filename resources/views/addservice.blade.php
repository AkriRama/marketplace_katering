@extends('layouts.mainlayoutsadmin')

@section('title','Service')

@section('content')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <h1>Tambah Pelayanan Baru</h1>

    <div class="mt-3 w-50">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="addservice" method="post" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label for="name" class="form-label">Nama Jasa</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
            </div>
            
            <div class="mb-3">
                <label for="price" class="form-label">Harga Jasa</label>
                <input type="text" name="price" id="price" class="form-control" value="{{ old('price') }}">
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <input type="text" name="description" id="description" class="form-control" placeholder="Deskripsi Barang" value="{{ old('description') }}">
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" name="image" id="image" class="form-control mb-3">
                <img id="image-preview" src="" alt="Preview Image" style="display: none; max-width: 200px; max-height: 200px;" />
            </div>

            <div>
                <label for="products" class="form-label">Daftar Barang</label>
                <select name="products[]" id="products" class="form-control multiple-select2" multiple>
                    @foreach($products as $item)
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


    <script>
        $(document).ready(function() {
            $('#image').change(function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#image-preview').attr('src', e.target.result);
                        $('#image-preview').show();
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#image-preview').attr('src', '');
                    $('#image-preview').hide();
                }
            });
        });
    </script>

@endsection