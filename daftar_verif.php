<?php
    require "inc/koneksi.php";
    // tampung data dari form daftar
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $password_ulang = htmlspecialchars($_POST['password_ulangi']);

    // ambil data buat ngecek username sudah ada atau belum
    $query_data = mysqli_query($con, "SELECT * FROM akun_login WHERE username = '$username' OR email = '$email' ");
    
    if ($password != $password_ulang)
    {
        echo "NOTIF:password beda!";
    }
    elseif(mysqli_num_rows($query_data) > 0)
    {
        echo "NOTIF: username atau email sudah digunakan!";
    }
    
    // lakukan proses data setelah tombol verifikasi ditekan
    if(isset($_POST['verifikasi']))
    {
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        $kode_verif = htmlspecialchars($_POST['kode_verif']);

        // ambil data kode verif 
        $query_kode = mysqli_query($con, "SELECT * FROM kode_verifikasi WHERE id_kode_verif = 1");
        $row = mysqli_fetch_assoc($query_kode);
        // cek apakah kode verif sudah benar
        if($kode_verif == $row['kode_verif'])
        {
            // buat enkripsi password
            $password = password_hash($password, PASSWORD_DEFAULT);
            $query = mysqli_query($con, "INSERT INTO akun_login VALUES('','$username','$password','penjual','$email') ");
            if (mysqli_affected_rows($con) == 1) {
                // ambil data yang baru ditambahkan tadi
                $query_data = mysqli_query($con, "SELECT * FROM akun_login WHERE username = '$username' ");
                $row = mysqli_fetch_assoc($query_data);
                // buat session
                session_start();
                $_SESSION['admin'] = $row['id_login'];
                $_SESSION['stt_akun'] = "penjual";

                // tambah ke profil
                $id_akun = $row['id_login'];
                mysqli_query($con, "INSERT INTO profil VALUES('','$id_akun','','','','','') ");
                
                header("Location:admin_penjual.php");
            }  
        }
        else
        {
            echo "Kode Verfikasi Salah!";
        }

    }
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
    <link rel="stylesheet" href="assets/icons/icomoon3/style.css">
    <link rel="stylesheet" href="assets/icons/icomoon4/style.css">
</head>
<body style="background: darkcyan;">
 
<div class="d-flex flex-column position-absolute w-100 py-5 align-items-center" style="top: 0;">
    <a href="index.php"><img src="assets/img/logo_white.svg" alt="" style="width: 200px; margin-bottom:20px;"></a>
    <span class="mb-3 text-white" style="font-size: 18px;">Belanja Nyaman dan Terpercaya</span>
    <div class="container form-login bg-white shadow p-5" >
        <div class="nav bg-white d-flex justify-content-between align-items-center py-3 ">
            <span style="color:darkcyan; font-size: 24px;" onclick="window.history.back();"><b class="icon-arrow-left2 mr-2"></b></span>
            <span class="text-dark text-center d-inline-block" style="width: 100%; font-weight:bold;">Masukkan Kode Verifikasi</span>
            <span class="text-secondary text-center d-inline-block w-100" style="font-size: small;">Dapatkan kode verifikasi dari Owner Smuhdastore</span>
        </div>
        <form action="" method="post">
            <input type="hidden" name="username" value="<?= $username; ?>">
            <input type="hidden" name="email" value="<?= $email; ?>">
            <input type="hidden" name="password" value="<?= $password; ?>">
            <input type="hidden" name="password_ulangi" value="<?= $password_ulang; ?>">
            <input type="text" name="kode_verif" id="" class="form-control mb-4 p-3 text-center" placeholder="Masukkan Kode" required>
            <button type="submit" name="verifikasi" class="btn w-100 text-white p-3 mb-4" style="background-color: darkcyan; font-weight: bold;">VERIFIKASI</button>
        </form>
        <span class="w-100 d-block text-center text-secondary" style="font-size: 14px;">Butuh bantuan? <a href="index.php#footer2">Hubungi Kami</a></span>
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