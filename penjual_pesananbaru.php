
<?php
    require "inc/koneksi.php";
    require "inc/fungsi.php";
 
    session_start();
    if(isset($_SESSION['admin']))
    {
        $id_akun = $_SESSION['admin'];
        $stt_akun = $_SESSION['stt_akun'];
        $id_profil = ambil_1_data("profil","id_login",$id_akun,"id_profil");

        // jika penjual menekan proses pesanan
        if(isset($_POST['proses']))
        {
            // id transaksi
            $id = $_POST['id'];
            // cari id penjual dan pembeli dari tabel transaksi berdasarkan id transaksi
            $result = mysqli_query($con, "SELECT * FROM produk_transaksi WHERE id_transaksi = '$id'");
            $trans = mysqli_fetch_assoc($result);
            $id_pembeli = $trans['id_pembeli'];
            $id_penjual = $trans['id_penjual'];
            // insert ke lastseen transaksi jika id tidak ada, update stt jika masih ada
            // lakukan sesuatu pada notif penjual
            $resPenjual = mysqli_query($con, "SELECT * FROM lastseen_transaksi WHERE id_login = '$id_penjual' AND id_transaksi = '$id' AND stt_transaksi = 'pesan'");
            if(mysqli_num_rows($resPenjual) > 0){
                $id_lastseen = mysqli_fetch_assoc($resPenjual)['id_lastseen'];
                mysqli_query($con, "UPDATE lastseen_transaksi SET stt_transaksi = 'proses' WHERE id_lastseen = '$id_lastseen'");
            }else{
                mysqli_query($con, "INSERT INTO lastseen_transaksi VALUES('','$id_penjual','$id','proses')");
            }
            // lakukan sesuatu pada notig pembeli
            $resPembeli = mysqli_query($con, "SELECT * FROM lastseen_transaksi WHERE id_login = '$id_pembeli' AND id_transaksi = '$id' AND stt_transaksi = 'pesan'");
            if(mysqli_num_rows($resPembeli) > 0){
                $id_lastseen = mysqli_fetch_assoc($resPembeli)['id_lastseen'];
                mysqli_query($con, "UPDATE lastseen_transaksi SET stt_transaksi = 'proses' WHERE id_lastseen = '$id_lastseen'");
            }else{
                mysqli_query($con, "INSERT INTO lastseen_transaksi VALUES('','$id_pembeli','$id','proses')");
            }

            // pergi ke proses pesanan dan lakukan update pada tabel produk_transaksi
            header("Location: penjual_proses.php?id=$id");
        }

 
        // hapus lastseen saat halaman ini dimuat jika notif baru ada
        if(cekUpdateTransaksi($id_akun, "id_login", "pesan", "update") != 0){
            cekUpdateTransaksi($id_akun, "id_login", "pesan", "seen");
        }
        
    }
    else
    {
        header("Location: login.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penjualan Saya - Smuhdastore Belaja Nyaman dan Terpercaya</title>
    <link rel="shortcut icon" href="assets/img/fav.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css">
    
    <link rel="stylesheet" href="assets/icons/icomoon4/style.css">
    <link rel="stylesheet" href="assets/icons/icomoon5/style.css">
    <link rel="stylesheet" href="assets/icons/icomoon1/icon.css">
    <link rel="stylesheet" href="assets/icons/icomoon2/icon2.css">
    <link rel="stylesheet" href="assets/icons/icomoon3/style.css">
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
        .formX {
            width: 600px;
        }
    </style>
</head>
<body>

<div class="container d-flex flex-column px-0">
    <!-- navigasi -->
    <div class="nav bg-white d-flex justify-content-between align-items-center px-3 py-3 ">
        <span style="color:seagreen; font-size: 24px;" onclick="window.history.back();"><b class="icon-arrow-left2 mr-2"></b><span class="text-dark">Pesanan Baru</span></span>
    </div>
    <!-- produk pesanan -->
    <div class="p-2" style="margin-bottom: 60px;">
    <?php
        if($stt_akun == "penjual"){
            $result_transaksi = mysqli_query($con, "SELECT * FROM produk_transaksi INNER JOIN produk ON produk_transaksi.id_produk = produk.id_produk INNER JOIN akun_login ON produk_transaksi.id_penjual = akun_login.id_login WHERE id_penjual = '$id_akun' AND stt_transaksi = 'pesan' ORDER BY id_transaksi DESC");
        }elseif($stt_akun == "pembeli"){
            $result_transaksi = mysqli_query($con, "SELECT * FROM produk_transaksi INNER JOIN produk ON produk_transaksi.id_produk = produk.id_produk INNER JOIN akun_login ON produk_transaksi.id_penjual = akun_login.id_login WHERE id_pembeli = '$id_akun' AND stt_transaksi = 'pesan' ORDER BY id_transaksi DESC");
        }
        
        if(mysqli_num_rows($result_transaksi) > 0):
            while($transaksi = mysqli_fetch_assoc($result_transaksi)):
    ?>
    <div class="d-block bg-white p-2 mb-2 ">
        <div class="d-flex justify-content-start align-items-center mb-2">
            <div class="d-flex justify-content-center align-items-center rounded-circle mr-2" style="width: 30px; height:30px; overflow:hidden;">
            <?php
            if($stt_akun == "penjual"){
                // ambil poto dari profil
                $id_pembeli = $transaksi['id_pembeli'];
                $result_poto = mysqli_query($con, "SELECT foto_profil FROM profil WHERE id_login = '$id_pembeli'");
                $poto = mysqli_fetch_assoc($result_poto);
                if(!empty($poto['foto_profil'])):
            ?>
                    <img class="h-100" src="assets/foto/<?= $poto['foto_profil']; ?>" alt="">
                <?php else: ?>
                    <img class="h-100" src="assets/img/poto-profil.png" alt="">
                <?php endif; ?>
            </div>
            <span><b><?= $transaksi['nama_pembeli']; ?></b></span>
            <?php }else{ 
                // ambil poto dari profil
                $id_penjual = $transaksi['id_penjual'];
                $result_poto = mysqli_query($con, "SELECT foto_profil FROM profil WHERE id_login = '$id_penjual'");
                $poto = mysqli_fetch_assoc($result_poto);
                if(!empty($poto['foto_profil'])):
            ?>
                    <img class="h-100" src="assets/foto/<?= $poto['foto_profil']; ?>" alt="">
                <?php else: ?>
                    <img class="h-100" src="assets/img/poto-profil.png" alt="">
                <?php endif; ?>
            </div>
            <span><b><?= ambil_1_data("akun_login","id_login",$id_penjual,"username"); ?></b></span>

            <?php } ?>
        </div>
        <!-- alamat -->
        <?php if($stt_akun == "penjual"): ?>
        <div class="mb-2 text-secondary pb-2 border-bottom" style="line-height: 18px; font-size:small;">Alamat Pembeli:<br> <?= $transaksi['alamat_pembeli']; ?></div>
        <?php endif; ?>

        <div class="d-flex justify-content-start align-items-top mb-2">
            <div class="d-flex flex-row justify-content-center align-items-center" style="width: 100px; height: 100px; overflow:hidden;">
            <?php
            // ambil gambar 
                $id_produk = $transaksi['id_produk'];
                $result_gambar = mysqli_query($con, "SELECT gambar FROM gambar_produk WHERE id_produk = '$id_produk' ");
                $h = mysqli_fetch_assoc($result_gambar);

            ?>
                <img class="w-100" src="assets/foto/<?= $h['gambar']; ?>" alt="">
            </div>
            <div class="d-flex flex-column justify-content-between p-2 w-75">
                <div><?= $transaksi['nama_produk']; ?></div>
                <div class="text-secondary" style="font-size: small;">Model Transaksi: Ketemu Langsung</div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-dark"><b><?= rupiah($transaksi['harga_peritem']); ?></b></div>
                    <div class="text-secondary">x<?= $transaksi['jml_pembelian']; ?></div>
                </div>
            </div>
        </div>
        <!-- total pembayaran -->
        <div class="p-2 border-top border-bottom text-right mb-2">
            <span>Total Pembayaran: </span><b class="text-danger"><?= rupiah($transaksi['harga_peritem']*$transaksi['jml_pembelian']); ?></b>
        </div>
        <!-- chat wa -->
        <div class="d-flex flex-row">
            <?php if($stt_akun == "penjual"): ?>
                <a href="
                    <?php
                        if($transaksi['id_pembeli'] == 11){
                            echo wa_nologin($transaksi['id_transaksi'],"Apakah kamu memesan ini? Terimakasih pesanan akan kami proses",$url_full); 
                        }else{
                            echo wa($id_pembeli,"Apakah kamu memesan ini? Terimakasih pesanan akan kami proses",$url_full); 
                        }
                        
                    ?>
                    " class="w-50 p-2 d-flex justify-content-center align-items-center bg-success text-white" style="text-decoration: none;"><b class="icon-whatsapp mr-2"></b><span>CHAT PEMBELI</span></a>
            <?php elseif($stt_akun == "pembeli"): ?>
                <a href="<?= wa($id_penjual,"Produk ini sudah saya pesan. Tolong segera diproses",$url_full); ?>" class="w-50 p-2 d-flex justify-content-center align-items-center bg-success text-white" style="text-decoration: none;"><b class="icon-whatsapp mr-2"></b><span>CHAT PENJUAL</span></a>
            <?php endif; ?>

            <?php if($stt_akun == "penjual"): ?>
                <form action="" method="post" class="w-50">
                    <input type="hidden" name="id" value="<?= $transaksi['id_transaksi']; ?>">
                    <button type="submit" name="proses" class="w-100 p-2 d-flex border-0  justify-content-center align-items-center text-white bg-danger" style="text-decoration: none;">PROSES PESANAN</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
        <?php  endwhile; ?>
    <?php  else: ?>
    <span class="d-inline-block w-100 text-secondary text-center" style="font-size: small;">Belum ada pesanan baru nih, tetap semangat ya!</span>
    <?php endif; ?>
    </div>
   
</div>
<!-- Menu Fixed Bawah -->
    <div id="navBottom">
        <div class="nav-bottom fixed-bottom bg-white py-2 px-3 d-flex flex-row justify-content-between align-items-center" style="display: flex; box-shadow: 0px -5px 10px rgba(0,0,0,0.05);">
            <a href="index.php" class="text-dark w-25" style="text-decoration: none;"><div class="d-flex flex-column justify-content-center align-items-center px-2 py-1 ">
                <b class="icon-home" style="font-size: 30px; color: seagreen;"></b>
                <span style="font-size:small;">Beranda</span>
            </div></a>
            <a href="favorit.php" class="text-dark w-25 " style="text-decoration: none; "><div class="d-flex flex-column justify-content-center align-items-center px-2 py-1 position-relative">
                <b class="icon-heart" style="font-size: 30px; color: seagreen;"></b>
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
                <b class="icon-cart" style="font-size: 30px; color: seagreen;"></b>
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
            <a href="admin_penjual.php" class="text-dark w-25 rounded" style="text-decoration: none; background: seagreen;"><div class="d-flex flex-column justify-content-center align-items-center px-2 py-1">
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