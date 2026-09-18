<?php
    $id_prov = $_GET['id_prov'];
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.rajaongkir.com/starter/city?province=$id_prov",
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

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $response_arr = json_decode($response, TRUE);
        $data_kotaKab = $response_arr['rajaongkir']['results'];


?>
        <option value="" selected>--Kota/Kabupaten--</option>
        <?php
            foreach ($data_kotaKab as $key => $row) {
        ?>
            <option value="<?= $row['type']." ".$row['city_name'] ?>" >
                <?= $row['type']." ".$row['city_name']; ?>
            </option>
        <?php
            }
        ?>

<?php
    }
?>
