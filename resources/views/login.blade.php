<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bengkel | Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

</head>

<style>
    .card-custom{
        border-radius : 30px 30px 30px 30px;
        width: 100%;
        background: white;
    }

    .card-custom2{
        max-width:100vh;
        background:white;
        border-radius : 40px 0px 40px 0px;
    }
    .card-custom3 input{
        max-width:100%;
        border-radius : 20px 20px 20px 20px;
    }
    .card-custom4 {
        max-height:100%;
        border-radius : 20px 0px 0px 20px;
    }
    .card-custom5 {
        border: 0;
        border-radius : 0px 20px 20px 0px;
    }
    .login {
        background-color: #072541;
        max-height: 100%;
        max-width: 100%;
    }

    .login-box {
        height: 100vh;
    }

    .login img {
        max-height: 150px;
    }
    
    
    
</style>
<body>
    <divn class="d-flex justify-content-center" style="width: 100%;">

        <div class="position-absolute" style="z-index: 9999; padding-top: 20px;">
            @if(session('message'))
            <div class="alert alert-danger">
                {{ session('message') }}
            </div>
            @endif
        </div>
    </div>

    <div class="container-fluid login text-white position-relative">
            
        <div class="container d-flex flex-column justify-content-center align-items-center login-box">
                    
            <div class="text text-center login">
                <img src="{{asset('images/logo4.png')}}" alt="">
            </div>
            

            <div class="card bg-transparent" style="border:0; width: 60vh;">
                
                <form action="" method="post">
                    @csrf
                    
                    <div class="card-body card-custom p-4">

                    <div class="form-group input-group mb-3 card-body card-custom2 shadow" style="height:100%;">
                        <div class="input-group-prepend">
                            <span class="input-group-text card-custom4 bg-dark" style="height:100%; --bs-bg-opacity: .15;">
                            <i class="bi bi-person-fill"></i>
                            </span>
                        </div>
                        <input id="username" type="text" class="form-control card-custom5 bg-dark" style="--bs-bg-opacity: .15;" name="username" placeholder="Nama Pengguna" required oninvalid="this.setCustomValidity('Nama Pengguna tidak boleh kosong')" oninput="setCustomValidity('')">
                    </div>
                    
                    <div class="form-group input-group mb-3 card-body card-custom2 shadow">
                        <div class="input-group-prepend">
                            <span class="input-group-text card-custom4 bg-dark" style="height:100%; --bs-bg-opacity: .15;  border: 10px;">
                            <i class="bi bi-key-fill"></i>
                            </span>
                        </div>
                        <input id="password" type="password" class="form-control bg-dark" style="--bs-bg-opacity: .15;" name="password" placeholder="Kata Sandi" required oninvalid="this.setCustomValidity('Kata Sandi tidak boleh kosong')" oninput="setCustomValidity('')">
                        <div class="input-group-prepend">
                            <span class="input-group-text card-custom5 bg-dark"  style="height:100%; --bs-bg-opacity: .15;">
                            <i class="fas fa-eye" id="togglePassword"></i>
                            </span>
                        </div>
                    </div>

                        <div class="form-text">
                        </div>

                    </div>

                    <div class="card-button mx-auto mt-3 mb-2" style="max-width:6rem;">
                        <button type="submit" class="btn btn-light form-control">
                            <Strong>LOGIN</Strong>
                        </button>
                    </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("togglePassword").addEventListener("click", function(e) {
            var passwordInput = document.getElementById("password");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                e.target.classList.add("fa-eye-slash"); 
                e.target.classList.remove("fa-eye");
            } else {
                passwordInput.type = "password";
                e.target.classList.add("fa-eye"); 
                e.target.classList.remove("fa-eye-slash");
            }
        });
    </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
    </html>