<?php

/*
FileName:spinner.php
Version:1.0.0
Purpose:Getting the required data for dropdown items
Devloper:rishitha, krishna tulasi
*/

session_start();

$json = file_get_contents( 'php://input' );
$obj = json_decode( $json, true );

$corp_code 		 = $obj['crop'];
$_SESSION['corp_code'] = $corp_code;

include 'connect.php';
// for Logs 
$logfile = 'log/log' .date('d-M-Y') . '.log';

$action     = $obj['action'];

if ( $action == 'desig' ) {

    $sql = "SELECT `designation` FROM `columnValues` where `designation`!='NA'";
    $sql_res = mysqli_query( $con, $sql ) or (logToFile($logfile,"  select designation - spinner.php"));

    if ( mysqli_num_rows( $sql_res )>0 ) {

        $r = 0;
        while( $fet = mysqli_fetch_array( $sql_res ) ) {

            $temp['id'] = $fet['designation'];
            $temp['value'] = $fet['designation'];

            $data[$r] = $temp;
            $r++;

        }
        $result['status'] = 'True';
        $result['message'] = 'Success';

        $result['data'] = $data;

    }
     else {
        $result['status'] = 'False';
        $result['message'] = 'Designation Failed';

    }

}


if ( $action == 'team' ) {

    $sql = 'SELECT `team` FROM `columnValues`';
    $sql_res = mysqli_query( $con, $sql ) or (logToFile($logfile,"  select team - spinner.php"));

    if ( mysqli_num_rows( $sql_res )>0 ) {
        $r = 0;

        while( $fet = mysqli_fetch_array( $sql_res ) ) {

            $temp['value'] = $fet['team'];
            $data[$r] = $temp;

            $r++;

        }
        $result['status'] = 'True';
        $result['message'] = 'Success';

        $result['data'] = $data;

    }
    else {
        $result['status'] = 'False';
        $result['message'] = 'Team Failed';

    }

}


if ( $action == 'status' ) {

    $sql = "SELECT `status` FROM `columnValues` where `status`!='NA'";
    $sql_res = mysqli_query( $con, $sql ) or (logToFile($logfile,"  select status - spinner.php"));

    if ( mysqli_num_rows( $sql_res )>0 ) {
        $r = 0;

        while( $fet = mysqli_fetch_array( $sql_res ) ) {

            $temp['value'] = $fet['status'];
            $data[$r] = $temp;

            $r++;

        }
        $result['status'] = 'True';
        $result['message'] = 'Success';

        $result['data'] = $data;

    }
else {
        $result['status'] = 'False';
        $result['message'] = 'Status Failed';

    }
}


header( 'Content-Type:application/json' );

echo json_encode( $result );
?>

