@extends('layouts.mainlayoutsuser')

@section('title', 'Daftar Barang')

@section('content')

<div class="card-body">
    <div>
        <div class="mb-3 d-flex justify-content-end">
            <form action="">
                <label for="search" class="fw-bold me-3">Cari : </label>
                <input type="text" name="search" style="border-radius: 5px;">
                <button hidden type="submit"></button>
            </form>
        </div>
        <table class="table table-bordered">
            <thead class="table table-dark">
                <tr>
                    <th>No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Stok</th>
                </tr>
            </thead>
            @if(isset($products))
            <tbody>
                @foreach($products as $item)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$item->code_product}}</td>
                    <td>{{$item->name}}</td>
                    <td>Rp{{number_format($item->price, 0, ',', '.')}}</td>
                    <td>{{$item->stock}}</td>
                </tr>
                @endforeach
            </tbody>
            
            @else
            <tbody>
                <tr>
                    <td colspan="5" style="text-align: center;">Tidak ada data yang tersedia ditabel</td>
                </tr>
            </tbody>
            @endif
        </table>
    </div>

    <!-- paginator -->
    {{ $products->links('pagination::paginator-bengkel') }}
</div>

@endsection