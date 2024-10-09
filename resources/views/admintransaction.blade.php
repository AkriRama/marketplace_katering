@extends('layouts.mainlayoutsadmin')

@section('title','Transaksi')

@section('content')

    <div class="mt-3">
        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>

        @endif
    </div>
    <div class="sideBar">
        <form action="" id="pagination-form">

            <select name="paginate" id="paginate" onchange="submitForm()">
                <option value="">Kuantitas</option>
                <option value="10">10</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <button hidden type="submit" >Hello</button>
        </form>
    </div>

    <div class="my-3 table">
        <table class="table table-hover table-bordered">
            <thead class="table table-dark" style="vertical-align: middle;">
                <tr>
                    <th>No.</th>
                    <form action="">
                        @if(session('sortdown'))
                        <input hidden name="sortdown" value="desc">
                        <th>Tanggal <button class="btn" href=""><i class="bi bi-sort-down"></i></button></th>
                        @else
                        <input hidden name="sortup" value="asc">
                        <th>Tanggal <button class="btn" href=""><i class="bi bi-sort-up"></i></button></th>
                        @endif
                    </form>
                    <th>No Pesanan</th>
                    <th>Petugas</th>
                    <th>Jumlah</th>
                    <th>Total Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($transactions) && count($transactions) > 0)
                    @foreach($transactions as $item)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$item->created_at}}</td>
                            <td>{{$item->order_id}}</td>
                            <td>{{$users->username}}</td>
                            <td>{{$item->quantity}}</td>
                            <td>Rp{{number_format($item->totalOrder, 0, ',', '.')}}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data transaksi</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <!-- paginator -->
    {{ $transactions->links('pagination::paginator-bengkel') }}

    <script>
        function submitForm() {
            document.getElementById('pagination-form').submit();
        }
    </script>

@endsection