<?php
    require 'inc/koneksi.php';
    require 'inc/fungsi.php';
    
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
    

    // favorit
    if(isset($_GET['fav'])){
        // cek apakah sudah login
        if(isset($id_akun)){
            $id_produk = $_GET['fav'];
            // cek apakah sudah dalam favorit
            // jika sudah
            if(favorit($id_produk,$id_akun,"cek")){
                $delete = favorit($id_produk,$id_akun,"delete");
                header("Location: favorit.php");
            }else{
                // jika belum ada dalam favorit
                $input = favorit($id_produk,$id_akun,"input");
                if($input){
                    header("Location: favorit.php");
                }else{
                    echo "Gagal input favorit";
                }
            }
        }else{
            header("Location: login.php;");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorit Saya - Smuhdastore Belaja Nyaman dan Terpercaya</title>
    <link rel="shortcut icon" href="assets/img/fav.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/style_mulaipromo.css">
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

<div class="container d-flex flex-column px-0">
    <!-- navigasi -->
    <div class="nav bg-white d-flex justify-content-between align-items-center px-3 py-3 border-bottom" >
        <a href="index.php" style="color:darkcyan; font-size: 24px; text-decoration: none;" >
            <b class="icon-arrow-left2 mr-2"></b>
            <span class="text-dark">Favorit Saya</span>
        </a>
    </div>

    <div class="container bg-white p-2 mb-5">
        <div class="d-flex justify-content-center align-items-center">
        <div class="d-flex row justify-content-center w-100">
        <?php
            $res = mysqli_query($con, "SELECT * FROM favorit INNER JOIN produk ON favorit.id_produk = produk.id_produk WHERE favorit.id_login = '$id_akun' ");
            if(mysqli_num_rows($res) > 0):
                while($fav = mysqli_fetch_assoc($res)):
                    $id_produk = $fav['id_produk'];
        ?>
        
        <div class="card d-inline-block border-0 position-relative mx-2 mb-2" style="width:160px; height:270px; border-radius: 3px;">
            <a href="detil_produk.php?id_produk=<?= $id_produk; ?>">
            <!-- gambar -->
            <div class="w-100 d-flex justify-content-center align-items-center mb-2" style="overflow: hidden; height:160px;">
                <img class="h-100" src="assets/foto/<?= ambil_1_data("gambar_produk","id_produk",$id_produk,"gambar"); ?>" alt="">
            </div>
            <!-- label diskon -->
            <?php if(cekDiskon($id_produk,"bool")): ?>
                <div class="d-flex flex-column p-2 text-danger" style="position: absolute; right:0; top:0; background:gold; border-bottom-left-radius:10px;">
                <b><?= cekDiskon($id_produk,"diskon"); ?>%</b>
                <b class="text-white">OFF</b>
                </div>
            <?php endif; ?>
            <!-- nama produk -->
            <span class="d-block mb-2" style="color:black; line-height: 14px; font-size:small; height:30px;"><?= $fav['nama_produk']; ?></span>
            <!-- harga -->
            <div class="mb-2" style="color: darkcyan;">
            <b>
                <?php if(cekDiskon($id_produk, "bool")){
                    echo rupiah(cekDiskon($id_produk,"harga_produk_diskon"));
                }else{
                    echo rupiah($fav['harga_produk']); 
                } 
                ?>
            </b>
            </div>
            </a>
            <div class="d-flex flex-row justify-content-between align-items-center">
                
                <!-- favorit -->
                    <?php if(favorit($id_produk,$id_akun,"cek")): ?>
                        <a href="favorit.php?fav=<?= $id_produk; ?>" style="text-decoration: none;">
                            <b class="icon-heart" style="color:crimson;"></b>
                        </a>
                    <?php else: ?>
                        <a href="favorit.php?fav=<?= $id_produk; ?>" style="text-decoration: none;">
                            <b class="icon-heart" style="color:silver;"></b>
                        </a>
                    <?php endif; ?>
                <!-- jml terjual -->
                <div class="text-secondary" style="font-size: small; "><?= cekTerjual($id_produk); ?> terjual</div>
            </div>
        </div>
        
            <?php endwhile; ?>
        <?php else: ?>
            <span class="py-2" style="font-size: small;">Belum ada produk favorit</span>
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
            <a href="favorit.php" class="text-dark w-25 rounded" style="text-decoration: none;  background: darkcyan;"><div class="d-flex flex-column justify-content-center align-items-center px-2 py-1 position-relative">
                <b class="icon-heart" style="font-size: 30px; color: white;"></b>
                <span style="font-size:small; color: white;">Favorit</span>
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
            <a href="keranjang.php" class="text-dark w-25" style="text-decoration: none;"><div class="d-flex flex-column justify-content-center align-items-center px-2 py-1 position-relative">
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
            <a href="admin_penjual.php" class="text-dark w-25" style="text-decoration: none;"><div class="d-flex flex-column justify-content-center align-items-center px-2 py-1">
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
</body>
</html>