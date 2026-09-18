<?php
    require "koneksi.php";
    require_once "../google-api/vendor/autoload.php";
    require "config_googleAPI.php";

    if(isset($_SESSION['access_token'])){
        $gClient->setAccessToken($_SESSION['access_token']);

    }else if(isset($_GET['code'])){
        $token = $gClient->fetchAccessTokenWithAuthCode($_GET['code']);
        $_SESSION['access_token'] = $token;

    }else{
        header("Location: ../login.php");
        exit();
    }

    $oAuth = new Google_Service_Oauth2($gClient);
    $userData = $oAuth->userinfo_v2_me->get();

    // cek apakah email sudah ada pada database
    $email = $userData['email'];
    $db = mysqli_query($con, "SELECT * FROM akun_login WHERE email = '$email'");
    if(mysqli_num_rows($db) > 0){
        // jika sudah ada email berarti langsung buat session admin
        $row = mysqli_fetch_assoc($db);
        // buat session
        $_SESSION['admin'] = $row['id_login'];
        // redirect berdasarkan status akun [penjual/pembeli]
        if ($row['stt_akun'] == "pembeli")
        {  
            $_SESSION['stt_akun'] = "pembeli";
            header("Location: ../admin_pembeli.php");
        }
        elseif($row['stt_akun'] == "penjual")
        {
            $_SESSION['stt_akun'] = "penjual";
            header("Location: ../admin_penjual.php");
        }
        elseif($row['stt_akun'] == "owner")
        {
            $_SESSION['stt_akun'] = "owner";
            header("Location: ../admin_owner.php");
        }
    }else{
        // jika belum ada email berarti tambah data ke akun_login
        $username = $userData['givenName'];
        $email = $userData['email'];
        $nama_profil = $userData['name'];
        $stt_akun = "pembeli";
        $password = password_hash($email, PASSWORD_DEFAULT);

        // cek apakah ada username yang sama kalo ada username akan dikonversi dengan menambahkan angka belakang secara random
        $cek_username = mysqli_query($con, "SELECT * FROM akun_login WHERE username = '$username'");
        if(mysqli_num_rows($cek_username) > 0){
            $username = $username.rand(1,100000);
        }

        // input ke tabel akun_login
        $res = mysqli_query($con, "INSERT INTO akun_login VALUES ('','$username','$password','$stt_akun','$email')");
        if(mysqli_affected_rows($con) > 0){
             // ambil data yang baru ditambahkan tadi
             $query_data = mysqli_query($con, "SELECT * FROM akun_login WHERE username = '$username' ");
             $row = mysqli_fetch_assoc($query_data);
             // buat session
             $_SESSION['admin'] = $row['id_login'];
             $_SESSION['stt_akun'] = $stt_akun;

             // tambah ke profil
             $id_akun = $row['id_login'];
             mysqli_query($con, "INSERT INTO profil VALUES('','$id_akun','$nama_profil','','','','') ");
             
             header("Location: ../admin_pembeli.php");

        }
    }
    

?>