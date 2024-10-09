    <div class="modal-header">
        <h1 class="modal-title fs-5" id="userAddModalLabel">Tambah User</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <form action="adduser" method="post" enctype="multipart/form-data">
        @csrf
        
        <div class="modal-body">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" style="border-radius: 5px 0 0 5px;" >
                    <i class="bi bi-person-fill"></i>
                    </span>
                </div>
                <input id="username" type="text" class="form-control" name="username" placeholder="Nama Pengguna" required>
            </div>
            
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" style="border-radius: 5px 0 0 5px;" >
                    <i class="bi bi-key-fill"></i>
                    </span>
                </div>
                <input id="password" type="password" class="form-control" name="password" placeholder="Kata Sandi" required>
                <div class="input-group-prepend">
                    <span class="input-group-text" style="border-radius: 0 5px 5px 0;" >
                    <i class="bi bi-eye" id="togglePassword"></i>
                    </span>
                </div>
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" style="border-radius: 5px 0 0 5px;" >
                    <i class="bi bi-key-fill"></i>
                    </span>
                </div>
                <input id="confirmpassword" type="password" class="form-control" name="password_confirmation" placeholder="Konfirmasi Kata Sandi" required>
                <div class="input-group-prepend">
                    <span class="input-group-text" style="border-radius: 0 5px 5px 0;" >
                    <i class="bi bi-eye" id="togglePassword1"></i>
                    </span>
                </div>
            </div>
            
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" style="border-radius: 5px 0 0 5px;" >
                    <i class="bi bi-telephone"></i>
                    </span>
                </div>
                <input id="phone" type="text" class="form-control" name="phone" placeholder="No Telepon" required>
            </div>
            
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" style="border-radius: 5px 0 0 5px; height: 100%;" >
                    <i class="bi bi-house-fill"></i>
                    </span>
                </div>
                <textarea id="address" class="form-control" name="address" placeholder="Alamat" required></textarea>
            </div>
            
            <div class="mb-3">
                <label for="image" class="form-label">Foto Profil</label>
                <input type="file" name="image" id="image" class="form-control">
            </div>            
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondarySmall" data-bs-dismiss="modal">Close</button>
            <button class="btn btn-success"><i class="bi bi-save"></i> Simpan</button>
        </div>
    </form>

    <script>
        document.getElementById("togglePassword").addEventListener("click", function(e) {
            var passwordInput = document.getElementById("password");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                e.target.classList.add("bi-eye-slash"); 
                e.target.classList.remove("bi-eye");
            } else {
                passwordInput.type = "password";
                e.target.classList.add("bi-eye"); 
                e.target.classList.remove("bi-eye-slash");
            }
        });
    </script>

    <script>
        document.getElementById("togglePassword1").addEventListener("click", function(e) {
            var passwordInput = document.getElementById("confirmpassword");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                e.target.classList.add("bi-eye-slash"); 
                e.target.classList.remove("bi-eye");
            } else {
                passwordInput.type = "password";
                e.target.classList.add("bi-eye"); 
                e.target.classList.remove("bi-eye-slash");
            }
        });
    </script>