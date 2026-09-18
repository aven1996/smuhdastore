<?php
    require "inc/koneksi.php";
    require "inc/fungsi.php";
    
    session_start();
    if(isset($_SESSION['admin']))
    {
        $id_akun = $_SESSION['admin'];
        $stt_akun = $_SESSION['stt_akun']; 

        // buat pesanan udah login dan dari cart/keranjang
        if(isset($_POST['buatpesanan'])){
            $result = mysqli_query($con, "SELECT * FROM keranjang INNER JOIN produk ON keranjang.id_produk = produk.id_produk INNER JOIN akun_login ON keranjang.id_pembeli = akun_login.id_login WHERE id_pembeli = '$id_akun' ");

            if(mysqli_num_rows($result) > 0){
                while ($row = mysqli_fetch_assoc($result)) {
                    $id_produk = $row['id_produk'];
                    $id_penjual = ambil_1_data("produk","id_produk",$id_produk,"id_login");
                    $id_pembeli = $id_akun;
                    $nama_pembeli = $row['username'];
                    $alamat_pembeli = ambil_1_data("profil","id_login",$id_pembeli,"alamat");
                    $hp_pembeli = ambil_1_data("profil","id_login",$id_pembeli,"hp");
                    $tanggal = date("Y-m-d");
                    $harga_peritem = $row['harga_keranjang'];
                    $jml_pembelian = $row['jumlah_pembelian'];
                    $stt_transaksi = "pesan";
                    $penilaian = 0;

                    mysqli_query($con, "INSERT INTO produk_transaksi VALUES(
                        '',
                        '$id_produk',
                        '$id_penjual',
                        '$id_pembeli',
                        '$nama_pembeli',
                        '$alamat_pembeli',
                        '$hp_pembeli',
                        '$tanggal',
                        '$harga_peritem',
                        '$jml_pembelian',
                        '$stt_transaksi',
                        '$penilaian',
                        ''
                        )");
                    if(mysqli_affected_rows($con) > 0){
                        // ambil id transaksi terbaru
                        $id_transaksi = mysqli_query($con, "SELECT MAX(id_transaksi) AS id_trans FROM produk_transaksi");
                        $id_transaksi = mysqli_fetch_assoc($id_transaksi);
                        $id_transaksi = $id_transaksi['id_trans'];

                        // input kedalam tabel lastseen (penjual)
                        mysqli_query($con, "INSERT INTO lastseen_transaksi VALUES(
                            '',
                            '$id_penjual',
                            '$id_transaksi',
                            '$stt_transaksi'
                        )");

                        // input kedalam tabel lastseen(pembeli)
                        mysqli_query($con, "INSERT INTO lastseen_transaksi VALUES(
                            '',
                            '$id_pembeli',
                            '$id_transaksi',
                            '$stt_transaksi'
                        )");
                        
                    }
                }
                // hapus pesana yang ada di keranjang
                mysqli_query($con, "DELETE FROM keranjang WHERE id_pembeli = $id_akun");
                header("Location: admin_penjual.php");
            }else{
                echo "<script> alert('Produk belum dipilih!'); </script>";
            }
        }


        // buat pesanan langsung dan udah login
        if(isset($_POST['buatpesanan_langsung'])){
            $id_produk = $_POST['id_produk'];
            // jika ada promo
            if(isset($_POST['promo'])){
                $promo = $_POST['promo'];
                // jika isi promonya adalah diskon
                if($promo == 'diskon'){
                    $result = mysqli_query($con, "SELECT * FROM produk_diskon WHERE id_produk = '$id_produk' ");
                    $produk = mysqli_fetch_assoc($result);

                    if(mysqli_num_rows($result) > 0){
                
                        $id_penjual = $produk['id_penjual'];
                        $id_pembeli = $id_akun;
                        $nama_pembeli = ambil_1_data("akun_login","id_login",$id_pembeli,"username");
                        $alamat_pembeli = ambil_1_data("profil","id_login",$id_pembeli,"alamat");
                        $hp_pembeli = ambil_1_data("profil","id_login",$id_pembeli,"hp");
                        $tanggal = date("Y-m-d");
                        $harga_peritem = $produk['harga_produk_diskon'];
                        $jml_pembelian = 1;
                        $stt_transaksi = "pesan";
                        $penilaian = 0;
    
                        mysqli_query($con, "INSERT INTO produk_transaksi VALUES(
                            '',
                            '$id_produk',
                            '$id_penjual',
                            '$id_pembeli',
                            '$nama_pembeli',
                            '$alamat_pembeli',
                            '$hp_pembeli',
                            '$tanggal',
                            '$harga_peritem',
                            '$jml_pembelian',
                            '$stt_transaksi',
                            '$penilaian',
                            ''
                            )");
                        if(mysqli_affected_rows($con) > 0){
                            // ambil id transaksi terbaru
                            $id_transaksi = mysqli_query($con, "SELECT MAX(id_transaksi) AS id_trans FROM produk_transaksi");
                            $id_transaksi = mysqli_fetch_assoc($id_transaksi);
                            $id_transaksi = $id_transaksi['id_trans'];
    
                            // input kedalam tabel lastseen (penjual)
                            mysqli_query($con, "INSERT INTO lastseen_transaksi VALUES(
                                '',
                                '$id_penjual',
                                '$id_transaksi',
                                '$stt_transaksi'
                            )");
    
                            // input kedalam tabel lastseen(pembeli)
                            mysqli_query($con, "INSERT INTO lastseen_transaksi VALUES(
                                '',
                                '$id_pembeli',
                                '$id_transaksi',
                                '$stt_transaksi'
                            )");
                            
                        }
                     
                        header("Location: admin_penjual.php");
                    }else{
                        echo "<script> alert('Produk belum dipilih!'); </script>";
                    }
                // jika isi promonya adalah jual cepat
                }elseif($promo == 'jualcepat'){
                    $result = mysqli_query($con, "SELECT * FROM produk_jualcepat WHERE id_produk = '$id_produk' ");
                    $produk = mysqli_fetch_assoc($result);

                    if(mysqli_num_rows($result) > 0){
                
                        $id_penjual = $produk['id_penjual'];
                        $id_pembeli = $id_akun;
                        $nama_pembeli = ambil_1_data("akun_login","id_login",$id_pembeli,"username");
                        $alamat_pembeli = ambil_1_data("profil","id_login",$id_pembeli,"alamat");
                        $hp_pembeli = ambil_1_data("profil","id_login",$id_pembeli,"hp");
                        $tanggal = date("Y-m-d");
                        $harga_peritem = $produk['harga_baru'];
                        $jml_pembelian = 1;
                        $stt_transaksi = "pesan";
                        $penilaian = 0;
    
                        mysqli_query($con, "INSERT INTO produk_transaksi VALUES(
                            '',
                            '$id_produk',
                            '$id_penjual',
                            '$id_pembeli',
                            '$nama_pembeli',
                            '$alamat_pembeli',
                            '$hp_pembeli',
                            '$tanggal',
                            '$harga_peritem',
                            '$jml_pembelian',
                            '$stt_transaksi',
                            '$penilaian',
                            ''
                            )");
                        if(mysqli_affected_rows($con) > 0){
                            // ambil id transaksi terbaru
                            $id_transaksi = mysqli_query($con, "SELECT MAX(id_transaksi) AS id_trans FROM produk_transaksi");
                            $id_transaksi = mysqli_fetch_assoc($id_transaksi);
                            $id_transaksi = $id_transaksi['id_trans'];
    
                            // input kedalam tabel lastseen (penjual)
                            mysqli_query($con, "INSERT INTO lastseen_transaksi VALUES(
                                '',
                                '$id_penjual',
                                '$id_transaksi',
                                '$stt_transaksi'
                            )");
    
                            // input kedalam tabel lastseen(pembeli)
                            mysqli_query($con, "INSERT INTO lastseen_transaksi VALUES(
                                '',
                                '$id_pembeli',
                                '$id_transaksi',
                                '$stt_transaksi'
                            )");
                            
                        }
                    
                        header("Location: admin_penjual.php");
                    }else{
                        echo "<script> alert('Produk belum dipilih!'); </script>";
                    }
                }
            }else{
                // jika tidak ada promo
                $result = mysqli_query($con, "SELECT * FROM produk WHERE id_produk = '$id_produk' ");
                $produk = mysqli_fetch_assoc($result);
                if(mysqli_num_rows($result) > 0){
                
                    $id_penjual = $produk['id_login'];
                    $id_pembeli = $id_akun;
                    $nama_pembeli = ambil_1_data("akun_login","id_login",$id_pembeli,"username");
                    $alamat_pembeli = ambil_1_data("profil","id_login",$id_pembeli,"alamat");
                    $hp_pembeli = ambil_1_data("profil","id_login",$id_pembeli,"hp");
                    $tanggal = date("Y-m-d");
                    $harga_peritem = $produk['harga_produk'];
                    $jml_pembelian = 1;
                    $stt_transaksi = "pesan";
                    $penilaian = 0;

                    mysqli_query($con, "INSERT INTO produk_transaksi VALUES(
                        '',
                        '$id_produk',
                        '$id_penjual',
                        '$id_pembeli',
                        '$nama_pembeli',
                        '$alamat_pembeli',
                        '$hp_pembeli',
                        '$tanggal',
                        '$harga_peritem',
                        '$jml_pembelian',
                        '$stt_transaksi',
                        '$penilaian',
                        ''
                        )");
                    if(mysqli_affected_rows($con) > 0){
                        // ambil id transaksi terbaru
                        $id_transaksi = mysqli_query($con, "SELECT MAX(id_transaksi) AS id_trans FROM produk_transaksi");
                        $id_transaksi = mysqli_fetch_assoc($id_transaksi);
                        $id_transaksi = $id_transaksi['id_trans'];

                        // input kedalam tabel lastseen (penjual)
                        mysqli_query($con, "INSERT INTO lastseen_transaksi VALUES(
                            '',
                            '$id_penjual',
                            '$id_transaksi',
                            '$stt_transaksi'
                        )");

                        // input kedalam tabel lastseen(pembeli)
                        mysqli_query($con, "INSERT INTO lastseen_transaksi VALUES(
                            '',
                            '$id_pembeli',
                            '$id_transaksi',
                            '$stt_transaksi'
                        )");
                        
                    }
                
                    header("Location: admin_penjual.php");
                }else{
                    echo "<script> alert('Produk belum dipilih!'); </script>";
                }
            }
        }

        
    }
    // buat pesanan langsung tanpa login
    if(isset($_POST['buatpesanan_langsung_nologin'])){
        $id_produk = $_POST['id_produk'];
        if(isset($_POST['promo'])){
            if($_POST['promo'] == "diskon"){
                $result_promo = mysqli_query($con, "SELECT * FROM produk_diskon WHERE id_produk = '$id_produk'");
            }elseif($_POST['promo'] == "jualcepat"){
                $result_promo = mysqli_query($con, "SELECT * FROM produk_jualcepat WHERE id_produk = '$id_produk'");
            }
            $produk_promo = mysqli_fetch_assoc($result_promo);
            $id_penjual = $produk_promo['id_penjual'];
            if($_POST['promo'] == "diskon"){
                $harga_peritem = $produk_promo['harga_produk_diskon'];
            }elseif($_POST['promo'] == "jualcepat"){
                $harga_peritem = $produk_promo['harga_baru'];
            }
        }else{
            $result = mysqli_query($con, "SELECT * FROM produk WHERE id_produk = '$id_produk' ");
            $produk = mysqli_fetch_assoc($result);
            $id_penjual = $produk['id_login'];
            $harga_peritem = $produk['harga_produk'];
        }

        if(mysqli_num_rows($result) > 0 OR mysqli_num_rows($result_promo) > 0){
            $id_pembeli = 11;
            $nama_pembeli = $_POST['nama'];
            $alamat_pembeli = $_POST['alamat'];
            $hp_pembeli = $_POST['hp'];
            $tanggal = date("Y-m-d");
            $jml_pembelian = 1;
            $stt_transaksi = "pesan";
            $penilaian = 0;

            mysqli_query($con, "INSERT INTO produk_transaksi VALUES(
                '',
                '$id_produk',
                '$id_penjual',
                '$id_pembeli',
                '$nama_pembeli',
                '$alamat_pembeli',
                '$hp_pembeli',
                '$tanggal',
                '$harga_peritem',
                '$jml_pembelian',
                '$stt_transaksi',
                '$penilaian',
                ''
                )");
            if(mysqli_affected_rows($con) > 0){
                // ambil id transaksi terbaru
                $id_transaksi = mysqli_query($con, "SELECT MAX(id_transaksi) AS id_trans FROM produk_transaksi");
                $id_transaksi = mysqli_fetch_assoc($id_transaksi);
                $id_transaksi = $id_transaksi['id_trans'];

                // input kedalam tabel lastseen (penjual)
                mysqli_query($con, "INSERT INTO lastseen_transaksi VALUES(
                    '',
                    '$id_penjual',
                    '$id_transaksi',
                    '$stt_transaksi'
                )");
            }
        
            header("Location: index.php?notif=success");
        }else{
            echo "<script> alert('Produk belum dipilih!'); </script>";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout- Smuhdastore Belaja Nyaman dan Terpercaya</title>
    <link rel="shortcut icon" href="assets/img/fav.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="assets/icons/icomoon1/icon.css">
    <link rel="stylesheet" href="assets/icons/icomoon2/icon2.css">
    <link rel="stylesheet" href="assets/icons/icomoon3/style.css">
    <link rel="stylesheet" href="assets/icons/icomoon4/style.css">
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

<div class="container d-flex flex-column px-0">
    <!-- navigasi -->
    <div class="nav bg-white d-flex justify-content-between align-items-center px-3 py-3 ">
        <span style="color:seagreen; font-size: 24px;" onclick="window.history.back();"><b class="icon-arrow-left2 mr-2"></b><span class="text-dark">Checkout</span></span>
    </div>
    
    <?php if(isset($_SESSION['admin'])): ?>
    <!-- detil cekout jika sudah login -->
    <div class="w-100 bg-white p-2" style="margin-bottom:50px;">
       
        <div class="d-flex flex-column justify-content-start align-items-left w-100 p-2 border-bottom pb-3">
            <div><b class="icon-location2 mr-2" style="color:seagreen;"></b><span>Alamat Pembeli</span></div>
            <div class="pl-4 pt-2" style="line-height: 18px; font-size:small;"><?= ambil_1_data("akun_login","id_login","$id_akun","username"); ?> ( <?php if(!empty(ambil_1_data("profil","id_login","$id_akun","hp"))){echo ambil_1_data("profil","id_login","$id_akun","hp");}else{echo "<i class='text-danger'>no phone</i>";} ?> )<br><?php if(!empty(ambil_1_data("profil","id_login","$id_akun","alamat"))){echo ambil_1_data("profil","id_login","$id_akun","alamat");}else{echo "<i class='text-danger'>no address</i>";} ?></div>
        </div>

        <?php
            // jika produk ada dalam cart
            if(isset($_GET['cart'])){
                $cart = $_GET['cart'];
                $result = mysqli_query($con, "SELECT * FROM keranjang WHERE id_pembeli = '$cart' ");
                while ($produk = mysqli_fetch_assoc($result)) {
                    $id_produk = $produk['id_produk'];
                    $id_penjual = ambil_1_data("produk","id_produk",$id_produk,"id_login");
        ?>
                <div class="d-flex flex-column justify-content-start align-items-left w-100 p-2 border-bottom">
                    <div><b class="icon-price-tag mr-2" style="color:seagreen;"></b><b><?= ambil_1_data("akun_login","id_login","$id_penjual","username"); ?></b></div>
                    <div class="pl-4 d-flex flex-row">
                        <div class="d-flex justify-content-center align-items-center border" style="width: 100px; height:100px; overflow:hidden;">
                            <img class="w-100" src="assets/foto/<?= ambil_1_data("gambar_produk","id_produk",$id_produk,"gambar"); ?>" alt="">
                        </div>
                        <div class="d-block p-2">
                            <div style="line-height: 18px; font-size:small;"><?= ambil_1_data("produk","id_produk",$id_produk,"nama_produk"); ?></div>
                            <div class="text-secondary pt-2" style="font-size: large;"><?= rupiah($produk['harga_akhir']); ?> x <?= $produk['jumlah_pembelian']; ?></div>
                        </div>
                    </div>
                </div>

            <?php } ?>
            <div class="d-flex flex-column justify-content-start align-items-left w-100 p-2">
                <div><b class="icon-credit-card mr-2" style="color:seagreen;"></b><span>Model Transaksi</span></div>
                <div class="pl-4 py-1 d-flex">
                    <div class="d-flex flex-column">
                        <b style="color:seagreen;">Ketemu Langsung</b>
                        <span class="text-secondary pb-2" style="font-size: small; line-height:16px;">Langsung ketemuan dengan penjual merupakan cara paling aman dalam berbelanja. Sebelum ketemuan harap hubungi penjual terlebih dahulu.</span>
                    </div>
                </div>
                <!-- <div class="pl-4 py-1 d-flex bg-primary">
                    <input type="radio" name="x" id="" style="width: 20px; margin-right: 10px;">
                    <div class="d-flex flex-column">
                        <b style="color:#ff008c;">Transfer Bank</b>
                        <span class="text-white pb-2" style="font-size: small; line-height:15px;">Lakukan pembayaran dengan mentransfer ke nomor rekening penjual dan penjual akan segera mengirim produkmu</span>
                        <b class="text-white border-top">6252393974947 . BRI . Supardi Nasir</b>
                    </div>
                </div> -->
            </div>

            <!-- sub total -->
            <?php 
                $result = mysqli_query($con, "SELECT SUM(harga_akhir) AS total FROM keranjang WHERE id_pembeli = '$id_akun' ");
                $total_bayar = mysqli_fetch_assoc($result);
            ?>
            <div class="container fixed-bottom w-100 bg-white d-flex justify-content-end align-items-center px-2 py-3">
                <div class="d-flex flex-row justify-content-end align-items-center">
                        <div class="d-flex flex-column  mr-2">
                            <span>Total Bayar: <b style="color:seagreen;"><?= rupiah($total_bayar['total']); ?></b></span>
                            <span class="text-secondary" style="font-size: small;">Belum termasuk ongkir</span>
                        </div>
                        <form action="" method="post">
                            <button type="submit" name="buatpesanan" class="btn text-white" style="background: seagreen;"><b>BUAT PESANAN</b></button>
                        </form>
                </div>
            </div>

        <?php }elseif(isset($_GET['id_produk']) AND isset($_GET['promo'])){ 
                $id_produk = $_GET['id_produk'];
                $promo = $_GET['promo'];
                
                if($promo == "diskon"){
                    $result_promo = mysqli_query($con, "SELECT * FROM produk_diskon WHERE id_produk = '$id_produk' "); 
                
                    $produk = mysqli_fetch_assoc($result_promo);
                
                    $id_penjual = $produk['id_penjual'];
        ?>
            <div class="d-flex flex-column justify-content-start align-items-left w-100 p-2 border-bottom">
                <div><b class="icon-price-tag mr-2" style="color:seagreen;"></b><b><?= ambil_1_data("akun_login","id_login","$id_penjual","username"); ?></b></div>
                <div class="pl-4 d-flex flex-row">
                    <div class="d-flex justify-content-center align-items-center border" style="width: 100px; height:100px; overflow:hidden;">
                        <img class="w-100" src="assets/foto/<?= ambil_1_data("gambar_produk","id_produk",$id_produk,"gambar"); ?>" alt="">
                    </div>
                    <div class="d-block p-2">
                        <div style="line-height: 18px; font-size:small;"><?= ambil_1_data("produk","id_produk",$id_produk,"nama_produk"); ?></div>
                        <div class="text-secondary pt-2" style="font-size: large;"><?= rupiah($produk['harga_produk_diskon']); ?> x 1</div>
                    </div>
                </div>
            </div>
        <?php 
                }elseif($promo == "jualcepat"){
                    $result_promo = mysqli_query($con, "SELECT * FROM produk_jualcepat INNER JOIN produk ON produk_jualcepat.id_produk = produk.id_produk WHERE produk_jualcepat.id_produk = '$id_produk' ");

                    $produk = mysqli_fetch_assoc($result_promo);
                
                    $id_penjual = $produk['id_penjual'];
        ?>
            <div class="d-flex flex-column justify-content-start align-items-left w-100 p-2 border-bottom">
                <div><b class="icon-price-tag mr-2" style="color:seagreen;"></b><b><?= ambil_1_data("akun_login","id_login","$id_penjual","username"); ?></b></div>
                <div class="pl-4 d-flex flex-row">
                    <div class="d-flex justify-content-center align-items-center border" style="width: 100px; height:100px; overflow:hidden;">
                        <img class="w-100" src="assets/foto/<?= ambil_1_data("gambar_produk","id_produk",$id_produk,"gambar"); ?>" alt="">
                    </div>
                    <div class="d-block p-2">
                        <div style="line-height: 18px; font-size:small;"><?= ambil_1_data("produk","id_produk",$id_produk,"nama_produk"); ?></div>
                        <div class="text-secondary pt-2" style="font-size: large;"><?= rupiah($produk['harga_baru']); ?> x 1</div>
                    </div>
                </div>
            </div>
        <?php
                }
        ?>
            <div class="d-flex flex-column justify-content-start align-items-left w-100 p-2">
                <div><b class="icon-credit-card mr-2" style="color:seagreen;"></b><span>Model Transaksi</span></div>
                <div class="pl-4 py-1 d-flex">
                    <!-- <input type="radio" name="x" id="" style="width: 20px; margin-right: 10px;" > -->
                    <div class="d-flex flex-column">
                        <b style="color:seagreen;">Ketemu Langsung</b>
                        <span class="text-secondary pb-2" style="font-size: small; line-height:16px;">Langsung ketemuan dengan penjual merupakan cara paling aman dalam berbelanja. Sebelum ketemuan harap hubungi penjual terlebih dahulu.</span>
                    </div>
                </div>
                <!-- <div class="pl-4 py-1 d-flex bg-primary">
                    <input type="radio" name="x" id="" style="width: 20px; margin-right: 10px;">
                    <div class="d-flex flex-column">
                        <b style="color:#ff008c;">Transfer Bank</b>
                        <span class="text-white pb-2" style="font-size: small; line-height:15px;">Lakukan pembayaran dengan mentransfer ke nomor rekening penjual dan penjual akan segera mengirim produkmu</span>
                        <b class="text-white border-top">6252393974947 . BRI . Supardi Nasir</b>
                    </div>
                </div> -->
            </div>

            <!-- sub total -->
            <div class="container fixed-bottom w-100 bg-white d-flex justify-content-end align-items-center px-2 py-3">
                <div class="d-flex flex-row justify-content-end align-items-center">
                    <div class="d-flex flex-column  mr-2">
                    <?php if($promo == "diskon"){ ?>
                        <span>Total Bayar: <b style="color:seagreen;"><?= rupiah($produk['harga_produk_diskon']); ?></b></span>
                    <?php }else{ ?>
                        <span>Total Bayar: <b style="color:seagreen;"><?= rupiah($produk['harga_baru']); ?></b></span>
                    <?php } ?>
                        <span class="text-secondary" style="font-size: small;">Belum termasuk ongkir</span>
                    </div>
                    <form action="" method="post">
                        <input type="hidden" name="id_produk" value="<?= $_GET['id_produk']; ?>">
                        <!-- jika produk ada promo (diskon/jualcepat) -->
                        <?php if(isset($_GET['promo'])): ?>
                            <input type="hidden" name="promo" value="<?= $_GET['promo']; ?>">
                        <?php endif; ?>
                        <button type="submit" name="buatpesanan_langsung" class="btn text-white" style="background: seagreen;"><b>BUAT PESANAN</b></button>
                    </form>
                </div>
            </div>
        <?php }elseif(isset($_GET['id_produk'])){
                 $id_produk = $_GET['id_produk'];
                 $result = mysqli_query($con, "SELECT * FROM produk WHERE id_produk = '$id_produk' ");
                 $produk = mysqli_fetch_assoc($result);
                 $id_penjual = $produk['id_login'];
                
        ?>
            <div class="d-flex flex-column justify-content-start align-items-left w-100 p-2 border-bottom">
                <div><b class="icon-price-tag mr-2" style="color:seagreen;"></b><b><?= ambil_1_data("akun_login","id_login","$id_penjual","username"); ?></b></div>
                <div class="pl-4 d-flex flex-row">
                    <div class="d-flex justify-content-center align-items-center border" style="width: 100px; height:100px; overflow:hidden;">
                        <img class="w-100" src="assets/foto/<?= ambil_1_data("gambar_produk","id_produk",$id_produk,"gambar"); ?>" alt="">
                    </div>
                    <div class="d-block p-2">
                        <div style="line-height: 18px; font-size:small;"><?= ambil_1_data("produk","id_produk",$id_produk,"nama_produk"); ?></div>
                        <div class="text-secondary pt-2" style="font-size: large;"><?= rupiah($produk['harga_produk']); ?> x 1</div>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-column justify-content-start align-items-left w-100 p-2">
                <div><b class="icon-credit-card mr-2" style="color:seagreen;"></b><span>Model Transaksi</span></div>
                <div class="pl-4 py-1 d-flex">
                    <!-- <input type="radio" name="x" id="" style="width: 20px; margin-right: 10px;" > -->
                    <div class="d-flex flex-column">
                        <b style="color:seagreen;">Ketemu Langsung</b>
                        <span class="text-secondary pb-2" style="font-size: small; line-height:16px;">Langsung ketemuan dengan penjual merupakan cara paling aman dalam berbelanja. Sebelum ketemuan harap hubungi penjual terlebih dahulu.</span>
                    </div>
                </div>
                <!-- <div class="pl-4 py-1 d-flex bg-primary">
                    <input type="radio" name="x" id="" style="width: 20px; margin-right: 10px;">
                    <div class="d-flex flex-column">
                        <b style="color:#ff008c;">Transfer Bank</b>
                        <span class="text-white pb-2" style="font-size: small; line-height:15px;">Lakukan pembayaran dengan mentransfer ke nomor rekening penjual dan penjual akan segera mengirim produkmu</span>
                        <b class="text-white border-top">6252393974947 . BRI . Supardi Nasir</b>
                    </div>
                </div> -->
            </div>

            <!-- sub total -->
            <div class="container fixed-bottom w-100 bg-white d-flex justify-content-end align-items-center px-2 py-3">
                <div class="d-flex flex-row justify-content-end align-items-center">
                    <div class="d-flex flex-column  mr-2">
                        <span>Total Bayar: <b style="color:seagreen;"><?= rupiah($produk['harga_produk']); ?></b></span>
                        <span class="text-secondary" style="font-size: small;">Belum termasuk ongkir</span>
                    </div>
                    <form action="" method="post">
                        <input type="hidden" name="id_produk" value="<?= $_GET['id_produk']; ?>">
                        <!-- jika produk ada promo (diskon/jualcepat) -->
                        <?php if(isset($_GET['promo'])): ?>
                            <input type="hidden" name="promo" value="<?= $_GET['promo']; ?>">
                        <?php endif; ?>
                        <button type="submit" name="buatpesanan_langsung" class="btn text-white" style="background: seagreen;"><b>BUAT PESANAN</b></button>
                    </form>
                </div>
            </div>
        <?php }else{
            header("Location: admin_pembeli.php");
        } ?>

    </div> 

    <?php else: ?>
    <!-- detil checkout jika belum login -->
        <?php if(isset($_GET['id_produk'])) { ?>
        <form action="" method="post">

        <input type="hidden" name="id_produk" value="<?= $_GET['id_produk']; ?>">
        
        <!-- jika ada promo kirim promo apa -->
        <?php if(isset($_GET['promo'])): ?>
            <input type="hidden" name="promo" value="<?= $_GET['promo']; ?>">
        <?php endif; ?>

        <div class="w-100 bg-white p-2" style="margin-bottom:50px;">
            <div class="d-flex flex-column justify-content-center align-items-center">
                <span class="pt-3">Kamu belum login loh!</span>
                <b style="font-size:small; font-weight:normal;" class="pb-3 pt-2 text-secondary text-center">Dengan melakukan login kamu akan lebih mudah dalam berbelanja</b>
                <a href="login.php"><button type="button" class="btn text-white mb-3" style="background:seagreen; width:200px;">Log In</button></a>
                <span style="font-size: small;" class="w-100 text-secondary border-bottom text-center pb-3">Lanjutkan tanpa login</span>
            </div>
            <div class="d-flex flex-column justify-content-start align-items-left w-100 p-2 border-bottom pb-3">
                <div><b class="icon-location2 mr-2" style="color:seagreen;"></b><span>Identitas dan Alamat Pembeli</span></div>
                <div class="pl-4 pt-2" style="line-height: 18px;">
                    <input type="text" name="nama" id="" placeholder="Nama Pembeli" class="form-control mb-2" required>
                    <input type="text" name="hp" id="" placeholder="Nomor Whatsapp" class="form-control mb-2" required>
                    <input type="text" name="alamat" id="" placeholder="Alamat Lengkap" class="form-control mb-2" required>
                    <span style="font-size: small;" class="text-secondary">Wajib diisi dengan benar dan lengkap agar tidak terjadi kesalahan dalam pengiriman</span>
                </div>
            </div>
            <div class="d-flex flex-column justify-content-start align-items-left w-100 p-2 border-bottom">
                <?php 
                    $id_produk = $_GET['id_produk'];
                    if(isset($_GET['promo'])){
                        if($_GET['promo'] == "diskon"){
                            $result_promo = mysqli_query($con, "SELECT * FROM produk_diskon WHERE id_produk = $id_produk");
                        }elseif($_GET['promo'] == "jualcepat"){
                            $result_promo = mysqli_query($con, "SELECT * FROM produk_jualcepat WHERE id_produk = $id_produk");
                        }
                    }else{
                        $result = mysqli_query($con, "SELECT * FROM produk WHERE id_produk = $id_produk");
                    }

                    // jika ada promo
                    if(isset($result_promo)):
                        $produk_promo = mysqli_fetch_assoc($result_promo);
                        $id_penjual = $produk_promo['id_penjual'];
                ?>
                <div><b class="icon-price-tag mr-2" style="color:seagreen;"></b><b><?= ambil_1_data("akun_login","id_login",$id_penjual,"username"); ?></b></div>
                <div class="pl-4 d-flex flex-row">
                    <div class="d-flex justify-content-center align-items-center" style="width: 100px;">
                        <img class="w-100" src="assets/foto/<?= ambil_1_data("gambar_produk","id_produk",$id_produk,"gambar"); ?>" alt="">
                    </div>
                    <div class="d-block p-2">
                        <div style="line-height: 18px; font-size:small;"><?= ambil_1_data("produk","id_produk",$id_produk,"nama_produk"); ?></div>
                        <div class="text-secondary pt-2" style="font-size: large;">
                        <?php
                            if($_GET['promo'] == "diskon"){
                                echo rupiah($produk_promo['harga_produk_diskon']);
                            }elseif($_GET['promo'] == "jualcepat"){
                                echo rupiah($produk_promo['harga_baru']);
                            }
                        ?>
                         x 1</div>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column justify-content-start align-items-left w-100 p-2">
                <div><b class="icon-credit-card mr-2" style="color:seagreen;"></b><span>Model Transaksi</span></div>
                <div class="pl-4 py-1 d-flex">
                    <!-- <input type="radio" name="x" id="" style="width: 20px; margin-right: 10px;" > -->
                    <div class="d-flex flex-column">
                        <b style="color:seagreen;">Ketemu Langsung</b>
                        <span class="text-secondary pb-2" style="font-size: small; line-height:15px;">Langsung ketemuan dengan penjual merupakan cara paling aman dalam berbelanja. Sebelum ketemuan harap hubungi penjual terlebih dahulu</span>
                    </div>
                </div>
                <!-- <div class="pl-4 py-1 d-flex">
                    <input type="radio" name="x" id="" style="width: 20px; margin-right: 10px;">
                    <div class="d-flex flex-column">
                        <b style="color:#ff008c;">Transfer Bank</b>
                        <span class="text-secondary pb-2" style="font-size: small; line-height:15px;">Lakukan pembayaran dengan mentransfer ke nomor rekening penjual dan penjual akan segera mengirim produkmu</span>
                        <b class="border-top">6252393974947 | BRI | Supardi Nasir</b>
                    </div>
                </div> -->
            </div>
            
        </div>
        <!-- sub total -->
        <div class="container fixed-bottom w-100 bg-white d-flex justify-content-end align-items-center px-2 py-3">
            <div class="d-flex flex-row justify-content-end align-items-center">
                    <div class="d-flex flex-column  mr-2">
                        <span>Total Bayar: <b style="color:seagreen;">
                        <?php
                            if($_GET['promo'] == "diskon"){
                                echo rupiah($produk_promo['harga_produk_diskon']);
                            }elseif($_GET['promo'] == "jualcepat"){
                                echo rupiah($produk_promo['harga_baru']);
                            }
                        ?>
                        </b></span>
                        <span class="text-secondary" style="font-size: small;">Belum termasuk ongkir</span>
                    </div>
                    <button type="submit" name="buatpesanan_langsung_nologin" class="btn text-white" style="background: seagreen;"><b>BUAT PESANAN</b></button>
            </div>
        </div>
            <?php else : 
                $produk = mysqli_fetch_assoc($result);
                $id_penjual = $produk['id_login'];
            ?>
                <div><b class="icon-price-tag mr-2" style="color:seagreen;"></b><b><?= ambil_1_data("akun_login","id_login",$id_penjual,"username"); ?></b></div>
                <div class="pl-4 d-flex flex-row">
                    <div class="d-flex justify-content-center align-items-center" style="width: 100px;">
                        <img class="w-100" src="assets/foto/<?= ambil_1_data("gambar_produk","id_produk",$id_produk,"gambar"); ?>" alt="">
                    </div>
                    <div class="d-block p-2">
                        <div style="line-height: 18px; font-size:small;"><?= ambil_1_data("produk","id_produk",$id_produk,"nama_produk"); ?></div>
                        <div class="text-secondary pt-2" style="font-size: large;"><?= rupiah($produk['harga_produk']); ?> x 1</div>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column justify-content-start align-items-left w-100 p-2">
                <div><b class="icon-credit-card mr-2" style="color:seagreen;"></b><span>Model Transaksi</span></div>
                <div class="pl-4 py-1 d-flex">
                    <!-- <input type="radio" name="x" id="" style="width: 20px; margin-right: 10px;" > -->
                    <div class="d-flex flex-column">
                        <b style="color:seagreen;">Ketemu Langsung</b>
                        <span class="text-secondary pb-2" style="font-size: small; line-height:15px;">Langsung ketemuan dengan penjual merupakan cara paling aman dalam berbelanja. Sebelum ketemuan harap hubungi penjual terlebih dahulu</span>
                    </div>
                </div>
                <!-- <div class="pl-4 py-1 d-flex">
                    <input type="radio" name="x" id="" style="width: 20px; margin-right: 10px;">
                    <div class="d-flex flex-column">
                        <b style="color:#ff008c;">Transfer Bank</b>
                        <span class="text-secondary pb-2" style="font-size: small; line-height:15px;">Lakukan pembayaran dengan mentransfer ke nomor rekening penjual dan penjual akan segera mengirim produkmu</span>
                        <b class="border-top">6252393974947 | BRI | Supardi Nasir</b>
                    </div>
                </div> -->
            </div>
            
        </div>
        <!-- sub total -->
        <div class="container fixed-bottom w-100 bg-white d-flex justify-content-end align-items-center px-2 py-3">
            <div class="d-flex flex-row justify-content-end align-items-center">
                    <div class="d-flex flex-column  mr-2">
                        <span>Total Bayar: <b style="color:seagreen;"><?= rupiah($produk['harga_produk']); ?></b></span>
                        <span class="text-secondary" style="font-size: small;">Belum termasuk ongkir</span>
                    </div>
                    <button type="submit" name="buatpesanan_langsung_nologin" class="btn text-white" style="background: seagreen;"><b>BUAT PESANAN</b></button>
            </div>
        </div>
            <?php endif; ?>
        </form>
        <?php }else{
            header("Location: index.php");
        }
        ?>
    <?php endif; ?>

</div>


<script src="assets/bootstrap/jquery/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.js"></script>
<script src="assets/bootstrap/popper/popper.js"></script>


</body>
</html>