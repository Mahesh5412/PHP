<?php

/*
FileName:managesubtasks.php
Version:1.0.1
Purpose:to perform multiple actions on subtasks( add, modify, delete, verify )
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

date_default_timezone_set( 'Asia/Kolkata' );

$time = date( 'Y-m-d H:i:s' );

$action  			 = $obj['action'];
$subtaskId  		 = $obj['subtaskid'];
$title          	 =  $obj['title'];
$description    	 =  $obj['description'];
$moduleid       	 =  $obj['moduleId'];
$maintaskid     	 =  $obj['maintaskId'];
$assignby       	 =  $obj['assignedBy'];
$estimatedhours 	 =  $obj['EstimatedHours'];
$assignto       	 =  $obj['assignedTo'];
$targetdate         =  $obj['targetdate'];
$dependencyId   	 =  $obj['dependencyId'];
$days           	 =  $obj['days'];
$hours          	 =  $obj['hours'];
$activecode        	 =  $obj['activecode'];
$empId  			 =  $obj['empId'];

//for adding subtask to particular maintask by checking dependency

if ( $action == 'add' ) {

    if ( $dependencyId == 'NA' ) {

        $cenvertedTime = date( 'Y-m-d H:i:s', strtotime( '+'.$days.' day +'.$hours.' hour ', strtotime( $time ) ) );

        $sql = "INSERT INTO `userSubTasks` (`mainTaskId`, `moduleId`, `taskTitle`, `taskDesc`, `assignedBy`, `estimatedHours`, `assignedTo`,`assignedDate`,`dependencyId`,`targetDate`) VALUES ('$maintaskid', '$moduleid', '$title', '$description', '$assignby', '$estimatedhours', '$assignto', '$time', '$dependencyId','$cenvertedTime')";

        $sql_res = mysqli_query( $con, $sql ) or (logToFile($logfile," adding subtask to particular maintask by checking dependency- managesubtask.php"));

        //set targetdate to maintask starts here

        $sqltargettime = "SELECT MAX(targetDate) as targetDate FROM  `userSubTasks` WHERE mainTaskId ='$maintaskid'";

        $sql_res1 = mysqli_query( $con, $sqltargettime ) or (logToFile($logfile," set targetdate to maintask starts here - managesubtask.php"));

        if ( $row = mysqli_fetch_assoc( $sql_res1 ) ) {

            $targetdate = $row['targetDate'];

            $sqlll = "UPDATE  `userMainTasks` SET  `targetDate` = '$targetdate' WHERE  `id` ='$maintaskid'";

            $sqlll_res = mysqli_query( $con, $sqlll );

        }
        //set targetdate to maintask ends here

        //taskenddate starts here

        $sqlenddate = "SELECT MAX(taskEndDate) as taskEndDate FROM  `userSubTasks` WHERE mainTaskId ='$maintaskid'";

        $sqlenddate_res = mysqli_query( $con, $sqlenddate ) or (logToFile($logfile,"taskenddate starts here - managesubtask.php"));

        if ( $rowend = mysqli_fetch_assoc( $sqlenddate_res ) ) {

            $updatedtaskdate  = $rowend['taskEndDate'];

            $sqltasks = "UPDATE  `userMainTasks` SET  `taskEndDate` = '$updatedtaskdate' WHERE  `id` ='$maintaskid'";

            $sqltasks_res = mysqli_query( $con, $sqltasks );

        }
        //taskenddate ends here

        //set taskstatus to maintask starts here

        $sql_status = "SELECT round(sum(ust.taskStatus)/(count(ust.subTaskId))) as mainTaskStatus FROM userMainTasks as umt inner join userSubTasks as ust 
					on umt.id=ust.mainTaskId where umt.id='$maintaskid'";

        $sql_res1 = mysqli_query( $con, $sql_status ) or (logToFile($logfile,"set taskstatus to maintask starts here - managesubtask.php"));

        $row1 = mysqli_fetch_assoc( $sql_res1 );

        $taskstatus = $row1['mainTaskStatus'];

        $sqlmaintaskstatus = "UPDATE  `userMainTasks` SET  `taskStatus` = '$taskstatus' WHERE `id` ='$maintaskid'";

        $sqlmaintaskstatus_res = mysqli_query( $con, $sqlmaintaskstatus ) or (logToFile($logfile,"set taskstatus to maintask Based on maintaskStatus - roadblock.php"));

        //maintasks status ends here

        if ( $sql_res ) {

            $sqlmainadd = "UPDATE  `userMainTasks` SET  `taskStatus` = '$taskstatus',`completeStatus`='pending' WHERE `id` ='$maintaskid'";

            $sqlmainadd_res = mysqli_query( $con, $sqlmainadd ) or (logToFile($logfile,"set taskstatus to maintask Based on maintaskid - managesubtask.php"));

            $result['status'] = 'true';
            $result['message'] = 'success';

        }

    } else {

        $sqldep = "select targetDate from `userSubTasks` where `subTaskId`='$dependencyId' ";

        $sql_resdep = mysqli_query( $con, $sqldep ) or (logToFile($logfile,"set taskstatus to maintask Based on dependecy id - managesubtask.php"));

        $row = mysqli_fetch_assoc( $sql_resdep );

        $dependencytargetDate = $row['targetDate'];

        $cenvertedTime = date( 'Y-m-d H:i:s', strtotime( '+'.$days.' day +'.$hours.' hour ', strtotime( $dependencytargetDate ) ) );

        $sql = "INSERT INTO `userSubTasks` (`mainTaskId`, `moduleId`, `taskTitle`, `taskDesc`, `assignedBy`, `estimatedHours`, `assignedTo`,`assignedDate`,`dependencyId`,`targetDate`) VALUES ('$maintaskid', '$moduleid', '$title', '$description', '$assignby', '$estimatedhours', '$assignto', '$time', '$dependencyId','$cenvertedTime')";

        $sql_res = mysqli_query( $con, $sql ) or (logToFile($logfile,"set taskstatus to maintask Based on maintaskStatus  - managesubtask.php"));

        //set targetdate to maintask starts here

        $sqltargettime = "SELECT MAX(targetDate) as targetDate FROM  `userSubTasks` WHERE mainTaskId ='$maintaskid'";

        $sql_res1 = mysqli_query( $con, $sqltargettime ) or (logToFile($logfile,"set targetdate to maintask - managesubtask.php"));

        if ( $row = mysqli_fetch_assoc( $sql_res1 ) ) {

            $targetdate = $row['targetDate'];

            $sqlll = "UPDATE  `userMainTasks` SET  `targetDate` = '$targetdate' WHERE  `id` ='$maintaskid'";

            $sqlll_res = mysqli_query( $con, $sqlll )  or (logToFile($logfile,"set targetdate to maintaskid and target date - managesubtask.php"));
        }

        //set targetdate to maintask ends here

        //taskenddate starts

        $sqlenddate = "SELECT MAX(taskEndDate) as taskEndDate FROM  `userSubTasks` WHERE mainTaskId ='$maintaskid'";

        $sqlenddate_res = mysqli_query( $con, $sqlenddate )  or (logToFile($logfile,"set targetdate to maintask - managesubtask.php"));

        if ( $rowend = mysqli_fetch_assoc( $sqlenddate_res ) ) {

            $updatedtaskdate  = $rowend['taskEndDate'];

            $sqltasks = "UPDATE  `userMainTasks` SET  `taskEndDate` = '$updatedtaskdate' WHERE  `id` ='$maintaskid'";

            $sqltasks_res = mysqli_query( $con, $sqltasks )  or (logToFile($logfile,"set targetdate to maintask  taskend Date- managesubtask.php"));

        }

        //taskenddate ends

        //set taskstatus to maintask starts here

        $sql_status = "SELECT round(sum(ust.taskStatus)/(count(ust.subTaskId))) as mainTaskStatus FROM userMainTasks as umt inner join userSubTasks as ust on umt.id=ust.mainTaskId where umt.id='$maintaskid'";
        $sql_res1 = mysqli_query( $con, $sql_status ) or (logToFile($logfile,"set taskstatus to maintask- managesubtask.php"));

        $row1 = mysqli_fetch_assoc( $sql_res1 );

        $taskstatus = $row1['mainTaskStatus'];

        $sqlmaintaskstatus = "UPDATE  `userMainTasks` SET  `taskStatus` = '$taskstatus' WHERE `id` ='$maintaskid'";
        $sqlmaintaskstatus_res = mysqli_query( $con, $sqlmaintaskstatus ) or (logToFile($logfile,"set taskstatus to maintaskStatus- managesubtask.php"));

        //maintasks status ends here

        if ( $sql_res ) {

            $sqlmainadd = "UPDATE  `userMainTasks` SET  `taskStatus` = '$taskstatus',`completeStatus`='pending' WHERE `id` ='$maintaskid'";

            $sqlmainadd_res = mysqli_query( $con, $sqlmainadd ) or (logToFile($logfile,"set taskstatus to maintask end- managesubtask.php"));

            $result['status'] = 'true';
            $result['message'] = 'success';

        }

    }
}

//for deleting subtask by taking subtask id

if ( $action == 'deletesubtask' ) {

    $sql = "UPDATE  `userSubTasks` SET  `status` =  'deleted' WHERE  `subTaskId` ='$subtaskId'";

    $sql_res = mysqli_query( $con, $sql ) or (logToFile($logfile,"deleting subtask by taking subtask id- managesubtask.php"));

    if ( $sql_res ) {

        $result['status'] = 'true';
        $result['message'] = 'success';

    } else {
        $result['status'] = 'false';
        $result['message'] = 'unsuccessful';
    }

}
//for modifying subtask by tasking subtask id

if ( $action == 'modify' ) {

    $results = mysqli_query( $con, 'SELECT subTaskId as sid, dependencyId did, targetDate as date FROM `userSubTasks`' );

    $sid = array();
    $did = array();

    foreach ( $results as $row ) {
        $sid[] = $row['sid'];
        $did[] = $row['did'];
    }

    $nsid = new SplFixedArray( sizeof( $sid ) );

    for ( $k = 0; $k <= sizeOf( $sid )-1;
    $k++ ) {
        $nsid[$k] = 0;
    }

    $id = $subtaskId;

    $nsid[0] = $id;

    $var = 1;
    for ( $j = 0; $j <= sizeOf( $nsid )-1;
    $j++ ) {

        if ( $nsid[$j] != 0 && $nsid[$j] != null ) {

            for ( $i = 0; $i <= sizeOf( $did )-1;
            $i++ ) {

                if ( $did[$i] == $nsid[$j] ) {

                    $nsid[$var] = $sid[$i];

                    $var = $var+1;
                }

            }
        }
    }

    $dhour = $days*24;
    $totalhours = $dhour+$hours;

    $query = "UPDATE `userSubTasks` SET targetDate=DATE_ADD(targetDate, INTERVAL '$totalhours' hour) WHERE subTaskId IN (";

    $query1 = "UPDATE `userSubTasks` SET targetDate=DATE_ADD(targetDate, INTERVAL '$totalhours' hour),taskTitle='$title',taskDesc='$description', assignedTo='$assignto',`estimatedHours`=estimatedHours+'$totalhours', modifiedBy='$assignby', modifiedDate='$time',dependencyId='$dependencyId' WHERE subTaskId IN (";

    $query1 = $query1.''.$nsid[0].')';

    if ( $nsid[1] != 0 ) {
        for ( $a = 1; $a <= sizeOf( $nsid )-2;
        $a++ ) {
            $flag = $nsid[$a+1];

            if ( $flag != 0 ) {
                $query = $query.''.$nsid[$a].',';

            } else {
                if ( $nsid[$a] != 0 ) {
                    $query = $query.''.$nsid[$a].')';
                }
            }
        }
        if ( $con->query( $query ) == TRUE ) {
            $result['status'] = 'true';
            $result['message'] = 'success';
        }
    }

    if ( $con->query( $query1 ) == TRUE ) {
        $result['status'] = 'true';
        $result['message'] = 'success';
    } else {
        $result['status'] = 'false';
        $result['message'] = 'unsuccessful';
    }

}

//to verify subtask, subtask can be verified only when subtask is completed
if ( $action == 'verify' ) {

    $sql = "SELECT * FROM `userSubTasks` WHERE `subTaskId`='$subtaskId' ";
    $sql_res = mysqli_query( $con, $sql )  or (logToFile($logfile,"to verify subtask, subtask can be verified only when subtask is completed- managesubtask.php"));

    $row = mysqli_fetch_assoc( $sql_res );

    $taskstatus = $row['taskStatus'];

    if ( $taskstatus == '100' ) {

        $sql1 = "UPDATE `userSubTasks` SET `taskStatus` = '100',`status` = 'verified' WHERE  `subTaskId` ='$subtaskId' ";

        $sql_res1 = mysqli_query( $con, $sql1 ) or (logToFile($logfile,"to verify subtask, subtask can be verified only when subtask is completed subtaskId- managesubtask.php"));

        $sqlveri = "select if(p/q=1, 1, 0) as x from
                (SELECT sum(case when status='verified' then 1 else 0 end) as p, count(*) as q FROM `userSubTasks` WHERE mainTaskId='$maintaskid') as t";

        $sqlveri_res1 = mysqli_query( $con, $sqlveri );

        $fetver = mysqli_fetch_assoc( $sqlveri_res1 );

        if ( $fetver['x'] == 1 ) {

            $sqlveriupdate = "UPDATE  `userMainTasks` SET  `taskStatus` =  '100',`completeStatus`='verified' WHERE  `id` ='$maintaskid'";

            $sqlveriupdate_res1 = mysqli_query( $con, $sqlveriupdate );

        }

        if ( $sql_res1 ) {

            $result['status'] = 'true';
            $result['message'] = 'success';

        } else {
            $result['status'] = 'false';
            $result['message'] = 'unsuccessful';
        }

    }
}

//to make subtask as active and you can not make subtask as active if you have already one active subtask
if ( $action == 'activetask' ) {

    $sql1 = "SELECT * FROM `userSubTasks` WHERE assignedTo='$empId' AND activeStatus='1'";
    $sql_res1 = mysqli_query( $con, $sql1 ) or (logToFile($logfile,"make subtask as active and you can not make subtask as active if you have already one active subtask- managesubtask.php"));
    if ( mysqli_num_rows( $sql_res1 )>0 ) {
        $result['status'] = 'true1';
        $result['message'] = 'You not able active this task untile complete your activated task';

    } else {
        $sql = "UPDATE userSubTasks set activeStatus='1',activeTime='$time' where subTaskId='$subtaskId'";

        $sql_res = mysqli_query( $con, $sql ) or (logToFile($logfile,"make subtask as active and you can not make subtask as active if you have already one active subtask- managesubtask.php"));

        if ( $sql_res ) {

            $result['status'] = 'true';
            $result['message'] = 'Activated successfully';

        } else {

            $result['status'] = 'false';
            $result['message'] = 'unsuccessful';

        }
    }

}

header( 'Content-Type:application/json' );
echo json_encode( $result );

?>
