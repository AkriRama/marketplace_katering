@extends('layouts.mainlayoutsadmin')

@section('title', 'Service Delete Permanen')

@section('content')

    <h2>Apakah anda yakin ingin menghapus pelayanan {{$services->name}} secara permanen ?</h2>
    <div class="mt-5">
        <a href="/forcedestroyservice/{{$services->slug}}" class="btn btn-danger me-5">Yakin</a>
        <a href="/deletedservice" class="btn btn-primary">Batal</a>
    </div>

@endsection