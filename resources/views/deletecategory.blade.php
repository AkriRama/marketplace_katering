@extends('layouts.mainlayoutsadmin')

@section('title','Delete Category')

@section('content')

    <h2>Are you sure to delete category {{$category->name}} ?</h2>
    <div class="mt-5">
        <a href="/destroycategory/{{$category->slug}}" class="btn btn-danger me-5">Sure</a>
        <a href="/admincategory" class="btn btn-primary">Cancel</a>
    </div>

@endsection