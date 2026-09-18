<?php
require "koneksi.php";

// fungsi ambil satu data tanpa relation
function ambil_1_data($table,$field,$key,$get_value)
{
    global $con;
    $query = mysqli_query($con, "SELECT * FROM $table WHERE $field = '$key' ");
    $row = mysqli_fetch_assoc($query);
    if ($row["$get_value"] != null) 
    {
        return $row["$get_value"];
    }
    
}


// fungsi upload gambar
function upload_gambar($table, $tmp_field)
{
    global $con;

    $namaFile = $_FILES['file_img']['name'];
    $ukuranFile = $_FILES['file_img']['size'];
    $errorFile = $_FILES['file_img']['error'];
    $tmpName = $_FILES['file_img']['tmp_name'];

    $extensiFileValid = ['jpg','jpeg','png','gif','svg'];

    // ambil ekstensi file 
    $extensiFile = explode('.', $namaFile);
    $extensiFile = strtolower(end($extensiFile));


    // validasi gambar sudah dipilih apa belum
    if ($errorFile === 4) 
    {
        $error = "File belum dipilih!"; 
        echo "<script>
                alert('$error');
              </script>";
    }
    // cek apakah ekstensi file sesuai
    elseif(!in_array($extensiFile, $extensiFileValid))
    {
        $error = "File yang kamu upload tidak valid!"; 
        echo "<script>
                alert('$error');
            </script>";
    }
    // apakah ukuran terlalu besar
    elseif( $ukuranFile > 5000000 )
    {
        echo "<script>
                alert('Ukuran file maksimal 5 MB!');
            </script>";
    }
    else
    {
        // cek apakah nama file ada yang sama
        $filenameDB = mysqli_query($con, "SELECT * FROM $table WHERE $tmp_field = '$namaFile' ");
        if(mysqli_num_rows($filenameDB) > 0)
        {
            // ambil nama file tanpa extensi
            $namaFileDBArray = explode(".", $namaFile);
            $nama_depan = array_search(end($namaFileDBArray), $namaFileDBArray);
            unset($namaFileDBArray[$nama_depan]);
            $namaFileDBnotExtensi = implode("", $namaFileDBArray);
            // tampung nama file dengan extensi ke dalam var
            $namaFileBaru = $namaFileDBnotExtensi.'_'.rand(1,1000).'.'.$extensiFile;
        }
        else
        {
            $namaFileBaru = $namaFile;
        }
        
        // pindah file dengan nama file baru dan return nama file baru
        move_uploaded_file($tmpName, 'assets/foto/'.$namaFileBaru);
        return $namaFileBaru;

    }
}

 

// fungsi upload gambar multiple
function upload_gambar_multiple($table, $tmp_field)
{
    global $con;
    $jmlFile = count($_FILES['file_img']['name']);
    $namaFileBaru = [];

    for($i = 0; $i < $jmlFile; $i++)
    {
        $namaFile = $_FILES['file_img']['name'][$i];
        $ukuranFile = $_FILES['file_img']['size'][$i];
        $errorFile = $_FILES['file_img']['error'][$i];
        $tmpName = $_FILES['file_img']['tmp_name'][$i];

        $extensiFileValid = ['jpg','jpeg','png','gif','svg'];

        // ambil ekstensi file 
        $extensiFile = explode('.', $namaFile);
        $extensiFile = strtolower(end($extensiFile));


        // validasi gambar sudah dipilih apa belum
        if ($errorFile === 4) 
        {
            $error = "File belum dipilih!"; 
            echo "<script>
                    alert('$error');
                </script>";
        }
        // cek apakah ekstensi file sesuai
        elseif(!in_array($extensiFile, $extensiFileValid))
        {
            $error = "File yang kamu upload tidak valid!"; 
            echo "<script>
                    alert('$error');
                </script>";
        }
        // apakah ukuran terlalu besar
        elseif( $ukuranFile > 10000000 )
        {
            echo "<script>
                    alert('Ukuran total file maksimal 10 MB!');
                </script>";
        }
        else
        {
            // cek apakah nama file ada yang sama
            $filenameDB = mysqli_query($con, "SELECT * FROM $table WHERE $tmp_field = '$namaFile' ");
            if(mysqli_num_rows($filenameDB) > 0)
            {
                // ambil nama file tanpa extensi
                $namaFileDBArray = explode(".", $namaFile);
                $nama_depan = array_search(end($namaFileDBArray), $namaFileDBArray);
                unset($namaFileDBArray[$nama_depan]);
                $namaFileDBnotExtensi = implode("", $namaFileDBArray);

                // tampung nama file dengan extensi ke dalam var
                $namaFileBaru[] = $namaFileDBnotExtensi.'_'.rand(1,10000).'.'.$extensiFile;
            }
            else
            {
                $namaFileBaru[] = $namaFile;
            }
           

            // pindah file dengan nama file baru dan return nama file baru
            move_uploaded_file($tmpName, 'assets/foto/'.$namaFileBaru[$i]);
        }
    }
    return $namaFileBaru;
}


// konversi rupiah
function rupiah($angka){
    return "Rp".number_format($angka,0,'.','.');
}

// hitung jumlah data suatu transaksi
function jml_transaksi($stt, $id, $jenis_akun){
    global $con;
    if($jenis_akun == "penjual")
    {
        $result = mysqli_query($con, "SELECT * FROM produk_transaksi WHERE id_penjual = '$id' AND stt_transaksi = '$stt'");
        return mysqli_num_rows($result);
    }
    elseif($jenis_akun == "pembeli")
    {
        $result = mysqli_query($con, "SELECT * FROM produk_transaksi WHERE id_pembeli = '$id' AND stt_transaksi = '$stt'");
        return mysqli_num_rows($result);
    }
    
}


// fungsi cek diskon 
function cekDiskon($id_produk,$valueOrBoolean){
    global $con;
    $result = mysqli_query($con, "SELECT * FROM produk_diskon WHERE id_produk = '$id_produk' ");
    if($valueOrBoolean == "bool"){
        if(mysqli_num_rows($result) > 0){
            return TRUE;
        }else{
            return FALSE;
        }
    }else{
        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
            return $row["$valueOrBoolean"];
        }else{
            return FALSE;
        }
    }
    

}

// fungsi cek diskon 
function cekJualCepat($id_produk,$valueOrBoolean){
    global $con;
    $result = mysqli_query($con, "SELECT * FROM produk_jualcepat WHERE id_produk = '$id_produk' ");
    if($valueOrBoolean == "bool"){
        if(mysqli_num_rows($result) > 0){
            return TRUE;
        }else{
            return FALSE;
        }
    }else{
        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);
            return $row["$valueOrBoolean"];
        }else{
            return FALSE;
        }
    }
    
}

 
// fungsi lastseen (notif pada penjualan/pesanan)
    function cekUpdateTransaksi($id_akun, $stt_akun, $stt_transaksi, $action){
        global $con;
        if($action == "update"){
            $result = mysqli_query($con, "SELECT * FROM lastseen_transaksi INNER JOIN produk_transaksi ON lastseen_transaksi.id_transaksi = produk_transaksi.id_transaksi WHERE lastseen_transaksi.$stt_akun = '$id_akun' AND lastseen_transaksi.stt_transaksi = '$stt_transaksi' ");
            if(mysqli_num_rows($result) > 0){
                return mysqli_num_rows($result);
            }
        }elseif($action == "seen"){
            mysqli_query($con, "DELETE FROM lastseen_transaksi WHERE $stt_akun = '$id_akun' AND stt_transaksi = '$stt_transaksi'");
        }

    }

// fungsi cek jml produk terjual
function cekTerjual($id_produk){
    global $con;
    $jmlTerjual = 0;
    $result = mysqli_query($con, "SELECT * FROM produk_transaksi WHERE id_produk = '$id_produk' AND stt_transaksi = 'selesai' ");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            $jmlTerjual += $row['jml_pembelian'];
        }
        return $jmlTerjual;
    }else{
        return mysqli_num_rows($result);
    }
}


// fungsi cek jml produk terjual yang dijual cepat
function cekTerjualCepat($id_produk){
    global $con;
    $jmlTerjual = 0;
    $harga_jualcepat = ambil_1_data("produk_jualcepat","id_produk",$id_produk,"harga_baru");
    $result = mysqli_query($con, "SELECT * FROM produk_transaksi WHERE id_produk = '$id_produk' AND stt_transaksi = 'selesai' AND harga_peritem = '$harga_jualcepat'");
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            $jmlTerjual += $row['jml_pembelian'];
        }
        return $jmlTerjual;
    }else{
        return mysqli_num_rows($result);
    }
}

// ambil alamat penjual berdasarkan id produk
function lokasiProduk($id_produk){
    global $con;
    $res_id_penjual = mysqli_query($con, "SELECT id_login FROM produk WHERE id_produk = '$id_produk' ");
    $id_penjual = mysqli_fetch_assoc($res_id_penjual);
    $id_penjual = $id_penjual['id_login'];
    $res_profil = mysqli_query($con, "SELECT alamat FROM profil WHERE id_login = '$id_penjual'");
    $alamat = mysqli_fetch_assoc($res_profil);
    $alamat = $alamat['alamat'];
    if(!empty($alamat)){
        $alamat_arr = explode(',',$alamat);
        return $alamat_arr[count($alamat_arr) - 2 ];
    }else{
        return "Lokasi tidak ada";
    }
}

// hitung jml produk dikeranjang berdasarkan id pembeli
function jmlDiKeranjang($id_akun){
    global $con;
    $res = mysqli_query($con, "SELECT * FROM keranjang WHERE id_pembeli = '$id_akun'");
    return mysqli_num_rows($res);
}

// favorit
// cek jml favorit berdasarkan id_login || <<null>>, <<id_login>>, <<jumlah>>
// input/del/cek id produk ini ke favorit || <<id_produk>>, <<id_login>>, <<input/delete/cek>>
function favorit($id_produk, $id_login, $action){
    global $con;
    if($action == "jumlah"){
        $res = mysqli_query($con, "SELECT * FROM favorit WHERE id_login = '$id_login'");
        return mysqli_num_rows($res);
    }elseif($action == "input"){
        $res = mysqli_query($con, "INSERT INTO favorit VALUES ('','$id_login','$id_produk') ");
        if(mysqli_affected_rows($con) > 0){
            return true;
        }else{
            return false;
        }
    }elseif($action == "delete"){
        $res = mysqli_query($con, "DELETE FROM favorit WHERE id_produk = '$id_produk' AND id_login = '$id_login' ");
        
    }else{
        $res = mysqli_query($con, "SELECT * FROM favorit WHERE id_produk = '$id_produk' AND id_login = '$id_login' ");
        if(mysqli_num_rows($res) > 0){
            return true;
        }else{
            return false;
        }
    }

}


// potong string jika kepanjangan
function cutString($string, $limit){
    if(strlen($string) > $limit){
        return substr($string, 0, $limit)."..";
    }else{
        return $string;
    }
}

// WHATSAPP VERIFIKASI
function wa($id_login,$pesan,$link){
    // cek apakah akun sudah memasukan no wa
    $noWa = ambil_1_data("profil","id_login","$id_login","hp");
    if(!empty($noWa) AND !empty($pesan)){
        // cek apakah nomor dimulai dari 0
        $noWa_arr = str_split($noWa,2);
        if($noWa_arr[0] == 62){
            return "https://wa.me/$noWa?text=$pesan - $link";
        }else{
            // jika nomor mulai dari 0 akan dilakukan konversi 62
            $noWa_arr[0] = 628;
            $noWa = implode("",$noWa_arr);
            return "https://wa.me/$noWa?text=$pesan - $link";
        }
        
    }else{
        return "#wa404";
    }

}


// WHATSAPP VERIFIKASI NO LOGIN
function wa_nologin($id_transaksi,$pesan,$link){
    // cek apakah akun sudah memasukan no wa
    $noWa = ambil_1_data("produk_transaksi","id_transaksi","$id_transaksi","hp_pembeli");
    if(!empty($noWa) AND !empty($pesan)){
        // cek apakah nomor dimulai dari 0
        $noWa_arr = str_split($noWa,2);
        if($noWa_arr[0] == 62){
            return "https://wa.me/$noWa?text=$pesan - $link";
        }else{
            // jika nomor mulai dari 0 akan dilakukan konversi 62
            $noWa_arr[0] = 628;
            $noWa = implode("",$noWa_arr);
            return "https://wa.me/$noWa?text=$pesan - $link";
        }
        
    }else{
        return "#wa404";
    }

}




?>

<!-- url kebutuhan redirect wa -->
<?php $url_full = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>