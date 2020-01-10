<?php

/*
FileName:get_modules.php
Version:1.0.1
Purpose:to list the all modules of a project
Devloper:sriram
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

    $action  		     = $obj['action'];
    $idea_id     		 = $obj['idea_id'];

	//for listing the modules of a project
    if ( $action == 'get' ) {

        $idea_id     		 = $obj['idea_id'];

        $sql_getmod = "SELECT moduleTable.*,emsUsers.fullName,emsUsers.username FROM `moduleTable` 
					inner join emsUsers on moduleTable.createdBy=emsUsers.empId where ideaId='$idea_id' and status<>'deleted'";

        $sql_getmod_res = mysqli_query( $con, $sql_getmod ) or (logToFile($logfile," for listing the modules of a project- get_modules.php"));

        if ( mysqli_num_rows( $sql_getmod_res )>0 ) {
            $r = 0;

            while( $fet = mysqli_fetch_assoc( $sql_getmod_res ) ) {

                $temp['moduleId']               =  $fet['moduleId'];

                $temp['ideaId']               =  $fet['ideaId'];
                $temp['moduleDesc']             =  $fet['moduleDesc'];
                $temp['createdBy']            =  $fet['fullName'];

                $timestamp = $fet['modifiedDate'];
                $splitTimeStamp = explode( ' ', $timestamp );
                $date = $splitTimeStamp[0];
                $time = $splitTimeStamp[1];
                $temp['created_on']           = $date;

                $data[$r] = $temp;

                $r++;

            }
            $result['status'] = 'True';
            $result['message'] = 'Success';

            $result['data'] = $data;
        } else {
            $result['status'] = 'False';
            $result['message'] = 'No Data Available';

            $result['data'] = $data;
        }

    }

}
		 else {
				$result['status']='FALSE';
				$result['message']='Request method wrong!';
			}

header( 'Content-Type:application/json' );
echo json_encode( $result );

?>
