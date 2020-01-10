<?php
//include 'DatabaseConfig1.php';
include 'DBconnection.php';
// Create connection
//$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);

    $nes=$_POST['fcmid'];
    $type=$_POST['type'];
    if($type=='user'){
   $query=mysqli_query($conn,"INSERT INTO token(token_key,type) VALUES('$nes','0');");}
   
   if($type=='admin'){$query=mysqli_query($conn,"INSERT INTO tokens(token_key,type) VALUES('$nes','1');");}
?>
