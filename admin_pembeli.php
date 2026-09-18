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
    elseif($stt_akun == "owner")
    {
        header("Location: admin_owner.php");
    }
    
}


?>

<!DOCTYPE html>
<html lang="en"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Smuhdastore Belaja Nyaman dan Terpercaya</title>
    <link rel="shortcut icon" href="assets/img/fav.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="assets/icons/icomoon1/icon.css">
    <link rel="stylesheet" href="assets/icons/icomoon2/icon2.css">
    <link rel="stylesheet" href="assets/icons/icomoon3/style.css">
    <link rel="stylesheet" href="assets/icons/icomoon4/style.css">
    <link rel="stylesheet" href="assets/css/admin.css">

    <style>
        .nav-bottom{
            -moz-backdrop-filter: blur(10px); 
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }

        .produkrekomendasi .more{
            font-size: 14px;
            color: #929292;
        }

        .produkrekomendasi .cart-produk-jualcepat{
            width: 175px;
            height: 300px;
            margin-right: 5px;
            margin-bottom: 5px;
            background-color: white;
            overflow: hidden;
            position: relative;
            border-radius: 5px;
            border: 1px solid transparent;
            border-bottom: 2px solid transparent;
        }

        .produkrekomendasi .cart-produk-jualcepat:hover{
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-bottom: 2px solid rgba(0, 0, 0, 0.2);
        }

        .produkrekomendasi .cart-produk-jualcepat b{
            margin: 0 5px;
            color: #ff008c;
            font-size: 15px;
        }

        .produkrekomendasi .cart-produk-jualcepat .diskon{
            position: absolute;
            right: 0;
            top: 0;
            background-color: gold;
            border-bottom-left-radius: 5px;
        }
        .produkrekomendasi .cart-produk-jualcepat .diskon b{
            font-weight: bold;
        }

        .produkrekomendasi .cart-produk-jualcepat b.harganormal{
            font-size: small;
            color: #929292;
            text-decoration: line-through;
        }

        .produkrekomendasi .cart-produk-jualcepat .cov-img-cart{
            width: 100%;
            height: 180px;
            overflow: hidden;
            margin-bottom: 3px;
        }

        .produkrekomendasi .cart-produk-jualcepat img{
            width: 100%;
        }

        .produkrekomendasi .cart-produk-jualcepat span.nama_produk{
            display: inline-block;
            font-size: small;
            margin: 0 5px;
            height: 35px;
            line-height: 14px;
        }

        .produkrekomendasi .cart-produk-jualcepat .harga{
            text-align: left;
        }

        .produkrekomendasi .cart-produk-jualcepat .lok_penjual{
            font-size: 12px;
            text-align: right;
            position: absolute;
            right: 10px;
            bottom: 15px;
            height: 10px;
            color: #929292;
        }

    </style>
</head>
<body> 

<div class="container d-flex flex-column px-0">
    <?php
        // cek apakah ada yang kosong di bio profil
        $ambil_data_profil = mysqli_query($con, "SELECT * FROM profil WHERE id_login = '$id_akun' ");
        $row_data_profil = mysqli_fetch_assoc($ambil_data_profil);
        if (empty($row_data_profil['nama_profil']) OR empty($row_data_profil['jenis_kelamin']) OR empty($row_data_profil['hp']) OR empty($row_data_profil['alamat']) OR empty($row_data_profil['foto_profil'])) :
    ?> 

    <!-- warning lengkapi profilmu -->
    <div class="d-flex justify-content-center align-itmes-center bg-warning p-2">
        <span>Lengkapi biodata profilmu! <a href="profil.php" class="btn btn-primary py-0 px-2">Setting</a></span>
    </div>

    <?php endif; ?>

    <!-- nav -->
    <div class="nav bg-white d-flex justify-content-between align-items-center px-3 py-3">
        <a href="index.php" style="color:darkcyan; font-size: 24px; text-decoration: none;"><b class="icon-arrow-left2"></b></a>
        <div class="d-flex">
            <a href="profil.php" style="text-decoration: none; padding-left:10px;"><b class="icon-cog mr-2" style="color:darkcyan; font-size: 24px;"></b></a>
        </div>
    </div>

    <!-- nama admin -->
    <div class="head bg-white d-flex align-items-center px-3 mb-2 pb-3">
        <!-- poto -->
        <div class="mr-2 d-flex flex-row justify-content-center align-items-center rounded-circle mb-2" style="width: 60px; height: 60px; overflow:hidden;">
            <?php 
                $poto = ambil_1_data("profil", "id_login","$id_akun","foto_profil");
                if (!empty($poto)) :
            ?>
                <img class="w-100" src="assets/foto/<?= $poto; ?>" alt="<?= $poto; ?>" title="<?= $poto; ?>"> 
            <?php
                    
                else:
            ?>
                <img class="w-100" src="assets/img/poto-profil.png" alt="poto-profil.png">

            <?php endif; ?>

        </div>
        
        <div class="d-flex flex-column">
            <span style="font-weight: bold;">
                <?php
                    // ambil data dari profil dulu klo belum ada masukan username dari akun login
                    
                    $id_login = $_SESSION['admin'];

                    // ambil data profil
                    $query_profil = mysqli_query($con, "SELECT * FROM profil WHERE id_login = '$id_login' ");
                    $row_profil = mysqli_fetch_assoc($query_profil);
                    if(!empty($row_profil['nama_profil']))
                    {
                        echo $row_profil['nama_profil'];
                    }
                    else
                    {
                        // ambil data akun login
                        $query_login = mysqli_query($con, "SELECT * FROM akun_login WHERE id_login = '$id_login' ");
                        $row_login = mysqli_fetch_assoc($query_login);

                        echo $row_login['username'];
                    }
                    
                ?>
            </span>
            <div class="rating d-flex flex-row align-items-center">
                 <span class="text-secondary" style="font-size: small;"><?= '@'.ambil_1_data("akun_login","id_login",$id_akun,"username"); ?></span>
            </div>
        </div>
    </div>
    <!-- penjualan saya -->
    <div class="aktifitas mb-2">
        <div class="title d-flex align-items-center bg-white py-2 px-3 border-bottom">
            <b class="icon-medal mr-3" style="color:darkcyan;"></b>
            <span>Pesanan Saya</span>
        </div>
        <div class="isi-aktifitas d-flex justify-content-around bg-white" style="overflow: auto;">
            <a href="penjual_pesananbaru.php" style="text-decoration: none; color:black;"><span class="d-flex flex-column justify-content-center position-relative">
                <img src="assets/icons/flaticon/035-shopping cart.svg" alt="" style="opacity: 0.3; width:50px;">
                <span class="d-block" style="font-size: small;">Pesanan</span>
                <?php
                    if(cekUpdateTransaksi($id_akun,"id_login","pesan","update") > 0):
                ?>
                <b class="d-flex justify-content-center align-items-center bg-danger text-white position-absolute rounded-circle" style="width: 20px; height:20px; top: 10px; right:0;"><?= cekUpdateTransaksi($id_akun,"id_login","pesan","update"); ?></b>
                <?php endif; ?>
            </span></a>
            
            <a href="penjual_proses.php" style="text-decoration: none; color:black;"><span class="d-flex flex-column justify-content-center position-relative">
                <img src="assets/icons/flaticon/013-delivery truck.svg" alt="" style="opacity: 0.3; width:50px;">
                <span class="d-block" style="font-size: small;">Proses</span>
                <?php
                    if(cekUpdateTransaksi($id_akun,"id_login","proses","update") > 0):
                ?>
                <b class="d-flex justify-content-center align-items-center bg-danger text-white position-absolute rounded-circle" style="width: 20px; height:20px; top: 10px; right:0;"><?= cekUpdateTransaksi($id_akun,"id_login","proses","update"); ?></b>
                <?php endif; ?>
            </span></a>

            <a href="penjual_selesai.php" style="text-decoration: none; color:black;"><span class="d-flex flex-column justify-content-center position-relative">
                <img src="assets/icons/flaticon/009-gift.svg" alt="" style="opacity: 0.3; width:50px;">
                <span class="d-block" style="font-size: small;">Selesai</span>
                <?php
                    if(cekUpdateTransaksi($id_akun,"id_login","selesai","update") > 0):
                ?>
                <b class="d-flex justify-content-center align-items-center bg-danger text-white position-absolute rounded-circle" style="width: 20px; height:20px; top: 10px; right:0;"><?= cekUpdateTransaksi($id_akun,"id_login","selesai","update"); ?></b>
                <?php endif; ?>
            </span></a>

             <a href="penjual_dibatalkan.php" style="text-decoration: none; color:black;"><span class="d-flex flex-column justify-content-center position-relative">
                <img src="assets/icons/flaticon/040-shopping cart.svg" alt="" style="opacity: 0.3; width:50px;">
                <span class="d-block" style="font-size: small;">Dibatalkan</span>
                <?php
                    if(cekUpdateTransaksi($id_akun,"id_login","batal","update") > 0):
                ?>
                <b class="d-flex justify-content-center align-items-center bg-danger text-white position-absolute rounded-circle" style="width: 20px; height:20px; top: 10px; right:0;"><?= cekUpdateTransaksi($id_akun,"id_login","batal","update"); ?></b>
                <?php endif; ?>
            </span></a>
        </div>
    </div>

    <!-- produk supplyer -->
    <!-- <div class="aktifitas mb-2">
        <div class="cov-title d-flex justify-content-between lign-items-center bg-white py-2 px-3 border-bottom">
            <div class="title d-flex align-items-center">
                <b class="icon-shipping mr-3"></b>
                <span>Update Produk Supplyer</span>
            </div>
            <a href="#" style="color:#ff008c;">Lihat lainnya</a>
        </div>
        <div class="overflow-auto" style="overflow: auto;">
            <div class="d-flex flex-row justify-content-center" style="width: 1550px;">
                <div class="isi-aktifitas row bg-white w-100">
                    
                    <div class="d-flex flex-column justify-content-center m-2 position-relative" style="width: 150px;">
                        <div class="position-absolute p-1 text-white" style="top: 0; left:0; background:#ff008c; border-bottom-right-radius: 10px;"><b class="icon-gift"></b></div>
                        <div class="d-flex flex-column justify-content-center overflow-hidden w-100" style="height: 150px;">
                            <img src="assets/img/13.png" alt="" class="w-100">
                        </div>
                        <span class="d-block text-left px-2 w-100" style="font-size: small; line-height: 14px;">Samsung A20 4/32GB PROMO</span>
                        <span class="d-block text-left px-2 py-0 w-100" style="font-size: 16px; line-height:14px; color: #ff008c;">Rp20.000</span>
                        <span class="d-block text-left px-2 pt-2 w-100 text-secondary" style="font-size: small; line-height: 14px;">
                            <span>Yudi Leo</span>
                        </span>
                        <div class="d-flex justify-content-between align-items-center mx-2">
                            <b class="icon-heart" style="color: silver;"></b>
                            <div class="text-secondary" style="font-size: small; ">Kab. Klaten</div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div> -->

    

    <!-- Favorit saya -->
    <div class="aktifitas mb-2">
        <div class="cov-title d-flex justify-content-between lign-items-center bg-white py-2 px-3 border-bottom">
            <div class="title d-flex align-items-center">
                <b class="icon-heart mr-3" style="color: darkcyan;"></b>
                <span>Favorit Saya</span>
            </div>
            <a href="favorit.php" style="color: darkcyan;"><div>Lihat</div></a>
        </div>
    </div>
    
    <!-- Terakhir dilihat -->
    <div class="aktifitas mb-2">
        <div class="cov-title d-flex justify-content-between lign-items-center bg-white py-2 px-3 border-bottom">
            <div class="title d-flex align-items-center">
                <b class="icon-eye mr-3" style="color: darkcyan;"></b>
                <span>Terakhir Dilihat</span>
            </div>
            <a href="lastseen.php" style="color: darkcyan;"><div>Lihat</div></a>
        </div>
    </div>

    
    <!-- riwayat pembelian -->
    <div class="aktifitas">
        <div class="cov-title d-flex justify-content-between lign-items-center bg-white py-2 px-3">
            <div class="title d-flex align-items-center">
                <b class="icon-history mr-3" style="color: darkcyan;"></b>
                <span>Riwayat Pembelian</span>
            </div>
        </div>
    </div>
    <div class="p-2 px-3 bg-warning text-center" style="font-size: small;">Harga dapat berubah sewaktu-waktu dan mungkin harga saat kamu beli berbeda dengan harga sekarang</div>
    <div class="produkrekomendasi container bg-white pt-2" style="padding-bottom: 70px;">
        
        <div class="d-flex flex-row justify-content-center">
            <div class=" w-100 produk-jualcepat row mt-2 pl-0 justify-content-around">

            <?php
                $result = mysqli_query($con, "SELECT * FROM produk_transaksi WHERE id_pembeli = '$id_akun' AND stt_transaksi = 'selesai'");
                if(mysqli_num_rows($result) > 0):
                while($produk = mysqli_fetch_assoc($result)):
                    $id_produk = $produk['id_produk'];
            ?> 
            <!-- card -->
            <div class="cart-produk-jualcepat d-block pb-2" style="width: 160px;">
                <a href="detil_produk.php?id_produk=<?= $id_produk; ?>" class="text-dark" style="text-decoration: none;">
                
                <!-- image -->
                <div class="d-flex flex-row justify-content-center align-items-center mb-2" style="width: 160px; height:160px; overflow:hidden;">
                    <img src="assets/foto/<?= ambil_1_data("gambar_produk","id_produk",$id_produk,"gambar"); ?>" alt="" class="h-100" style="width: auto;">
                </div>
                <!-- nama produk -->
                <span class="nama_produk"><?= ambil_1_data("produk","id_produk",$id_produk,"nama_produk"); ?></span>
                <!-- harga -->
                <b class="harga" style="color: darkcyan;"><?= rupiah($produk['harga_peritem']); ?></b>
                </a>
                <!-- jml pembelian -->
                <div class="rating ml-2">
                    <span class="text-secondary" style="font-size: small;"><?= "Jumlah Beli: ".$produk['jml_pembelian']; ?></span>
                </div>
                
            </div>
            <?php endwhile; ?>
            
            <?php else: ?>
                <span class="text-secondary" style="font-size: small;">Belum ada pembelian</span>
            <?php endif; ?>

            </div>
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
</body>
</html>