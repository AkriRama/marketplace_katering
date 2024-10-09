@extends('layouts.mainlayouts')

@section('title','Detail Product')

@section('content')

<div class="container col-10 mx-auto">
    <div class="mt-3">
        @if(session('status'))
            <div class="alert alert-danger">
                {{ session('status') }}
            </div>

        @endif
    </div>

    <div class="card mb-2 mt-5 mb-5 card-custom  shadow">
        <div class="card-body ms-3 me-3">
            <div class="row">
    
                <div class="col-lg-4 col-md-5 col-sm-6 card-custom5">
                    <img src="{{ $products->cover != null ? asset('storage/cover/'.$products->cover) : asset('images/no-image4.png')  }}" alt="" style="max-width: 100%; max-height: 100%;">
                </div> 

                <div class="col-md-7 col-sm-7">
                    <h5 class="card-title mb-3">{{$products->name}}</h5>
                    <h2 class="card-subtitle mb-2 text-body-secondary">Rp{{ number_format($products->price,0, ',','.') }}</h2 >

                    <div class="mt-4 fw-bold text-white p-2 mb-2" style="background: #072541;">Deskripsi Barang</div>
                    <p style="text-align: justify;">{{$products->description}}</p>
                    <p style="text-align: justify;">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ipsam fugiat neque nam! At, eveniet. Sapiente suscipit praesentium adipisci omnis porro, ipsam nemo pariatur. Repellat voluptatem tempora autem. Asperiores earum sit nobis doloribus sequi harum quaerat nisi minus unde sed amet perspiciatis qui ipsa architecto beatae, vitae atque accusantium! Rem ea consectetur nobis dolore illum in eaque mollitia! Vero aliquid, sunt eligendi quia blanditiis porro! Consectetur quos alias officia corporis nam quisquam optio tempore explicabo distinctio harum sit, temporibus qui? Quam tempora harum ea quo, sit non, nesciunt ad tempore, quis rem hic culpa ipsum! Quae aliquid in sunt quos magni?</p>
                    
                    <div class="fw-bold fs-5 position-absolute end-0 top-0 mt-3 me-3">
                        <p>Stok : {{$products->stock}}</p>
                    </div>
                    
                </div>

            </div>
        </div>
    </div>    
</div>

@endsection