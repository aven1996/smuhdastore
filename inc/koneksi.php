<?php
    $con = mysqli_connect("localhost","root","","smuhdastore_db");

    // Check connection
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

?>