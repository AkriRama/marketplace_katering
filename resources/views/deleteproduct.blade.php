@extends('layouts.mainlayoutsadmin')

@section('title','Delete Service')

@section('content')

    <h2>Apakah anda yakin ingin menghapus Barang {{$products->name}} ?</h2>
    <div class="mt-5">
        <a href="/destroyproduct/{{$products->slug}}" class="btn btn-danger me-5">Yakin</a>
        <a href="/adminproduct" class="btn btn-primary">Batal</a>
    </div>

@endsection