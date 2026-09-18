<?php
    require "inc/koneksi.php";
    require "inc/fungsi.php";
    
    session_start();
    if(isset($_SESSION['admin']))
    {
        $id_akun = $_SESSION['admin'];
        $stt_akun = $_SESSION['stt_akun'];

        // input ke tabel lastseen (terakhir dilihat) jika login
        if(isset($_GET['id_produk'])){
            $id_produk = $_GET['id_produk'];
            $res = mysqli_query($con, "SELECT * FROM lastseen WHERE id_login = '$id_akun' AND id_produk = '$id_produk' ");
            // cek dulu produk sudah ada apa belum
            if(mysqli_num_rows($res) == 0){
                mysqli_query($con, "INSERT INTO lastseen VALUES ('','$id_akun',$id_produk) ");
            }
        }
    }
    
    if(isset($_GET['id_produk'])){
        $id_produk = $_GET['id_produk'];
        $id_penjual = ambil_1_data("produk","id_produk",$id_produk,"id_login");
    }else{
        header("Location: index.php");
    }

    // saat tombol cart ditekan
    if(isset($_POST['tambah_cart'])){
        if(isset($id_akun)){
            $id_pembeli = $_POST['id_pembeli'];
            $id_produk = $_POST['id_produk'];
            $harga = $_POST['harga'];
            $jml_pembelian = 1;
            mysqli_query($con, "INSERT INTO keranjang VALUES('','$id_pembeli','$id_produk','$harga','$harga','$jml_pembelian')");

            if(mysqli_affected_rows($con)){
                if(isset($_POST['jualcepat'])){
                    header("Location: detil_produk.php?id_produk=$id_produk&jualcepat");
                }else{
                    header("Location: detil_produk.php?id_produk=$id_produk");
                }
            }


        }else{
            header("Location: login.php");
        }
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Belanja Sekarang - Smuhdastore Belaja Nyaman dan Terpercaya</title>
    <link rel="shortcut icon" href="assets/img/fav.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css">
    
    <link rel="stylesheet" href="assets/icons/icomoon4/style.css">
    <link rel="stylesheet" href="assets/icons/icomoon1/icon.css">
    <link rel="stylesheet" href="assets/icons/icomoon2/icon2.css">
    <link rel="stylesheet" href="assets/icons/icomoon3/style.css">
    <link rel="stylesheet" href="assets/icons/icomoon5/style.css">
    <link rel="stylesheet" href="assets/icons/icomoon6/style.css">
    <link rel="stylesheet" href="assets/icons/icomoon7/style.css">
    <link rel="stylesheet" href="assets/css/admin.css">

    <style>
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

<div class="container d-flex flex-column px-0 mb-5">
    <!-- navigasi -->
    <div class="nav bg-white d-flex justify-content-between align-items-center px-3 py-3 ">
        <span style="color:darkcyan; font-size: 24px;" onclick="window.history.back();"><b class="icon-arrow-left2 mr-2"></b></span>
        <div class="d-flex justify-content-center align-items-center">
            <div class="d-flex justify-content-center align-items-center position-relative">
                <a href="keranjang.php" class="px-3" style="color: darkcyan; font-size: 24px; text-decoration:none;"><b class="icon-cart"></b></a>
                <?php 
                    if(isset($id_akun)){ 
                        if(jmlDiKeranjang($id_akun) != 0){ ?>
                        <span class="d-flex justify-content-center align-items-center p-1 bg-danger text-white text-center rounded-circle" style="font-size:small; width:20px; height:20px; position:absolute; top:0;right:0;">
                            <?= jmlDiKeranjang($id_akun);?>
                        </span>
                <?php
                        }
                    } 
                ?>
                
            </div>

            <span class="px-2 ml-3" style="font-size: 24px; color: darkcyan;" data-toggle="modal" data-target="#popmenu"><b class="icon-ellipsis-v mr-2"></b></span>
        </div>
    </div>
 
    <!-- Poto -->
    <!-- carousel -->
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <?php
            $result = mysqli_query($con, "SELECT * FROM gambar_produk WHERE id_produk = '$id_produk'");
            $jml_gambar = mysqli_num_rows($result);
            $img_arr = [];
            while ($img = mysqli_fetch_assoc($result)) {
                $img_arr[] = $img['gambar'];
            }

        ?>
        <ol class="carousel-indicators">
            <?php for($v=0; $v < $jml_gambar; $v++) : ?>
                <li data-target="#carouselExampleIndicators" data-slide-to="<?= $v ?>" class="active"></li>
            <?php endfor; ?>
        </ol>
        <div class="carousel-inner bg-white">
            <div class="carousel-item active mx-auto" style="max-height: 550px; max-width: 550px; overflow:hidden;">
                <img class="d-block w-100" src="assets/foto/<?= $img_arr[0]; ?>" >
            </div>
            <?php
                for($c = 1; $c < $jml_gambar; $c++):
            ?>
                <div class="carousel-item mx-auto" style="max-height: 550px; max-width: 550px; overflow:hidden;">
                    <img class="d-block w-100" src="assets/foto/<?= $img_arr[$c]; ?>" >
                </div>
            <?php endfor; ?>
            
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
    <!-- nama produk, harga, rating -->
    <div class="bg-white p-2 mb-2">
        <span class="mb-2 d-block" style="line-height: 18px;"><?= ambil_1_data("produk","id_produk",$id_produk,"nama_produk"); ?></span>
        <span class="mb-2 d-block" style="color: darkcyan; font-size:large; font-weight:bold;">
        <?php
            if(isset($_GET['jualcepat'])){
                $promo = "jualcepat";
                $harga = cekJualCepat($id_produk, "harga_baru");
                echo rupiah($harga);

        ?>
                <span style="padding:3px; font-size:small; background-color: gold; color:black;">JUAL CEPAT</span>

                <span class="text-secondary" style="font-weight:normal; font-size:small; text-decoration:line-through;"><?= rupiah(ambil_1_data("produk","id_produk",$id_produk,"harga_produk"));?></span>
                <div style="font-size: small; font-weight:lighter;">Produk ini sedang dijual cepat berakhir pada <?= cekJualCepat($id_produk,"berakhir"); ?></div>
        <?php
            }elseif(cekDiskon($id_produk,"bool")){
                $promo = "diskon";
                $harga = cekDiskon($id_produk,"harga_produk_diskon");
                echo rupiah(cekDiskon($id_produk,"harga_produk_diskon"));
        ?>
                
                <span style="padding:3px; font-size:small; background-color: gold; color:black;"><?= cekDiskon($id_produk,"diskon")."% OFF"; ?></span>

                <span class="text-secondary" style="font-weight:normal; font-size:small; text-decoration:line-through;"><?= rupiah(ambil_1_data("produk","id_produk",$id_produk,"harga_produk"));?></span>
                
        <?php
            }else{
                $harga = ambil_1_data("produk","id_produk",$id_produk,"harga_produk");
                echo rupiah(ambil_1_data("produk","id_produk",$id_produk,"harga_produk"));
            }
        ?>
        </span>
        <div class="mb-2 d-flex justify-content-between align-items-center">
            <span class="d-flex justify-content-start align-items-center" >
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
                <span class="text-secondary px-2" style="font-size: small;"><?= cekTerjual($id_produk); ?> Terjual</span>
            </span>
        </div>
    </div>

    <!-- akun penjual -->
    <div class="bg-white p-2 mb-2">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex flex-row justify-content-start align-items-center">
                <div class="cov-img d-flex justify-content-center align-items-center rounded-circle mr-2" style="width: 50px; height: 50px; overflow:hidden;">
                    <?php 
                        if(!empty(ambil_1_data("profil","id_login",$id_penjual,"foto_profil"))):
                    ?>
                        <img class="h-100" src="assets/foto/<?= ambil_1_data("profil","id_login",$id_penjual,"foto_profil"); ?>" alt="">
                    <?php else: ?>
                        <img class="w-100" src="assets/img/poto-profil.png" alt="">
                    <?php endif; ?>
                </div>
                <div class="d-flex flex-column">
                    <span>
                    <b>
                    <?php if(!empty(ambil_1_data("profil","id_login",$id_penjual,"nama_profil"))){

                    ?>
                                <span><b> <?= ambil_1_data("profil","id_login",$id_penjual,"nama_profil");?> </b></span>
                                <span class="text-secondary" style="font-size: small; font-weight:normal;"> <?= "@".ambil_1_data("akun_login","id_login",$id_penjual,"username");?> </span>
                    <?php
                        }else{
                    ?>
                                <span><b> <?= ambil_1_data("akun_login","id_login",$id_penjual,"username"); ?> </b></span>
                    <?php
                        }
                    ?>
                    </b>
                    </span>
                    <span class="d-flex justify-content-start align-items-center text-secondary"  style="font-size: small;"><b class="icon-location2 mr-1"></b><?= lokasiProduk($id_produk); ?></span>
                </div>
                
            </div>
            <a href="lihat_toko.php?id_penjual=<?= $id_penjual; ?>"><button class="btn bg-white" style="font-size: small; border:1px solid darkcyan; color:darkcyan;">Lihat Toko</button></a>
        </div>
        <div class="d-flex justify-content-center align-items-center">
            <span class="text-secondary w-50 text-center">
                <span style="color:darkcyan; font-size: small;">
                <?php
                    $result = mysqli_query($con, "SELECT * FROM produk WHERE id_login = '$id_penjual'");
                    echo mysqli_num_rows($result);
                ?>
                </span>
                <span style="font-size: small;"> Produk</span>
            </span>
            <span class="text-secondary">|</span>
            <span class="text-secondary w-50 text-center" style="font-size: small;">
                Rating<span style="color:darkcyan;"> 
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
                    <b class="icon-star-full" style="color: silver;"></b>
                <?php
                    endfor;
                ?>
                </span>
            </span> 
        </div>
    </div>

    <!-- rincian produk -->
    <div class="d-block p-2 bg-white border-bottom">
        <div class="p-2"><b>Rincian Produk</b></div>
        <div class="d-flex jutify-content-start align-items-center">
                <span class="text-secondary p-2 w-25">Stok</span>
                <span class="p-2"><?= ambil_1_data("produk","id_produk",$id_produk,"stok_produk"); ?></span>
        </div>
        <div class="d-flex jutify-content-start align-items-center">
                <span class="text-secondary p-2 w-25">Kategori</span>
                <span class="p-2"><?= ambil_1_data("produk","id_produk",$id_produk,"kategori_produk"); ?></span>
        </div>
        <div class="d-flex jutify-content-start align-items-center">
                <span class="text-secondary p-2 w-25">Kondisi</span>
                <span class="p-2"><?= ambil_1_data("produk","id_produk",$id_produk,"kondisi_produk"); ?></span>
        </div>
        <div class="d-flex jutify-content-start align-items-center">
                <span class="text-secondary p-2 w-25">Alamat Penjual</span>
                <span class="p-2 w-75">
                <?php if(!empty(ambil_1_data("profil","id_login",$id_penjual,"alamat"))): ?>
                    <?= ambil_1_data("profil","id_login",$id_penjual,"alamat"); ?>
                <?php else: ?>
                    <i>Tidak ada lokasi</i>
                <?php endif; ?>
                </span>
        </div>
    
    </div>

    <!-- diskripsi produk -->
    <div class="d-block p-3 bg-white text-secondary" style="max-height: 300px; overflow:auto; line-height:20px;">
        <?= ambil_1_data("produk","id_produk",$id_produk,"deskripsi_produk"); ?>
    </div>

    <!-- tombol beli -->
    <div class="container fixed-bottom w-100 bg-white d-flex justify-content-between p-0" >
       <a href="<?= wa($id_penjual,"Saya tertarik dengan produk ini. Apakah produk ini tersedia?",$url_full); ?>" class="py-2 px-3 text-center" style="background: forestgreen; color: white; font-size: 24px; width:25%; text-decoration:none;"><b class="icon-whatsapp"></b></a>
        
       <form action="" method="post" style="width: 25%;">
            <?php if(isset($_GET['jualcepat'])): ?>
                <input type="hidden" name="jualcepat" value="jualcepat">
            <?php endif; ?>
            <input type="hidden" name="id_produk" value="<?= $id_produk; ?>">
            <input type="hidden" name="id_pembeli" value="<?php if(isset($id_akun)){echo $id_akun;} ?>">
            <input type="hidden" name="harga" value="<?= $harga; ?>">
            <button type="submit" name="tambah_cart" class="py-2 px-3 text-center border-0" style="background: dodgerblue; color: white; font-size: 24px; width:100%; text-decoration:none;"><b class="icon-cart-plus"></b></button>
       </form>

       <a href="checkout.php?id_produk=<?= $id_produk ?><?php if(isset($promo)){echo "&promo=".$promo ;}?>" class="py-2 px-3 text-center d-flex justify-content-center align-items-center" style="background: crimson; color: white; font-size: 18px; width:50%; text-decoration:none;"><span>BELI SEKARANG</span></a>
    </div>
    

    <!-- produk lain dari toko ini -->
    <?php 
        $res_toko = mysqli_query($con, "SELECT * FROM produk WHERE id_login = '$id_penjual'");
        if(mysqli_num_rows($res_toko) > 0):
    ?>
    <div class="produk-jualcepat container bg-white mt-2 py-2">
        <div class="d-flex justify-content-between pb-2">
            <span class="title font-weight-bold" >
            <span>Produk lain toko ini</span>
            </span>
            <a href="lihat_toko.php?id_penjual=<?= $id_penjual; ?>" style="text-decoration: none;"><span class="more text-secondary">Lihat lainnya</span></a>
        </div> 

        <div class="w-100 overflow-auto" style="overflow: auto;">
            <div id="jml_jualcepat" hidden><?= mysqli_num_rows($res_toko); ?></div>
            <div id="cov_jualcepat" class="produk-jualcepat d-flex row mt-2 pl-3 pb-2" style="width: 2450px;">
                <?php
                    while($produk_toko = mysqli_fetch_assoc($res_toko)):
                        $id_produk = $produk_toko['id_produk'];
                ?>
                <a href="detil_produk.php?id_produk=<?= $id_produk; ?>" class="text-dark mr-2" style="text-decoration: none;">
                    <div class="cart-produk-jualcepat d-flex flex-column text-center pb-2" style="width: 120px; position: relative;">
                        <!-- icon jual cepat -->
                        <div class="cov-img-cart d-flex justify-content-center align-items-center mb-2" style="height: 120px; width:120px; overflow:hidden;">
                            <img class="h-100" style="width: auto;" src="assets/foto/<?= ambil_1_data("gambar_produk","id_produk",$id_produk,"gambar"); ?>" alt="">
                        </div>
                        <!-- diskon --> 
                        <?php if(cekDiskon($produk_toko['id_produk'],"bool")) :?>
                            <div class="d-flex flex-column p-1" style="position: absolute; top:0; right:0; background:gold; border-bottom-left-radius:10px;">
                            <b class="text-danger"><?= cekDiskon($produk_toko['id_produk'],"diskon");?>%</b>
                            <b class="text-white">OFF</b>
                            </div>
                        <?php endif; ?>
                        <!-- nama  -->
                        <div class="text-left" style="font-size: small; height:50px;">
                            <?= $produk_toko['nama_produk']; ?>
                        </div>
                        <!-- harga -->
                        <b class="text-left" style="color: darkcyan; font-size:14px; font-weight:bold;" >
                            <?php
                                if(cekDiskon($id_produk,"bool")){
                                    echo rupiah(cekDiskon($id_produk,"harga_produk_diskon"));
                                }else{
                                    echo rupiah($produk_toko['harga_produk']);
                                }
                            ?>
                        </b>
                    </div>
                </a>
                    
               
                <?php endwhile; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>


    <!-- beli yang lain juga -->
    <div class="container bg-white p-3 mt-2">
        <span class="text-dark" style="font-weight: bold;">Produk yang serupa</span>
    </div>
    <div class="d-flex flex-row justify-content-center">
    <div class="container d-flex row justify-content-around bg-white px-0">
        <?php
            // ambil nama produk dari produk saat ini
            $id_prod = $_GET['id_produk'];
            $nama = ambil_1_data("produk", "id_produk", $id_prod, "nama_produk");
            $kategori = ambil_1_data("produk", "id_produk", $id_prod, "kategori_produk");
            $nama_arr = explode(" ", $nama);
            $nama1 = $nama_arr[0];
            $nama2 = $nama_arr[1];
            $nama_1_2 = $nama1." ".$nama2;
            // tampil produk serupa dengan full name
            $res_fullname = mysqli_query($con, "SELECT * FROM produk WHERE NOT id_produk = '$id_prod' AND nama_produk LIKE '%$nama%' OR nama_produk LIKE '%$nama_1_2%' OR nama_produk LIKE '%$nama1%' OR nama_produk LIKE '%$nama2%'  LIMIT 20");
            if(mysqli_num_rows($res_fullname) > 0):
            while($prod1 = mysqli_fetch_assoc($res_fullname)):
                $id_produk1 = $prod1['id_produk'];
        ?>
            <div class="card d-inline-block border-0 mb-2" style="width:160px; border-radius: 3px;">
                <!-- gambar -->
                <div class="w-100 d-flex justify-content-center align-items-center mb-2" style="overflow: hidden; height:160px;">
                    <img class="h-100" src="assets/foto/<?= ambil_1_data("gambar_produk","id_produk",$id_produk1,"gambar"); ?>" alt="">
                </div>
                <!-- diskon --> 
                <?php if(cekDiskon($id_produk1,"bool")) :?>
                    <div class="d-flex flex-column p-1" style="position: absolute; top:0; right:0; background:gold; border-bottom-left-radius:10px;">
                    <b class="text-danger"><?= cekDiskon($id_produk1,"diskon");?>%</b>
                    <b class="text-white">OFF</b>
                    </div>
                <?php endif; ?>
                <!-- nama -->
                <span class="mx-2 d-block mb-2" style="line-height: 14px; font-size:small; height:30px;">
                    <?= $prod1['nama_produk']; ?>
                </span>
                <!-- harga -->
                <div class="mx-2 mb-2" style="color: darkcyan;">
                <?php
                    if(cekDiskon($id_produk1,"bool")){
                        echo rupiah(cekDiskon($id_produk1,"harga_produk_diskon"));
                    }else{
                        echo rupiah($prod1['harga_produk']);
                    }
                ?>
                </div>
                <div class="d-flex justify-content-between align-items-center mx-2">
                    <div class="text-secondary" style="font-size: small; ">
                    <?= cekTerjual($id_produk1); ?>
                    terjual</div>
                </div>
            </div>
            <?php endwhile; ?>
            <?php else: 
                $res_fullname1 = mysqli_query($con, "SELECT * FROM produk WHERE kategori_produk = '$kategori' AND id_produk != '$id_prod' LIMIT 20");
                 while($prod2 = mysqli_fetch_assoc($res_fullname1)):
                    $id_produk2 = $prod2['id_produk'];    
            ?>
                <div class="card d-inline-block border-0 mb-2" style="width:160px; border-radius: 3px;">
                <!-- gambar -->
                <div class="w-100 d-flex justify-content-center align-items-center mb-2" style="overflow: hidden; height:160px;">
                    <img class="h-100" src="assets/foto/<?= ambil_1_data("gambar_produk","id_produk",$id_produk2,"gambar"); ?>" alt="">
                </div>
                <!-- diskon --> 
                <?php if(cekDiskon($id_produk2,"bool")) :?>
                    <div class="d-flex flex-column p-1" style="position: absolute; top:0; right:0; background:gold; border-bottom-left-radius:10px;">
                    <b class="text-danger"><?= cekDiskon($id_produk2,"diskon");?>%</b>
                    <b class="text-white">OFF</b>
                    </div>
                <?php endif; ?>
                <!-- nama -->
                <span class="mx-2 d-block mb-2" style="line-height: 14px; font-size:small; height:30px;">
                    <?= $prod2['nama_produk']; ?>
                </span>
                <!-- harga -->
                <div class="mx-2 mb-2" style="color: darkcyan;">
                <?php
                    if(cekDiskon($id_produk2,"bool")){
                        echo rupiah(cekDiskon($id_produk2,"harga_produk_diskon"));
                    }else{
                        echo rupiah($prod2['harga_produk']);
                    }
                ?>
                </div>
                <div class="d-flex justify-content-between align-items-center mx-2">
                    <div class="text-secondary" style="font-size: small; ">
                    <?= cekTerjual($id_produk2); ?>
                    terjual</div>
                </div>
            </div>
            <?php endwhile; ?>
            <?php endif; ?>
    </div>
    </div>


    
    
</div>



<!------------------ POPPER ------------>
    <!-- Modal Menu -->
    <div class="modal fade" id="popmenu" tabindex="-1" aria-labelledby="popmenu" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content p-2">
                <div class="d-flex flex-column justify-content-between">
                    <a href="" onclick="copy()" class="btn text-dark text-left p-3 border-bottom">
                        <span class="icon-share-alt mr-3"></span>
                        <span>Salin tautan</span>
                    </a>
                    <a href="lapor.php?id_produk=<?= $_GET['id_produk']; ?>" class="btn text-dark text-left p-3">
                        <span class="icon-fa mr-3"></span>
                        <span>Laporkan produk ini</span>
                    </a>
                    <input type="text" id="box-url" class="form-control text-dark border-0">
                </div>
            </div>
        </div>



<script src="assets/bootstrap/jquery/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.js"></script>
<script src="assets/bootstrap/popper/popper.js"></script>

<!-- lebar responsive pada produk promo -->
<script>
    $("#cov_jualcepat").css("width", Number($("#jml_jualcepat").text())*150+"px");
</script>


<!-- copy link url -->
<script>
function copy(){
    var inputC = document.getElementById("box-url");
    inputC.value = window.location.href;
    inputC.focus();
    inputC.select();
    document.execCommand("copy");
    console.log(inputC.value);
    alert("Link telah disalin");
}
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