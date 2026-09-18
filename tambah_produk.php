<?php
    require "inc/koneksi.php";
    require "inc/fungsi.php";

    session_start();
    if(isset($_SESSION['admin']) AND $_SESSION['stt_akun'] == "penjual")
    {
        $id_akun = $_SESSION['admin'];
        $stt_akun = $_SESSION['stt_akun'];
        $id_profil = ambil_1_data("profil","id_login",$id_akun,"id_profil");
    }
    else
    {
        header("Location: login.php");
    }


    // tambah produk
    if(isset($_POST['submit']))
    {
        $nama = htmlspecialchars($_POST['nama_produk']);
        $deskripsi = htmlspecialchars($_POST['deskripsi_produk']);
        $kategori = htmlspecialchars($_POST['kategori_produk']);
        $kondisi = htmlspecialchars($_POST['kondisi_produk']);
        $harga = htmlspecialchars($_POST['harga_produk']);
        $stok = htmlspecialchars($_POST['stok_produk']);
        $unixKey = rand(1,1000000);

        mysqli_query($con, "INSERT INTO produk VALUES('','$id_akun','$nama','$deskripsi','$kategori','$kondisi','$harga','$stok','$unixKey') ");

        if(mysqli_affected_rows($con) > 0)
        {
            // ambil data yang baru saja ditambahkan
            $dataProdukBaru = mysqli_query($con, "SELECT * FROM produk WHERE unixKey = '$unixKey'");
            $row_dataProdukBaru = mysqli_fetch_assoc($dataProdukBaru);
            $id_produk = $row_dataProdukBaru['id_produk'];
            // upload gambar produk
            $img_produk = upload_gambar_multiple("gambar_produk","gambar");
            $jml_img = count($img_produk);
            for($i = 0; $i < $jml_img; $i++)
            {
                mysqli_query($con, "INSERT INTO gambar_produk VALUES ('','$id_produk','$img_produk[$i]')");
            }
            if(mysqli_affected_rows($con) > 0)
            {
                header("Location: admin_$stt_akun.php#produk_saya");
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk Baru - Smuhdastore Belaja Nyaman dan Terpercaya</title>
    <link rel="shortcut icon" href="assets/img/fav.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="assets/icons/icomoon1/icon.css">
    <link rel="stylesheet" href="assets/icons/icomoon2/icon2.css">
    <link rel="stylesheet" href="assets/icons/icomoon3/style.css">
    <link rel="stylesheet" href="assets/icons/icomoon4/style.css">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>

<div class="container d-flex flex-column px-0">
    <!-- navigasi -->
    <div class="nav bg-white d-flex justify-content-between align-items-center px-3 py-3 mb-2">
        <span style="color:darkcyan; font-size: 24px;" onclick="window.history.back();"><b class="icon-arrow-left2 mr-2"></b><span class="text-dark">Tambah Produk</span></span>
    </div>

    <form action="" method="post" enctype="multipart/form-data">
    <!-- poto produk -->
    <div class="bg-white d-flex align-items-center p-3 mb-2 pb-3">
        <div class="d-flex flex-row justify-content-start align-items-center">
            <div class="d-flex flex-column justify-content-center align-items-center mr-1" style="width: 100px; height: 100px; border:1px dashed darkcyan; border-radius: 5px; font-size:small; color:darkcyan; position: relative; overflow:hidden;">
                <input id="poto_produk" type="file" name="file_img[]" multiple="multiple" style="position: absolute; top:0; right:0; bottom:0; left:0; opacity:0;" required>
                <span class="icon-camera" style="font-size: 22px;"></span>
            </div>
            <!-- poto produk -->
            <div id="cov_img_view" class="d-flex justify-content-start align-items-center mr-1" style="max-width: 450px ;height: 100px; border-radius: 5px; font-size:small; color:#ff008c; overflow-x:auto; overflow-y:hidden;">
                <!-- <img id="img_view" class="w-100" src="" alt=""> -->
            </div>
        </div>
    </div>
    <!-- nama produk -->
    <div class="aktifitas mb-2">
        <div class="cov-title d-flex justify-content-between lign-items-center bg-white py-2 px-3">
            <div class="title d-flex align-items-center">
                <span><b class="icon-box" style="color:darkcyan;"></b> Nama Produk</span>
            </div>
        </div>
        <input type="text" name="nama_produk" class="form-control border-0" placeholder="Masukkan nama produk" required>
    </div>
    <!-- deskripsi produk -->
    <div class="aktifitas mb-2">
        <div class="cov-title d-flex justify-content-between lign-items-center bg-white py-2 px-3">
            <div class="title d-flex align-items-center">
                <span><b class="icon-clipboard" style="color:darkcyan;"></b> Deskripsi Produk</span>
            </div>
        </div>
        <textarea name="deskripsi_produk" class="w-100 border-0 p-3" style="line-height: 16px;" name="" id="" rows="5" placeholder="Deskripsi produk" required></textarea>
    </div>
    <!-- kategori -->
    <div class="aktifitas mb-2">
        <div class="cov-title d-flex justify-content-between lign-items-center bg-white py-2 px-3">
            <div class="title d-flex align-items-center">
                <span><b class="icon-archive" style="color:darkcyan;"></b> Kategori</span>
            </div>
        </div>
        <select name="kategori_produk" class="custom-select border-0" name="" id="" required>
            <option value="">Pilih Kategori</option>
            <option value="Fashion">Fashion</option>
            <option value="Aksesoris">Aksesoris</option>
            <option value="Elektronik">Elektronik</option>
            <option value="Makanan & Minuman">Makanan & Minuman</option>
            <option value="Perabotan Rumah">Perabotan Rumah</option>
            <option value="Kecantikan">Kecantikan</option>
            <option value="Hobi & Koleksi">Hobi & Koleksi</option>
        </select>
    </div>
    <!-- kondisi -->
    <div class="aktifitas mb-2">
        <div class="cov-title d-flex justify-content-between lign-items-center bg-white py-2 px-3">
            <div class="title d-flex align-items-center">
                <span><b class="icon-star-full" style="color:darkcyan;"></b> Kondisi</span>
            </div>
        </div>
        <select name="kondisi_produk" class="custom-select border-0" name="" id="" required>
            <option value="Baru">Baru</option>
            <option value="Bekas">Bekas</option>
        </select>
    </div>
    <!-- harga -->
    <div class="aktifitas mb-2">
        <div class="cov-title d-flex justify-content-between lign-items-center bg-white py-2 px-3">
            <div class="title d-flex align-items-center">
                <span><b class="icon-dollar" style="color:darkcyan;"></b> Harga</span>
            </div>
        </div>
        <div class="position-relative">
            <input id="harga" name="harga_produk" type="number" class="form-control border-0" style="padding-left: 30px;" placeholder="Atur harga" required>
            <span id="temp_harga" class="position-absolute bg-white" style="left: 10px; top: 5px;"></span>
        </div>
    </div>

    <!-- stok -->
    <div class="aktifitas mb-2">
        <div class="cov-title d-flex justify-content-between lign-items-center bg-white py-2 px-3">
            <div class="title d-flex align-items-center">
                <span><b class="icon-stack" style="color:darkcyan;"></b> Stok</span>
            </div>
        </div>
        <input name="stok_produk" type="number" class="form-control border-0" placeholder="Masukkan nama produk" required>
    </div>

    <!-- model transaksi-->
    <!-- <div class="aktifitas mb-2">
        <div class="cov-title d-flex justify-content-between lign-items-center bg-white py-2 px-3">
            <div class="title d-flex align-items-center">
                <span><b class="icon-shipping" style="color:darkcyan;"></b> Model Transaksi</span>
            </div>
        </div>
        <select class="form-select w-100 border-0 pl-2" multiple aria-label="multiple select example" style="height:70px;">
            <option value="1">Ketemu Langsung</option>
            <option value="2">Transfer Bank</option>
        </select>
    </div> -->
 
    <!-- tombol simpan -->
    <button type="submit" name="submit" class="btn w-100 mb-2 border-0 text-white py-2" style="background:darkcyan;">Jual Sekarang</button>
    </form>
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


<!-- Preview gambar sebelum diupload -->
<script>
    function readURL(input, placeImage)
    {
        if(input.files )
        {
            var jmlFile = input.files.length;
            for(i = 0; i < jmlFile; i++)
            {
                var reader = new FileReader();

                reader.onload = (e)=>{
                    $($.parseHTML("<img style='max-width:100px; max-height:100px; margin-right:5px;'>")).attr('src', e.target.result).appendTo(placeImage);
                    // $("#img_view").attr('src',e.target.result);
                    // $("#cov_img_view").css('border','1px solid silver');
                }

                reader.readAsDataURL(input.files[i]);
            }
            
        }
    }
    $("#poto_produk").change(function(){
        readURL(this, '#cov_img_view');
    });

    // cek jika gambar belum dipilih
    if($("#poto_produk")[0].files.length === 0)
    {   
        $("#cov_img_view").css('border','none');
    }
</script>

<!-- mengubah angka harga menjadi rp secara realtime -->
<script>
    $('#harga').keyup(function(){
        var value_harga = document.getElementById("harga").value;
        var temp_harga = document.getElementById("temp_harga");
        //instansiasi object ajax
        var xhr = new XMLHttpRequest();
        //cek kesiapan ajax
        xhr.onreadystatechange = function(){
            if (xhr.readyState == 4 && xhr.status == 200) {
                temp_harga.innerHTML = xhr.responseText;
            }
        }
        //eksekusi
        xhr.open('get', 'inc/ajax_format_ribuan.php?v=' + value_harga, true);
        xhr.send();
    });
</script>


</body>
</html>