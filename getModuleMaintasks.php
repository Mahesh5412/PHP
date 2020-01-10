<?php

/*
FileName:get_modulemaintasks.php
Version:1.1
Purpose:To list the all maintasks related to particular module
Devloper:sriram, rakesh
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

if ( $_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET' )
{
    date_default_timezone_set( 'Asia/Kolkata' );

    $time = date( 'Y-m-d H:i:s' );

    $moduleId  	 =   	$obj['moduleId'];

    if ( $moduleId == null )
    {
        $sql_get = "SELECT umt.*, eu.userName as toEmp,mu.userName as byEmp FROM `userMainTasks` as umt
										 INNER JOIN emsUsers as eu ON eu.empId =umt.assignedTo 
										 INNER JOIN emsUsers as mu ON mu.empId =umt.assignedBy ORDER BY umt.modifiedDate DESC,umt.id DESC";

        $sql_get_res = mysqli_query( $con, $sql_get ) or (logToFile($logfile,"  modules main task- modulemaintasks.php"));

        $data = array();

        while( $fet = mysqli_fetch_assoc( $sql_get_res ) )
        {

            $temp['ideaId']   		   = $fet['ideaId'];
            $temp['id']  		       = $fet['id'];
            $temp['taskStatus']       = $fet['taskStatus'];
            $temp['taskTitle']        = $fet['taskTitle'];
            $temp['taskDesc']         = $fet['taskDesc'];
            $temp['byEmp']            = $fet['byEmp'];
            $temp['toEmp']            = $fet['toEmp'];
            $temp['assignedTo']            = $fet['assignedTo'];
            $temp['taskStatusDesc']   = $fet['taskStatusDesc'];
            $temp['assignedDate']     = $fet['assignedDate'];
            $temp['taskEndDate']      = $fet['taskEndDate'];
            $temp['completeStatus']   = $fet['completeStatus'];
            $temp['modifiedDate']     = $fet['modifiedDate'];
            $temp['moduleId']         = $fet['moduleId'];
            $temp['targetDate']      = $fet['targetDate'];

            $maintaskid = $fet['id'];

            $targetTime = $fet['targetDate'];

            $cenvertedTime = date( 'Y-m-d h:i:s', strtotime( $targetTime ) );

            $datetime = date( 'Y-m-d h:i:s' );

            //timeleft calculation starts
            $date1 = strtotime( $datetime );

            $date2 = strtotime( $cenvertedTime );

            $diff = abs( $date2 - $date1 );

            $years = floor( $diff / ( 365*60*60*24 ) );

            $months = floor( ( $diff - $years * 365*60*60*24 )
            / ( 30*60*60*24 ) );

            $days = floor( ( $diff - $years * 365*60*60*24 -   $months*30*60*60*24 )/ ( 60*60*24 ) );

            $hours = floor( ( $diff - $years * 365*60*60*24  - $months*30*60*60*24 - $days*60*60*24 ) / ( 60*60 ) );
            $minutes = floor( ( $diff - $years * 365*60*60*24  - $months*30*60*60*24 - $days*60*60*24  - $hours*60*60 )/ 60 );

            $seconds = floor( ( $diff - $years * 365*60*60*24
            - $months*30*60*60*24 - $days*60*60*24
            - $hours*60*60 - $minutes*60 ) );

            $temp['timeleft'] = $days .'days '. $hours .':'.$minutes .':'.$seconds;

            //timeleft calculation ends

            array_push( $data, $temp );
            $data['val'] = '1';

        }

        $result['status'] = 'True';
        $result['message'] = 'sucess';
        $result['data'] = $data;
    } else {
        $sql_get = "SELECT umt.*, eu.userName as toEmp,mu.userName as byEmp FROM `userMainTasks` as umt
										 INNER JOIN emsUsers as eu ON eu.empId =umt.assignedTo 
										 INNER JOIN emsUsers as mu ON mu.empId =umt.assignedBy  where moduleId = $moduleId and completeStatus<>'deleted'ORDER BY umt.modifiedDate DESC,umt.id DESC ";

        $sql_get_res = mysqli_query( $con, $sql_get )  or (logToFile($logfile,"  modules main task timeleft calculation - modulemaintasks.php"));

        $data = array();
        while( $fet = mysqli_fetch_assoc( $sql_get_res ) )
        {

            $temp['ideaId']   		   = $fet['ideaId'];
            $temp['id']  		       = $fet['id'];
            $temp['taskStatus']       = $fet['taskStatus'];
            $temp['taskTitle']        = $fet['taskTitle'];
            $temp['taskDesc']         = $fet['taskDesc'];
            $temp['byEmp']            = $fet['byEmp'];
            $temp['toEmp']            = $fet['toEmp'];
            $temp['taskStatusDesc']   = $fet['taskStatusDesc'];
            $temp['assignedDate']     = $fet['assignedDate'];
            $temp['taskEndDate']      = $fet['taskEndDate'];
            $temp['completeStatus']   = $fet['completeStatus'];
            $temp['modifiedDate']     = $fet['modifiedDate'];
            $temp['moduleId']         = $fet['moduleId'];
            $temp['targetDate']      = $fet['targetDate'];
            $temp['assignedTo']            = $fet['assignedTo'];

            $maintaskid = $fet['id'];

            //timeleft calculation starts

            $targetTime = $fet['targetDate'];

            $cenvertedTime = date( 'Y-m-d h:i:s', strtotime( $targetTime ) );

            $datetime = date( 'Y-m-d h:i:s' );

            $date1 = strtotime( $datetime );

            $date2 = strtotime( $cenvertedTime );

            $diff = abs( $date2 - $date1 );

            $years = floor( $diff / ( 365*60*60*24 ) );

            $months = floor( ( $diff - $years * 365*60*60*24 )
            / ( 30*60*60*24 ) );

            $days = floor( ( $diff - $years * 365*60*60*24 -   $months*30*60*60*24 )/ ( 60*60*24 ) );

            $hours = floor( ( $diff - $years * 365*60*60*24  - $months*30*60*60*24 - $days*60*60*24 ) / ( 60*60 ) );
            $minutes = floor( ( $diff - $years * 365*60*60*24  - $months*30*60*60*24 - $days*60*60*24  - $hours*60*60 )/ 60 );

            $seconds = floor( ( $diff - $years * 365*60*60*24
            - $months*30*60*60*24 - $days*60*60*24
            - $hours*60*60 - $minutes*60 ) );

            $temp['timeleft'] = $days .'days '. $hours .':'.$minutes .':'.$seconds;

            //timeleft calculation ends

            array_push( $data, $temp );

        }

        $result['status'] = 'True';
        $result['message'] = 'sucess';
        $result['data'] = $data;
    }

}
 		 else {
				$result['status']='FALSE';
				$result['message']='Request method wrong!';
			}

header( 'Content-Type:application/json' );
echo json_encode( $result );

?>
