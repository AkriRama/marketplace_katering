@extends('layouts.mainlayoutsuser')

@section('title','Profile')

@section('content')

<div class="container p-3">

        <div class="card p-3">
            <div>
                <h1>Proses Pesanan</h1>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Pesanan</th>
                        <th>Total Pesanan</th>
                        <th>Metode Pembayaran</th>
                        <th>Tanggal Pesanan</th>
                        <th>Status Pembayaran</th>
                        <th>Status Pesanan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $item)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$item->order_id}}</td>
                        <td>Rp{{ number_format($item->totalOrder, 0, ',', '.') }}</td>
                        <td>{{$payments->name}}</td>
                        <td>{{$item->created_at->format('d-m-Y H:i:s')}}</td>
                        <td>
                            @if($item->status == "unpaid")
                                <p class="text-danger fw-bold">Belum Dibayar</p>
                            @else
                                <p class="text-success fw-bold">Sudah Dibayar</p>
                            @endif
                        </td>
                        <td>{$item->status_service}</td>
                        <td>
                            <a href="">Detail</a>
                            <a href="" type="button" onclick="cetakHalaman(); return false;">Cetak</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
        </table>
    </div>
</div>

<script>
    function cetakHalaman() {
        const myWindow = window.open("/cetak", "_blank");
        myWindow.focus();

        myWindow.onload = function() {
            myWindow.print();
        }
    }
</script>
@endsection