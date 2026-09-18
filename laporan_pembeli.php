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
        <span style="color:darkcyan; font-size: 24px;" onclick="window.history.back();"><b class="icon-arrow-left2 mr-2"></b><span class="text-dark">Laporan Pembeli</span></span>
    </div>
    <div class="container p-2 mb-2 d-flex flex-column justify-content-start align-items-start">
        <div class="text-secondary bg-warning text-dark p-2 px-3 mb-2" style="font-size: small;">Daftar produk yang dilaporkan oleh pembeli. Mohon untuk diverifikasi kembali</div>

        <?php
            $res = mysqli_query($con, "SELECT * FROM lapor_produk INNER JOIN produk ON lapor_produk.id_produk = produk.id_produk GROUP BY id_login");
            if(mysqli_num_rows($res) > 0):
                while($penjual = mysqli_fetch_assoc($res)):
                    $id_penjual = $penjual['id_login'];
        ?>
                <!-- nama penjual -->
                <div class="d-flex bg-white justify-content-between align-items-center p-2 border-bottom w-100">
                    <span style="color:darkcyan;">
                        <b class="icon-user mr-2"></b>
                        <?php
                            if(!empty(ambil_1_data("profil","id_login","$id_penjual","nama_profil"))){
                                echo ambil_1_data("profil","id_login","$id_penjual","nama_profil");
                            }else{
                                echo "@".ambil_1_data("akun_login","id_login","$id_penjual","username");
                            }
                        ?>
                    </span>
                    <a href="?clear=<?= $id_penjual; ?>">
                    <span class="d-flex justify-content-start align-items-center bg-danger text-white p-1 rounded" style="font-size: small;">
                        <b class="icon-warning mr-2"></b>
                        <?php
                            $produk = mysqli_query($con, "SELECT * FROM lapor_produk INNER JOIN produk ON lapor_produk.id_produk = produk.id_produk WHERE id_penjual = '$id_penjual'");
                            echo mysqli_num_rows($produk)." Laporan";
                        ?>
                    </span>
                    </a>
                </div>
                <!-- produk yang terlapor -->
                <div class="mb-2 bg-white w-100">
                    <?php
                        while($prod = mysqli_fetch_assoc($produk)): 
                            $id_prod = $prod['id_produk'];
                    ?>
                            <a href="detil_produk.php?id_produk=<?= $id_prod; ?>" class="d-flex p-2">

                            <!-- poto -->
                            <div class="d-flex justify-content-center align-items-center mr-2" style="width: 70px; height:70px; overflow:hidden;">
                                <img class="h-100" src="assets/foto/<?= ambil_1_data("gambar_produk","id_produk","$id_prod","gambar"); ?>" alt="">
                            </div>

                            <!-- judul dan alasan -->
                            <div class="d-flex flex-column">
                                <span class="text-dark" style="font-weight: bold;"><?= $prod['nama_produk']; ?></span>
                                <span class="text-danger" style="font-size: small;"><?= $prod['alasan']; ?></span>
                                <span class="text-secondary" style="font-size: 12px;"><?= $prod['alasan_tambahan']; ?></span>
                            </div>
                            </a>
                    <?php
                        endwhile;
                    ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <span class="text-center w-100 p-2">Tidak ada laporan yang ditemukan</span>
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