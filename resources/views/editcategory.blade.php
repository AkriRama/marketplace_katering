@extends('layouts.mainlayoutsadmin')

@section('title','Edit Category')

@section('content')

    <h1>Edit Kategori</h1>

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

        <form action="/editcategory/{{$category->slug}}" method="post">
            @csrf
            
            <div>
                <label for="name" class="form-label">Nama</label>
                <input type="text" name="name" id="name" class="form-control" value="{{$category->name}}" placeholder="Nama Kategori">
            </div>

            <div class="mt-3">
                <button class="btn btn-success">Perbarui</button>
            </div>
        </form>
    </div>

@endsection