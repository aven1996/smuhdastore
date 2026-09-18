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
    if($stt_akun == "pembeli")
    {
        header("Location: admin_pembeli.php");
    }
    elseif($stt_akun == "owner")
    {
        header("Location: admin_owner.php");
    }
    
    // HAPUS PRODUK
    if(isset($_POST['hapus_produk'])){
        $id = $_POST['id_produk'];
        echo "<script>
                var del = confirm('PERINGATAN: Produk yang dihapus akan mempengaruhi riwayat transaksi dan rician penghasilan! Apakah kamu yakin akan menghapus produk in?');
                if(del == true){
                    window.location.href = 'admin_penjual.php?del=$id';
                }else{
                    window.location.href = 'admin_penjual.php';
                }
            </script>";
    }
    if(isset($_GET['del'])){
        $id = $_GET['del'];
        mysqli_query($con, "DELETE FROM produk WHERE id_produk = '$id' ");
        header("Location: admin_penjual.php");
    }
}
 


?>

<!DOCTYPE html>
<html lang="en"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penjual - Smuhdastore Belaja Nyaman dan Terpercaya</title>
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
        .diskon{
            position: absolute;
            right: 0;
            top: 0;
            background-color: gold;
            border-bottom-left-radius: 5px;
        }
        .diskon b{
            font-weight: bold;
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
                <img class="h-100" src="assets/foto/<?= $poto; ?>" alt="<?= $poto; ?>" title="<?= $poto; ?>"> 
            <?php
                    
                else:
            ?>
                <img class="w-100" src="assets/img/poto-profil.png" alt="poto-profil.png">

            <?php endif; ?>

        </div>
        <!-- nama dan rating -->
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

            <!-- rating -->
            <div class="rating d-flex flex-row align-items-center">
            <!-- jika id belum punya produk -->
            <?php 
                $res = mysqli_query($con, "SELECT * FROM produk WHERE id_login = '$id_akun'");
                if(mysqli_num_rows($res) == 0):
            ?>
                    <?php for($z = 0; $z < 5; $z++): ?>
                        <b class="icon-star-full" style="color: silver; font-size: 12pt;"></b>
                    <?php endfor; ?>

            <?php else: ?>
                <!-- jika id sudah ada produk -->
                <?php
                $result_transaksi = mysqli_query($con, "SELECT AVG(penilaian) AS rating FROM produk_transaksi WHERE id_penjual = '$id_akun' AND stt_transaksi = 'selesai' GROUP BY id_penjual ");
                if(mysqli_num_rows($result_transaksi) > 0):

                    $rating = mysqli_fetch_assoc($result_transaksi)['rating'];
                ?>
                    <?php if(!empty($rating)): ?>
                        <?php for($z = 0; $z < $rating; $z++): ?>
                            <b class="icon-star-full" style="color: gold; font-size: 12pt;"></b>
                        <?php endfor; ?>
                        <?php for($z = 0; $z < 5 - $rating; $z++): ?>
                            <b class="icon-star-full" style="color: silver; font-size: 12pt;"></b>
                        <?php endfor; ?>
                    <?php else: ?>
                        <?php for($z = 0; $z < 5; $z++): ?>
                            <b class="icon-star-full" style="color: silver; font-size: 12pt;"></b>
                        <?php endfor; ?>
                    <?php endif; ?>
                <?php else: ?>
                        <?php for($z = 0; $z < 5; $z++): ?>
                            <b class="icon-star-full" style="color: silver; font-size: 12pt;"></b>
                        <?php endfor; ?>
                <?php endif; ?>    
            <?php endif; ?>

                <span class="text-secondary" style="font-size: small; margin-left:5px;">
                <?php 
                    $produk = mysqli_query($con, "SELECT * FROM produk WHERE id_login = '$id_akun'");
                    if(mysqli_num_rows($produk) > 0):
                ?>
                    (<?= mysqli_num_rows($produk); ?> Produk)
                <?php else: ?>
                    (0 Produk)
                <?php endif; ?>
                </span>
            </div>
        </div>
        <!-- tombol lihat toko -->
        <a href="lihat_toko.php?id_penjual=<?= $id_akun; ?>" class="ml-3 p-1 rounded" style="border:1px solid darkcyan; color: darkcyan;">Lihat Toko</a>
        
    </div>
    
    <!-- tombol jual -->
    <div class="d-flex flex-row justify-content-center align-items-center">
        <a href="tambah_produk.php" class="w-100"><button class="w-100 btn p-2 text-white mb-2" style="background: darkcyan;">MULAI JUAL PRODUK</button></a>
    </div>
    <!-- penjualan saya -->
    <div class="aktifitas mb-2">
        <div class="title d-flex align-items-center bg-white py-2 px-3 border-bottom">
            <b class="icon-medal mr-3" style="color:darkcyan;"></b>
            <span>Penjualan Saya</span>
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
    <!-- penghasilan saya -->
    <div class="aktifitas mb-2">
        <div class="cov-title d-flex justify-content-between lign-items-center bg-white py-2 px-3 border-bottom">
            <div class="title d-flex align-items-center">
                <b class="icon-dollar mr-3" style="color: darkcyan;"></b>
                <span>Penghasilan Saya</span>
            </div>
            <a href="rincian_penghasilan.php" style="color: darkcyan;"><div>Lihat Rincian</div></a>
        </div>
        <div class="d-flex justify-content-center w-100 bg-white py-3 px-1" style="font-size: 48px; color: darkcyan; font-weight: bold;">
            <span>

            <?php
                // query total penghasilan penjual
                $result = mysqli_query($con, "SELECT id_transaksi, id_penjual, SUM(harga_peritem * jml_pembelian) AS penghasilan FROM produk_transaksi INNER JOIN produk ON produk_transaksi.id_produk = produk.id_produk WHERE id_penjual = '$id_akun' AND stt_transaksi = 'selesai' GROUP BY id_penjual");
                
                if(mysqli_num_rows($result) > 0)
                {
                    $row_penghasilan = mysqli_fetch_assoc($result);
                    echo "Rp".number_format($row_penghasilan['penghasilan'],0,'.','.');
                }
                else
                {
                    echo "Rp0";
                }
            ?>
            </span>
        </div>
    </div>
    <!-- promosi saya -->
    <div class="aktifitas mb-2">
        <div class="title d-flex align-items-center bg-white py-2 px-3 border-bottom">
            <b class="icon-ticket mr-3" style="color: darkcyan;"></b>
            <span>Promosi Saya</span>
        </div>
        <div class="isi-aktifitas d-flex justify-content-around bg-white">
            
            <a href="penjual_diskon.php" class="text-dark">
            <span class="d-flex flex-column justify-content-center">
                <img src="assets/icons/flaticon/003-signboard.svg" alt="" >
                <span class="d-block">Diskon</span>
            </span>
            </a>
         
            <a href="penjual_jualcepat.php" class="text-dark">
            <span class="d-flex flex-column justify-content-center">
                <img src="assets/icons/flaticon/004-shopping bag.svg" alt="" >
                <span class="d-block">Jual Cepat</span>
            </span>
            </a>

            <a href="error_page.php" class="text-dark">
            <span class="d-flex flex-column justify-content-center">
                <img src="assets/icons/flaticon/020-qr code.svg" alt="" >
                <span class="d-block">Voucher</span>
            </span>
            </a>
        </div>
    </div>

    <!-- produk saya -->
    <div id="produk_saya" class="aktifitas bg-white" style="padding-bottom:100px;">
        <div class="cov-title d-flex justify-content-between align-items-center bg-white py-2 px-3 border-bottom">
            <div class="title d-flex align-items-center">
                <b class="icon-box mr-3" style="color:darkcyan;"></b>
                <span>Produk Saya</span>
            </div>
            <a href="tambah_produk.php" style="color:darkcyan;">Tambah Produk</a>
        </div>
        <div class="d-flex justify-content-center">
        <div class="d-flex flex-wrap justify-content-start bg-white w-100 pt-2 px-4">
        <?php
            $ambilProduk = mysqli_query($con, "SELECT * FROM produk WHERE id_login = '$id_akun' ");
            if(mysqli_num_rows($ambilProduk) > 0):
                while ($row = mysqli_fetch_assoc($ambilProduk)) :
        ?>
            
            <div class="d-inline-block m-2" style="width: 160px; height: 290px; position:relative;">
            <a href="detil_produk.php?id_produk=<?= $row['id_produk'];?>" style="display: block;">
                <!-- diskon --> 
                <?php if(cekDiskon($row['id_produk'],"bool")): ?>
                    <div class="diskon d-flex flex-column p-2 text-danger">
                    <b><?= cekDiskon($row['id_produk'],"diskon"); ?>%</b>
                    <b class="text-white">OFF</b>
                    </div>
                <?php endif; ?>

                <div class="d-flex flex-column justify-content-center align-items-center overflow-hidden w-100" style="height: 150px; width:150px; overflow: hidden;">
                <?php
                   // ambil gambar 
                    $id_produk = $row['id_produk'];
                    $result_gambar = mysqli_query($con, "SELECT gambar FROM gambar_produk WHERE id_produk = '$id_produk' ");
                    $h = mysqli_fetch_assoc($result_gambar);
                ?>
                    <img src="assets/foto/<?= $h['gambar']; ?>" alt="" class="h-100">
                </div>
                <span class="d-block text-left text-dark pt-2 w-100" style="font-size: small; line-height: 18px; height:40px;">
                    <?php
                        if(strlen($row['nama_produk']) > 40){
                            echo substr($row['nama_produk'],0,37).".."; 
                        }
                        else{
                            echo $row['nama_produk']; 
                        } 
                    ?>
                </span>
                <span class="d-block text-left py-0 mt-2 w-100" style="font-size: 16px; line-height:18px; color: darkcyan;">
                <b>
                    <?php if(cekDiskon($id_produk, "bool")){
                        echo rupiah(cekDiskon($id_produk, "harga_produk_diskon"));
                    }else{
                        echo rupiah($row['harga_produk']);
                    }
                    ?>
                </b>
                </span>
                <span class="d-block text-right pt-0 w-100 text-secondary" style="font-size: small; line-height: 14px; text-align:right;"><?= cekTerjual($id_produk); ?> Terjual</span>
            </a>
            
                <form action="" method="POST" class="d-inline-block w-100">
                    <input type="hidden" name="id_produk" value="<?= $id_produk; ?>">
                    <button type="submit" name="hapus_produk" class="btn btn-danger w-100 mt-2" style="border-radius: 0;">Hapus</button>
                </form>
            </div>
            
            
            <?php endwhile; ?>
        <?php else: ?>
            <span class="text-secondary p-2 pb-5" style="font-size: small;">Belum ada produk</span>
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