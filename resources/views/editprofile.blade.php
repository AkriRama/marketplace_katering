@extends('layouts.mainlayoutsuser')

@section('title','Profile')

@section('content')

<div class="container p-3">
        <div class="card col-lg">
            <div class="card-header">
                <h5>Profile</h5>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 image-container">
                        <img src="{{$users->photo_profile != null ? asset('storage/photoprofile/'. $users->photo_profile) : asset('images/no-profile.png')}}" class="img-fluid block" alt="">
                    </div>

                    <div class="col-sm-6">
                        <form action="/editprofile/{{$users->slug}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="username">Nama</label>
                                <input type="text" id="username" name="username" class="form-control" value="{{$users->username}}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="phone">No HP</label>
                                <input type="text" id="phone" name="phone" class="form-control" value="{{$users->phone}}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="address">Alamat</label>
                                <input type="text" id="address" name="address" class="form-control" value="{{$users->address    }}" >
                            </div>
                            
                            <div class="mb-3">
                                <label for="image">Photo Profile</label>
                                <input type="file"  name="image" id="image" class="form-control" >
                            </div>
                            
                            <button class="btn btn-success" class="form-control" type="submit" style="width: 100%;"><i class="bi bi-save"></i> Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection