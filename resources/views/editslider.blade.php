@extends('layouts.mainlayoutsadmin')

@section('title', 'Edit Slider')

@section('content')

    <h1>Edit Slider</h1>

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

        <form action="/editslider/{{$sliders->slug}}" method="post" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{$sliders->name}}">
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Slider</label>
                <input type="file" name="image" id="image" class="form-control">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <input type="text" name="description" id="description" class="form-control" value="{{$sliders->description}}">
            </div>
            
            <div class="mt-3">
                <button class="btn btn-success">Simpan</button>
            </div>
        </form>
    </div>

@endsection
