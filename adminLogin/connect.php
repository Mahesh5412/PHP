<?php
/*
*Description : Connecting Main DataBase
*/


$db_host = '192.168.0.26';
$db_user = 'qptms';
$db_password = 'qptms@123';
$db_name = $_SESSION['corp_code'];

$con = mysqli_connect( $db_host, $db_user, $db_password, $db_name );

?>
