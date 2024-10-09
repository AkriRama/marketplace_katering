@extends('layouts.mainlayoutsadmin')

@section('title','Detail Pelanggan')

@section('content')

    <h1>Detail Pelanggan</h1>
    <div class="card shadow p-3 mt-3">
        <div class="row">
            <div class="col-sm-4 image-container">
                <img src="{{$users->photo_profile != null ? asset('storage/photoprofile/'. $users->photo_profile) : asset('images/no-profile.png')}}" class="img-fluid block" alt="" style="max-width: 100%; max-height: 50vh;">
            </div>

            <div class="col-sm-6">
                <div class="mb-3">
                    <label for="username">Nama</label>
                    <input type="text" id="username" class="form-control" value="{{$users->username}}" disabled >
                </div>
                
                <div class="mb-3">
                    <label for="password">Kata Sandi</label>
                    <input type="password" id="password" class="form-control" value="" disabled >
                </div>
                
                <div class="mb-3">
                    <label for="phone">No HP</label>
                    <input type="text" id="phone" class="form-control" value="{{$users->phone}}" disabled >
                </div>
                
                <div class="mb-3">
                    <label for="address">Alamat</label>
                    <input type="text" id="address" class="form-control" value="{{$users->address}}" disabled >
                </div>

                <div class="mb-3">
                    <label for="staus">Status</label>
                    <input type="text" id="status" class="form-control" value="{{$users->status}}" disabled >
                </div>
            </div>
        </div>
    </div>


@endsection