<?php

/*
FileName:healthcheck.php
Version:1.1
Purpose:to check health condition of a server and database
Devloper:rishitha
*/

$db_host = '192.168.0.26';
$db_user = 'qptms';

$db_password = 'qptms@123';

$db_name = $_SESSION['corp_code'];

//$db_name = 'qptmsnew';

$con = mysqli_connect( $db_host, $db_user, $db_password, $db_name );

if ( $con ) {
    $status = 'True';

} else {
    $status = 'False';

}
$result['status'] = $status;

echo json_encode( $result );
?>
