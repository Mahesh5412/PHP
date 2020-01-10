<?php

/*
FileName:getProfile.php
Version:1.0.1
Purpose:to get the details of employee and to update employee status
Devloper:rishitha
*/

session_start();

$json = file_get_contents( 'php://input' );
$obj = json_decode( $json, true );

$corp_code 		 = $obj['crop'];
$_SESSION['corp_code'] = $corp_code;

	include 'connect.php';

        // for Logs 
        $logfile = 'log/log' .date('d-M-Y') . '.log';

header( 'Access-Control-Allow-Origin: *' );
header( 'Access-Control-Allow-Methods: POST, GET, OPTIONS' );

date_default_timezone_set( 'Asia/Kolkata' );

$time = date( 'Y-m-d H:i:s' );

$action  		 = $obj['action'];
$empId  		 = $obj['empId'];
$status    		 = $obj['status'];

//to get the employee details
if ( $action == 'get' ) {
    $sql_add = "SELECT * FROM `emsUsers` where empId='$empId'";
    $sql_add_res = mysqli_query( $con, $sql_add ) or (logToFile($logfile," to get the employee details- getProfile.php"));

    if ( mysqli_num_rows( $sql_add_res ) > 0 ) {
        $r = 0;
        while( $fet = mysqli_fetch_assoc( $sql_add_res ) ) {

            $temp['empid']                   =  $fet['empId'];
            $temp['fullname']                =  $fet['fullName'];
            $temp['mobile']                  =  $fet['mobileNumber'];
            $temp['email']                   =  $fet['email'];
            $temp['username']                =  $fet['userName'];
            $temp['role']            		 =  $fet['role'];
            $temp['designation']     		 =  $fet['designation'];
            $temp['team']                    =  $fet['team'];
            $temp['empStatus']               =  $fet['empStatus'];

            $data[$r] = $temp;
            $r++;

        }
        $result['status'] = 'True';
        $result['message'] = 'Success';

        $result['data'] = $data;

    } else {

        $result['status'] = 'False';
        $result['message'] = 'No Data Available';

    }

}

//to update the employee mobile number
else if ( $action == 'update' ) {
    $mobileNumber  		 = $obj['number'];

    $sql_up = "update `emsUsers` set `mobileNumber`='$mobileNumber' where `empId`='$empId'";
    $sql_up_res = mysqli_query( $con, $sql_up )  or (logToFile($logfile," to update the employee mobile number- getProfile.php"));
    if ( $sql_up_res ) {

        $result['status'] = 'True';
        $result['message'] = 'Success';

    } else {
        $result['status'] = 'false';
        $result['message'] = 'false';

    }

}

//to update the employee status
else if ( $action == 'updatestatus' ) {

    $sql = "update emsUsers set `empStatus`='$status'  where `empId`=$empId" ;
    $sql_res	 = 	mysqli_query( $con, $sql );

    if ( $sql_res ) {
        $result['status'] = 'True';
        $result['message'] = 'Success';

    } else {
        $result['status'] = 'False';
        $result['message'] = 'Fail';

    }
}
else{
	$result['status'] = 'False';
        $result['message'] = 'Wrong Data';

}
		

header( 'Content-Type:application/json' );
echo json_encode( $result );

?>
