<?php
require "inc/koneksi.php";
require "inc/fungsi.php";
session_start();

// cek jika belum login/session belum dibuat
if (!isset($_SESSION['admin'])) 
{
    header("Location: login.php");
}
else
{
    
    $id_akun = $_SESSION['admin'];
    $stt_akun = $_SESSION['stt_akun'];
    if($stt_akun == "penjual")
    {
        header("Location: admin_penjual.php");
    }
    elseif($stt_akun == "pembeli")
    {
        header("Location: admin_pembeli.php");
    }
    
    
    // jika ada execute pada url ==> ok untuk clear laporan
    if(isset($_GET['execute'])){
        $id = $_GET['clear'];
        mysqli_query($con, "DELETE FROM lapor_produk WHERE id_penjual = '$id'");
        header("Location: admin_owner.php");

    }
}
?> 


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smuhdastore Belaja Nyaman dan Terpercaya</title>
    <link rel="shortcut icon" href="assets/img/fav.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css">
    
    <link rel="stylesheet" href="assets/icons/icomoon4/style.css">
    <link rel="stylesheet" href="assets/icons/icomoon1/icon.css">
    <link rel="stylesheet" href="assets/icons/icomoon2/icon2.css">
    <link rel="stylesheet" href="assets/icons/icomoon3/style.css">
    <link rel="stylesheet" href="assets/css/admin.css">

    <style>
        .nav-bottom{
            -moz-backdrop-filter: blur(10px); 
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
        .card:hover{
            border: 1px solid rgba(0, 0, 0, 0.2);
        }
        .nav.active{
            border-bottom: 3px solid #ff008c;
        }
        .nav:hover{
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="container d-flex flex-column px-0" style="margin-bottom: 150px;">
    <!-- navigasi -->
    <div class="nav bg-white d-flex justify-content-between align-items-center px-3 py-3 ">
        <span style="color:darkcyan; font-size: 24px;" onclick="window.history.back();"><b class="icon-arrow-left2 mr-2"></b><span class="text-dark">Kritik dan Saran</span></span>
    </div>
    <div class="container p-2 mb-2 d-flex flex-column justify-content-start align-items-start">
        <div class="text-secondary text-dark p-2 px-3 mb-2" style="font-size: small; background-color:lightcyan; border:1px solid rgba(0,0,0,0.1)">Daftar kritik dan saran terkait pengembangan aplikasi ini yang dikirim oleh pembeli atau penjual</div>

        <?php
            $res = mysqli_query($con, "SELECT * FROM kritik_saran ORDER BY id_krisar DESC");
            if(mysqli_num_rows($res) > 0):
                while($krisar = mysqli_fetch_assoc($res)):
                    $id_krisar = $krisar['id_krisar'];
        ?>
                <!-- KRISAR -->
                
                <div class="mb-2 bg-white w-100 p-2">
                    <b style="color:darkcyan;"><?= $krisar['nama']; ?></b>
                    <span class="text-secondary" style="font-size: small;"><?= $krisar['email']; ?></span>
                    <p style="font-style: italic;">"<?= $krisar['krisar']; ?>"</p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <span class="text-center w-100 p-2">Tidak ada data yang ditemukan</span>
        <?php
            endif;
        ?>
    </div>
    
</div>
 


<!-- Menu Fixed Bawah -->
<div id="navBottom">
        <div class="nav-bottom fixed-bottom py-2 px-3 d-flex flex-row justify-content-between align-items-center" style="display: flex; box-shadow: 0px -5px 10px rgba(0,0,0,0.05);">
            <a href="index.php" class="text-dark w-25" style="text-decoration: none;"><div class="d-flex flex-column justify-content-center align-items-center px-2 py-1 ">
                <b class="icon-home" style="font-size: 30px; color: darkcyan;"></b>
                <span style="font-size:small;">Beranda</span>
            </div></a>
            <a href="favorit.php" class="text-dark w-25 " style="text-decoration: none; "><div class="d-flex flex-column justify-content-center align-items-center px-2 py-1 ">
                <b class="icon-heart" style="font-size: 30px; color: darkcyan;"></b>
                <span style="font-size:small; ">Favorit</span>
            </div></a>
            <a href="keranjang.php" class="text-dark w-25 " style="text-decoration: none; "><div class="d-flex flex-column justify-content-center align-items-center px-2 py-1">
                <b class="icon-cart" style="font-size: 30px; color: darkcyan;"></b>
                <span style="font-size:small;">Belanjaan</span>
            </div></a>
            <a href="admin_penjual.php" class="text-dark w-25 rounded" style="text-decoration: none; background: white;"><div class="d-flex flex-column justify-content-center align-items-center px-2 py-1">
                <b class="icon-user" style="font-size: 30px; color: darkcyan;"></b>
                <span style="font-size:small;">Profil</span>
            </div></a>
        </div>
    </div>


<script src="assets/bootstrap/jquery/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.js"></script>
<script src="assets/bootstrap/popper/popper.js"></script>

<!-- mebuat menu nav bawah ressponsive saat width window tertentu -->

<script type="text/javascript">
  $(window).ready(function(){
  if ($(window).width() <= 800) {
   $('#navBottom').css('display','flex');
  }
  else {
    $('#navBottom').css('display','none');
  }
 });
</script>

<!-- // confirm clear laporan -->
<script>
    var url = window.location.search;
    if(url.indexOf("clear") > -1 && url.indexOf("execute") == -1){
        var clear = confirm("Apakah produk yang bermasalah sudah clear semua?");
        if(clear == true){
            window.location.href = window.location.href + "&execute";
        }else{
            window.history.go(-1);
        }
    }

</script>
</body>
</html>