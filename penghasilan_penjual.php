<?php
    require "inc/koneksi.php";
    require "inc/fungsi.php";
    
    session_start();
    if(isset($_SESSION['admin']))
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
    }
    else
    {
        header("Location: index.php");
    }

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner - Smuhdastore Belaja Nyaman dan Terpercaya</title>
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
        <a href="admin_owner.php" style="color:darkcyan; font-size: 24px; text-decoration: none;" >
            <b class="icon-arrow-left2 mr-2"></b>
            <span class="text-dark">Penghasilan Penjual</span>
        </a>
    </div>
    <?php
    $key = "";
    if(isset($_GET['key']) AND !empty($_GET['key'])){
        $key = $_GET['key'];
        $key = "AND akun_login.username LIKE '%$key%' OR profil.nama_profil LIKE '%$key%'";
    }
    $res = mysqli_query($con, "SELECT * FROM akun_login INNER JOIN profil ON akun_login.id_login = profil.id_login WHERE akun_login.stt_akun = 'penjual' $key");
    ?>
    <div class="container bg-white p-2 mb-2">
        <div class="d-flex justify-content-start align-items-center mb-2 position-relative">
            <span class="nav d-inline-block p-2 text-center active" style="width:180px; border:none;">Penjual (<?= mysqli_num_rows($res); ?>)</span>
            <form action="" method="get" style="min-width: 70%;">
                <input type="text" name="key" class="form-control" placeholder="cari penjual" value="<?php if(isset($_GET['key']) AND !empty($_GET['key'])){ echo $_GET['key']; } ?>">
            </form>
            <b class="icon-search position-absolute text-secondary" style="right: 10px;"></b>
            <!-- <span class="nav d-inline-block p-3 text-center" style="width:150px; border-color:darkcyan;">Supplier (0)</span> -->
        </div>
        <?php
            if(mysqli_num_rows($res) > 0):
                while($data = mysqli_fetch_assoc($res)):
        ?>
        <div class="p-2 d-flex justify-content-start border-bottom">
        
            <div class="d-flex flex-row justify-content-center align-items-center mr-2 rounded-circle" style="width: 70px; height:70px; overflow:hidden;">
                <?php if(!empty($data['foto_profil'])): ?>
                    <img class="w-100" src="assets/foto/<?= $data['foto_profil']; ?>" alt="">
                <?php else: ?>
                    <img class="w-100" src="assets/img/poto-profil.png" alt="">
                <?php endif; ?>
            </div>
            <a href="rincian_penghasilan.php?id_penjual=<?= $data['id_login']; ?>" class="w-75 text-dark">
            <div class="d-flex flex-column " style="line-height: 18px;">
                <span>
                <b>
                    <?php 
                        if(!empty($data['nama_profil'])){
                            echo $data['nama_profil'];
                        }else{
                            echo "@".$data['username'];
                        }
                    ?>
                </b>
                </span>
                <span class="text-secondary" style="font-size: small;">Terjual   
                <?php
                    $id_penjual = $data['id_login'];
                    $jml_terjual = mysqli_query($con, "SELECT SUM(jml_pembelian) AS jml_terjual FROM produk_transaksi WHERE id_penjual = '$id_penjual' AND stt_transaksi = 'selesai' GROUP BY id_penjual");
                    if(mysqli_num_rows($jml_terjual) > 0){
                        $jml = mysqli_fetch_assoc($jml_terjual);
                        echo $jml['jml_terjual'];
                    }else{
                        echo "0";
                    }
                ?>
                Produk</span>
                <span style="font-size: small;">Total Penghasilan: 
                <b style="color: darkcyan;">
                <?php 
                    $id_penjual = $data['id_login'];
                    $total = mysqli_query($con, "SELECT SUM(harga_peritem * jml_pembelian) AS total FROM produk_transaksi  WHERE id_penjual = '$id_penjual' AND stt_transaksi = 'selesai'  GROUP BY id_penjual");
                    if(mysqli_num_rows($total) > 0){
                        $total = mysqli_fetch_assoc($total);
                        echo rupiah($total['total']);
                    }else{
                        echo "Rp0";
                    }
                    
                ?>
                </b>
                </span>
            </div>
            </a>
        </div>
            <?php endwhile; ?>
        <?php else: ?>
            <span class="text-center">Data tidak ditemukan</span>
        <?php endif; ?>
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