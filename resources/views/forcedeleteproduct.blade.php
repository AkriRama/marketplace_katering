@extends('layouts.mainlayoutsadmin')

@section('title', 'Product Delete Permanen')

@section('content')

    <h2>Apakah anda yakin ingin menghapus Barang {{$products->name}} secara permanen ?</h2>
    <div class="mt-5">
        <a href="/forcedestroyproduct/{{$products->slug}}" class="btn btn-danger me-5">Yakin</a>
        <a href="/deletedproduct" class="btn btn-primary">Batal</a>
    </div>

@endsection