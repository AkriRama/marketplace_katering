@extends('layouts.mainlayoutsuser')

@section('title','Profile')

@section('content')

<div class="container p-3">
        <div class="card col-lg">
            <div class="card-header">
                <h5>Informasi Perusahaan</h5>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 image-container">
                        <img src="{{$users->photo_profile != null ? asset('storage/photoprofile/'. $users->photo_profile) : asset('images/no-profile.png')}}" class="img-fluid block" alt="">
                    </div>

                    <div class="col-sm-6">
                        <form id="form" action="/editprofile/{{$users->slug}}">
                            <div class="mb-3">
                                <label for="username">Nama Perusahaan</label>
                                <input type="text" id="username" class="form-control" value="{{$users->username}}" disabled >
                            </div>

                            <div class="mb-3">
                                <label for="address">Alamat</label>
                                <input type="text" id="address" class="form-control" value="{{$users->address}}" disabled >
                            </div>
                            
                            <div class="mb-3">
                                <label for="phone">Kontak Telepon</label>
                                <input type="text" id="phone" class="form-control" value="{{$users->phone}}" disabled >
                            </div>

                            <div class="mb-3">
                                <label for="address">Alamat</label>
                                <input type="text" id="address" class="form-control" value="{{$users->address}}" disabled >
                            </div>
                            
                            <div class="mb-3">
                                <label for="description">Deskripsi</label>
                                <input type="text" id="description" class="form-control" value="{{$users->description}}" disabled >
                            </div>
                            
                            
                            <div class="mb-3">
                                <label for="Image">Photo Profile</label>
                                <input type="file" id="image" class="form-control" disabled >
                            </div>
                            
                            <button class="btn btn-navy" type="submit"><i class="bi bi-pencil"></i> Perbarui Profil</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection