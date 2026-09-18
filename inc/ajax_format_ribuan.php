<?php
// format angka ribuan
$harga = $_GET['v'];
if(!empty($harga))
{
    $hargaBaru = 'Rp'. number_format($harga, 0,'.','.');
    echo $hargaBaru;
}
?>

 