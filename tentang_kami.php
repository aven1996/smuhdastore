<?php
    require "inc/koneksi.php";
    require "inc/fungsi.php";
    
    session_start();
    if(isset($_SESSION['admin']))
    {
        $id_akun = $_SESSION['admin'];
        $stt_akun = $_SESSION['stt_akun'];
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Smuhdastore | Situs Jual Beli Online SMK Muhammadiyah 2 Klaten Utara</title>
    
    <link rel="shortcut icon" href="assets/img/fav.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/style_mulaipromo.css">
    <link rel="stylesheet" href="assets/icons/icomoon5/style.css">
    <link rel="stylesheet" href="assets/icons/icomoon4/style.css">
    <link rel="stylesheet" href="assets/icons/icomoon1/icon.css">
    <link rel="stylesheet" href="assets/icons/icomoon2/icon2.css">
    <link rel="stylesheet" href="assets/icons/icomoon3/style.css">

    <style> 
        .nav-bottom{
            -moz-backdrop-filter: blur(10px); 
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
        .header .item-header-2 .wrap-shop:hover{
            background: darkcyan;
        }

        .header .item-header-1 a:hover, .header .item-header-1 span:hover{
            text-decoration: underline;
            color: white;
        }
        @media only screen and (max-width : 800px){
            .garansi{
                display: flex;
                flex-direction: column;
            }
            .footer-info{
                display: flex;
                flex-direction: column;
            }
        }
    </style>
</head>
<body class="bg-white">

<!-- header -->
<div class="header w-100">
        <div class="item-header-1" id="navAdd" style="display: block;">
            <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <b class="icon-whatsapp mr-2" style="color: white;"></b>
                <a href="#footer2"><span>Hubungi Kami Lewat Whatsapp</span></a>
            </div>
 
            <div class="d-flex">
                <a href="#footer1"><span class="mr-4">Kurang Yakin?</span></a>
                <a href="#"><span class="mr-4">Tentang Kami</span></a>
            </div>
            </div>
        </div>

        <div class="item-header-2 d-flex flex-column px-0">
            <div class="container d-flex justify-content-between align-items-center">
                <a href="index.php">
                <img class="img-logo mr-3" src="assets/img/logo_white.svg" alt="">
                </a>
                <!-- form search -->
                <div class="wrap-search mr-2">
                    <!-- form search jika diklik akan muncul modal search(dibgn bawah) -->
                    <form action="main_produk.php" method="get" style="background: darkcyan;">
                        <input type="text" name="key" class="form-control shadow border-0" placeholder="Cari barang bagus">
                        <button type="submit" class="btn wrap-icon-search d-flex justify-content-center align-items-center">
                            <i class="icon-search img-search" style="color: darkcyan;"></i>
                        </button>
                    </form>
                </div>

                <!-- icon favorit-->
                <div id="favorit">
                    <div  class="wrap-shop mr-2 p-2 d-flex justify-content-center align-items-center position-relative" >
                        <a href="favorit.php" style="text-decoration:none;" title="daftar produk yang saya suka"><b class="icon-heart" style="color: white;"></b></a>
                        <?php 
                            if(isset($id_akun)){ 
                                if(favorit("",$id_akun,"jumlah") > 0){ ?>
                                <span class="d-flex justify-content-center align-items-center p-0 bg-danger text-white text-center rounded-circle" style="font-size:small; width:20px; height:20px; position:absolute; top:0;right:-5px;">
                                    <?= favorit("",$id_akun,"jumlah");?>
                                </span>
                        <?php
                                }
                            } 
                        ?>
                    </div>
                </div>

                <!-- icon cart shop -->
                <div id="keranjang" >
                    <div class="wrap-shop mr-2 p-2 d-flex justify-content-center align-items-center position-relative">
                        <a href="keranjang.php" style="text-decoration:none;" title="Keranjang pembelian saya"><b class="icon-cart" style="color: white;"></b></a>
                        <?php 
                            if(isset($id_akun)){ 
                                if(jmlDiKeranjang($id_akun) != 0){ ?>
                                <span class="d-flex justify-content-center align-items-center p-0 bg-danger text-white text-center rounded-circle" style="font-size:small; width:20px; height:20px; position:absolute; top:0;right:-5px;">
                                    <?= jmlDiKeranjang($id_akun);?>
                                </span>
                        <?php
                                }
                            } 
                        ?>
                    </div>
                </div>

                <!-- button masuk dan daftar -->
                <div id="profil" style="width: 310px;">
                <?php
                    if(!isset($_SESSION['admin'])):
                ?>

                    <a href="login.php" class="login"><button class="btn-masuk btn mr-1 font-weight-bold" style="background: white; color: darkcyan;">Login</button></a>
                    <a href="daftar.php" class="login"><button class="btn-daftar btn font-weight-bold" style="background: darkcyan; border:1px solid white; color: white;">Daftar</button></a>
                
                <?php
                    else:
                ?>
                    <a class="d-flex justify-content-start align-items-center" style=" text-decoration:none;" href="<?php 
                                if($_SESSION['stt_akun'] == 'penjual')
                                {
                                    echo "admin_penjual.php";
                                }
                                elseif($_SESSION['stt_akun'] == 'pembeli')
                                {
                                    echo "admin_pembeli.php";
                                }
                                elseif($_SESSION['stt_akun'] == 'owner')
                                {
                                    echo "admin_owner.php";
                                }
                            ?>">
                        <!-- poto --> 
                        <div class="mr-1 d-flex flex-row justify-content-center align-items-center rounded-circle" style="width: 40px; height: 40px; overflow:hidden;">
                            <?php 
                                $poto = ambil_1_data("profil", "id_login","$id_akun","foto_profil");
                                if (!empty($poto)) :

                            ?>
                                <img class="h-100" src="assets/foto/<?= $poto; ?>" alt="<?= $poto; ?>" title="<?= $poto; ?>"> 
                            <?php
                                    
                                else:
                            ?>
                                <img class="w-100" src="assets/img/poto-profil.png" alt="poto-profil.png">
                                
                            <?php endif; ?>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="text-white">Profil</span>
                            <span class="text-white">
                            <b>
                                <?php
                                    if(isset($_SESSION['admin']))
                                    {
                                        echo ambil_1_data("akun_login","id_login","$id_akun","username");
                                    }
                                ?>
                            </b></span>
                        </div>
                    </a>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- gambar hero -->
    <div class="d-flex flex-row justify-content-center align-items-center w-100" style=" overflow:hidden;">
        <img class="w-100" src="assets/img/slide/3.jpg" alt="">
    </div>

    <!-- tagline -->
    <div class="container text-center p-3">
        <h4 class="py-3" style="font-weight: bold; color:darkcyan;">SMUHDASTORE ADALAH SITUS JUAL BELI ONLINE YANG AMAN, MUDAH DAN DIJAMIN TERPERCAYA</h4>
        <span class="text-secondary">Diluncurkan pada tahun 2021, sebuah situs jual beli online yang didirikan oleh <a href="http://smkmuh2klaten.sch.id">SMK Muhammadiyah 2 Klaten Utara</a> dengan tujuan memudahkan siswa dalam menjual produk dan memberikan pengalaman yang menarik serta kepercayaan kepada pembeli dalam berbelanja secara online</span>
    </div>
    
    <!-- subtagline -->
    <div class="container text-center p-3">
        <h4 class="py-3" style="font-weight: bold; color:darkcyan;">MENGAPA SMUHDASTORE TERPERCAYA?</h4>
        <span class="text-secondary">Semua produk yang dijual adalah produk nyata sesuai keterangan produk dan dapat dipertanggungjawabkan karena penjual-penjual kami adalah siswa dari <a href="http://smkmuh2klaten.sch.id">SMK Muhammadiyah 2 Klaten Utara</a> sehingga sangat terkontrol. Jika pembeli mendapati produk yang tidak sesuai atau penipuan segera laporkan kepada kami melalui menu yang sudah disediakan</span>
    </div>

    <!-- lokasi map smk -->
    <div class="container text-center">
        <h4 class="py-3" style="font-weight: bold; color:darkcyan;">LOKASI KANTOR KAMI</h4>
        <iframe class="w-100" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.944857621272!2d110.60270591534294!3d-7.689067294457056!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a43e4ffb18437%3A0x8ef082bee152bfff!2sSMK%20Muhammadiyah%202%20Klaten%20Utara!5e0!3m2!1sid!2sid!4v1620495433890!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
    </div>

    <div class="container item-foot2 d-flex flex-column w-100 p-3">
        <img class="py-2" style="width: 150px;" src="assets/img/logo.svg" alt="">
        <span class="d-flex align-items-center text-secondary py-2">
            <b class="icon-whatsapp mr-2"></b>
            <span>
            <?php
                $data_hp_owner = ambil_1_data("profil","id_login",6,"hp");
                $data_nama_owner = ambil_1_data("profil","id_login",6,"nama_profil");
                
                if(!empty($data_hp_owner) && !empty($data_nama_owner))
                {
                    echo $data_hp_owner." - ".$data_nama_owner;
                }
                else
                {
                    echo "Data Disembunyikan";
                }
            ?>
            </span>
        </span>
        <span class="d-flex align-items-center text-secondary py-2">
            <b class="icon-location2 mr-2"></b>
            <span style="line-height: 17px;">
            <?php
                $data_alamat_owner = ambil_1_data("profil","id_login",6,"alamat");
                
                if(!empty($data_alamat_owner))
                {
                    echo $data_alamat_owner;
                }
                else
                {
                    echo "Data Disembunyikan";
                }
            ?>  
            </span>
        </span>
        <span class="d-flex align-items-center text-secondary py-2">
            <b class="icon-envelop mr-2"></b>
            <span>
            <?php
                $data_email_owner = ambil_1_data("akun_login","id_login",6,"email");
                
                if(!empty($data_email_owner))
                {
                    echo $data_email_owner;
                }
                else
                {
                    echo "Data Disembunyikan";
                }
            ?>  
            </span>
        </span>
    </div>

<script src="assets/bootstrap/jquery/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.js"></script>
<script src="assets/bootstrap/popper/popper.js"></script>

<!-- background memudar jika card produk diskon digeser kekiri -->
<script type="text/javascript">
    $("#cover_diskon").scroll(function(){
        var Opacity = 1-$("#cover_diskon").scrollLeft()/100;
        var PosX = -50-$("#cover_diskon").scrollLeft()/10;
        $("#img_diskon").css("filter","opacity("+ Opacity +")");
        $("#img_diskon").css("background-position",PosX+"px 10px");
    });

</script>

<!-- membuat header fixed top saat halaman mulai di scroll -->
<script type="text/javascript">
  $(window).scroll(function(){
    if ($(window).scrollTop() >= 150) {
        $('.header').addClass('position-fixed');
        $('.item-header-1').css('display','none');
    }
    else if($(window).scrollTop() < 150 && $(window).width() > 800)
    {
        $('.header').removeClass('position-fixed');
        $('.item-header-1').css('display','flex');
    }
    else
    {
        $('.header').removeClass('position-fixed');
    }
 });
 </script>

 <!-- membuat container menjadi besar saat ukuran width window kurang dari nilai tertentu -->
<script type="text/javascript">
  $(window).ready(function(){
  if ($(window).width() <= 800) {
   $('.container').addClass('container-full');
  }
  else {
   $('.container').removeClass('container-full');
  }
 });
</script>

<!-- membuat menu nav bawah ressponsive saat width window tertentu -->
<script type="text/javascript">
  $(window).ready(function(){
  if ($(window).width() <= 800) {
    $('#navAdd').css('display','none');
    $('#navBottom').css('display','flex');
    $('#favorit').css('display','none');
    $('#keranjang').css('display','none');
    $('#profil').css('display','none');
    $('.item-header-1').css('display','none');
  }
  else
  {
    $('#navAdd').css('display','block');
    $('#navBottom').css('display','none');
    $('#favorit').css('display','block');
    $('#keranjang').css('display','block');
    $('#profil').css('display','inline-block');
  }
 });

</script>

<!-- lebar responsive pada produk promo -->
<script>
    $("#promocard").css("width", Number($("#jmlcard").text())*170+"px");
    $("#cov_jualcepat").css("width", Number($("#jml_jualcepat").text())*150+"px");
</script>
</body>
</html>