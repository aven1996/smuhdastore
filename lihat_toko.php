<?php
    require "inc/koneksi.php";
    require "inc/fungsi.php";
    
    session_start();
    if(isset($_SESSION['admin']))
    {
        $id_akun = $_SESSION['admin'];
        $stt_akun = $_SESSION['stt_akun'];
    }

    // jika tidak ada id penjual maka lempar ke index
    if(isset($_GET['id_penjual'])){
        $id_penjual = $_GET['id_penjual'];
        $result_penjual = mysqli_query($con, "SELECT * FROM profil INNER JOIN akun_login ON profil.id_login = akun_login.id_login WHERE profil.id_login = '$id_penjual'");
        $penjual = mysqli_fetch_assoc($result_penjual);

        $result_produk = mysqli_query($con, "SELECT * FROM produk WHERE id_login = '$id_penjual'");

    }else{
        header("Location: index.php");
    }

    
    
?>

<!DOCTYPE html>
<html lang="en"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko - Smuhdastore Belaja Nyaman dan Terpercaya</title>
    <link rel="shortcut icon" href="assets/img/fav.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css">
    
    <link rel="stylesheet" href="assets/icons/icomoon5/style.css">
    <link rel="stylesheet" href="assets/icons/icomoon6/style.css">
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
    <!-- nav -->
    <div class="nav d-flex justify-content-between align-items-center px-3 py-3" style="background-color: darkcyan;">
        <span style="color:white; font-size: 24px;" onclick="window.history.back();"><b class="icon-arrow-left2 mr-2"></b></span>

    </div> 
    <!-- nama penjual -->
    <div class="border-0 head d-flex justify-content-start align-items-center px-3 pb-3" style="background-color: darkcyan;">
        <div>
        <div class="d-flex justify-content-center align-items-center rounded-circle mr-3" style="width: 70px; height:70px; overflow:hidden;">
        <?php if(!empty($penjual['foto_profil'])): ?>
            <img src="assets/foto/<?= $penjual['foto_profil']; ?>" alt="" class="h-100">
        <?php else: ?>
            <img src="assets/img/poto-profil.png" alt="" class="h-100">
        <?php endif; ?>
        </div>
        </div>

        <div class="d-flex flex-column w-75">
            
            <?php 
                if(empty($penjual['nama_profil'])):
            ?>
                <span style="font-weight: bold; color: white;"><?= $penjual['username']; ?></span>    
            <?php else : ?>
                <span style="font-weight: bold; color: white;"><?= $penjual['nama_profil']; ?></span>
                <span style="font-weight: normal; color: white; font-size:small;">@<?= $penjual['username']; ?></span> 
            <?php endif; ?>
            
            <div class="rating d-flex flex-row align-items-center">
                <?php
                $result_penilaian = mysqli_query($con, "SELECT AVG(penilaian) AS rate FROM produk_transaksi WHERE id_penjual = '$id_penjual' AND stt_transaksi = 'selesai' ");
                if(mysqli_num_rows($result_penilaian) > 0){
                    $penilaian = mysqli_fetch_assoc($result_penilaian);
                    $penilaian = round($penilaian['rate']);
                }else{
                    $penilaian = 0;
                }
                
                for ($z=0; $z < $penilaian; $z++) :
                ?>
                    <b class="icon-star-full" style="color: gold;"></b>
                <?php
                    endfor;
                ?>
                <?php  
                for ($z=0; $z < 5 - $penilaian; $z++) :
                ?>
                    <b class="icon-star-full" style="color: rgba(255,255,255,0.5);"></b>
                <?php
                    endfor;
                ?>
                <span class="text-white" style="font-size: small; margin-left:5px;">(<?= mysqli_num_rows($result_produk); ?> Produk)</span>
            </div>
        </div>
        <?php $url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>
        <a href="<?= wa($id_penjual,"Aku sedang melihat tokomu!","$url"); ?>">
            <div class="d-flex justify-content-center align-items-center px-2 py-1 rounded  text-white" style="background-color: darkcyan; border:1px solid white;">
                <b class="icon-whatsapp mr-2" style="font-size:18px;"></b>
                <span>Chat</span>
            </div>
        </a>
    </div>
    <!-- menu  -->
    <div class="d-flex justify-content-center align-items-center">
        <div class="py-2 px-3 text-center" style="border-top: 2px solid darkcyan; width:33%; color:darkcyan; background:white;">Semua</div>
        <a href="lihat_toko_diskon.php?id_penjual=<?= $_GET['id_penjual'];?>" class="py-2 px-3 text-center text-white" style="border-top: 2px solid darkcyan; width: 33%; background:darkcyan;">Diskon</a>
        <a href="lihat_toko_jualcepat.php?id_penjual=<?= $_GET['id_penjual'];?>" class="py-2 px-3 text-center text-white" style="border-top: 2px solid darkcyan; width: 34%; background:darkcyan;">Jual Cepat</a>
    </div>

    <div class="produkrekomendasi bg-white container pt-3" style="padding-bottom: 20px;">
        <div class="d-flex flex-row justify-content-center">
            <div class=" w-100 produk-jualcepat row mt-2 pl-0 justify-content-around">

            <?php
                if(mysqli_num_rows($result_produk) > 0) :
                    
                while ($produk = mysqli_fetch_assoc($result_produk)) :
                $id_produk = $produk['id_produk'];
            ?> 
            <!-- card -->
            <div class="cart-produk-jualcepat d-block pb-2" style="width: 160px;">
                <a href="detil_produk.php?id_produk=<?= $produk['id_produk']; ?>" class="text-dark" style="text-decoration: none;">
                <!-- diskon --> 
                <?php if(cekDiskon($produk['id_produk'],"bool")) :?>
                <div class="diskon d-flex flex-column">
                   <b><?= cekDiskon($produk['id_produk'],"diskon");?>%</b>
                   <b class="text-white">OFF</b>
                </div>
                <?php endif; ?>
                
                <!-- image -->
                <div class="cov-img-cart d-flex justify-content-center align-items-center" style="overflow: hidden; height:150px;">
                    <img class="h-100" style="width: auto;" src="assets/foto/<?= ambil_1_data("gambar_produk","id_produk",$id_produk,"gambar"); ?>" alt="">
                </div>
                <!-- nama produk -->
                <span class="nama_produk m-0"><?= $produk['nama_produk'];?></span>
                <!-- harga -->
                <b class="harga m-0" style="color: darkcyan;">
                    <?php
                    if(cekDiskon($id_produk,"bool")){
                        echo rupiah(cekDiskon($id_produk,"harga_produk_diskon"));
                    }else{
                        echo rupiah($produk['harga_produk']);
                    }
                    ?>
                </b>
                </a>
                <!-- rating -->
                <div class="rating d-flex justify-content-start align-items-center">
                    <?php
                    $res_nilai_produk = mysqli_query($con, "SELECT AVG(penilaian) AS nilai FROM produk_transaksi WHERE id_produk = '$id_produk' AND stt_transaksi = 'selesai' ");
                    $jml_nilai_produk = mysqli_query($con, "SELECT * FROM produk_transaksi WHERE id_produk = '$id_produk' AND stt_transaksi = 'selesai' ");
                    if(mysqli_num_rows($res_nilai_produk) > 0){
                        $nilai = mysqli_fetch_assoc($res_nilai_produk);
                        $nilai = round($nilai['nilai']);
                    }else{
                        $nilai = 0;
                    }
                    
                    for ($i=0; $i < $nilai; $i++) :
                    ?>
                        <b class="icon-star-full m-0" style="color: gold;"></b>
                    <?php
                        endfor;
                    ?>

                    <?php  
                    for ($i=0; $i < 5 - $nilai; $i++) :
                    ?>
                        <b class="icon-star-full m-0" style="color: rgba(0,0,0,0.2);"></b>
                    <?php
                        endfor;
                    ?>
                    <span class="text-secondary ml-2" style="font-size: 14px;">(<?= mysqli_num_rows($jml_nilai_produk);?>)</span>
                </div>
                <!-- lokasi penjual -->
                <div class="d-flex justify-content-between align-items-center">
                    <b class="icon-heart m-0" style="color:silver;"></b>
                    <div class="text-secondary m-0" style="font-size: small; "><?= lokasiProduk($id_produk); ?></div>
                </div>
            </div>
            <?php endwhile; ?>
            
            <?php else: ?>
                <span style="font-size: small;">Belum ada produk yang dijual</span>
            <?php endif; ?>

            </div>
        </div>
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


<!-- jika ada #wa404 pada url -->
<script>
    var cek = window.location.href;
    cek = cek.indexOf("#wa404");
    if(cek > -1){
        alert('Nomor Whatsapp yang dituju tidak ditemukan!');
    }
</script>


</body>
</html>