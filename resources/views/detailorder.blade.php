@extends('layouts.mainlayoutsuser')

@section('title','Detail Pesanan')

@section('content')

<div class="container p-3">

        <div class="card p-3">
            <div>
                <h1>Riwayat Pesanan</h1>
            </div>

            <div class="card-header p-3">
                <div class="row">
                    <div class="col-2 fw-bold">No Pesanan</div>
                    <div class="col-6">: {{$transactionDetail->order_id}}</div>

                    <div class="col-2 fw-bold">Tanggal Pesanan</div>
                    <div class="col-2">: {{$transactionDetail->created_at->format('d-m-Y H:i:s')}}</div>
                </div>

                <div class="row">
                    <div class="col-2 fw-bold">Metode Pembayaran</div>
                    <div class="col-6">: {{$payments->name}}</div>

                    <div class="col-2 fw-bold">Status Pesanan</div>
                    @if($transactionDetail->status == "unpaid")
                    <div class="col-2 text-danger fw-bold">: Belum Bayar</div>
                    @else
                    <div class="col-2 text-success fw-bold">: Sudah Bayar</div>
                    @endif
                </div>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Proses Pemesanan</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $item)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$item->name}}</td>
                        <td>{{$item->price}}</td>
                        <td>{{$item->quantity}}</td>
                        <td>{{$item->status_service}}</td>
                        <td style="text-align: right;">Rp{{number_format($item->total, 0, ',', '.')}}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" style="text-align: right;">Total Pesanan</th>
                        <td style="text-align: right;">Rp{{ number_format($item->totalOrder, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
        </table>
    </div>
</div>

<script>
    function cetakHalaman() {
        const myWindow = window.open("/cetak", "_blank");
        myWindow.focus();

        // Menunggu jendela sepenuhnya dimuat sebelum mencetak
        myWindow.onload = function() {
            myWindow.print();
        }
    }
</script>
@endsection