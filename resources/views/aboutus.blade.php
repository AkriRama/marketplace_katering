@extends('layouts.mainlayouts')

@section('title','Tentang Kami')

@section('content')

    
<style>
    .card img {
        width: 30vh;
        height:30vh;
        margin:auto;
    }
</style>
    
        <div class="container">
            <div class="card m-5" style="border-color: #072541;">
                <div class="card-header text-white" style="background: #072541;">
                    <h4>Tentang Kami</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="card col-lg-4 col-md-6 col-sm-12" style="border:0;">
                            <img src="{{asset('images/logo.png')}}" alt="">
                        </div>

                        <div class="col-lg-7 col-md-6 col-sm-12">
                            <h5></h5>
                            <p style="text-align:justify;"></p>
                            <h6>Hubungi Kami</h6>
                            <table>
                                <tr>
                                    <th>Alamat</th>
                                    <td> :</td>
                                    <td> </td>
                                </tr>
                                <tr>
                                    <th>No Telepon</th>
                                    <td> :</td>
                                    <td> </td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td> :</td>
                                    <td> </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-5" style="border-color: #072541;">

                <div class="card-header mb-2 text-white text-center"  style="background: #072541;">
                    <h5>Lokasi</h5>
                </div>
                
                <div class="card-body" style="border:0;">
                    <div class="embed-responsive embed-responsive-21by9">
                        <iframe src="" width="100%" height="500" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            
            </div>
        </div>


@endsection