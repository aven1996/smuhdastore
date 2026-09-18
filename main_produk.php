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
                header("Location: main_produk.php");
            }else{
                // jika belum ada dalam favorit
                $input = favorit($id_produk,$id_akun,"input");
                if($input){
                    header("Location: main_produk.php");
                }else{
                    echo "Gagal input favorit";
                }
            }
        }else{
            header("Location: login.php;");
        }
    }


    // buat session filter jika tombol diterapkan
    if(isset($_POST['terapkan'])){
        // bersihkan session filter yang sebelumnya
        unset($_SESSION['lokasi']);
        unset($_SESSION['min_harga']);
        unset($_SESSION['max_harga']);
        unset($_SESSION['kondisi']);
        // ambil semua data dari form filter
        $lokasi = $_POST['lokasi'];
        $min = $_POST['min_harga'];
        $max = $_POST['max_harga'];
        $kondisi = $_POST['kondisi'];

        if(!empty($lokasi)){
            $_SESSION['lokasi'] = $lokasi;
        }
        if(!empty($min) AND !empty($max)){
            $_SESSION['min_harga'] = $min;
            $_SESSION['max_harga'] = $max;
        }
        if(!empty($kondisi)){
            $_SESSION['kondisi'] = $kondisi;
        }
    }

    // hapus session filter jika tombol reset ditekan
    if(isset($_POST['reset'])){
        unset($_SESSION['lokasi']);
        unset($_SESSION['min_harga']);
        unset($_SESSION['max_harga']);
        unset($_SESSION['kondisi']);
    }


    // buat variabel untuk filter
    $lokasi = "";
    $min = "";
    $max = "";
    $kondisi = "";
    $kisaran_harga = "";
    // isi variabel filter jika filter diterapkan
    if(isset($_SESSION['lokasi'])){
        $lokasi = $_SESSION['lokasi'];
        // koversi for sql
        $lokasi = "AND alamat LIKE '%$lokasi%' ";
    }
    if(isset($_SESSION['min_harga']) AND isset($_SESSION['max_harga'])){
        $min = $_SESSION['min_harga'];
        $max = $_SESSION['max_harga'];
        $kisaran_harga = "AND harga_produk BETWEEN '$min' AND '$max'";
    }
    if(isset($_SESSION['kondisi']) ){
        $kondisi  = $_SESSION['kondisi'];
        $kondisi = "AND kondisi_produk LIKE '%$kondisi%' ";
    }
    
    // urutan
    // set urutan harga berdasarkan tombol yang ditekan
    if(isset($_POST['btn_urut_harga'])){
        $_SESSION['urutan_harga'] = $_POST['urutan_harga'];
    }else{
        // set urutan harga terbaru untuk pertama kali dimuat
        $_SESSION['urutan_harga'] = "terendah";
    }

    if($_SESSION['urutan_harga'] == "terendah"){
        $urutan = "ORDER BY harga_produk ASC";
    }elseif($_SESSION['urutan_harga'] == "tertinggi"){
        $urutan = "ORDER BY harga_produk DESC";
    }


    // set urutan berdasarkan tombol yang ditekan
    // if(isset($_POST['btn_urut'])){
    //     $_SESSION['urutan'] = $_POST['urutan'];
    // }else{
    //     // set urutan terbaru untuk pertama kali dimuat
    //     $_SESSION['urutan'] = "terkait";
    // }
    
    // if($_SESSION['urutan'] == "terbaru"){
    //     $urutan = "ORDER BY id_produk DESC $urutan_harga";
    // }elseif($_SESSION['urutan'] == "terkait"){
    //     $urutan = "ORDER BY stok_produk DESC $urutan_harga";
    // }elseif($_SESSION['urutan'] == "terlaris"){
    //     // urutan yang terlaris belum diprogram karena harus buat table baru jml terjual
    // }
    

    // query default
    $res_produk = mysqli_query($con, "SELECT * FROM produk INNER JOIN akun_login ON produk.id_login = akun_login.id_login INNER JOIN profil ON produk.id_login = profil.id_login WHERE nama_produk LIKE '% %' $lokasi $kondisi $kisaran_harga $urutan");

    // jika ada pencarian
    if(isset($_GET['key'])){
        $keyword = $_GET['key'];
        $res_produk = mysqli_query($con, "SELECT * FROM produk INNER JOIN akun_login ON produk.id_login = akun_login.id_login INNER JOIN profil ON produk.id_login = profil.id_login WHERE kategori_produk LIKE '%$keyword%' OR nama_produk LIKE '%$keyword%' $lokasi $kondisi $kisaran_harga $urutan");
    }
    // jika klik kategori
    if(isset($_GET['ktg'])){
        $kategori = $_GET['ktg'];
        $res_produk = mysqli_query($con, "SELECT * FROM produk INNER JOIN akun_login ON produk.id_login = akun_login.id_login INNER JOIN profil ON produk.id_login = profil.id_login WHERE kategori_produk LIKE '%$kategori%' $lokasi $kondisi $kisaran_harga $urutan");
    }

    // jika pelanggan menampilkan promo
    if(isset($_GET['promo'])){
        $keyword = "";
        if(isset($_GET['keyword'])){
            $keyword = $_GET['keyword'];
        }
        if($_GET['promo'] == "diskon"){
            $res_produk = mysqli_query($con, "SELECT * FROM produk_diskon INNER JOIN produk ON produk_diskon.id_produk = produk.id_produk INNER JOIN akun_login ON produk_diskon.id_penjual = akun_login.id_login INNER JOIN profil ON produk_diskon.id_penjual = profil.id_login WHERE nama_produk LIKE '%$keyword%' $lokasi $kondisi ORDER BY diskon DESC");
            unset($_SESSION['urutan_harga']);

        }elseif($_GET['promo'] == "jualcepat"){
            $res_produk = mysqli_query($con, "SELECT * FROM produk_jualcepat INNER JOIN produk ON produk_jualcepat.id_produk = produk.id_produk INNER JOIN akun_login ON produk_jualcepat.id_penjual = akun_login.id_login INNER JOIN profil ON produk_jualcepat.id_penjual = profil.id_login WHERE nama_produk LIKE '%$keyword%' AND mulai <= CURDATE() AND berakhir >= CURDATE() $lokasi $kondisi ORDER BY harga_baru ASC");
            unset($_SESSION['urutan_harga']);
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
        .icon-key:hover{
                background: whitesmoke;
                cursor: pointer;
                border-radius: 5px;
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
                <a href="index.php#footer2"><span>Hubungi Kami Lewat Whatsapp</span></a>
            </div>
 
            <div class="d-flex">
                <a href="index.php#footer1"><span class="mr-4">Kurang Yakin?</span></a>
                <a href="#"><span class="mr-4">Tentang Kami</span></a>
            </div>
            </div>
        </div>

        <div class="item-header-2 d-flex flex-column px-0" style="background: darkcyan;">
            <div class="container d-flex justify-content-between align-items-center">
                <!-- logo -->
                <a href="index.php" class="mr-3"><img class="img-logo" src="assets/img/logo_white.svg" alt=""></a>

                <!-- filter -->
                <b class="icon-filter py-2 px-3 text-white" title="Filter" data-toggle="modal" data-target="#exampleModal2"></b>

                <!-- form search -->
                <div class="wrap-search mr-2">
                    <!-- form search jika diklik akan muncul modal search(dibgn bawah) -->
                    <form action="" method="get">
                        <input name="key" type="text" class="form-control" placeholder="Cari barang bagus" style="border: none; border-radius:0;" value="<?php if(isset($_GET['key'])){ echo $_GET['key']; } ?><?php if(isset($_GET['keyword'])){ echo $_GET['keyword']; } ?>">
                        <button type="submit" class="btn wrap-icon-search d-flex justify-content-center align-items-center px-2 m-0">
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
                <div id="profil" style="width: 340px;">
                <?php
                    if(!isset($_SESSION['admin'])):
                ?>

                    <a href="login.php" class="login"><button class="btn-masuk btn mr-0 font-weight-bold" style="background: white; color: darkcyan;">Login</button></a>
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
                                <img class="w-100" src="assets/foto/<?= $poto; ?>" alt="<?= $poto; ?>" title="<?= $poto; ?>"> 
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
     
    <!-- hasil pencarian terkait -->
    <?php if(isset($_GET['key']) AND !empty($_GET['key'])): ?>
    <div class="container py-3 pl-2" style="font-size: small;">
        <span class="text-secondary">Hasil pencarian untuk</span> <b style="color: darkcyan;">'<?= $_GET['key']; ?>'</b>
    </div>
    <?php endif; ?>
    <?php if(isset($_GET['keyword']) AND !empty($_GET['keyword'])): ?>
    <div class="container py-3 pl-2" style="font-size: small;">
        <span class="text-secondary">Hasil pencarian untuk</span> <b style="color: darkcyan;">'<?= $_GET['keyword']; ?>'</b>
    </div>
    <?php endif; ?>

    <!-- urutkan -->
    <div class="container d-flex flex-row justify-content-start align-items-center pl-2 pt-2  pb-2 pr-0" style="background: #ececec;">
        <span class="mr-2 text-secondary" style="font-size: small;">Urutkan</span>
        <div class="d-flex flex-row justify-content-start align-items-center mr-0" style="overflow:auto;">
        <!-- <form action="" method="POST">
            <input type="hidden" name="urutan" value="terkait">
            <?php if(isset($_SESSION['urutan']) AND $_SESSION['urutan'] == "terkait" ): ?>
                <button type="submit" name="btn_urut" class="btn text-white mr-2" style="background: darkcyan; font-size: small;">Terkait</button>
            <?php else: ?>
                <button type="submit" name="btn_urut" class="btn bg-white text-secondary mr-2" style="font-size: small;">Terkait</button>
            <?php endif; ?>
        </form>
        <form action="" method="POST">
            <input type="hidden" name="urutan" value="terbaru">
            <?php if(isset($_SESSION['urutan']) AND $_SESSION['urutan'] == "terbaru" ): ?>
                <button type="submit" name="btn_urut" class="btn mr-2 text-white" style="background:darkcyan; font-size: small;">Terbaru</button>
                
            <?php else: ?>
                <button type="submit" name="btn_urut" class="btn bg-white mr-2 text-secondary" style="font-size: small;">Terbaru</button>
            <?php endif; ?>
        </form> -->
        <form action="" method="POST">
            <input type="hidden" name="urutan_harga" value="terendah">
            <?php if(isset($_SESSION['urutan_harga']) AND $_SESSION['urutan_harga'] == "terendah" ): ?>
                <button type="submit" name="btn_urut_harga" class="btn mr-2 text-white" style="background:darkcyan; font-size: small;">Harga Terendah</button>
                
            <?php else: ?>
                <button type="submit" name="btn_urut_harga" class="btn bg-white mr-2 text-secondary" style="font-size: small;">Harga Terendah</button>
            <?php endif; ?>
        </form>
        <form action="" method="POST">
            <input type="hidden" name="urutan_harga" value="tertinggi">
            <?php if(isset($_SESSION['urutan_harga']) AND $_SESSION['urutan_harga'] == "tertinggi" ): ?>
                <button type="submit" name="btn_urut_harga" class="btn mr-2 text-white" style="background:darkcyan; font-size: small;">Harga Tertinggi</button>
                
            <?php else: ?>
                <button type="submit" name="btn_urut_harga" class="btn bg-white mr-2 text-secondary" style="font-size: small;">Harga Tertinggi</button>
            <?php endif; ?>
        </form>

        <form action="" method="GET">
            <input type="hidden" name="promo" value="diskon">
            <?php if(isset($_GET['key'])): ?>
                <input type="hidden" name="keyword" value="<?= $_GET['key']; ?>">
            <?php endif; ?>
            <?php if(isset($_GET['keyword'])): ?>
                <input type="hidden" name="keyword" value="<?= $_GET['keyword']; ?>">
            <?php endif; ?>
            <?php if(isset($_GET['promo']) AND $_GET['promo'] == "diskon" ): ?>
                <button type="submit" name="btn_promo" class="btn mr-2 text-white" style="background:darkcyan; font-size: small;">Diskon</button>
                
            <?php else: ?>
                <button type="submit" name="btn_promo" class="btn bg-white mr-2 text-secondary" style="font-size: small;">Diskon Tertinggi</button>
            <?php endif; ?>
        </form>

        <form action="" method="GET">
            <input type="hidden" name="promo" value="jualcepat">
            <?php if(isset($_GET['key'])): ?>
                <input type="hidden" name="keyword" value="<?= $_GET['key']; ?>">
            <?php endif; ?>
            <?php if(isset($_GET['keyword'])): ?>
                <input type="hidden" name="keyword" value="<?= $_GET['keyword']; ?>">
            <?php endif; ?>
            <?php if(isset($_GET['promo']) AND $_GET['promo'] == "jualcepat" ): ?>
                <button type="submit" name="btn_promo" class="btn mr-2 text-white" style="background:darkcyan; font-size: small;">Jual Cepat</button>
                
            <?php else: ?>
                <button type="submit" name="btn_promo" class="btn bg-white mr-2 text-secondary" style="font-size: small;">Jual Cepat</button>
            <?php endif; ?>
        </form>
        <!-- <form action="" method="POST">
            <input type="hidden" name="urutan" value="terlaris">
            <?php if(isset($_SESSION['urutan']) AND $_SESSION['urutan'] == "terlaris" ): ?>
                <button type="submit" name="btn_urut" class="btn text-white mr-2" style="background: darkcyan; font-size: small;">Terlaris</button>
            <?php else: ?>
                <button type="submit" name="btn_urut" class="btn bg-white text-secondary mr-2" style="font-size: small;">Terlaris</button>
            <?php endif; ?>
        </form> -->
        </div>
    </div>

    <!-- Produk -->
    <div class="produkrekomendasi container bg-white my-2 py-2">
        <div class="d-flex flex-row justify-content-center">
            <div class=" w-100 produk-jualcepat row mt-2 pl-0 justify-content-around">

            <?php
                if(mysqli_num_rows($res_produk) > 0):
                    while($pro = mysqli_fetch_assoc($res_produk)): 
                        $id_produk = $pro['id_produk'];
            ?> 
            <!-- card -->
            
            <div class="cart-produk-jualcepat d-block pb-2" style="width: 160px;">
                <a href="detil_produk.php?id_produk=<?= $pro['id_produk']; ?><?php if(isset($_GET['promo']) AND $_GET['promo'] == "jualcepat"){
                    echo "&jualcepat";
                    }
                ?>
                " class="text-dark" style="text-decoration: none;">
                <!-- diskon -->
                <?php if(!isset($_GET['promo']) OR isset($_GET['promo']) AND $_GET['promo'] != "jualcepat"): ?>
                    <?php if(cekDiskon($pro['id_produk'],"bool")): ?>
                        <div class="diskon d-flex flex-column p-2 text-danger">
                        <b><?= cekDiskon($pro['id_produk'],"diskon"); ?>%</b>
                        <b class="text-white">OFF</b>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- jualcepat -->
                <?php if(isset($_GET['promo']) AND $_GET['promo'] == "jualcepat"): ?>
                        <div class="d-flex flex-column p-2 text-danger" style="position:absolute; left: 0; background:crimson; color:white; border-bottom-right-radius:10px;">
                            <b class="icon-stopwatch text-white"></b>
                        </div>
                <?php endif; ?>

                <!-- image -->
                <div class="cov-img-cart d-flex justify-content-center align-items-center">
                    <img class="h-100" style="width:auto;" src="assets/foto/<?= ambil_1_data("gambar_produk","id_produk",$id_produk,"gambar"); ?>" alt="">
                </div>
                <!-- nama produk -->
                <span class="nama_produk m-0"><?= $pro['nama_produk']; ?></span>
                <!-- harga -->
                <b class="harga m-0" style="color: darkcyan; font-weight:bold;">
                    <?php if(isset($_GET['promo']) AND $_GET['promo'] == "jualcepat"){
                        echo rupiah($pro['harga_baru']);
                        
                    }else{
                        if(cekDiskon($id_produk, "bool")){
                            echo rupiah(cekDiskon($id_produk, "harga_produk_diskon"));
                        }else{
                            echo rupiah($pro['harga_produk']);
                        }
                    }
                    
                    ?>
                </b>
                </a>
                <!-- rating -->
                <div class="rating">
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
                <!-- lokasi penjual -->
                <div class="d-flex justify-content-between align-items-center">
                    <?php if(isset($id_akun)): ?>
                    <?php if(!isset($_GET['promo']) OR isset($_GET['promo']) AND $_GET['promo'] != "jualcepat"):?>

                        <!-- favorit -->
                        <?php if(favorit($id_produk,$id_akun,"cek")): ?>
                            <a href="main_produk.php?fav=<?= $id_produk; ?>" style="text-decoration: none;">
                                <b class="icon-heart" style="color:crimson;"></b>
                            </a>
                        <?php else: ?>
                            <a href="main_produk.php?fav=<?= $id_produk; ?>" style="text-decoration: none;">
                                <b class="icon-heart" style="color:silver;"></b>
                            </a>
                        <?php endif; ?>

                    <?php endif; ?>
                    <?php endif; ?>
                    <div class="text-secondary text-right" style="font-size: small; "><?= lokasiProduk($id_produk); ?></div>
                </div>
            </div>
            
                <?php endwhile; ?>
            <?php else: ?>
                <span class="text-secondary" style="font-size:small;">Produk tidak ditemukan</span>

            <?php endif; ?>

            </div>
        </div>
    </div>

    
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
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content m-c-head">
                <div class="d-flex justify-content-between">
                    <span class="font-weight-bold title">Pencarian terakhir</span>
                    <span class="btn-hapus font-weight-normal text-danger" style="font-size: 14px;">Hapus semua</span>
                </div>
                <span class="opt-hist-search pl-2 py-1 mt-2" style="font-size: 14px;">Kemeja cowok</span>
                <span class="opt-hist-search pl-2 py-1 mt-2" style="font-size: 14px;">Celana dalam boxer</span>
                <span class="opt-hist-search pl-2 py-1 mt-2" style="font-size: 14px;">Mantol ruwet</span>

                <div class="d-flex flex-column">
                    <span class="font-weight-bold title mt-3">Terpopuler</span>
                    <div class="mt-2">
                        <span class="d-inline-block px-2 mr-1 rounded-pill" style="font-size: 14px; color: darkcyan; border: 1px solid darkcyan;">Headset</span>
                        <span class="d-inline-block px-2 mr-1 rounded-pill" style="font-size: 14px; color: darkcyan; border: 1px solid darkcyan;">Samsung</span>
                        <span class="d-inline-block px-2  mr-1 rounded-pill" style="font-size: 14px; color: darkcyan; border: 1px solid darkcyan;">Boxer</span>
                        <span class="d-inline-block px-2 mr-1 rounded-pill" style="font-size: 14px; color: darkcyan; border: 1px solid darkcyan;">Masker murah</span>
                        <span class="d-inline-block px-2 mr-1 rounded-pill" style="font-size: 14px; color: darkcyan; border: 1px solid darkcyan;">Vaksin covid</span>
                        <span class="d-inline-block px-2 mr-1 rounded-pill" style="font-size: 14px; color: darkcyan; border: 1px solid darkcyan;">Telo puhung</span>
                        <span class="px-2 mr-1 rounded-pill" style="font-size: 14px; color: darkcyan; border: 1px solid darkcyan;">Pacar orang</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Filter -->
    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content m-c-head" style="overflow-y: auto;">
                <div class="d-flex justify-content-star pb-2 mb-2">
                    <span class="font-weight-bold title">Filter</span>
                </div>

                <form method="POST" action="">
                <div class="d-flex flex-column mb-2 pb-2">
                        <span class="mb-1" style="color: darkcyan;">Lokasi</span>
                        <select class="form-control" name="lokasi" id="" >
                            <option value="">Pilih Lokasi</option>
                            <option value="klaten" <?php if(isset($_SESSION['lokasi'])){
                                if($_SESSION['lokasi'] == "klaten"){
                                    echo "selected";
                                }
                            }?>>Kab. Klaten</option>
                            <option value="solo" <?php if(isset($_SESSION['lokasi'])){
                                if($_SESSION['lokasi'] == "solo"){
                                    echo "selected";
                                }
                            }?>>Kota Solo</option>
                            <option value="yogyakarta" <?php if(isset($_SESSION['lokasi'])){
                                if($_SESSION['lokasi'] == "yogyakarta"){
                                    echo "selected";
                                }
                            }?>>D.I. Yogyakarta</option>
                            <option value="salatiga" <?php if(isset($_SESSION['lokasi'])){
                                if($_SESSION['lokasi'] == "salatiga"){
                                    echo "selected";
                                }
                            }?>>Kab. Salatiga</option>
                        </select>
                </div>
                <div class="d-flex flex-column mb-2 justify-content-start align-items-left">
                        <span class="d-block mb-1 w-100" style="color: darkcyan;">Kisaran Harga</span>
                        <div class="d-flex">
                            <input name="min_harga" type="text" class="form-control mr-1" placeholder="MIN" style="font-size: small; width:50%;" value="<?php if(isset($_SESSION['min_harga'])){
                                echo $_SESSION['min_harga'];
                            }?>">
                            <b style="font-weight:normal;">-</b>
                            <input name="max_harga" type="text" class="form-control ml-1" placeholder="MAX" style="font-size: small; width:50%;" value="<?php if(isset($_SESSION['max_harga'])){
                                echo $_SESSION['max_harga'];
                            }?>">
                        </div>
                </div>
                <!-- <div class="d-flex flex-column mb-2">
                        <span class="mb-1" style="color: darkcyan;">Promo</span>
                        <div class="d-flex row justify-content-start px-2">
                            <div class="border p-2 mr-1 mb-1" style="font-size: small;">Diskon</div>
                            <div class="border p-2 mr-1 mb-1" style="font-size: small;">Voucher</div>
                            <div class="border p-2 mr-1 mb-1" style="font-size: small;">Jual Cepat</div>
                        </div>
                </div> -->
                <div class="d-flex flex-column mb-2">
                        <span class="mb-1" style="color: darkcyan;">Kondisi</span>
                        <select class="form-control" name="kondisi" id="">
                            <option value="">Pilih Kondisi</option>
                            <option value="Baru" <?php if(isset($_SESSION['kondisi'])){
                                if($_SESSION['kondisi'] == "Baru"){
                                    echo "selected";
                                }
                            }?>>Baru</option>
                            <option value="Bekas" <?php if(isset($_SESSION['kondisi'])){
                                if($_SESSION['kondisi'] == "Bekas"){
                                    echo "selected";
                                }
                            }?>>Bekas</option>
                        </select>
                </div>
                <div class="d-flex flex-row py-3">
                        <button type="submit" name="reset" class="btn mr-2" style="color: darkcyan; border:1px solid darkcyan; background:white;  width:150px;">Reset</button>
                        <button type="submit" name="terapkan" class="btn" style="background: darkcyan; color:white; border:1px solid darkcyan;  width:150px;">Terapkan</button>
                </div>
                </form>

            </div>
        </div>
    </div>

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


</body>
</html>