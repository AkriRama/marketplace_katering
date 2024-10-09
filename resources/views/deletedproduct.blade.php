@extends('layouts.mainlayoutsadmin')

@section('title','Deleted Service')

@section('content')

    <!-- <h1>Daftar Barang Terhapus</h1> -->

    <div class="mt-3 d-flex justify-content-end">
        <a href="adminproduct" class="btn btn-primary">View Data</a>
    </div>

    <div class="mt-3">
        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>

        @endif
    </div>

    <div class="my-3">
        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deletedProducts as $item)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$item->name}}</td>
                    <td>
                        <a href="restoreproduct/{{$item->slug}}">Restore</a>
                        <a href="forcedeleteproduct/{{$item->slug}}">Force Delete</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection