<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BengkelAdmin | @yield('title')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{asset('css/style.css/')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/style.css/') }}">

    <!-- Font Awesome untuk icon atas dan bawah -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>
<style>
    .main {
        height: 100vh;
    }

    

    /* .sidebar ul {
        list-style: none;
    }

    .sidebar li {
        padding: 10px;
    } */

    .sidebar a {
        color: #fff;
        text-decoration: none;
        display: block;
        padding: 20px 20px;
    }

    .sidebar a:hover {
        background: #000;
    }

    .active {
        background: #000;
        border-right: solid 8px orange;
    }

    .nav-link .img {
        max-width: 50px;
    }
    
    /* a{
        text-decoration: none;
        color: black;
    } */

    body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

.content-container {
    flex: 1; /* Ini akan mengisi ruang kosong antara header dan footer */
    padding: 20px; /* Sesuaikan dengan kebutuhan Anda */
}

footer {
    background: #173551;
    color: white;
    text-align: center;
    padding: 10px;
}




</style>

<body>
    <div class="main ">
        <!-- <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Bengkel</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin" aria-controls="navbarAdmin" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </nav> -->

            <div class="row g-0 h-100">

                <div class="sidebar">                    
                    
                    <div class="titleSide p-2 bg-dark d-flex justify-content-center">
                        <img src="{{asset('images/logo5.png')}}" alt=""  style="max-height: 100px; max-width: 150px;">
                    </div>
                    <a href="/dashboard" @if(request()->route()->uri == 'dashboard') class='active' @endif>
                        <i class="bi bi-diagram-2"></i>
                        <span class="description">Dashboard</span>
                    </a>
                    
                    <a href="/adminslider" @if(request()->route()->uri == 'adminslider') class='active' @endif>
                        <i class="bi bi-sliders"></i>
                        <span class="description">Slider</span>
                    </a>

                    <a href="/admincategory" @if(request()->route()->uri == 'admincategory' || request()->route()->uri == 'addcategory' || request()->route()->uri == 'editcategory' || request()->route()->uri == 'deletecatecategory' || request()->route()->uri == 'deletedcategory') class='active' @endif>
                        <i class="bi bi-tags-fill"></i> 
                        <span class="description">Kategori</span>
                    </a>
                    
                    <a href="/adminproduct" @if(request()->route()->uri == 'adminproduct') class='active' @endif>
                        <i class="bi bi-box-fill"></i> 
                        <span class="description">Barang</span>
                    </a>

                    <a href="/users" @if(request()->route()->uri == 'users') class='active' @endif>
                        <i class="bi bi-person"></i> 
                        <span class="description">Profil</span>
                    </a>
                    </a>

                    <a href="/adminreportincome" @if(request()->route()->uri == 'adminreportincome') class='active' @endif>
                        <i class="bi bi-coin"></i>
                        <span class="description">Laporan Keuangan</span>
                    </a>
                    <a href="/admintransaction" @if(request()->route()->uri == 'admintransaction') class='active' @endif>
                        <i class="bi bi-bag-check-fill"></i> 
                        <span class="description">Transaksi</span>
                    </a>

                    <a href="/logout">
                        <i class="bi bi-box-arrow-right"></i> 
                        <span class="description">Logout</span>
                    </a>
                    
    
    
                </div>

                <div class="col d-flex flex-column justify-content-between">
                    <nav class="navbar navbar-expand-sm bg-dark" data-bs-theme="dark">
                        <div class="container-fluid">
                            <div id="menu-button">
                                <input type="checkbox" id="menu-checkbox">
                                <label for="menu-checkbox" id="menu-label">
                                    <div id="hamburger"></div>

                                </label>
                            </div>

                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                            </button>

                            <div class="collapse navbar-collapse justify-content-end me-3" id="navbarSupportedContent">
                                <ul class="navbar-nav mb-2 mb-lg-0">
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <img src="{{Auth::user()->photo_profile != null ? asset('storage/photoprofile/'. Auth::user()->photo_profile) : asset('images/no-profile2.png')}}" alt="" style="max-width: 30px;">
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <div class="d-flex justify-content-center p-3">
                                                <img src="{{Auth::user()->photo_profile != null ? asset('storage/photoprofile/'. Auth::user()->photo_profile) : asset('images/no-profile2.png')}}" alt="" style="max-width: 30px;">
                                            </div>
                                            <div class="text-center mb-3">{{Auth::user()->username}}</div>
                                            <li><a class="dropdown-item" href="home"><i class="bi bi-house-door"></i> Beranda</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item" href="logout"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                            
                        </div>
                        
                    </nav>

                    <div class="title row">
                        
                        <div class="col-6 fw-bold fs-5">
                            @if(request()->route()->uri == 'dashboard') Dashboard
                            @elseif(request()->route()->uri == 'adminslider') Daftar Slider 
                            @elseif(request()->route()->uri == 'admincategory') Daftar Kategori 
                            @elseif(request()->route()->uri == 'adminproduct') Daftar Barang 
                            @elseif(request()->route()->uri == 'adminservice') Daftar Pelayanan 
                            @elseif(request()->route()->uri == 'users') Daftar User
                            @elseif(request()->route()->uri == 'admincashier') Kasir 
                            @elseif(request()->route()->uri == 'purchaseproduct') Pembelian Barang 
                            @elseif(request()->route()->uri == 'adminexpenses') Daftar Pengeluaran
                            @elseif(request()->route()->uri == 'adminreportincome') Laporan Pendapatan
                            @elseif(request()->route()->uri == 'admintransactionpurchases') Daftar Transaksi Pembelian
                            @elseif(request()->route()->uri == 'admintransaction') Daftar Transaksi Penjualan @endif
                        </div>
                        
                        <div class="col-6" style="text-align: right; font-size: 13px;">
                                @if(request()->route()->uri == 'dashboard') <i class="bi bi-speedometer2"></i> Dashboard 
                                @elseif(request()->route()->uri == 'adminslider') <i class="bi bi-speedometer2"></i> Dashboard > Slider
                                @elseif(request()->route()->uri == 'admincategory') <i class="bi bi-tags-fill"></i> Kategori
                                @elseif(request()->route()->uri == 'adminproduct') <i class="bi bi-box-fill"></i>  Barang 
                                @elseif(request()->route()->uri == 'adminservice') <i class="bi bi-gear"></i> </i> Pelayanan 
                                @elseif(request()->route()->uri == 'users') <i class="bi bi-people"></i> </i> User 
                                @elseif(request()->route()->uri == 'adduser') <i class="bi bi-fill-bag"></i> </i> <a href="/users">User</a> > Tambah User
                                @elseif(request()->route()->uri == 'admincashier') <i class="bi bi-display"></i> Kasir
                                @elseif(request()->route()->uri == 'purchaseproduct') <i class="bi bi-credit-card"></i> Pembelian
                                @elseif(request()->route()->uri == 'adminexpenses') <i class="bi bi-wallet2"></i> Pengeluaran
                                @elseif(request()->route()->uri == 'adminreportincome') <i class="bi bi-coin"></i> Pendapatan
                                @elseif(request()->route()->uri == 'admintransactionpurchases') <i class="bi bi-cart-check-fill"></i>  Transaksi Pembelian
                                @elseif(request()->route()->uri == 'admintransaction') <i class="bi bi-bag-check-fill"></i>  Transaksi @endif
                            </div>
                        </div>
                    <div class="ps-5 pe-5 content-container">
                        @yield('content')
                    </div>

                    <!-- Footer -->
                    <footer>

                    <!-- Copyright -->
                    <div class="text-center p-2" style="background: #173551; font-size: 15px;">
                        © 2024 Copyright: <span class="fw-bold">Marketplace Katering</span>
                    </div>
                    <!-- Copyright -->
                    </footer>
                </div>
            </div>
    </div>


    <script>
        const menu = document.getElementById('menu-label');
        const sidebars = document.getElementsByClassName('sidebar');

        if (menu && sidebars.length > 0) { 
            const sidebar = sidebars[0];

            menu.addEventListener('click', function() {
                sidebar.classList.toggle('hide');
            });
        }

    </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>