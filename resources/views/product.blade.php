@extends('layouts.mainlayouts')

@section('title', 'Barang')

@section('content')

            <div class="row g-0">
                <div class="sidebarUser col-2 collapse d-block p-2" id="navbarAdmin">
                    
                    <form action="" method="get">
                        <div class="sidebarUser bar col-lg-2 collapse d-block shadow-lg" style="width:100%;" id="navbarAdmin">
                        <input type="hidden" name="category" id="hiddenInputId">
                        <label for="title" class="p-3  fw-bold text-white" style="width: 100%; background: #173551;">Kategori Barang</label>
                        @foreach ($categories as $item)
                            <button type="submit"style="width:100%; border:0;  text-align: left;"  onclick="setCategoryValue({{ $item->id }})" @if(request()->id != null) active @endif>{{ $item->name }}</button>
                        @endforeach
                        </div>
                    </form>

                </div>

                <div class="content p-2 col-lg-10">

                    <!-- Fitur Pencarian & Button Pencarian -->
                    <form class="d-flex justify-content-end mt-3" role="search">
                        <input class="form-control  me-2" name="search" style="width:550px; max-height:100%;" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-navySmall me-5" type="submit" style="height:40px; width: 50px;"><i class="bi bi-search"></i></button>
                    </form>

                    <div class="m-5">
                        <div class="row">
                            @foreach($products as $item)
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                <div class="card p-2 h-100 shadow" style="width: 100%; height: 100%;">
                                    <div class="card-custom6">
                                        <img src="{{  $item->cover != null ? asset('storage/cover/'.$item->cover) : asset('images/no-image4.png') }}" class="card-img-top" alt="..." >
                                    </div>
                                    @if($item->discount -= 0)
                                    <div class="position-absolute top-0 start-0 text-black fs-5 p-2 shadow-sm" style="background: yellow; border-radius: 5px 0 0 0; ">{{$item->discount}}%</div>
                                    @endif

                                    <div class="card-body position-relative">
                                        <h5 class="card-title">{{ $item->name }}</h5>
                                            @if($item->discount > 0)
                                            <span class="card-text text-decoration-line-through" >Rp{{ number_format($item->price, 0, ',', '.') }}</span>
                                            <span class="card-text position-absolute me-2 end-0" >Rp{{ number_format($item->price - (($item->price * $item->discount)/100), 0, ',', '.') }}</span>
                                            @else
                                            <span class="card-text" >Rp{{ number_format($item->price, 0, ',', '.') }}</span>
                                            @endif
                                        @if($item->stock > 0)
                                            <p class="text-dark position-absolute top-0 end-0 fw-bold">Stok : {{$item->stock}}</p>
                                        @else
                                            <p class="text-danger position-absolute top-0 end-0 fw-bold">Habis</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @endforeach
                            
                        </div>
                    </div>   
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        @if ($products->hasPages())
                            <ul class="pagination">
                                {{-- Previous Page Link --}}
                                @if ($products->onFirstPage())
                                    <li class="page-item disabled"><span class="page-link text-white" style="background: #173551;">«</span></li>
                                @else
                                    <li class="page-item"><a class="page-link text-dark" href="{{ $products->previousPageUrl() }}" rel="prev">«</a></li>
                                @endif
    
                                {{-- Page Numbers --}}
                                @foreach(range(1, $products->lastPage()) as $i)
                                    @if($i >= $products->currentPage() - 2 && $i <= $products->currentPage() + 2)
                                        @if ($i == $products->currentPage())
                                            <li class="page-item "><span class="page-link text-white" style="background: #072541;">{{ $i }}</span></li>
                                        @else
                                            <li class="page-item"><a class="page-link text-dark" href="{{ $products->url($i) }}">{{ $i }}</a></li>
                                        @endif
                                    @endif
                                @endforeach
    
                                {{-- Next Page Link --}}
                                @if ($products->hasMorePages())
                                    <li class="page-item"><a class="page-link text-dark" href="{{ $products->nextPageUrl() }}" rel="next">»</a></li>
                                @else
                                    <li class="page-item disabled"><span class="page-link text-white" style="background: #173551;">»</span></li>
                                @endif
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
            
@endsection