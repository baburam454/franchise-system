<?php
    $conn = mysqli_connect("localhost", "root","","votingsystem");
    if(!$conn){
        die("connection failed: ". mysqli_connect_error());
    }
    /*else{
        echo "Connected";
    }*/
 ?>