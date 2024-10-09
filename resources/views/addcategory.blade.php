@extends('layouts.mainlayoutsadmin')

@section('title', 'AddCategory')

@section('content')

    <h1>Tambah Kategori</h1>

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

        <form action="addcategory" method="post">
            @csrf
            
            <div class="mb-3">
                <label for="name" class="form-label">Nama Kategori</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Nama Kategori">
            </div>
            
            <div class="mt-3">
                <button class="btn btn-success"><i class="bi bi-save"></i> Simpan</button>
            </div>
        </form>
    </div>

@endsection
