@extends('layouts.mainlayoutsadmin')

@section('title','Service')

@section('content')

    `<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <h1>Edit Jasa   </h1>

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

        <form action="/editservice/{{$services->slug}}" method="post" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label for="name" class="form-label">Nama Jasa</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $services->name }}">
            </div>
            
            <div class="mb-3">
                <label for="price" class="form-label">Harga Jasa</label>
                <input type="text" name="price" id="price" class="form-control" value="{{ $services->price }}">
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <input type="text" name="description" id="description" class="form-control" value="{{ $services->description }}">
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" name="image" id="image" class="form-control mb-3">
                <input type="hidden" name="old_image" value="{{ $services->cover }}">
                <img id="image-preview" src="{{$services->cover != null ? asset('storage/coverService/'. $services->cover) : asset('images/no-image.png')}}" alt="Preview Gambar" style="max-width: 200px; max-height: 200px">
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
                <button class="btn btn-success">Simpan</button>
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
        function previewImage(input) {
            var preview = document.getElementById('image-preview');
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = "{{ asset('storage/cover/'. $item->cover) }}";
            }
        }
    </script>

@endsection