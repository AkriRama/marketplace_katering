<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bengkel | @yield('title')</title>
    
    <!-- Bootsrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="{{asset('css/style.css/')}}">

</head>

<body>
    
    <!-- Navbar -->

    <nav class="navbar navbar-expand-lg" style="background-color: #072541;"  data-bs-theme="dark" >
        <div class="container-fluid">
            <div class="p-3 d-flex justify-content-center">
                <img src="{{asset('images/logo5.png')}}" alt=""  style="max-height: 50px; max-width: 90px;">
            </div>
            <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                <!-- nav-link active -->
                <a class="btn btn-navy" aria-current="page" href="/home">Beranda</a>
                </li>
                <li class="nav-item">
                <a class="btn btn-navy" aria-current="page" href="/product">Barang</a>
                </li>
                
                <li class="nav-item">
                <a class="btn btn-navy" aria-current="page" href="/aboutus">Tentang</a>
                </li>
            </ul>

            @if(auth()->id() == 1)
            <div class="collapse navbar-collapse justify-content-end me-3" id="navbarSupportedContent">
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <!-- {{Auth::user()->username}} -->
                            <img src="{{Auth::user()->photo_profile != null ? asset('storage/photoprofile/'. Auth::user()->photo_profile) : asset('images/no-profile2.png')}}" alt="" style="max-width: 30px;">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <div class="d-flex justify-content-center p-3">
                                <img src="{{Auth::user()->photo_profile != null ? asset('storage/photoprofile/'. Auth::user()->photo_profile) : asset('images/no-profile2.png')}}" alt="" style="max-width: 30px;">
                            </div>
                            <div class="text-center mb-3">{{Auth::user()->username}}</div>
                            <li><a class="dropdown-item" href="dashboard"><i class="bi bi-house-door"></i> Dashboard</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            @elseif(auth()->id() != null)
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
                            <li><a class="dropdown-item" href="/profile"><i class="bi bi-diagram-2"></i> Dashboard</a></li>
                            <li><a class="dropdown-item" href="historyorderservice"><i class="bi bi-gear"></i> Pesanan Pelayanan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            @endif
            </div>
    </nav>

    <div class="body h-100">
        @yield('content')
    </div>
    
        <!-- Footer -->
    <footer class="footerGuest text-center text-lg-start text-white" style="background: #173551; height:">

    <!-- Section: Links  -->
    <section class="d-flex justify-content-center">
        <div class="container text-center text-md-start mt-2">
        <!-- Grid row -->
        <div class="row mt-3">
            <!-- Grid column -->
            <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mb-4">
            <!-- Content -->
            <h6 class="text-uppercase fw-bold mb-4">Tentang Kami
            </h6>
            <p style="text-align: justify;">
                
            </p>
            </div>
            <!-- Grid column -->

            <!-- Grid column -->
            <div class="col-md-6 col-lg-6 col-xl-6 mx-auto mb-4">
            <!-- Links -->
            <h6 class="text-uppercase fw-bold mb-4">Lokasi</h6>
            
                <div class="embed-responsive embed-responsive-1by1">
                    <iframe src="" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
            <!-- Grid column -->

            <!-- Grid column -->
            <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
            <!-- Links -->
            <h6 class="text-uppercase fw-bold mb-4">Kontak</h6>
            <p style="font-size: 15px;"><i class="fas fa-home"></i> </p>
            <p style="font-size: 15px;"><i class="fas fa-envelope"></i></p>
            <p style="font-size: 15px;"><i class="fas fa-phone"></i></p>
            </div>
            <!-- Grid column -->
        </div>
        <!-- Grid row -->
        </div>
    </section>
    <!-- Section: Links  -->

    <!-- Copyright -->
    <!-- background-color: rgba(0, 0, 0, 0.05); -->
    <div class="text-center p-3" style="background: #072541;">
    © 2024 Copyright: <span class="fw-bold">Marketplace Katering</span>
    </div>
    <!-- Copyright -->
    </footer>
    <!-- Footer -->
    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <script>
    function setCategoryValue(id) {
        document.getElementById('hiddenInputId').value = id;
    }
    </script>

    <script>
        var slideIndex = 1;
        showCategory(categoryIndex);

        function showSlides(n) {
            var i;
            var slides = document.getElementsByClassName("category");
            if (n > slides.length) {categoryIndex = 1}    
            if (n < 1) {categoryIndex = slides.length}
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";  
            }
            
            slides[categoryIndex-1].style.display = "block";  
            dots[categoryeIndex-1].className += " active2";
        }
    </script>

    <!-- detailproduct -->
    <script>
    document.getElementById('increaseQuantity').addEventListener('click', function() {
        var input = document.getElementById('quantity');
        input.value = parseInt(input.value) + 1;
    });

    document.getElementById('decreaseQuantity').addEventListener('click', function() {
        var input = document.getElementById('quantity');
        if (parseInt(input.value) > 0) {
            input.value = parseInt(input.value) - 1;
        }
    });
</script>

<script>
    $(document).ready(function() {
        $('.increase').click(function() {
            let id = $(this).data('id');
            let input = $('#quantity_' + id);
            input.val(parseInt(input.val()) + 1);
        });

        $('.decrease').click(function() {
            let id = $(this).data('id');
            let input = $('#quantity_' + id);
            if (parseInt(input.val()) > 1) { 
                input.val(parseInt(input.val()) - 1);
            }
        });
    });
</script>

<script>
    document.querySelector('.btn.card-custom4').addEventListener('click', function() {
    const quantityValue = document.getElementById('quantity').value;
    document.getElementById('hiddenQuantity').value = quantityValue;
}); 
</script>
<script>
    document.querySelector('.btn.card-custom3').addEventListener('click', function() {
    const quantityValue = document.getElementById('quantity').value;
    document.getElementById('hiddenQuantity2').value = quantityValue;
}); 
</script>

<!-- Home -->
<script>
        var slideIndex = 1;
        showSlides(slideIndex);

        function plusSlides(n) {
            showSlides(slideIndex += n);
        }

        function currentSlide(n) {
            showSlides(slideIndex = n);
        }

        function showSlides(n) {
            var i;
            var slides = document.getElementsByClassName("mySlides");
            var dots = document.getElementsByClassName("dot");
            if (n > slides.length) {slideIndex = 1}    
            if (n < 1) {slideIndex = slides.length}
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";  
            }
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active2", "");
            }
            slides[slideIndex-1].style.display = "block";  
            dots[slideIndex-1].className += " active2";
        }
    </script>
    
    <script>
        var slideIndex2 = 1;
        showSlides2(slideIndex2);

        function plusSlides2(n) {
            showSlides2(slideIndex2 += n);
        }

        function currentSlide2(n) {
            showSlides2(slideIndex2 = n);
        }

        function showSlides2(n) {
            var i;
            var slides2 = document.getElementsByClassName("mySlides2");
            var dots2 = document.getElementsByClassName("dot2");
            if (n > slides2.length) {slideIndex2 = 1}    
            if (n < 1) {slideIndex2 = slides2.length}
            for (i = 0; i < slides2.length; i++) {
                slides2[i].style.display = "none";  
            }
            for (i = 0; i < dots2.length; i++) {
                dots2[i].className = dots2[i].className.replace(" active2", "");
            }
            slides2[slideIndex2-1].style.display = "block";  
            dots2[slideIndex2-1].className += " active2";
        }
    </script>
</body>
</html>