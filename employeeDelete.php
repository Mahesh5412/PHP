<?php

/*
FileName:employeeDelete.php
Version:1.0.1
Purpose:to delete the employee
Devloper:krishna tulasi
*/

session_start();

$json = file_get_contents( 'php://input' );
$obj = json_decode( $json, true );

$corp_code 		 = $obj['crop'];
$_SESSION['corp_code'] = $corp_code;

include 'connect.php';

// for Logs 
$logfile = 'log/log' .date('d-M-Y') . '.log';

date_default_timezone_set( 'Asia/Kolkata' );

$time = date( 'Y-m-d H:i:s' );

$empid  = $obj['empid'];
$action = $obj['action'];

//to delete the employee
if ( $action == 'delete' ) {
    $sql = "delete  from `emsUsers` where  empId = '$empid'";

    $sql_res = mysqli_query( $con, $sql )  or (logToFile($logfile," to delete the employee - employeeDelete.php"));

    if ( $sql_res ) {

        $result['status'] = 'True';
        $result['message'] = 'success';

    } else {

        $result['status'] = 'false';
        $result['message'] = 'failed to delete';

    }
} else {
    $result['status'] = 'false';
    $result['message'] = 'failed to delete';
}

header( 'content-type:application/json' );
echo json_encode( $result );

?>

