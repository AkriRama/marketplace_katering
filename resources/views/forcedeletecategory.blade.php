@extends('layouts.mainlayoutsadmin')

@section('title', 'Category Delete Permanen')

@section('content')

    <h2>Are you sure to delete category {{$category->name}} ?</h2>
    <div class="mt-5">
        <a href="/forcedestroycategory/{{$category->slug}}" class="btn btn-danger me-5">Sure</a>
        <a href="/deletedcategory" class="btn btn-primary">Cancel</a>
    </div>

@endsection