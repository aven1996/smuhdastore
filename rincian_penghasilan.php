<?php
    require "inc/koneksi.php";
    require "inc/fungsi.php";

    session_start();
    if(isset($_SESSION['admin']))
    {   
        // jika yang membuka adalah owner maka id akun diganti menjadi id_penjual
        if(isset($_GET['id_penjual'])){
            $id_akun = $_GET['id_penjual'];
        }else{
            $id_akun = $_SESSION['admin'];
            $stt_akun = $_SESSION['stt_akun'];
        }
        
        $id_profil = ambil_1_data("profil","id_login",$id_akun,"id_profil");
    }
    else
    {
        header("Location: login.php");
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penghasilan - Smuhdastore Belaja Nyaman dan Terpercaya</title>
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
        
        .formX {
            width: 600px;
        }
    </style>
</head>
<body>

<div class="container d-flex flex-column px-0" style="margin-bottom: 150px;">
    <!-- navigasi -->
    <div class="nav bg-white d-flex justify-content-between align-items-center px-3 py-3 ">
        <span style="color:darkcyan; font-size: 24px;" onclick="window.history.back();"><b class="icon-arrow-left2 mr-2"></b><span class="text-dark">Rincian Penghasilan</span></span>
    </div>
    <div class="container bg-white p-2 mb-2">
        <div class="d-flex justify-content-start align-items-center mb-2">
            <span class="nav d-inline-block p-2 text-center active" style="width:180px; border-color:darkcyan;">
            <?php
                // ambil data dari table produk terjual
                $result_produk_transaksi = mysqli_query($con, "SELECT * FROM produk_transaksi INNER JOIN produk ON produk_transaksi.id_produk = produk.id_produk INNER JOIN akun_login ON produk_transaksi.id_penjual = akun_login.id_login  WHERE id_penjual = '$id_akun' AND stt_transaksi = 'selesai' ORDER BY id_transaksi DESC");
                if(mysqli_num_rows($result_produk_transaksi) > 0){
                    $jml = mysqli_num_rows($result_produk_transaksi);
                    echo "Produk Terjual ($jml)";
                }
                else
                {
                    echo "Produk Terjual (0)";
                }
            ?>
            </span>
            <!-- <span class="nav d-inline-block p-3 text-center" style="width:150px; border-color:darkcyan;">Supplier (0)</span> -->
        </div>
        <?php

            if(mysqli_num_rows($result_produk_transaksi)):
            while($i = mysqli_fetch_assoc($result_produk_transaksi)):
                // ambil data gambar produk berdasarkan id produk dari produk terjual
                $id_produk = $i['id_produk'];
                $result_gambar = mysqli_query($con, "SELECT gambar FROM gambar_produk WHERE id_produk = '$id_produk' ");
                $h = mysqli_fetch_assoc($result_gambar);
        ?>
        <div class="p-2 d-flex justify-content-start border-bottom">
            <div class="d-flex flex-row justify-content-center align-items-center mr-2" style="width: 70px; height:70px; overflow:hidden;">
                <img class="w-100" src="assets/foto/<?= $h['gambar']; ?>" alt="">
            </div>
            <div class="d-flex flex-column" style="line-height: 18px;">
                <span><?= $i['nama_produk']; ?></span>
                <span class="text-secondary" style="font-size: small;">Nama Pembeli: <?= $i['nama_pembeli']; ?> | <?= $i['tanggal']; ?></span>
                <span class="text-secondary" style="font-size: small;">Jumlah Pembelian: <?= $i['jml_pembelian'].'x'; ?></span>
                <span style="font-size: small;">Total Pembayaran: <b><?= "Rp".number_format($i['harga_peritem']*$i['jml_pembelian'],0,'.','.'); ?></b></span>
            </div>
        </div> 
        <?php endwhile; ?> 
        <?php endif; ?> 
    </div>
    
</div>
 <!-- sub total -->
 <div class="container fixed-bottom d-flex justify-content-between align-items-center px-2 py-3" style="bottom: 70px; background: rgba(221,255,215,0.90); ">
        <div class="d-flex flex-row justify-content-end align-items-center">
                <div class="d-flex flex-column  mr-2">
                    <span>Total Penghasilan: <b style="color:darkcyan;">
                    <?php
                        // query total penghasilan penjual
                        $result_total = mysqli_query($con, "SELECT id_transaksi, id_penjual, SUM(harga_peritem * jml_pembelian) AS penghasilan FROM produk_transaksi INNER JOIN produk ON produk_transaksi.id_produk = produk.id_produk WHERE id_penjual = '$id_akun' AND stt_transaksi = 'selesai' GROUP BY id_penjual");
                        
                        if(mysqli_num_rows($result_total) > 0)
                        {
                            $row_penghasilan = mysqli_fetch_assoc($result_total);
                            echo "Rp".number_format($row_penghasilan['penghasilan'],0,'.','.');
                        }
                        else
                        {
                            echo "Rp0";
                        }
                    ?>
                    
                    </b></span>
                    <span class="text-secondary" style="font-size: 12px;">Perhitungan penghasilan berdasarkan penjualan dalam aplikasi (Tidak termasuk biaya di luar aplikasi)</span>
                </div>
        </div>
    </div>

<!-- Menu Fixed Bawah -->
<div id="navBottom">
        <div class="nav-bottom fixed-bottom py-2 px-3 d-flex flex-row justify-content-between align-items-center" style="display: flex; box-shadow: 0px -5px 10px rgba(0,0,0,0.05);">
            <a href="index.php" class="text-dark w-25" style="text-decoration: none;"><div class="d-flex flex-column justify-content-center align-items-center px-2 py-1 ">
                <b class="icon-home" style="font-size: 30px; color: darkcyan;"></b>
                <span style="font-size:small;">Beranda</span>
            </div></a>
            <a href="favorit.php" class="text-dark w-25 " style="text-decoration: none; "><div class="d-flex flex-column justify-content-center align-items-center px-2 py-1 position-relative">
                <b class="icon-heart" style="font-size: 30px; color: darkcyan;"></b>
                <span style="font-size:small; ">Favorit</span>
                <?php 
                    if(isset($id_akun)){ 
                        if(favorit("",$id_akun,"jumlah") > 0){ ?>
                        <span class="d-flex justify-content-center align-items-center p-0 bg-danger text-white text-center rounded-circle" style="font-size:small; width:20px; height:20px; position:absolute; top:0;right:10px;">
                            <?= favorit("",$id_akun,"jumlah");?>
                        </span>
                <?php
                        }
                    } 
                ?>
            </div></a>
            <a href="keranjang.php" class="text-dark w-25 " style="text-decoration: none; "><div class="d-flex flex-column justify-content-center align-items-center px-2 py-1 position-relative">
                <b class="icon-cart" style="font-size: 30px; color: darkcyan;"></b>
                <span style="font-size:small;">Belanjaan</span>
                <?php 
                    if(isset($id_akun)){ 
                        if(jmlDiKeranjang($id_akun) != 0){ ?>
                        <span class="d-flex justify-content-center align-items-center p-0 bg-danger text-white text-center rounded-circle" style="font-size:small; width:20px; height:20px; position:absolute; top:0;right:10px;">
                            <?= jmlDiKeranjang($id_akun);?>
                        </span>
                <?php
                        }
                    } 
                ?>
            </div></a>
            <a href="admin_penjual.php" class="text-dark w-25 rounded" style="text-decoration: none; background: darkcyan;"><div class="d-flex flex-column justify-content-center align-items-center px-2 py-1">
                <b class="icon-user" style="font-size: 30px; color: white;"></b>
                <span style="font-size:small; color: white;">Profil</span>
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


<!-- membuat form menjadi kecil saat ukuran width window melebihi nilai tertentu -->
<script type="text/javascript">
  $(window).ready(function(){
  if ($(window).width() >= 700) {
   $('.container').addClass('formX');
  }
  else {
   $('.container').removeClass('formX');
  }
 });
</script>


</body>
</html>