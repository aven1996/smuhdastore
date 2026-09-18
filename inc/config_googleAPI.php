<?php
    session_start();
    

    $gClient = new Google_Client();
    $gClient->setClientId("971431054520-7g2eq6985uq5ei42bs3h4dkk3psttc1u.apps.googleusercontent.com");
    $gClient->setClientSecret("YvKlwzkCbD76OjqD0nzCstPw");
    $gClient->setApplicationName("Smuhdastore");
    $gClient->setRedirectUri("http://localhost/smuhdastore/inc/g-callback.php");
    $gClient->addScope("https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email ");


?>