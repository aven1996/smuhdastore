<?php
    require "inc/koneksi.php";
    require "inc/fungsi.php";
    require "inc/API_Provinsi_RajaOngkir.php";
    

    session_start();
    // cek jika belum login/session belum dibuat
    if (!isset($_SESSION['admin'])) 
    {
        header("Location: login.php");
    }
    
    $id_akun = $_SESSION['admin'];
    $stt_akun = $_SESSION['stt_akun'];


    // update nama
    if(isset($_POST['simpan_nama']))
    {
        $nama = htmlspecialchars($_POST['nama']);
        if(!empty($nama))
        {
            mysqli_query($con, "UPDATE profil SET nama_profil = '$nama' WHERE id_login = '$id_akun' ");
        }
        else
        {
            echo "Data belum diisi!";
        }
    } 
    // update username
    if(isset($_POST['simpan_username']))
    {
        $username = htmlspecialchars($_POST['username']);
        if(!empty($username))
        {
            mysqli_query($con, "UPDATE akun_login SET username = '$username' WHERE id_login = '$id_akun' ");
        }
        else
        {
            echo "Data belum diisi!";
        }
    }

    // update jenis kelamin
    if(isset($_POST['simpan_jenis_kelamin']))
    {
        $jk = $_POST['jenis_kelamin'];
        if(!empty($jk))
        {
            mysqli_query($con, "UPDATE profil SET jenis_kelamin = '$jk' WHERE id_login = '$id_akun' ");
        }
        else
        {
            echo "Data belum diisi!";
        }
    }

    // update nomor whatsapp
    if(isset($_POST['simpan_no_hp']))
    {
        $hp = htmlspecialchars($_POST['no_hp']);
        if(!empty($hp))
        {
            mysqli_query($con, "UPDATE profil SET hp = '$hp' WHERE id_login = '$id_akun' ");
        }
        else
        {
            echo "Data belum diisi!";
        }
    }

    // update email
    if(isset($_POST['simpan_email']))
    {
        $email = htmlspecialchars($_POST['email']);
        if(!empty($email))
        {
            mysqli_query($con, "UPDATE akun_login SET email = '$email' WHERE id_login = '$id_akun' ");
        }
        else
        {
            echo "Data belum diisi!";
        }
    }

    // update alamat
    if(isset($_POST['simpan_alamat']))
    {
        $provinsi = $_POST['provinsi'];
        $kota_kabupaten = $_POST['kota_kabupaten'];
        $alamat = htmlspecialchars($_POST['alamat']);

        if(!empty($provinsi) AND !empty($kota_kabupaten) AND !empty($alamat))
        {
             $alamat_lengkap = $alamat.", ".$kota_kabupaten.", ".$provinsi;
             mysqli_query($con, "UPDATE profil SET alamat = '$alamat_lengkap' WHERE id_login = '$id_akun' ");
        }
        else
        {
            echo "Data belum diisi!";
        }
    }

    // update password
    if(isset($_POST['simpan_password']))
    {
        $password = htmlspecialchars($_POST['password_baru']);
        $password_ulangi = htmlspecialchars($_POST['password_ulangi']);
        if($password == $password_ulangi)
        {
            $password = password_hash($password, PASSWORD_DEFAULT);
            mysqli_query($con, "UPDATE akun_login SET `password` = '$password' WHERE id_login = '$id_akun' ");
        }
        else
        {
            echo "password ulangi harus sama dengan password baru!";
        }
    }

    // update poto
    if (isset($_POST['simpan_poto'])) {
		
		$namaFile = upload_gambar("profil", "foto_profil");
		
		
		if (!$namaFile) {
			$error = "File gagal diupload!"; 
			echo "<script>
					alert('$error');
				</script>";
		}else{
			mysqli_query($con, "UPDATE profil SET foto_profil = '$namaFile' WHERE id_login = '$id_akun' ");
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Smuhdastore Belaja Nyaman dan Terpercaya</title>
    <link rel="shortcut icon" href="assets/img/fav.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/style_login.css">
    <link rel="stylesheet" href="assets/css/profil.css">
    <link rel="stylesheet" href="assets/icons/icomoon4/style.css">
    <link rel="stylesheet" href="assets/icons/icomoon1/icon.css">
    <link rel="stylesheet" href="assets/icons/icomoon2/icon2.css">
    <link rel="stylesheet" href="assets/icons/icomoon3/style.css">

    <style>
        body{
            background: darkcyan;
        }
        .container .item:hover span{
            color: darkcyan;
        }
    </style>
</head>

<body> 

<div class="container bg-white d-flex flex-column p-5 shadow my-5">
    <nav class="d-flex align-items-center mb-3">
        <a href="admin_penjual.php" style="color:darkcyan; font-size: 24px; margin-right:20px;text-decoration:none;">
            <b class="icon-arrow-left2 mr-3"></b>
            <span class="text-dark">Profil</span>
        </a> 
    </nav>
    <div class="cov-poto d-flex flex-row justify-content-center align-items-center rounded-circle mb-2" style="width: 150px; height: 150px;">
        <?php 
            $poto = ambil_1_data("profil", "id_login","$id_akun","foto_profil");
            if (!empty($poto)) :

        ?>
            <img class="img-poto h-100" style="width: auto;" src="assets/foto/<?= $poto; ?>" alt="<?= $poto; ?>" title="<?= $poto; ?>"> 
        <?php
                
            else:
        ?>
            <img class="img-poto w-100" src="assets/img/poto-profil.png" alt="poto-profil.png">

        <?php endif; ?>

    </div>
    <a href="#" class="d-flex justify-content-center align-items-center text-secondary"  data-toggle="modal" data-target="#editpoto"><b class="icon-pencil mr-1"></b><span>Edit Poto</span></a>

    <div class="item d-flex justify-content-between py-3" data-toggle="modal" data-target="#editnama">
        <span>Nama</span>
        <span>
            <?php
                $nama =  ambil_1_data("profil", "id_login","$id_akun","nama_profil"); 
                if(!empty($nama) AND strlen($nama) > 20){
                    substr($nama,0,20)."..";
                }else{
                    echo $nama;
                }
            ?> 
            <b class="icon-play3 ml-2 text-secondary"></b>
        </span>
    </div>
    <div class="item d-flex justify-content-between py-3" data-toggle="modal" data-target="#editusername">
        <span>Username</span>
        <span>
            <?php 
                echo ambil_1_data("akun_login", "id_login","$id_akun","username"); 
            ?>  
            <b class="icon-play3 ml-2 text-secondary"></b>
        </span>
    </div>
    <div class="item d-flex justify-content-between py-3" data-toggle="modal" data-target="#editjeniskelamin">
        <span>Jenis Kelamin</span>
        <span> 
            <?php 
                echo ambil_1_data("profil", "id_login","$id_akun","jenis_kelamin"); 
            ?>  
            <b class="icon-play3 ml-2 text-secondary"></b>
        </span>
    </div>
    <div class="item d-flex justify-content-between py-3" data-toggle="modal" data-target="#editnowa">
        <span>Handphone</span>
        <span>
            <?php 
                echo ambil_1_data("profil", "id_login","$id_akun","hp"); 
            ?>  
            <b class="icon-play3 ml-2 text-secondary"></b>
        </span>
    </div>
    <div class="item d-flex justify-content-between py-3" data-toggle="modal" data-target="#editemail">
        <span>Email</span>
        <span>
            <?php 
                if((strlen(ambil_1_data("akun_login", "id_login","$id_akun","email"))) > 20){
                    echo substr(ambil_1_data("akun_login", "id_login","$id_akun","email"),0,20)."..";
                }else{
                    echo ambil_1_data("akun_login", "id_login","$id_akun","email");
                }
                 
            ?>  
            <b class="icon-play3 ml-2 text-secondary"></b>
        </span>
    </div>
    <div class="item d-flex justify-content-between py-3" data-toggle="modal" data-target="#editalamat">
        <span>Alamat</span>
        <span>
            <?php
                $data_alamat = ambil_1_data("profil", "id_login","$id_akun","alamat");
                if(!empty($data_alamat))
                {
                    if(strlen($data_alamat) > 24){
                        echo substr($data_alamat,0,25)."..";
                    }else{
                        echo $data_alamat;
                    }
                    
                }
                
            ?>  
            <b class="icon-play3 ml-2 text-secondary"></b>
        </span>
    </div>
    <!-- <div class="item d-flex justify-content-between py-3">
        <span>Rekening Bank</span>
    </div> -->

    <div class="item d-flex justify-content-between py-3 mb-3" data-toggle="modal" data-target="#editpassword" style="border-bottom: 0;">
        <span class="text-danger">Ubah Password</span>
    </div>

    <a href="logout.php">
    <div class="item text-center py-3 bg-danger rounded" style="border-bottom: 0;">
        <span class="text-white">Logout</span>
    </div>
    </a>
</div> 

<div class="container text-white text-center mb-3">
    <span>Butuh bantuan? <a href="index.php#footer2" style="font-weight: bold; color:white;">Hubungi kami</a></span>
</div>



<!-- Modal Edit Profil -->
    <!-- poto -->
    <div class="modal fade" id="editpoto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content p-3" style="overflow-y: auto;">
                <div class="d-flex justify-content-star pb-2 mb-2">
                    <span class="font-weight-bold title">Poto</span>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="file" name="file_img" class="form-control mb-2" required>
                    <button type="submit" name="simpan_poto" class="btn" style="background: darkcyan; color:white;">Simpan</button>
                </form>
            </div>
        </div>
    </div>

    <!-- nama -->
    <div class="modal fade" id="editnama" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content p-3" style="overflow-y: auto;">
                <div class="d-flex justify-content-star pb-2 mb-2">
                    <span class="font-weight-bold title">Nama</span>
                </div>
                <form action="" method="post">
                    <input type="text" name="nama" class="form-control mb-2" value="<?= ambil_1_data("profil", "id_login","$id_akun","nama_profil");?>">
                    <button type="submit" name="simpan_nama" class="btn" style="background: darkcyan; color:white;">Simpan</button>
                </form>
            </div>
        </div>
    </div>

    <!-- username -->
    <div class="modal fade" id="editusername" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content p-3" style="overflow-y: auto;">
                <div class="d-flex justify-content-star pb-2 mb-2">
                    <span class="font-weight-bold title">Username</span>
                </div>
                <form action="" method="post">
                    <input type="text" name="username" class="form-control mb-2" value="<?= ambil_1_data("akun_login", "id_login","$id_akun","username");?>">
                    <button type="submit" name="simpan_username" class="btn" style="background: darkcyan; color:white;">Simpan</button>
                </form>
            </div>
        </div>
    </div>

    <!-- jenis kelamin-->
    <div class="modal fade" id="editjeniskelamin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content p-3" style="overflow-y: auto;">
                <div class="d-flex justify-content-star pb-2 mb-2">
                    <span class="font-weight-bold title">Jenis Kelamin</span>
                </div>
                <form action="" method="post">
                    <select name="jenis_kelamin"  class="custom-select mb-2">
                        <?php
                            // fungsi cek selected jenis kelamin
                            function cekSttJK($jk)
                            {
                                global $id_akun;

                                $dataJK = ambil_1_data("profil", "id_login", "$id_akun", "jenis_kelamin");
                                if($dataJK == $jk)
                                {
                                    return "selected";
                                }
                            }
                        ?>
                        <option >Pilih Jenis Kelamin</option>
                        <option value="Laki-laki" <?= cekSttJK("Laki-laki"); ?>>Laki-laki</option>
                        <option value="Perempuan" <?= cekSttJK("Perempuan"); ?>>Perempuan</option>
                        <option value="Lainnya" <?= cekSttJK("Lainnya"); ?>>Lainnya</option>
                    </select>
                <button type="submit" name="simpan_jenis_kelamin" class="btn" style="background: darkcyan; color:white;">Simpan</button>
                </form>
            </div>
        </div>
    </div>

    <!-- nomor hp -->
    <div class="modal fade" id="editnowa" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content p-3" style="overflow-y: auto;">
                <div class="d-flex justify-content-star pb-2 mb-2">
                    <span class="font-weight-bold title">Nomor WhatsApp</span>
                </div>
                <form action="" method="post">
                    <input type="text" name="no_hp" class="form-control mb-2" value="<?= ambil_1_data("profil", "id_login","$id_akun","hp");?>">
                    <button type="submit" name="simpan_no_hp" class="btn" style="background: darkcyan; color:white;">Simpan</button>
                </form>
            </div>
        </div>
    </div>

    <!-- email -->
    <div class="modal fade" id="editemail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content p-3" style="overflow-y: auto;">
                <div class="d-flex justify-content-star pb-2 mb-2">
                    <span class="font-weight-bold title">Email</span>
                </div>
                <form action="" method="post">
                    <input type="text" name="email" class="form-control mb-2" value="<?= ambil_1_data("akun_login", "id_login","$id_akun","email");?>">
                    <button type="submit" name="simpan_email" class="btn" style="background: darkcyan; color:white;">Simpan</button>
                </form>
            </div>
        </div>
    </div>
    <!-- alamat-->
    <div class="modal fade" id="editalamat" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content p-3" style="overflow-y: auto;">
                <div class="d-flex justify-content-star pb-2 mb-2">
                    <span class="font-weight-bold title">Alamat</span>
                </div>
                <form action="" method="post">
                        <?php
                            // fungsi cek selected Alamat
                            function cekSttAlamat($region, $value)
                            {
                                global $id_akun;
                                // semua alamat
                                $dataAlamat = ambil_1_data("profil", "id_login", "$id_akun", "alamat");

                                if(!empty($dataAlamat))
                                {
                                    // ambil povinsi dari alamat
                                    $provinsi = explode(",",$dataAlamat);
                                    $provinsi_v = $provinsi[count($provinsi)-1];
                                    $provinsi_v = trim($provinsi_v);

                                    // ambil kota kab dari alamat
                                    $kota_kab = explode(",",$dataAlamat);
                                    $kota_kab_v = $kota_kab[count($kota_kab)-2];
                                    $kota_kab_v = trim($kota_kab_v);

                                    if ($region == "provinsi") 
                                    {
                                        if($value == $provinsi_v)
                                        {
                                            return "selected";
                                        }
                                    }
                                    elseif($region == "kota_kabupaten")
                                    {
                                        if($value == $kota_kab_v)
                                        {
                                            return "selected";
                                        }
                                    }
                                }
                            }

                            // ambil alamat depan kecuali provinsi dan kabupaten
                            $dataAlamat = ambil_1_data("profil", "id_login", "$id_akun", "alamat");
                            if(!empty($dataAlamat))
                            {
                                $alamat_depan = explode(",",$dataAlamat);
                                unset($alamat_depan[count($alamat_depan)-1]);
                                unset($alamat_depan[count($alamat_depan)-1]);
                                $alamat_depan = implode(", ",$alamat_depan);
                            }
                            
                        ?>
                    <select name="provinsi" id="" class="custom-select mb-2" required>
                        <option value="" selected>--Provinsi--</option>
                        <?php
                            foreach ($data_provinsi as $key => $row) {
                        ?>
                            <option value="<?= $row['province'] ?>" id_prov=<?= $row['province_id'] ?> 
                            <?php 
                                if(cekSttAlamat("provinsi",$row['province']) == "selected"){
                                    echo "selected";
                                    if(!isset($_GET['id_prov'])){
                                        $idprov = $row['province_id'];
                                    }
                                } 
                            ?>>
                                <?= $row['province']; ?>
                            </option>
                        <?php
                            }
                        ?>
                    </select>
                    
                    <?php require "inc/API_KotaKabupaten_RajaOngkir.php"; ?>
                    <select name="kota_kabupaten" id="" class="custom-select mb-2" required>
                        <option value="" selected>--Kota/Kabupaten--</option>
                        <?php
                            foreach ($data_kotaKab as $key => $row) {
                        ?>
                            <option value="<?= $row['type']." ".$row['city_name'] ?>" <?= cekSttAlamat("kota_kabupaten",$row['type']." ".$row['city_name']); ?>>
                                <?= $row['type']." ".$row['city_name']; ?>
                            </option>
                        <?php
                            }
                        ?>
                        
                    </select>
                    <input type="text" name="alamat" class="form-control mb-2" placeholder="Alamat lengkap" value="<?php if(isset($alamat_depan)){ echo $alamat_depan; }?>">
                <button type="submit" name="simpan_alamat" class="btn" style="background: darkcyan; color:white;">Simpan</button>
                </form>
            </div>
        </div>
    </div>
    <!-- password -->
    <div class="modal fade" id="editpassword" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content p-3" style="overflow-y: auto;">
                <div class="d-flex justify-content-star pb-2 mb-2">
                    <span class="font-weight-bold title">Password</span>
                </div>
                <form action="" method="post">
                    <input type="password" name="password_baru" class="form-control mb-2" placeholder="Password Baru" required>
                    <input type="password" name="password_ulangi" class="form-control mb-2" placeholder="Ulangi Password Baru" required>
                    <button type="submit" name="simpan_password" class="btn" style="background: darkcyan; color:white;">Simpan</button>
                </form>
            </div>
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

 <!-- ajax saat provinsi dipilih akan otomatis memuat kota/kab sesuai provinsi yang terpilih -->
 <script type="text/javascript">
    $("select[name=provinsi]").on("change",function(){
        var id_prov = $("option:selected",this).attr("id_prov");
        $.ajax({
            type: "get",
            url: "inc/API_KotaKabupaten_RajaOngkir_AJAX.php?id_prov="+id_prov,
            success:function(res){
                $("select[name=kota_kabupaten]").html(res);
            }
        });
    });

 </script>
</body>
</html>