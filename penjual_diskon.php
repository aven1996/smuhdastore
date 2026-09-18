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
    
    // tambah diskon/harga promo
    if(isset($_POST['tambah_diskon'])){
        $id_produk = $_POST['id_produk'];
        $diskon = $_POST['diskon'];
        $harga = $_POST['harga_diskon_fix'];

        if($diskon != 0){
            //ambil data dari table produk diskon untuk mengecek apakah sudah ada diskon untuk produk ini
            $result = mysqli_query($con, "SELECT * FROM produk_diskon WHERE id_produk = '$id_produk'");
            if(mysqli_num_rows($result) > 0){
                mysqli_query($con, "UPDATE produk_diskon SET diskon = '$diskon', harga_produk_diskon = '$harga' WHERE id_produk = '$id_produk'");
                header("Location: penjual_diskon.php");
            }else{
                mysqli_query($con, "INSERT INTO produk_diskon VALUES('','$id_akun','$id_produk','$diskon','$harga')");
                header("Location: penjual_diskon.php");
            }
            
        }
    }


    // hapus diskon
    if(isset($_POST['hapus'])){
        $id = $_POST['id'];
        mysqli_query($con, "DELETE FROM produk_diskon WHERE id_diskon = '$id'");
        header("Location: admin_penjual.php");

    }


}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promo - Smuhdastore Belaja Nyaman dan Terpercaya</title>
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
        .produkrekomendasi .more{
            font-size: 14px;
            color: #929292;
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
        .formX {
            width: 600px;
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
    <!-- navigasi -->
    <div class="nav bg-white d-flex justify-content-between align-items-center px-3 py-3 mb-2">
        <a href="admin_penjual.php" style="color:darkcyan; font-size: 24px; text-decoration:none;">
            <b class="icon-arrow-left2 mr-2"></b>
            <span class="text-dark">Produk Diskon</span>
        </a>
    </div>
     
    <!-- produk -->
    <div class="bg-white p-2 px-3">
        <span>Produk</span>
    </div>
    <div class="bg-white d-flex align-items-center p-3 mb-2 pb-3">
        <div id="btn_pilih_produk" class="d-flex flex-row justify-content-start align-items-center" style="overflow: hidden;">
            <div class="d-flex flex-column justify-content-center align-items-center mr-1" style="width: 100px; height: 100px; border:1px dashed darkcyan; border-radius: 5px; font-size:small; color:darkcyan;">
            <?php 
                if(isset($_GET['id'])): 
                $id = $_GET['id'];
                $img = mysqli_query($con, "SELECT gambar FROM gambar_produk WHERE id_produk = '$id' ");
                $img = mysqli_fetch_assoc($img);
            ?>
                <img class="w-100" src="assets/foto/<?= $img['gambar']; ?>" alt="">
                
            <?php 
                else:    
            ?>
                <span class="icon-plus" style="font-size: 22px;"></span>
                <span>Pilih Produk</span>
                
            <?php endif; ?>
            </div>
            <div class="d-flex flex-column">
                <?php if(isset($_GET['id'])): ?>
                    <span><?= ambil_1_data("produk","id_produk","$id","nama_produk")?></span>
                <?php endif; ?>
                
                <?php
                    if(isset($_GET['id'])):
                        $result = mysqli_query($con, "SELECT * FROM produk_diskon WHERE id_produk = '$id'");
                         if(mysqli_num_rows($result) > 0):
                             $row = mysqli_fetch_assoc($result);
                ?>
                            <span id="tampilan_aja" style="color: darkcyan; font-weight:bold; ">Rp<?= $row['harga_produk_diskon'];?></span>
                    <?php else: ?>
                            <span id="tampilan_aja" style="color: darkcyan; font-weight:bold; ">Rp<?= ambil_1_data("produk","id_produk","$id","harga_produk");?></span>
                    
                    <?php endif; ?>
                <?php endif; ?>

            </div>
        </div>
    </div>
    

    <form action="" method="post">
    <!-- Diskon -->
    <input type="hidden" id="val_id" name="id_produk" value="<?php if(isset($_GET['id'])){echo $_GET['id']; } ?>">
    <div class="bg-white p-2 px-3">
        <span>Diskon</span>
    </div>
    <div class="bg-white d-flex align-items-center mb-2 pb-3">
        <input id="diskon" type="text" name="diskon" class="form-control border-0 px-3" placeholder="% Diskon" value="<?php 
            if(isset($_GET['id'])){
                $result = mysqli_query($con, "SELECT * FROM produk_diskon WHERE id_produk = '$id'");
                if(mysqli_num_rows($result) > 0){
                    $row = mysqli_fetch_assoc($result);
                    echo $row['diskon'];
                }else{
                    echo 0;
                }
            } ?>">
       
    </div>

    <!-- Harga Promo -->
    <div style="display: none;">
        <span><b>Harga Promo</b></span>
    </div>
    <div style="display: none;">
        <input id="harga_diskon" type="hidden" name="harga_diskon" class="form-control border-0 px-3" placeholder="Rp" value="<?php if(isset($_GET['id'])){echo ambil_1_data("produk","id_produk","$id","harga_produk");}?>">
        <input id="harga_diskon_tampil" type="text" name="harga_diskon_fix" class="form-control border-0 px-3" placeholder="Rp" value="<?php if(isset($_GET['id'])){echo ambil_1_data("produk","id_produk","$id","harga_produk");}?>">
    </div>
    
    <button type="submit" name="tambah_diskon" class="btn w-100 text-white mb-2" style="background-color: darkcyan;">Simpan</button>
    </form>

    <!-- Produk Promo -->
    <div class="produkrekomendasi container bg-white py-2 px-0">
        <div class="bg-white p-2 px-3">
            <span>Produk yang lagi promo</span>
        </div>
 
        <div class="d-flex flex-row justify-content-center">
            <div class=" w-100 produk-jualcepat row mt-2 pl-0 justify-content-around">

            <?php
                $result_produk = mysqli_query($con, "SELECT * FROM produk_diskon WHERE id_penjual = '$id_akun'");
                if(mysqli_num_rows($result_produk) > 0):
                while($produk = mysqli_fetch_assoc($result_produk)) : 
                $id_produk = $produk['id_produk'];
            ?> 
            <!-- card -->
            <div class="cart-produk-jualcepat d-block pb-2" style="width: 160px;">
                <a href="detil_produk.php?id_produk=<?= $id_produk; ?>" class="text-dark" style="text-decoration: none;">
                <!-- diskon --> 
                <div class="diskon d-flex flex-column">
                   <b><?= $produk['diskon']; ?>%</b>
                   <b class="text-white">OFF</b>
                </div>
                
                <!-- image -->
                <div class="cov-img-cart d-flex align-items-center">
                    <img src="assets/foto/<?= ambil_1_data("gambar_produk","id_produk","$id_produk","gambar"); ?>" alt="">
                </div>
                <!-- nama produk -->
                <span class="nama_produk"><?= ambil_1_data("produk","id_produk","$id_produk","nama_produk"); ?></span>
                <!-- harga -->
                <b class="harga" style="color: darkcyan;"><?= rupiah($produk['harga_produk_diskon']); ?></b>
                </a>
                <!-- button hapus -->
                <form action="" method="post">
                    <input type="hidden" name="id" value="<?= $produk['id_diskon'];?>">
                    <button type="submit" name="hapus" class="d-block w-100 btn btn-danger text-white" style="border-top-left-radius:0; border-top-right-radius:0;">Hapus</button>
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


<!-- popup pilih produk -->
<div id="popup_pilih_produk" style="position: fixed; top:0; left:0; right:0; bottom:0; background:white; display:none;">
<a href="penjual_diskon.php" class="d-inline-block mx-3 my-2" style="color:darkcyan; font-size: 24px; text-decoration:none;">
    <b class="icon-arrow-left2 mr-2"></b>
    <b style="color: black; font-weight:normal; font-size: 20px;">Pilih Produk</b>
</a>
<div class="d-flex justify-content-center align-items-center bg-white border-top pb-3">
    
    <div class="d-flex row justify-content-center align-items-center">
    <?php 
        $result = mysqli_query($con, "SELECT * FROM produk WHERE id_login = '$id_akun' ");
        if(mysqli_num_rows($result) > 0):
    ?>
        <?php while($i = mysqli_fetch_assoc($result)): ?>
            <a style="width:150px; height:300px; color:black; overflow:hidden; position: relative;" class="m-2" href="penjual_diskon.php?id=<?= $i['id_produk']; ?>">
                <!-- diskon --> 
                <?php if(cekDiskon($i['id_produk'],"bool")): ?>
                <div class="diskon d-flex flex-column p-2 text-danger">
                   <b><?= cekDiskon($i['id_produk'],"diskon"); ?>%</b>
                   <b class="text-white">OFF</b>
                </div>
                <?php endif; ?>

                <div class="d-flex flex-row justify-content-center align-items-center mb-2" style="overflow:hidden; height:150px; width:150px;"> 
                    <?php 
                    $id_produk = $i['id_produk'];
                    $img = mysqli_query($con, "SELECT gambar FROM gambar_produk WHERE id_produk = '$id_produk' ");
                    $img = mysqli_fetch_assoc($img);
                    ?>
                    <img class="h-100" src="assets/foto/<?= $img['gambar']; ?>" alt="">
                    
                </div>
                <span class="d-inline-block mb-2" style="line-height: 16px; height:40px; font-size:small;"><?= $i['nama_produk']; ?></span>
                <div><b style="color:darkcyan;">
                    <?php if(cekDiskon($i['id_produk'],"bool")): ?>
                        <?= rupiah(cekDiskon($i['id_produk'],"harga_produk_diskon")); ?>
                    <?php else: ?>
                        <?= rupiah($i['harga_produk']); ?>
                    <?php endif; ?>
                </b></div>
            </a>
        <?php endwhile; ?>
        <?php else: ?>
            <span class="text-secondary p-2 pb-5" style="font-size: small;">Belum ada produk</span>

        <?php endif; ?>
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

 <!-- memunculkan popup pilih produk -->
 <script>
    $('#btn_pilih_produk').click(function(){
        $('#popup_pilih_produk').css('display','block');
    });
 </script>


 <!-- hitung diskon dan harga promo saat diskon/harga promo diedit -->
 <script>
    var id = document.getElementById('val_id').value;

    if(id.length != 0){
        $('#diskon').keyup(function(){
            var harga = document.getElementById('harga_diskon').value;
            var diskon = document.getElementById('diskon').value;
            diskon_harga = (diskon / 100) * harga;
            harga_now = harga - diskon_harga;
            console.log(harga);
            console.log(diskon);
            console.log(harga_now);
            document.getElementById('harga_diskon_tampil').value = harga_now;
            $("#tampilan_aja").html("Rp"+harga_now);
            
        });

        // var harga_awal = document.getElementById('harga_diskon_tampil').value;
        // $('#harga_diskon').keyup(function(){
        //     var harga = document.getElementById('harga_diskon').value;
        //     var diskon = document.getElementById('diskon').value;
        //     diskon_baru = (harga / harga_awal) * diskon;
        //     document.getElementById('diskon_tampil').value = diskon_baru;
        // });
    }
    

 </script>
</body>
</html>