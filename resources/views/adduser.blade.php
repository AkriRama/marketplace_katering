@extends('layouts.mainlayoutsadmin')

@section('title','Tambah User')

@section('content')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <h1>Tambah User Baru</h1>

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

        <form action="adduser" method="post" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Username">
            </div>
            
            <div class="mb-3">
                <label for="phone" class="form-label">No Telepon</label>
                <input type="text" name="phone" id="phone" class="form-control" placeholder="No Telepon"">
            </div>
            
            <div class="mb-3">
                <label for="address" class="form-label">Alamat</label>
                <input type="text" name="address" id="address" class="form-control" placeholder="Alamat">
            </div>
            
            <div class="mb-3">
                <label for="image" class="form-label">Foto Profil</label>
                <input type="file" name="image" id="image" class="form-control">
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