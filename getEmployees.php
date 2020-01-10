<?php

/*
FileName:getEmployees.php
Version:1.0.1
Purpose:to list the employees details
Devloper:krishna tulasi,jagadeesh
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

$sql = 'select * from emsUsers';
$sql_res = mysqli_query( $con, $sql ) or (logToFile($logfile," for getting empolyee details - getEmployees.php"));

$r = 0;

while( $row = mysqli_fetch_assoc( $sql_res ) )
{

    $item['id']			 = $row['empId'];
    $item['name']			 = $row['fullName'];
    $item['mobileNumber']	 = $row['mobileNumber'];
    $item['email']			 = $row['email'];
    $item['userName']		 = $row['userName'];
    $item['password']		 = $row['password'];
    $item['role']			 = $row['role'];
    $item['designation']	 = $row['designation'];
    $item['team']			 = $row['team'];
    $item['createdBy']		 = $row['createdBy'];
    $item['createdDate']	 = $row['createdDate'];
    $item['modifiedBy']	 = $row['modifiedBy'];
    $item['modifieddate']	 = $row['modifieddate'];
    $item['empStatus']		 = $row['empStatus'];
    $item['workingStatus']	 = $row['workingStatus'];

    $from_id = $item['empId'];

    $data[$r] = $item;
    $r++;

}
if ( $sql_res ) {

    $response['status'] = 'True';
    $response['message'] = 'Success';

    $response['data'] = $data;
}

header( 'Content-Type:application/json' );
echo json_encode( $response );

?>
