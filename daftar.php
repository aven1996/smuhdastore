<?php
    require "inc/koneksi.php";
    require_once "google-api/vendor/autoload.php";
    require "inc/config_googleAPI.php";
    
    // GOOGLE API
    $loginURL = $gClient->createAuthUrl();

    if(isset($_POST['submit']))
    {
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $password_ulang = htmlspecialchars($_POST['password_ulangi']);

        // ambil data buat ngecek username sudah ada atau belum
        $query_data = mysqli_query($con, "SELECT * FROM akun_login WHERE username = '$username' ");
        
        if ($password != $password_ulang)
        {
            echo "NOTIF:password beda!";
        }
        elseif(mysqli_num_rows($query_data) > 0)
        {
            echo "NOTIF: username sudah digunakan!";
        }
        else
        {
            // buat enkripsi password
            $password = password_hash($password, PASSWORD_DEFAULT);
            $query = mysqli_query($con, "INSERT INTO akun_login VALUES('','$username','$password','pembeli','$email') ");
            if (mysqli_affected_rows($con) == 1) {
                // ambil data yang baru ditambahkan tadi
                $query_data = mysqli_query($con, "SELECT * FROM akun_login WHERE username = '$username' ");
                $row = mysqli_fetch_assoc($query_data);
                
                // buat session
                session_start();
                $_SESSION['admin'] = $row['id_login'];
                $_SESSION['stt_akun'] = "pembeli";

                // tambah ke profil
                $id_akun = $row['id_login'];
                mysqli_query($con, "INSERT INTO profil VALUES('','$id_akun','','','','','') ");

                header("Location:admin_pembeli.php");
            }
        }

    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Smuhdastore Belaja Nyaman dan Terpercaya</title>
    <link rel="shortcut icon" href="assets/img/fav.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/style_login.css">
    <link rel="stylesheet" href="assets/css/style_daftar.css">
</head>
<body style="background: darkcyan;">
 
<div class="d-flex flex-column position-absolute w-100 py-5 align-items-center" style="top: 0;">
    <a href="index.php"><img src="assets/img/logo_white.svg" alt="" style="width: 200px; margin-bottom:20px;"></a>
    <span class="mb-3 text-white" style="font-size: 18px;">Belanja Nyaman dan Terpercaya</span>
    <div class="container form-login bg-white shadow p-5" >
        <h3 class="text-secondary mb-4">Daftar</h3>
        <form action="" method="post">
            <input type="text" name="username" id="" class="form-control mb-4 p-3" placeholder="Username" required>
            <input type="text" name="email" id="" class="form-control mb-4 p-3" placeholder="Email" required>
            <input type="password" name="password" id="" class="form-control mb-4 p-3" placeholder="Password" required>
            <input type="password" name="password_ulangi" id="" class="form-control mb-4 p-3" placeholder="Ulangi Password" required>
            <button type="submit" name="submit" class="btn w-100 text-white p-3" style="background-color: darkcyan; font-weight: bold;">SUBMIT</button>
        </form>
        <hr>
        <span class="text-secondary" style=" font-size: small; width: 100%; text-align: center; display: inline-block;">DAFTAR MUDAH DENGAN AKUN GOOGLE</span>
        <a href="<?= $loginURL; ?>">
        <div class=" d-flex justify-content-between align-items-center bg-primary text-color" style="text-align: center; padding: 10px;font-weight: bold; color: white; margin: 10px auto; font-size:large;width:130px; border-radius:5px;">
            <img style="width: 40px;" src="assets/img/google.jpg" alt="">
            <span>Google</span>
        </div>
        </a>
        <span class="w-100 d-block text-center text-secondary" style="font-size: 14px;">Dengan mendaftar, Anda setuju dengan Syarat, Kententuan dan Kebijakan dari Smuhdastore & Kebijakan Privasi</span>
        <span class="text-secondary" style="width: 100%; display: inline-block; text-align: center; margin-top:20px; height:fit-content;">Punya akun? <a href="login.php" style="color: darkcyan; font-weight: bold;">Log In
    </div>
    <div class="container d-flex justify-content-center text-white py-3">
        <a href="daftar_sup_penj.php" class="text-white">Daftar Akun Penjual</a>
    </div>
    
</div> 

<script src="assets/bootstrap/jquery/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.js"></script>
<script src="assets/bootstrap/popper/popper.js"></script>


<!-- membuat form menjadi kecil saat ukuran width window melebihi nilai tertentu -->
<script type="text/javascript">
  $(window).ready(function(){
  if ($(window).width() >= 700) {
   $('.form-login').addClass('formX');
  }
  else {
   $('.form-login').removeClass('formX');
  }
 });
 </script>
</body>
</html>