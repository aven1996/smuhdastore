<?php
    require "inc/koneksi.php";
    require_once "google-api/vendor/autoload.php";
    require "inc/config_googleAPI.php";
    
    // GOOGLE API
    $loginURL = $gClient->createAuthUrl();

    
    // cek apakah session sudah dibuat atau belum/sudah login atau belum
    if(isset($_SESSION['admin']))
    {
        header("Location: index.php");
    }

    // jika ditekan tombol login
    if(isset($_POST['submit']))
    {
        $usermail = htmlspecialchars($_POST['usermail']);
        $password = htmlspecialchars($_POST['password']);

        // ambil data di database dengan username atau email diatas
        $query_ambilData = mysqli_query($con, "SELECT * FROM akun_login WHERE username = '$usermail' OR email = '$usermail' ");
        // cek datanya ada atau tidak di database
        if (mysqli_num_rows($query_ambilData) > 0)
        {
            // fetch data yang telah diambil
            $row = mysqli_fetch_assoc($query_ambilData);
            // cek password
            if (password_verify($password, $row['password']))
            {   
                // buat session
                $_SESSION['admin'] = $row['id_login'];
                // redirect berdasarkan status akun [penjual/pembeli]
                if ($row['stt_akun'] == "pembeli")
                {  
                    $_SESSION['stt_akun'] = "pembeli";
                    header("Location: admin_pembeli.php");
                }
                elseif($row['stt_akun'] == "penjual")
                {
                    $_SESSION['stt_akun'] = "penjual";
                    header("Location: admin_penjual.php");
                }
                elseif($row['stt_akun'] == "owner")
                {
                    $_SESSION['stt_akun'] = "owner";
                    header("Location: admin_owner.php");
                }
            }
            else
            {
                echo "<script>alert('Password salah');</script>";
            }
        }
        else
        {
            echo "<script>alert('Akun belum terdaftar. Silahkan daftar terlebih dahulu!');</script>";
        }
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In - Smuhdastore Belaja Nyaman dan Terpercaya</title>
    <link rel="shortcut icon" href="assets/img/fav.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="assets/icons/icomoon1/icon.css">
    <link rel="stylesheet" href="assets/css/style_login.css">
    
    <style>
    </style>
</head>
<body style="background: darkcyan;"> 
 
<div class="d-flex flex-column position-absolute w-100 py-5 align-items-center" style="top: 0;">
    <a href="index.php"><img src="assets/img/logo_white.svg" alt="" style="width: 200px; margin-bottom:20px;"></a>
    <span class="mb-3 text-white" style="font-size: 18px;">Belanja Nyaman dan Terpercaya</span>
    <div class="container form-login bg-white shadow p-5" >
        <h3 class="text-secondary mb-4">Log In</h3>
        <form action="" method="post">
            <input type="text" name="usermail" id="" class="form-control mb-4 p-3" placeholder="Username/Email" required>
            <div class="position-relative">
            <input type="password" name="password" id="" class="form-control mb-4 p-3" placeholder="Password" required>
            <b onclick="showPass();" class="icon-eye position-absolute p-2" style="right: 5px; top:13px; color:silver; font-size:14pt;"></b>
            </div>
            <button type="submit" name="submit" class="btn w-100 text-white p-3" style="background-color: darkcyan; font-weight: bold;">LOG IN</button>
        </form>
        <hr>
        <span class="text-secondary" style="color: darkgrey; font-size: small; width: 100%; text-align: center; display: inline-block;">LOG IN MUDAH DENGAN AKUN GOOGLE</span>
        <a href="<?= $loginURL; ?>">
        <div class=" d-flex justify-content-between align-items-center bg-primary text-color" style="text-align: center; padding: 10px;font-weight: bold; color: white; margin: 10px auto; font-size:large;width:130px; border-radius:5px;">
            <img style="width: 40px;" src="assets/img/google.jpg" alt="">
            <span>Google</span>
        </div>
        </a>
        <span class="text-secondary" style=" width: 100%; display: inline-block; text-align: center; margin: 10px 0;">Belum punya akun? <a href="daftar.php" style="color: darkcyan; font-weight: bold;">Daftar</a></span>
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


 <!-- fungsi show dan hide form password -->
 <script>
    var icon_eye = document.querySelector(".icon-eye");
    var typeForm = document.querySelector("input[type='password']");
    var trigger = "off";
    function showPass()
    {
        if(trigger == "off")
        {
            icon_eye.style.color = "darkcyan";
            typeForm.type = "text";
            trigger = "on";
        }
        else
        {
            icon_eye.style.color = "silver";
            typeForm.type = "password";
            trigger = "off";
        }
        
        
    }
 </script>
</body>
</html>