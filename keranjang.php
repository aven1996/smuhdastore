<?php
    require "inc/koneksi.php";
    require "inc/fungsi.php";
    
    session_start();
    if(isset($_SESSION['admin']))
    {
        $id_akun = $_SESSION['admin'];
        $stt_akun = $_SESSION['stt_akun'];

        // tombol mines produk
        if(isset($_POST['min_jml'])){
            if($_POST['jml_beli'] > 1){
                $id_produk = $_POST['id_produk'];
                $jmlbeli = $_POST['jml_beli'] - 1;
                $hargatotal = $_POST['harga'] * $jmlbeli;
                mysqli_query($con, "UPDATE keranjang SET jumlah_pembelian = '$jmlbeli' , harga_akhir = '$hargatotal' WHERE id_pembeli = '$id_akun' AND id_produk = '$id_produk' ");

            }
        } 
        // tombol ples produk
        if(isset($_POST['plus_jml'])){
            $id_produk = $_POST['id_produk'];
            $jmlbeli = $_POST['jml_beli'] + 1;
            $hargatotal = $_POST['harga'] * $jmlbeli;
            mysqli_query($con, "UPDATE keranjang SET jumlah_pembelian = '$jmlbeli' , harga_akhir = '$hargatotal' WHERE id_pembeli = '$id_akun' AND id_produk = '$id_produk' ");
            
        }
        // tombol delete produk
        if(isset($_POST['delete'])){
            $id_produk = $_POST['id_produk'];
            $harga = $_POST['harga'];
            mysqli_query($con, "DELETE FROM keranjang WHERE id_pembeli = '$id_akun' AND id_produk = '$id_produk' AND harga_keranjang = '$harga'");
        }


    }else{
        header('Location: login.php');
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
    
    <link rel="stylesheet" href="assets/icons/icomoon4/style.css">
    <link rel="stylesheet" href="assets/icons/icomoon5/style.css">
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
    <div class="nav bg-white d-flex justify-content-between align-items-center px-3 py-3 border-bottom">
            <a href="index.php" style="color:darkcyan; font-size: 24px; text-decoration:none;"><b class="icon-arrow-left2 mr-2"></b><span class="text-dark">Keranjang</span></a>
    </div>
 
    <!-- produk yang di keranjang -->
    <div class="container bg-white p-2 mb-2">
        <?php
            $result = mysqli_query($con, "SELECT * FROM keranjang WHERE id_pembeli = $id_akun");
            if(mysqli_num_rows($result) > 0):
                while ($produk = mysqli_fetch_assoc($result)):
                    $id_produk = $produk['id_produk'];
        ?>
        <div class="d-flex flex-row justify-content-start align-items-center w-100 p-0 mb-1">

            <div id="imgProd" onclick="pilihProd();" class="mr-3 d-flex justify-content-center align-items-center border" style="width: 150px; height: 150px; overflow: hidden;">
                <img class="w-100" src="assets/foto/<?= ambil_1_data("gambar_produk","id_produk","$id_produk","gambar") ?>" alt="">
            </div>
            <div> 
                <?php
                    $id_penjual = ambil_1_data("produk","id_produk",$id_produk,"id_login");
                    $nama_penjual = ambil_1_data("akun_login","id_login",$id_penjual,"username");
                ?>
                <div class="text-secondary" style="font-size: small;"><?= "@".$nama_penjual; ?></div>
                <div style="font-size: small;"><?= ambil_1_data("produk","id_produk","$id_produk","nama_produk"); ?></div>
                <div class="mb-2" style="font-size: large; color:darkcyan; font-weight:bold;"><?= rupiah($produk['harga_akhir']); ?></div>
                <span class="text-secondary" style="font-size: small;">Jumlah :</span>
                <div class="d-flex justify-content-start align-items-center">
                    <form action="" method="post" class="d-flex justify-content-start align-items-center">
                        <input type="hidden" name="id_produk" value="<?= $id_produk; ?>">
                        <input type="hidden" name="jml_beli" value="<?= $produk['jumlah_pembelian']; ?>">
                        <input type="hidden" name="harga" value="<?= $produk['harga_keranjang']; ?>">
                        <!-- kurangi pembelian -->
                        <button type="submit" name="min_jml" class="icon-minus border p-3 m-0" style="background:whitesmoke; color: rgba(0, 0, 0, 0.3);"></button>
                        <!-- jml pembelian -->
                        <span class="px-3" style="font-weight: bold; font-size:large; color:darkcyan;"><?= $produk['jumlah_pembelian']; ?></span>
                        <!-- tambah pembelian -->
                        <button type="submit" name="plus_jml" class="icon-plus border p-3 m-0" style="background:whitesmoke; color: rgba(0, 0, 0, 0.3);"></button>
                        <!-- hapus -->
                        <button type="submit" name="delete" class="icon-bin p-3 text-white bg-danger ml-0" style="border:1px solid crimson;"></button>
                    </form>
                </div>
            </div> 
        </div>
        <?php endwhile; ?>
        <?php else: ?>
            <span class="text-secondary text-center w-100 d-inline-block py-2" style="font-size: small;">Tidak ada produk di keranjang</span>

        <?php endif; ?>
    </div>

    <!-- sub total -->
    <div class="container fixed-bottom w-100 d-flex justify-content-end align-items-center px-2 py-3" style="bottom: 70px; background: lightcyan;">
        <div class="d-flex flex-row justify-content-end align-items-center">
            <?php $result = mysqli_query($con, "SELECT SUM(harga_akhir) AS subtotal FROM keranjang WHERE id_pembeli = $id_akun"); ?>
                <div class="d-flex flex-column  mr-2">
                    <span>Total: <b style="color:darkcyan;">
                    <?php
                        if(mysqli_num_rows($result) > 0){
                            $subtotal = mysqli_fetch_assoc($result);
                            echo rupiah($subtotal['subtotal']);
                        }else{
                            echo "Rp0";
                        }
                    ?>
                    </b></span>
                    <span class="text-secondary" style="font-size: small;">Belum termasuk ongkir</span>
                </div>
                <a href="checkout.php?cart=<?=$id_akun;?>"><button type="button" class="btn text-white" style="background: darkcyan;"><b>CHECKOUT</b></button></a>
        </div>
    </div>
    
    <!-- beli yang lain juga -->
    <!-- <div class="container bg-white p-3 border-bottom">
        <span class="text-dark " style="font-weight: bold;">Beli yang lain juga</span>
    </div>
    <div class="d-flex flex-row justify-content-center">
    <div class="conatiner d-flex row justify-content-around bg-white p-2 w-100" style="margin-bottom:130px;">
        <?php
            for($i=0; $i < 4; $i++) :
        ?>
        <div class="card d-inline-block border-0" style="width:160px; height:310px; border-radius: 3px;">
            <div  class=" w-100 d-flex justify-content-center align-items-center mb-2" style="overflow: hidden; height:200px;">
                <img class="w-100" src="assets/img/8.png" alt="">
            </div>
            <span class="mx-2 d-block mb-2" style="line-height: 14px; font-size:small;">HP Samsung A20S 4/32GB Original Sein</span>
            <div class="mx-2 mb-2" style="color: darkcyan;">Rp1.359.900</div>
            <div class="d-flex justify-content-between align-items-center mx-2">
                <b class="icon-heart" style="color: silver;"></b>
                <div class="text-secondary" style="font-size: small; ">234 terjual</div>
            </div>
        </div>
        <?php endfor; ?>
    </div>
    </div> -->
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
            <a href="keranjang.php" class="text-dark w-25 rounded" style="text-decoration: none; background: darkcyan;"><div class="d-flex flex-column justify-content-center align-items-center px-2 py-1 position-relative">
                <b class="icon-cart" style="font-size: 30px; color: white;"></b>
                <span style="font-size:small; color: white;">Belanjaan</span>
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

<script>
    function pilihProd(){
        
    }
</script>

</body>
</html>