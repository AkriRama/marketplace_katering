@extends('layouts.mainlayoutsuser')

@section('title','Riwayat Pesanan')

@section('content')

<div class="container p-3">

        <div class="card p-3">
            <div>
                <h1>Riwayat Pesanan</h1>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Pesanan</th>
                        <th>Total Pesanan</th>
                        <th>Metode Pembayaran</th>
                        <th>Tanggal Pesanan</th>
                        <th>Status</th>
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
                                <p class="text-danger fw-bold">Belum Bayar</p>
                            @else
                                <p class="text-success fw-bold">Sudah Bayar</p>
                            @endif
                        </td>
                        <td>
                            <a href="detailorder/{{$item->order_id}}">Detail</a>
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