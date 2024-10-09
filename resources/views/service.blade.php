@extends('layouts.mainlayouts')

@section('title','Service')

@section('content')

        <div class="body-content h-100">
            <div class="row g-0 h-100">

                <div class="content p-2 col-lg-12">

                    <div class="m-5">
                        <div class="row">
                            @foreach($services as $item)
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                <div class="card p-2 h-100" style="width: 100%; height: 100%;">
                                    <div class="card d-flex flex-column justify-content-center m-auto" style="width: 100%; height: 100%; border: 0;">
                                        <img src="{{  $item->cover != null ? asset('storage/coverService/'.$item->cover) : asset('images/no-image4.png') }}" class="card-img-top" alt="..." >
                                    </div>

                                    <div class="card-body position-relative">
                                        <h5 class="card-title">{{ $item->name }}</h5>
                                        <p class="card-text">Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                                        @if($item->status != "available")
                                        <p class="text-capitalize text-danger position-absolute top-0 end-0" style="float: right;">{{$item->status}}</p>
                                        <a href="detailservice/{{$item->slug}}" class="btn btn-secondary disabled">Detail</a>
                                        @else
                                        <p class="text-capitalize text-success position-absolute top-0 end-0" style="float: right;">{{$item->status}}</p>
                                        <a href="detailservice/{{$item->slug}}" type="button" class="btn btn-navy">Detail</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            
                        </div>
                    </div>   
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        @if ($services->hasPages())
                            <ul class="pagination">
                                {{-- Previous Page Link --}}
                                @if ($services->onFirstPage())
                                    <li class="page-item disabled"><span class="page-link">«</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $services->previousPageUrl() }}" rel="prev">«</a></li>
                                @endif
    
                                {{-- Page Numbers --}}
                                @foreach(range(1, $services->lastPage()) as $i)
                                    @if($i >= $services->currentPage() - 2 && $i <= $services->currentPage() + 2)
                                        @if ($i == $services->currentPage())
                                            <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                                        @else
                                            <li class="page-item"><a class="page-link" href="{{ $services->url($i) }}">{{ $i }}</a></li>
                                        @endif
                                    @endif
                                @endforeach
    
                                {{-- Next Page Link --}}
                                @if ($services->hasMorePages())
                                    <li class="page-item"><a class="page-link" href="{{ $services->nextPageUrl() }}" rel="next">»</a></li>
                                @else
                                    <li class="page-item disabled"><span class="page-link">»</span></li>
                                @endif
                            </ul>
                        @endif
                    </div>
                </div>

            </div>
        </div>       

    </div>
    
@endsection