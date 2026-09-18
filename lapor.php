<?php
    require "inc/koneksi.php";
    require "inc/fungsi.php";
    if(isset($_POST['alasan'])){
        $id = $_POST['id_produk'];
        $alasan = $_POST['alasan'];
        $alasan_tambahan = $_POST['alasan_tambahan'];
        $id_penjual = ambil_1_data("produk","id_produk",$id,"id_login");
        $res = mysqli_query($con, "INSERT INTO lapor_produk VALUES ('','$id','$id_penjual','$alasan','$alasan_tambahan') ");

        if(mysqli_affected_rows($con) > 0){
            header("Location: lapor.php?id_produk=$id&laporanterkirim");
        }
    }

    if(isset($_GET['laporanterkirim'])){
        $id = $_GET['id_produk'];
        echo "<script>
                var a = confirm('Laporan telah dikirim untuk verifikasi. Terimakasih telah melapor');
                if(a == true){
                    window.history.go(-2);
                }else{
                    window.history.go(-2);
                }
            </script>";
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
        <span style="color:darkcyan; font-size: 24px;" onclick="window.history.back();"><b class="icon-arrow-left2 mr-2"></b><span class="text-dark">Laporkan Produk Ini</span></span>
    </div>

    <!-- content -->
    <div class="p-3 bg-white">
        <!-- judul -->
        <div class="mb-2" style="font-size:normal; font-weight:bold;">Mengapa produk ini kamu laporkan?</div>
        <!-- pilihan -->
        <form action="" method="post">
            <input type="hidden" name="id_produk" value="<?= $_GET['id_produk']; ?>">
            <div class="form-control border-0">
                <input type="radio" name="alasan" id="alasan1" class="mr-2" required value="Palsu dan melanggar hak cipta">
                <label for="alasan1">Palsu dan melanggar hak cipta</label>
            </div>
            <div class="form-control border-0">
                <input type="radio" name="alasan" id="alasan2" class="mr-2" required value="Produk tidak pantas">
                <label for="alasan2">Produk tidak pantas</label>
            </div>
            <div class="form-control border-0">
                <input type="radio" name="alasan" id="alasan3" class="mr-2" required value="Daftar penipuan">
                <label for="alasan3">Daftar penipuan</label>
            </div>
            <div class="form-control border-0">
                <input type="radio" name="alasan" id="alasan4" class="mr-2" required value="Barang curian">
                <label for="alasan4">Barang curian</label>
            </div>
            <div class="form-control border-0">
                <input type="radio" name="alasan" id="alasan5" class="mr-2" required value="Lainnya">
                <label for="alasan5">Lainnya</label>
            </div>
            <input type="text" name="alasan_tambahan" placeholder="Berikan alasan" class="form-control my-3">
            <button type="submit" class="btn btn-success">Kirim Laporan</button>
        </form>
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
                <b class="icon-user" style="font-size: 30px; color:darkcyan;"></b>
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