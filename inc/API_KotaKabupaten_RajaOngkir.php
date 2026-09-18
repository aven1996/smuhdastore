<?php
    if(isset($idprov)){
        $id = $idprov;
    }else{
        $id = 1;
    }
    $curlX = curl_init();
    curl_setopt_array($curlX, array(
    CURLOPT_URL => "https://api.rajaongkir.com/starter/city?province=$id",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "key: 69a485efa892c44da74cc20ec0eda1aa"
    ),
    ));

    $responseX = curl_exec($curlX);
    $errX = curl_error($curlX);

    curl_close($curlX);

    if ($errX) {
        echo "cURL Error #:" . $errX;
    } else {
        $response_arrX = json_decode($responseX, TRUE);
        $data_kotaKab = $response_arrX['rajaongkir']['results'];
    }

?>
