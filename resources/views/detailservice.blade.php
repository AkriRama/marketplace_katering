@extends('layouts.mainlayouts')

@section('title','Detail Service')

@section('content')

<div class="container col-10 mx-auto">
    <!-- <div class="row mx-auto mt-5 mb-5" style="width: 45rem;"> -->

    <div class="mt-3">
        @if(session('status'))
            <div class="alert alert-danger">
                {{ session('status') }}
            </div>
        @endif
    </div>

    <div class="card mb-5 mt-5 card-custom shadow">
        <div class="card-body ms-3 me-3">
            <div class="row">
    
                <div class="col-lg-4 col-md-5 col-sm-6 card-custom5">
                    <img src="{{ $services->cover != null ? asset('storage/coverService/'.$services->cover) : asset('images/no-image4.png') }}" alt="" style="max-width: 100%; max-height: 100%;">
                </div> 

                <div class="col-md-7 col-sm-7">
                    <h5 class="card-title mb-3">{{ $services->name }}</h5>
                    <h2 class="card-subtitle mb-2 text-body-secondary">Rp{{ number_format($services->price, 0, ',', '.') }}</h2>

                    <div class="mt-4 fw-bold text-white p-2 mb-2" style="background: #072541;">Deskripsi Barang</div>
                    <p style="text-align: justify;">{{ $services->description }}</p>

                    <div class="card mb-3 card-lancip">
                        <div class="card-body">
                            <form action="{{ route('order.selectedItems') }}" method="POST">
                                @csrf
                                <p class="fw-bold">List Barang</p>
                                <p>Berikut barang-barang yang akan digunakan dalam Pelayanan {{ $services->name }}:</p>
                                
                                @foreach($services->products as $product)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="selected_products[]" value="{{ $product->id }}" id="product{{ $product->id }}">
                                        <label class="form-check-label" for="product{{ $product->id }}">
                                            {{ $loop->iteration }}. {{ $product->name }}
                                        </label>
                                    </div>
                                @endforeach
                                
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">Order Selected Items</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="fw-bold fs-5 position-absolute end-0 top-0 mt-3 me-3 text-capitalize">
                        <p>{{ $services->status }}</p>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

@endsection
