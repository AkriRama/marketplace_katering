@extends('layouts.mainlayoutsadmin')

@section('title','Delete Service')

@section('content')

    <h2>Apakah anda yakin ingin menghapus pelayanan {{$services->name}} ?</h2>
    <div class="mt-5">
        <a href="/destroyservice/{{$services->slug}}" class="btn btn-danger me-5">Sure</a>
        <a href="/adminservice" class="btn btn-primary">Cancel</a>
    </div>

@endsection