<?php
    require "inc/koneksi.php";
    require "inc/fungsi.php";
    
    session_start();
    if(isset($_SESSION['admin']))
    {
        $id_akun = $_SESSION['admin'];
        $stt_akun = $_SESSION['stt_akun'];
    }

    // favorit
    if(isset($_GET['fav'])){
        // cek apakah sudah login
        if(isset($id_akun)){
            $id_produk = $_GET['fav'];
            // cek apakah sudah dalam favorit
            // jika sudah
            if(favorit($id_produk,$id_akun,"cek")){
                $delete = favorit($id_produk,$id_akun,"delete");
                header("Location: index.php");
            }else{
                // jika belum ada dalam favorit
                $input = favorit($id_produk,$id_akun,"input");
                if($input){
                    header("Location: index.php");
                }else{
                    echo "Gagal input favorit";
                }
            }
        }else{
            header("Location: login.php;");
        }
    }

    // KRISAR
    if(isset($_POST['krisar'])){
        $nama = htmlspecialchars($_POST['nama']);
        $email = htmlspecialchars($_POST['email']);
        $krisar = htmlspecialchars($_POST['krisar']);

        $res = mysqli_query($con, "INSERT INTO kritik_saran VALUES ('','$nama','$email','$krisar') ");
        if(mysqli_affected_rows($con) > 0){
            echo "<script>
                    var feed = confirm('Kritik dan saran kamu telah dikirim. Terimakasih atas masukannya');
                    if(feed == true){
                        window.location.href = 'index.php';
                    }else{
                        window.location.href = 'index.php';
                    }
                </script>";
        }
    }
    
?>
 
<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smuhdastore - Situs Jual Beli Online Aman, Mudah dan Terpercaya</title>
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
<body> 
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
                <a href="tentang_kami.php"><span class="mr-4">Tentang Kami</span></a>
            </div>
            </div>
        </div>

        <div class="item-header-2 d-flex flex-column px-0">
            <div class="container d-flex justify-content-between align-items-center">

                <img class="img-logo mr-3" src="assets/img/logo_white.svg" alt="">
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

    <!-- carousel -->
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
            <img class="d-block w-100" src="assets/img/slide/3.jpg" alt="First slide">
            </div>
            <div class="carousel-item">
            <img class="d-block w-100" src="assets/img/slide/4.jpg" alt="Second slide">
            </div>
            <div class="carousel-item">
            <img class="d-block w-100" src="assets/img/slide/2.jpg" alt="Third slide">
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <!-- kategori/Fitur -->
    <div class="fitur container d-flex justify-content-between mt-2 px-2 py-3 overflow-auto bg-white" style="overflow: auto;">
        <a href="main_produk.php" class="d-flex flex-column justify-content-between text-center p-2 px-3 text-secondary" style=" text-decoration:none;">
            <img class="img-fitur" src="assets/icons/promotional.svg" alt="" style="width: 40px;">
            <b style="font-size: small; font-weight: normal;line-height:16px;">Semua</b>
        </a>
        <a href="main_produk.php?ktg=fashion" class="d-flex flex-column justify-content-between text-center p-2 px-3 text-secondary" style=" text-decoration:none;">
            <img class="img-fitur" src="assets/icons/dress.svg" alt="" style="width: 40px;">
            <b style="font-size: small; font-weight: normal; line-height:16px;">Fashion</b>
        </a>
        <a href="main_produk.php?ktg=aksesoris" class="d-flex flex-column justify-content-between text-center p-2 px-3 text-secondary" style=" text-decoration:none;"> 
            <img class="img-fitur" src="assets/icons/watch.svg" alt="" style="width: 40px;">
            <b style="font-size: small; font-weight: normal; line-height:16px;">Aksesoris</b>
        </a>
        <a href="main_produk.php?ktg=elektronik"  class="d-flex flex-column justify-content-between text-center p-2 px-3 text-secondary" style=" text-decoration:none;">
            <img class="img-fitur" src="assets/icons/desktop.svg" alt="" style="width: 40px;">
            <b style="font-size: small; font-weight: normal; line-height:16px;">Elektronik</b>
        </a>
        <a href="main_produk.php?ktg=makanan & minuman"  class="d-flex flex-column justify-content-between text-center p-2 px-3 text-secondary" style=" text-decoration:none;">
            <img class="img-fitur" src="assets/icons/food.svg" alt="" style="width: 40px;">
            <b style="font-size: small; font-weight: normal; line-height:16px;">Makanan & Minuman</b>
        </a>
        <a href="main_produk.php?ktg=perabotan rumah"  class="d-flex flex-column justify-content-between text-center p-2 px-3 text-secondary" style=" text-decoration:none;">
            <img class="img-fitur" src="assets/icons/sofa.svg" alt="" style="width: 40px;">
            <b style="font-size: small; font-weight: normal; line-height:16px;">Perabotan Rumah</b>
        </a>
        <a href="main_produk.php?ktg=kecantikan"  class="d-flex flex-column justify-content-between text-center p-2 px-3 text-secondary" style=" text-decoration:none;">
            <img class="img-fitur" src="assets/icons/makeup.svg" alt="" style="width: 40px;">
            <b style="font-size: small; font-weight: normal; line-height:16px;">Kecantikan</b>
        </a>
        <a href="main_produk.php?ktg=hobi & koleksi"  class="d-flex flex-column justify-content-between text-center p-2 px-3 text-secondary" style=" text-decoration:none;">
            <img class="img-fitur" src="assets/img/svg/038-games.svg" alt="" style="width: 40px;">
            <b style="font-size: small; font-weight: normal; line-height:16px;">Hobi & Koleksi</b>
        </a>
    </div>

    <!-- banner ads kecil 1 -->
    <div class="container bg-white p-0">
        <div class="d-flex justify-content-center overflow-auto w-100">
            <img class="w-100" src="assets/img/17.png" alt="">
        </div>
    </div>

    <!-- produk jual cepat -->
    <?php 
        $res_jualcepat = mysqli_query($con, "SELECT * FROM produk_jualcepat WHERE mulai <= CURDATE() AND berakhir >= CURDATE()");
        if(mysqli_num_rows($res_jualcepat) > 0):
    ?>
    <div class="produk-jualcepat container bg-white mt-2 py-2">
        <div class="d-flex justify-content-between pb-2">
            <span class="title font-weight-bold" style="color: darkcyan;">
            <span>JUAL CEPAT</span>
            <!-- <span class="px-1 ml-2" style="background: black; color: white; border-radius:2px;">23:30:14 </span> -->
            </span>
            <a href="main_produk.php?promo=jualcepat" style="text-decoration: none;"><span class="more text-secondary">Lihat lainnya</span></a>
        </div> 

        <div class="w-100 overflow-auto" style="overflow: auto;">
            <div id="jml_jualcepat" hidden><?= mysqli_num_rows($res_jualcepat); ?></div>
            <div id="cov_jualcepat" class="produk-jualcepat d-flex row mt-2 pl-3 pb-2" style="width: 2450px;">
                <?php
                    while($jualcepat = mysqli_fetch_assoc($res_jualcepat)):
                        $id_produk = $jualcepat['id_produk'];
                ?>
                <a href="detil_produk.php?id_produk=<?= $id_produk; ?>&jualcepat" class="text-dark" style="text-decoration: none;">
                <div class="cart-produk-jualcepat d-flex flex-column text-center pb-2" style="width: 120px; position: relative;">
                    <!-- icon jual cepat -->
                    <div class="d-flex flex-column p-2 text-danger" style="position:absolute; left: 0; background:crimson; color:white; border-bottom-right-radius:10px;">
                        <b class="icon-stopwatch text-white" style="font-size: small;"></b>
                    </div>
                    <div class="cov-img-cart d-flex justify-content-center align-items-center mb-2" style="height: 120px;">
                        <img class="h-100" style="width: auto;" src="assets/foto/<?= ambil_1_data("gambar_produk","id_produk",$id_produk,"gambar"); ?>" alt="">
                    </div>
                    <b style="color: darkcyan; font-size:14px; font-weight:bold;" ><?= rupiah($jualcepat['harga_baru']); ?></b>
                    <div class="progress mt-2" style="background: #cbf2cb; position:relative;" >
                        <div class="progress-bar" role="progressbar" style="width: <?= cekTerjualCepat($id_produk) / ambil_1_data('produk','id_produk',$id_produk,'stok_produk') * 100; ?>%; background: #74d874;" aria-valuemin="0" aria-valuemax="100"></div>
                         <span style="position: absolute; font-weight:normal; color:darkcyan; font-size:small; width:100%;">
                            Terjual
                            <?= cekTerjualCepat($id_produk); ?>
                            /
                            <?= ambil_1_data('produk','id_produk',$id_produk,'stok_produk'); ?>
                         </span>
                    </div>
                </div>
                </a>
                    
               
                <?php endwhile; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>


    <!-- Produk diskon -->
    <?php $diskon = mysqli_query($con, "SELECT * FROM produk_diskon ORDER BY diskon DESC"); ?>
    <?php if(mysqli_num_rows($diskon) > 0):    
    ?>
    <div class="produkpromo container bg-white mt-2 pt-2">

        <div class="d-flex justify-content-between pb-2">
            <span class="title font-weight-bold" style="color: darkcyan;">LAGI DISKON</span>
            <a href="main_produk.php?promo=diskon" style="text-decoration: none;"><span class="more">Lihat lainnya</span></a>
        </div>
        
        <div class="d-flex row" style="background: darkcyan; position:relative; overflow:hidden;">
            <div id="img_diskon" style="background: url(assets/img/bg-promo.png); background-size: 250px; background-repeat: no-repeat; background-position: -50px 10px; width:100%; height:100%; position:absolute; filter: opacity(1);"></div>

            <div id="cover_diskon" class="w-100 overflow-auto pb-2" style="overflow:auto; padding-left: 150px;">
            
            <div id="promocard" class="produk-jualcepat d-flex row mt-2 pl-3" style="width: 2450px;">
            <div id="jmlcard" hidden><?= mysqli_num_rows($diskon); ?></div>
            
            <?php while($dis = mysqli_fetch_assoc($diskon)): 
                    $id_produk = $dis['id_produk'];
            ?>

            <a href="detil_produk.php?id_produk=<?= $id_produk; ?>" class="text-dark" style="text-decoration: none;">
            <div class="cart-produk-jualcepat d-flex flex-column align-items-center text-center pb-2" style="width: 150px;">
                <div class="diskon d-flex flex-column">
                   <b><?= cekDiskon($dis['id_produk'],"diskon"); ?>%</b>
                   <b class="text-white">OFF</b>
                </div>
                <div class="d-flex flex-row justify-content-center align-items-center mb-2" style="width: 150px; height: 150px; overflow: hidden;">
                    <img class="h-100" style="width: auto;" src="assets/foto/<?= ambil_1_data("gambar_produk","id_produk",$id_produk,"gambar"); ?>" alt="">
                </div>
                <span style="padding:0 5px;"><?= ambil_1_data("produk","id_produk",$id_produk,"nama_produk"); ?></span>
                <b style="color: darkcyan;"><?= rupiah(cekDiskon($id_produk,"harga_produk_diskon")); ?></b>
                <b class="harganormal"><?= rupiah(ambil_1_data("produk","id_produk",$id_produk,"harga_produk")); ?></b>
            </div>
            </a>

            <?php endwhile; ?>

            </div>
            </div>
            
        </div>
        
    </div>
    <?php endif; ?>

     <!-- banner ads kecil 2 -->
     <div class="container bg-white p-0 mb-2 mt-2">
        <div class="d-flex justify-content-center overflow-auto w-100">
            <img class="w-100" src="assets/img/lazada.png" alt="">
        </div>
    </div>
    
    <!-- Rekomendasi -->
    <?php $produk = mysqli_query($con, "SELECT * FROM produk ORDER BY id_produk DESC LIMIT 40 "); ?>
    <?php if(mysqli_num_rows($produk) > 0):    
    ?>
    <div class="produkrekomendasi container bg-white mt-2 py-2">
        <div class="d-flex justify-content-between pb-2">
            <span class="title font-weight-bold" style="color: darkcyan;">REKOMENDASI</span>
        </div>

        <div class="d-flex flex-row justify-content-center">
            <div class=" w-100 produk-jualcepat row mt-2 pl-0 justify-content-around">

            <?php
                while($pro = mysqli_fetch_assoc($produk)): 
                    $id_produk = $pro['id_produk'];
            ?> 
            <!-- card -->
            <div class="cart-produk-jualcepat d-block pb-2" style="width: 160px;">
                <a href="detil_produk.php?id_produk=<?= $pro['id_produk']; ?>" class="text-dark" style="text-decoration: none;">
                <!-- diskon --> 
                <?php if(cekDiskon($pro['id_produk'],"bool")): ?>
                <div class="diskon d-flex flex-column">
                   <b><?= cekDiskon($pro['id_produk'],"diskon"); ?>%</b>
                   <b class="text-white">OFF</b>
                </div>
                <?php endif; ?>
                
                <!-- image -->
                <div class="cov-img-cart d-flex justify-content-center align-items-center">
                    <img class="h-100" style="width: auto;" src="assets/foto/<?= ambil_1_data("gambar_produk","id_produk",$id_produk,"gambar"); ?>" alt="">
                </div>
                <!-- nama produk -->
                <span class="nama_produk"><?= $pro['nama_produk']; ?></span>
                <!-- harga -->
                <b class="harga" style="color: darkcyan;">
                <?php 
                    if(cekDiskon($id_produk,"bool")){
                        echo rupiah(cekDiskon($id_produk,"harga_produk_diskon"));
                    }else{
                        echo rupiah($pro['harga_produk']);
                    }
                ?>
                </b>
                </a>
                <!-- rating -->
                <div class="rating ml-2">
                    <?php
                        $res_nilai_produk = mysqli_query($con, "SELECT AVG(penilaian) AS nilai FROM produk_transaksi WHERE id_produk = '$id_produk' AND stt_transaksi = 'selesai' ");
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
                    <span class="text-secondary" style="font-size: 14px;">(<?= cekTerjual($id_produk); ?>)</span>
                </div>
                <!-- lokasi penjual dan favorit -->
                <div class="d-flex justify-content-between align-items-center">
                    <?php if(isset($id_akun)): ?>
                    <!-- favorit -->
                    <?php if(favorit($id_produk,$id_akun,"cek")): ?>
                        <a href="index.php?fav=<?= $id_produk; ?>" style="text-decoration: none;"><b class="icon-heart" style="color:crimson;"></b></a>
                    <?php else: ?>
                        <a href="index.php?fav=<?= $id_produk; ?>" style="text-decoration: none;"><b class="icon-heart" style="color:silver;"></b></a>
                    <?php endif; ?>
                    <?php endif; ?>
                    <!-- lokasi -->
                    <div class="text-secondary" style="font-size: small; "><?= lokasiProduk($id_produk); ?></div>
                </div>
            </div>
            <?php endwhile; ?>

            </div>
        </div>
    </div>
    <?php endif; ?>
    <!-- Terlaris -->
    <!-- <div class="produkterlaris container bg-white mt-2 py-2">
        <div class="d-flex justify-content-between pb-2">
            <span class="title font-weight-bold" style="color: darkcyan;">BEST SELLER</span>
            <span class="more">Lihat lainnya</span>
        </div> -->

        <!-- <div class="d-flex flex-row justify-content-center">
            <div class=" w-100 produk-jualcepat row mt-2 pl-0 justify-content-center"> -->

            <!-- card -->
            <!-- <div class="cart-produk-jualcepat align-items-center"> -->
                <!-- image -->
                <!-- <div class="cov-img-cart d-flex align-items-center">
                    <img src="assets/img/12.jpg" alt="">
                </div>
                <span class="d-flex align-items-center justify-content-center">Terjual </span>
            </div> -->

            <!-- </div>
        </div>
    </div> -->

    <!-- Footer1 -->
    <div class="footer1 py-5 bg-white mt-2 w-100" id="footer1">
        <div class="garansi container d-flex justify-content-between">
        <div class="item-foot d-flex align-items-center w-100 my-2">
            <img src="assets/img/garansi.svg" alt="" style="width: 70px;" class="mr-2">
            <div class="d-flex flex-column align-items-left px-2">
                <h5 style="color: darkcyan;">Garansi</h5>
                <span>Kami garansi jika barang tidak sesuai keterangan produk yang dijual</span>
            </div>
            
        </div>
        <div class="item-foot d-flex align-items-center w-100 my-2">
            <img src="assets/img/terpercaya.svg" alt="" style="width: 70px;" class="mr-2">
            <div class="d-flex flex-column align-items-left px-2">
                <h5 style="color: darkcyan;">Terpercaya</h5>
                <span>Pejual kami sangat terpercaya karena merupakan siswa-siswi dari sekolah kami</span>
            </div>
        </div>
        <div class="item-foot d-flex align-items-center w-100 my-2">
            <img src="assets/img/mudah.svg" alt="" style="width: 70px;" class="mr-2">
            <div class="d-flex flex-column align-items-left px-2">
                <h5 style="color: darkcyan;">Cepat dan Mudah</h5>
                <span>Pelayanan cepat dan mudah dalam melakukan pembelian</span>
            </div>
        </div>
        </div>
    </div>

    <div class="container bg-white border-bottom"></div>

    <!-- Footer2 -->
    <div class="footer2 d-flex justify-content-between bg-white py-5" id="footer2">
        <div class="container footer-info d-flex justify-content-between">
        <div class="item-foot2 d-flex flex-column w-100 px-2">
            <img style="width: 150px;" src="assets/img/logo.svg" alt="">
            <span class="d-flex align-items-center">
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
            <span class="d-flex align-items-center">
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
            <span class="d-flex align-items-center">
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

        <div class="item-foot2 d-flex flex-column w-100" style="padding-top: 20px;">
            <span style="font-weight: bold; color: darkcyan;">JELAJAHI SMUHDASTORE</span>
            <a class="text-secondary py-2" style="font-size:small;" href="tentang_kami.php">Tentang Kami</a>
            <a class="text-secondary py-2" style="font-size:small;" href="#">Syarat dan Ketentuan</a>
            <a class="text-secondary py-2" style="font-size:small;" href="#">Kebijakan Privasi</a>
        </div>

        <div class="item-foot2 d-flex flex-column w-100" style="padding-top: 20px;">
            <span style="font-weight: bold; color: darkcyan;">KRITIK DAN SARAN</span>
            <span style="font-size: smaller; line-height:16px; margin-bottom: 10px;">Dengan adanya kritik dan saran dari pengguna kepada aplikasi ini diharapkan mampu membantu developer dalam mengembangkan aplikasi ini menjadi lebih baik.</span>
            <form action="" method="POST">
                <input type="text" name="nama" id="" class="form-control mb-2" placeholder="Nama" required>
                <input type="email" name="email" id="" class="form-control mb-2" placeholder="Email" required>
                <textarea name="krisar" id="" cols="30" rows="2" class="form-control mb-2" placeholder="Kritik dan Saran" required></textarea>
                <button class="btn" style="background: darkcyan; color:white; width:130px;" type="submit">Kirim</button>
            </form>
        </div>

        </div>
    </div>

    <div class="container bg-white border-bottom"></div>

    <!-- Footer3 -->
    <div class="footer3 bg-white py-3" >
        <div class="container d-flex justify-content-between">
            <span>&copy; Smuhdastore 2021. Hak Cipta Dilindungi</span>
            <span>SMK MUHAMMADIYAH 2 KLATEN UTARA</span>
            <span>Negara: Indonesia</span>
        </div>

    </div>


<!-- POPPER -->
    <!-- Modal Search -->
    <!-- <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content m-c-head">
                <div class="d-flex justify-content-between">
                    <span class="font-weight-bold title" style="color:darkcyan;">Pencarian terakhir</span>
                    <span class="btn-hapus font-weight-normal text-danger" style="font-size: 14px;">Hapus semua</span>
                </div>
                <span class="opt-hist-search pl-2 py-1 mt-2" style="font-size: 14px;">Kemeja cowok</span>
                <span class="opt-hist-search pl-2 py-1 mt-2" style="font-size: 14px;">Celana dalam boxer</span>
                <span class="opt-hist-search pl-2 py-1 mt-2" style="font-size: 14px;">Mantol ruwet</span>

                <div class="d-flex flex-column">
                    <span class="font-weight-bold title mt-3" style="color:darkcyan;">Terpopuler</span>
                    <div class="mt-2">
                        <a href="#" style="text-decoration: none;"><span class="px-2 mr-1 rounded-pill" style="font-size: 14px; color: darkcyan; border: 1px solid darkcyan;">Headset</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

<!-- Menu Fixed Bawah -->
    <div id="navBottom">
        <div class="nav-bottom fixed-bottom py-2 px-3 d-flex flex-row justify-content-between align-items-center" style="display: flex; box-shadow: 0px -5px 10px rgba(0,0,0,0.05);">
            <a href="index.php" class="text-dark w-25 rounded" style="text-decoration: none; background: darkcyan;"><div class="d-flex flex-column justify-content-center align-items-center px-2 py-1 " >
                <b class="icon-home" style="font-size: 30px; color: white;"></b>
                <span style="font-size:small; color: white;">Beranda</span>
            </div></a>
            <a href="favorit.php" class="text-dark w-25" style="text-decoration: none;"><div class="d-flex flex-column justify-content-center align-items-center px-2 py-1 position-relative">
                <b class="icon-heart" style="font-size: 30px; color: darkcyan;"></b>
                <span style="font-size:small;">Favorit</span>
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

<!-- jika ada notif success -->
<script>
    var Url = window.location.search;
    if(Url.indexOf("notif") > -1){
        var notif = confirm("Pesanan kamu sudah dikirim ke penjual untuk diproses. Terimakasih");
        if(notif){
            window.location.href = "index.php";
        }else{
            window.location.href = "index.php";
        }
    }
</script>

</body>
</html>