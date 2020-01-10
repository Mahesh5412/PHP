<?php

/*
FileName:manage_module.php
Version:1.0.1
Purpose:to perform multiple actions on modules( add, modify, delete )
Devloper:Rishitha, krishna tulasi, jagadeesh
*/

session_start();

$json = file_get_contents( 'php://input' );
$obj = json_decode( $json, true );

$corp_code 		 = $obj['crop'];
$_SESSION['corp_code'] = $corp_code;

include 'connect.php';

// for Logs 
$logfile = 'log/log' .date('d-M-Y') . '.log';

include 'GCM.php';

date_default_timezone_set( 'Asia/Kolkata' );

$time = date( 'Y-m-d H:i:s' );

$action  		 = $obj['action'];
$idea_id     	 = $obj['idea_id'];

//to add module to project

if ( $action == 'add' ) {
    $module_name  		 = $obj['module_Name'];
    $idea_id     		 = $obj['idea_id'];
    $empId     	        = $obj['empId'];

    $sql_add = "insert into moduleTable(`createdBy`,`moduleDesc`,`createdDate`,`modifiedBy`,`modifiedDate`,`ideaId`) values ('$empId','$module_name','$time','$empId','$time','$idea_id')";

    $sql_add_res = mysqli_query( $con, $sql_add ) or (logToFile($logfile,"  add module to project  - manage_module.php"));

    if ( $sql_add_res ) {
        $result['status'] = 'True';
        $result['message'] = 'Success';

    } else {
        $result['status'] = 'false';
        $result['message'] = 'Something went wrong';
    }

}

//to modify module

else if ( $action == 'modify' ) {
    $module_id  		 = $obj['moduleId'];
    $empId     	        = $obj['empId'];
    $module_name  		 = $obj['moduleDesc'];

    $sql_modify  = "update moduleTable set `moduleDesc`='$module_name',`modifiedBy`='$empId',`modifiedDate`='$time' where `moduleId`= $module_id";
    $sql_modify_res	 = 	mysqli_query( $con, $sql_modify ) or (logToFile($logfile,"  to modify module  - manage_module.php"));

    if ( $sql_modify_res ) {
        $result['status'] = 'True';
        $result['message'] = 'Success';

    }
     else {
        $result['status'] = 'false';
        $result['message'] = 'Something went wrong';
    }
}

//to delete module, module can be deleted only when module does not have maintasks
else if ( $action == 'delete' ) {

    $empId     	        = $obj['empId'];
    $module_id			 = $obj['moduleid'];

    $sql = "select ifnull((sum(case when completeStatus in ('pending','completed') then 1 else 0 end)),0) as remaining,
										ifnull((sum(case when completeStatus='verified' then 1 else 0 end)),0) as verified from userMainTasks where moduleId='$module_id'";

    $sql_res = mysqli_query( $con, $sql );

    $fet = mysqli_fetch_assoc( $sql_res );

    if ( $fet['remaining'] == 0 and $fet['verified'] == 0 ) {

        $sql = "UPDATE `moduleTable` SET `status`= 'deleted' WHERE  `moduleid` ='$module_id' ";

        $sql_res = mysqli_query( $con, $sql )  or (logToFile($logfile,"  to delete module, module can be deleted only when module does not have maintasks  - manage_module.php"));

        $result['status'] = 'True';
        $result['message'] = 'Success';

    } else {
        $result['status'] = 'false';
        $result['message'] = 'Having maintasks,you cannot delete this module';
    }

} else {
    $result['status'] = 'false';
    $result['message'] = 'Something went wrong';
}

header( 'Content-Type:application/json' );
echo json_encode( $result );

?>
