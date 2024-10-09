@extends('layouts.mainlayouts')

@section('title','Beranda')

@section('content')
 
    <!-- Banner Slider Home -->
    <div class="d-flex flex-column align-items-center justify-content-center" style="background: #072541; height: 100%; width: 100%;">
            <div class="slideshow-container">
                @foreach($sliders as $item)
                <div class="mySlides fade">
                    <img src="{{$item->cover != null ? asset('storage/slider/'. $item->cover) : asset('images/no-image.png')}}">
                </div>
                @endforeach
            </div>

            @if(count($sliders) > 1)
            <a class="prev" onclick="plusSlides(-1)">❮</a>
            <a class="next" onclick="plusSlides(1)">❯</a>
            <br>
            
            <div style="text-align:center">
                    @foreach($sliders as $item)
                    <span class="dot" onclick="currentSlide({{$item ->id}})"></span> 
                    @endforeach
                </div>      
            @endif
    </div>


        <!-- List Barang Diskon -->
        <div class="container-lg d-flex flex-column justify-content-center align-items-center">
        
            <div class="content border border-dark col-lg-12 text-center p-2 mt-5">
                <h5>Barang Potongan Harga   </h5>
            </div>
            
            <div class="content p-2 mb-3 mt-2 d-flex flex-column align-items-center justify-content-center" style="background: #072541; width: 100%;">
                <div class="m-5 ">
                    <div class="row">
                        @foreach($products as $item)
                        <div class="col-lg mb-3 ">
                            <div class="card shadow h-100" style="max-width: 15rem; position-relative">
                                <div class="card m-auto h-100" style="width: 100%; height: 100%; border: 0;">
                                    <img src="{{  $item->cover != null ? asset('storage/cover/'.$item->cover) : asset('images/no-image4.png') }}" class="card-img-top" alt="..." >
                                </div>
                                @if($item->discount != 0)
                                <div class="position-absolute top-0 start-0 text-black fs-5 p-2 shadow-sm" style="background: yellow; border-radius: 5px 0 0 0; ">{{$item->discount}}%</div>
                                @endif
                                <div class="card-body" >
                                    <h6 class="card-title">{{ $item->name }}</h6>
                                    @if($item->discount > 0)
                                    <span class="card-text text-decoration-line-through" >Rp{{ number_format($item->price, 0, ',', '.') }}</span>
                                    <span class="card-text position-absolute me-2 end-0" >Rp{{ number_format($item->price - (($item->price * $item->discount)/100), 0, ',', '.') }}</span>
                                    @else
                                    <span class="card-text" >Rp{{ number_format($item->price, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        @endforeach
                        
                    </div>
                </div> 
            </div>
    </div>

@endsection