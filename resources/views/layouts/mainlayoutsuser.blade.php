<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bengkel | @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/style.css/') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

</head>

<style>
body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

.content-container {
    flex: 1; 
    padding: 20px;
}

.title {
    margin: 40px 40px 0;
}

</style>
<body>
    <div class="main">

            <div class="row g-0 h-100">
                <div class="sidebar">

                    <div class="profile-bar p-3" style="background: #072541;">

                        <div class="fw-bold">
                            <img src="{{Auth::user()->photo_profile != null ? asset('storage/photoprofile/'. Auth::user()->photo_profile) : asset('images/no-profile2.png')}}" alt="" style=" max-width: 80px; max-height: 80px;" class="rounded-circle">
                            <span class="description">
                                {{Auth::user()->username}}

                            </span>
                        </div>
                    </div>

                    <a href="/cashier" @if(request()->route()->uri == 'cashier') class='active' @endif>
                        <i class="bi bi-display"></i>
                         <span class="description">Kasir</span>
                    </a>
                    
                    <a href="/productcashier" @if(request()->route()->uri == 'productcashier') class='active' @endif>
                        <i class="bi bi-bag-fill"></i> 
                        <span class="description">Barang</span>
                    </a>
                    
                    <a href="/report" @if(request()->route()->uri == 'report') class='active' @endif>
                        <i class="bi bi-clipboard-data"></i> 
                        <span class="description">Transaksi</span>
                    </a>
                    
                    <a href="/profile" @if(request()->route()->uri == 'profile') class='active' @endif>
                        <i class="bi bi-person-fill"></i> 
                        <span class="description">Profile</span>
                    </a>
                    
                    <a href="/logout">
                        <i class="bi bi-box-arrow-right"></i> 
                        <span class="description">Logout</span>
                    </a>
                </div>
                
                <div class="col-lg  d-flex flex-column justify-content-between">
            
                    <nav class="navbar navbar-expand-lg" style="background: #072541;" data-bs-theme="dark">
                        <div class="container-fluid ms-3 me-5">
                            <div id="menu-button">
                                <input type="checkbox" id="menu-checkbox">
                                <label for="menu-checkbox" id="menu-label">
                                    <div id="hamburger"></div>
                                </label>
                                
                            </div>
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse justify-content-end " id="navbarSupportedContent">
                                <ul class="navbar-nav mb-2 mb-lg-0">
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            {{Auth::user()->username}}
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="home"><i class="bi bi-house-door"></i> Beranda</a></li>
                                            <li><a class="dropdown-item" href="historyorderservice"><i class="bi bi-gear"></i> Pesanan Pelayanan</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item" href="logout"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>

                    <div class="title row" style="">
        
                        <div class="col-6 fw-bold fs-4">
                            @if(request()->route()->uri == 'cashier') Kasir 
                            @elseif(request()->route()->uri == 'productcashier') Daftar Barang 
                            @elseif(request()->route()->uri == 'report') Daftar Transaksi Penjualan
                            @elseif(request()->route()->uri == 'profile') Profil @endif
                        </div>
                        
                        <div class="col-6" style="text-align: right; font-size: 15px;">
                            @if(request()->route()->uri == 'cashier') <i class="bi bi-speedometer2"></i> Kasir
                            @elseif(request()->route()->uri == 'productcashier') <i class="bi bi-bag-fill"></i> Barang
                            @elseif(request()->route()->uri == 'report') <i class="bi bi-clipboard-data"></i> Transaksi
                            @elseif(request()->route()->uri == 'profile') <i class="bi bi-person-fill"></i>  Profil @endif
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