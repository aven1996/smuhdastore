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
    }else{
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
    <link rel="stylesheet" href="assets/icons/icomoon5/style.css">
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

<!-- jika klik tombol hapus maka akan muncul popup konfirmasi di bawah ini -->
<?php
if(isset($_GET['del'])){
    $id = $_GET['del'];

?>
<div class="position-fixed d-flex justify-content-center align-items-center" style="background: rgba(0, 0, 0, 0.6); -moz-backdrop-filter: blur(10px); backdrop-filter: blur(10px); top:0; right:0; bottom:0; left:0; z-index:9999;">
  <div class="d-flex flex-column justify-content-center bg-white text-secondary p-5 rounded shadow" style="max-width: 350px;">
        <h5 class="mb-4 text-center">Apakah kamu yakin ingin menghapus akun ini?</h5>
        <div class="d-flex justify-content-center">
            <a href="?del=<?= $id; ?>&ya" class="w-50 mr-1">
                <button class="btn btn-danger w-100 ">Ya</button>
            </a>
            <a href="?del=<?= $id; ?>&kembali" class="w-50">
                <button class="btn btn-primary w-100">Kembali</button>
            </a>
        </div>
  </div>  
</div>
<?php
}
?>


<!-- jika klik tombol reset maka akan muncul popup konfirmasi di bawah ini -->
<?php
if(isset($_GET['reset'])){
    $id = $_GET['reset'];

?>
<div class="position-fixed d-flex justify-content-center align-items-center" style="background: rgba(0, 0, 0, 0.6); -moz-backdrop-filter: blur(10px); backdrop-filter: blur(10px); top:0; right:0; bottom:0; left:0; z-index:9999;">
  <div class="d-flex flex-column justify-content-center bg-white text-secondary p-5 rounded shadow" style="max-width: 350px;">
        <h5 class="mb-4 text-center">Apakah kamu yakin ingin menreset password ini?</h5>
        
        <div class="d-flex justify-content-center mb-2">
            <a href="?reset=<?= $id; ?>&ya" class="w-50 mr-1">
                <button class="btn btn-danger w-100 ">Ya</button>
            </a>
            <a href="?reset=<?= $id; ?>&kembali" class="w-50">
                <button class="btn btn-primary w-100">Kembali</button>
            </a>
        </div>
        <span class="text-center">Password baru : 12345</span>
  </div>  
</div>
<?php
}
?>


<!-- proses delete atau reset password saat klik 'Ya' -->
<?php
    if(isset($_GET['del']) AND isset($_GET['ya'])){
        mysqli_query($con, "DELETE FROM akun_login WHERE id_login = '$id'");
        header("Location: data_penjual.php");
    }elseif(isset($_GET['del']) AND isset($_GET['kembali'])){
        header("Location: data_penjual.php");
    }


    if(isset($_GET['reset']) AND isset($_GET['ya'])){
        $password = password_hash('12345', PASSWORD_DEFAULT);
        mysqli_query($con, "UPDATE akun_login SET password = '$password' WHERE id_login = '$id'");
        header("Location: data_penjual.php");
    }elseif(isset($_GET['reset']) AND isset($_GET['kembali'])){
        header("Location: data_penjual.php");
    }

?>

<div class="container d-flex flex-column px-0" style="margin-bottom: 150px;">
    <!-- navigasi -->
    <div class="nav bg-white d-flex justify-content-between align-items-center px-3 py-3 ">
        <a href="admin_owner.php" style="color:darkcyan; font-size: 24px; text-decoration: none;" >
            <b class="icon-arrow-left2 mr-2"></b>
            <span class="text-dark">Data Pengguna</span>
        </a>
    </div>
    <div class="container bg-white p-2 mb-2">
        <?php
            if(isset($_GET['pembeli'])){
                $_SESSION['stt'] = "pembeli";
            }elseif(isset($_GET['penjual'])){
                $_SESSION['stt'] = "penjual";
            }else{
                if(!isset($_SESSION['stt'])){
                    $_SESSION['stt'] = "penjual";
                }
            }
            $stt = $_SESSION['stt'];
            // jika ada pencarian
            $key = "";
            if(isset($_GET['key']) AND !empty($_GET['key'])){
                $key = $_GET['key'];
                $key = "AND akun_login.username LIKE '%$key%' OR profil.nama_profil LIKE '%$key%' OR profil.alamat LIKE '%$key%'";
                $res = mysqli_query($con, "SELECT * FROM akun_login INNER JOIN profil ON akun_login.id_login = profil.id_login WHERE akun_login.username != 'unknown' $key ");
                $_SESSION['stt'] = "key";
            }else{
                 // query utama untuk menampilkan data
                $res = mysqli_query($con, "SELECT * FROM akun_login INNER JOIN profil ON akun_login.id_login = profil.id_login WHERE akun_login.stt_akun = '$stt' AND akun_login.username != 'unknown' ");
            }
           

            // query untuk menghitung saja
            $pembeli = mysqli_query($con, "SELECT * FROM akun_login WHERE stt_akun = 'pembeli' AND username != 'unknown' ");
            $penjual = mysqli_query($con, "SELECT * FROM akun_login WHERE stt_akun = 'penjual' AND username != 'unknown' ");
        ?>
        <div class="d-flex justify-content-start align-items-center mb-2 position-relative">
            <span class="nav d-inline-block p-2 text-center active" style="width:200px; border:none;">
            <form action="">
                <select name="" id="pengguna" class="form-control" style="font-size: small;">
                    <?php if(isset($_SESSION['stt']) AND $_SESSION['stt'] == "key"){ ?>
                    <option value="" selected>--</option>
                    <?php } ?>

                    <option value="penjual" 
                        <?php if(isset($_SESSION['stt']) AND $_SESSION['stt'] == "penjual"){
                            echo "selected";
                        }?>
                    >Penjual (<?= mysqli_num_rows($penjual); ?>)</option>
                    <option value="pembeli"
                        <?php if(isset($_SESSION['stt']) AND $_SESSION['stt'] == "pembeli"){
                            echo "selected";
                        }?>
                    >Pembeli (<?= mysqli_num_rows($pembeli); ?>)</option>
                    
                </select>
            </form>
            </span>
            <!-- form cari -->
            <form style="min-width: 65%; position:relative;" action="" method="get">
            <input type="text" name="key" class="form-control" placeholder="cari pengguna" value="<?php if(isset($_GET['key'])){ echo $_GET['key']; } ?>">
            <b class="icon-search position-absolute text-secondary" style="right: 10px; top:13px;"></b>
            </form>
        </div>
        <?php 
            
            if(mysqli_num_rows($res) > 0):
            while($akun = mysqli_fetch_assoc($res)):
                $id = $akun['id_login'];
        ?>
            <div class="p-2 pr-0 d-flex justify-content-start border-bottom ">
                <div class="w-75 d-flex">
                <div class="d-flex flex-row justify-content-center align-items-center mr-2 rounded-circle" style="width: 70px; height:70px; overflow:hidden;">
                    <?php if(!empty(ambil_1_data("profil","id_login","$id","foto_profil"))): ?>
                        <img class="h-100" src="assets/foto/<?= ambil_1_data("profil","id_login","$id","foto_profil"); ?>" alt="">
                    <?php else: ?>
                        <img class="h-100" src="assets/img/poto-profil.png" alt="">
                    <?php endif; ?>
                </div>
                <a href="<?php if($akun['stt_akun'] == "penjual"){ echo "lihat_toko.php?id_penjual=$id";}else{ echo "#"; } ?>" class="d-inline-block  text-dark">
                    <div class="d-flex flex-column w-100" style="line-height: 18px;">
                        <!-- nama akun -->
                        <span style="font-weight: bold;">
                            <?php if(!empty(ambil_1_data("profil","id_login","$id","nama_profil"))){
                                echo ambil_1_data("profil","id_login","$id","nama_profil");
                            }else{
                                echo $akun['username'];
                            } ?>
                        </span>

                        <!-- akun -->
                        <span style="color:darkcyan; font-size:small;">
                            <?= "@".$akun['username']; ?>
                        </span>

                        <!-- jumlah produk -->
                        <?php if($stt == "penjual"): ?>
                        <span class="text-secondary" style="font-size: small;">
                            <?php 
                                $jmlprod = mysqli_query($con, "SELECT * FROM produk WHERE id_login = '$id'");
                                echo mysqli_num_rows($jmlprod);
                            ?> Produk
                        </span>
                        <?php endif; ?>

                        <!-- alamat -->
                        <span style="font-size: small;">
                            <?php if(!empty(ambil_1_data("profil","id_login","$id","alamat"))){
                                echo cutString(ambil_1_data("profil","id_login","$id","alamat"),25);
                            }else{
                                echo "Lokasi tidak ditemukan";
                            }?>
                        </span>
                    </div>
                </a>
                </div>
                <div class="d-flex flex-row justify-content-center align-items-center text-white">
                    <a href="?reset=<?= $id; ?>" title="Reset Password" class="text-white"><b class="icon-loop p-3 bg-primary"></b></a>
                </div>
                <div class="d-flex flex-row justify-content-center align-items-center text-white">
                    <a href="?del=<?= $id; ?>" title="Hapus Akun" class="text-white"><b class="icon-bin p-3 bg-danger"></b></a>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?> 

        <span class="d-inline-block w-100 text-center">Belum ada data</span>

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

 <!-- pilih kategori pengguna -->
 <script>
    $("#pengguna").change(function(){
        if($("#pengguna").val() == "pembeli"){
            window.location.href = "data_penjual.php?pembeli";
        }else if($("#pengguna").val() == "penjual"){
            window.location.href = "data_penjual.php?penjual";
        }
    });
 </script>
</body>
</html>