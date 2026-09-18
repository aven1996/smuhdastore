<?php
    require "inc/koneksi.php"; 
    
?>

<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Penjual dan Supplier - Smuhdastore Belaja Nyaman dan Terpercaya</title>
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
        <h3 class="text-secondary mb-4">Daftar Sebagai Penjual</h3>
        <form action="daftar_verif.php" method="post">
            <input type="text" name="username" id="" class="form-control mb-4 p-3" placeholder="Username" required>
            <input type="text" name="email" id="" class="form-control mb-4 p-3" placeholder="Email" required>
            <input type="password" name="password" id="" class="form-control mb-4 p-3" placeholder="Password" required>
            <input type="password" name="password_ulangi" id="" class="form-control mb-4 p-3" placeholder="Ulangi Password" required>
            <button type="submit" name="submit" class="btn w-100 text-white p-3" style="background-color:darkcyan; font-weight: bold;">SUBMIT</button>
        </form>
        
        <span class="w-100 d-block text-center text-secondary mt-3" style="font-size: 14px;">Dengan mendaftar, Anda setuju dengan Syarat, Kententuan dan Kebijakan dari Smuhdastore & Kebijakan Privasi</span>
        <span class="text-secondary" style="width: 100%; display: inline-block; text-align: center; margin-top:20px; height:fit-content;">Punya akun penjual? <a href="login.php" style="color: darkcyan; font-weight: bold;">Log In
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