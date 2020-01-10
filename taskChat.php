<?php

/*
FileName:taskchat.php
Version:1.1
Purpose:Getting the List of task messages
Devloper:Rishitha
*/

session_start();

$json = file_get_contents( 'php://input' );
$obj = json_decode( $json, true );

$corp_code 		 = $obj['corp_code'];
$_SESSION['corp_code'] = $corp_code;

include 'connect.php';
// for Logs 
$logfile = 'log/log_' .date('d-M-Y') . '.log';


if ( $_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET' )
{

    date_default_timezone_set( 'Asia/Kolkata' );

    $time = date( 'Y-m-d H:i:s' );

    $action  		        = $obj['action'];
    $groupId  				 = $obj['groupId'];
    $messagedBy				 = $obj['messagedBy'];
    $message				 = $obj['message'];

    //message sending

    if ( $action == 'send' ) {

        $sql_add = "insert into taskChat(`groupId`,`messagedBy`,`message`,`messagedTime`) 
					values('$groupId','$messagedBy','$message','$time')";

        $sql_add_res = mysqli_query( $con, $sql_add ) or (logToFile($logfile,"  sending message - taskchat.php"));

        if ( $sql_add_res ) {
            $result['status'] = 'True';
            $result['message'] = 'Success';

        } else {
            $result['status'] = 'False';
        }

        //message getting

    } else if ( $action == 'getmessages' ) {

        $sql = "SELECT tc.*, eu.username FROM `taskChat` AS tc INNER JOIN emsUsers AS eu ON eu.empId= tc.messagedBy WHERE groupId='$groupId'";

        $sql_res = mysqli_query( $con, $sql ) or (logToFile($logfile,"  getting message - taskchat.php"));

        if ( mysqli_num_rows( $sql_res ) > 0 )
        {

            $r = 0;

            while( $fet = mysqli_fetch_assoc( $sql_res ) )
            {

                $temp['groupId']   		   	   = $fet['groupId'];
                $temp['messagedBy']  		       = $fet['messagedBy'];
                $temp['message']       		   = $fet['message'];
                $temp['messagedTime']       	   = $fet['messagedTime'];
                $temp['username']       	   	   = $fet['username'];

                $messagedTime = $fet['messagedTime'];

                $timestamp = $messagedTime;
                $splitTimeStamp = explode( ' ', $timestamp );
                $date = $splitTimeStamp[0];
                $time = $splitTimeStamp[1];
                $temp['date']       	   = $time;

                $data[$r] = $temp;

                $r++;
            }
            $result['status']  = 'True';
            $result['message'] = 'Success';

            $result['data']    = $data;
        } else {
            $result['status'] = 'False';
            $result['message'] = 'No Data Available';

            $result['data'] = $data;
        }

    }
} else {
    $result['status'] = 'FALSE';
    $result['message'] = 'Request method wrong!';
}

header( 'Content-Type:application/json' );
echo json_encode( $result );

?>
