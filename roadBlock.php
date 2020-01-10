<?php

/*
FileName:roadblock.php
Version:1.1
Purpose:to list roadblocks and update roadblocks
Devloper:Rishitha, krishna tulasi, jagadeesh
*/

session_start();

$json = file_get_contents( 'php://input' );
$obj = json_decode( $json, true );

date_default_timezone_set( 'Asia/Kolkata' );

$time = date( 'Y-m-d H:i:s' );

$corp_code 		 = $obj['crop'];
$_SESSION['corp_code'] = $corp_code;

include 'connect.php';
// for Logs
$logfile = 'log/log' .date( 'd-M-Y' ) . '.log';
$subTaskId = array();

$empId                =  $obj['empId'];
$action               =  $obj['action'];

//to list the all roadblocks of subtasks
if ( $action == 'getting' ) {

    $sql = "select * from roadBlocks where `subTaskId` = '$subtaskid'" ;
    $sql_res = mysqli_query( $con, $sql ) or ( logToFile( $logfile, '  roadblocks of subtasks - roadblock.php' ) );

    if ( mysqli_num_rows( $sql_res ) ) {

        while( $row = mysqli_fetch_assoc( $sql_res ) ) {

            $temp['subTaskId'] = $row['subTaskId'];
            $temp['roadBlockDescription'] = $row['roadBlockDescription'];
            $temp['roadBlockDate'] = $row['roadBlockDate'];
            $temp['roadBlockStatus'] = $row['status'];

            $temp['sno'] = $row['sno'];

            $aa = mysqli_query( $con, "SELECT count(*) FROM `roadBlocks` WHERE `subTaskId` = '$subtaskid'" );
            $row1 = mysqli_fetch_assoc( $aa );
            $temp['size'] = $row1['count(*)'];

            $data[] = $temp;

        }
        $result['status'] = 'True';
        $result['message'] = 'Success';

        $result['data'] = $data;
    } else {
        $result['status'] = 'False';
        $result['message'] = 'No Data Available';

    }
}
// to list the all roadblocks which are not cleared after 24 hours
if ( $action == 'critical' ) {

    $sql_critical = "SELECT ust.subTaskId, ust.taskTitle, roadBlockDescription, date(roadBlockDate) as createdDate FROM `roadBlocks` AS rb INNER JOIN userSubTasks AS ust 
			on ust.subTaskId= rb.subTaskId WHERE Date(roadBlockdate) < DATE(NOW() - INTERVAL 1 DAY) and rb.status='not solved' and ust.status='pending'";

    $sql_critical_res = mysqli_query( $con, $sql_critical ) or ( logToFile( $logfile, '  list the all roadblocks which are not cleared after 24 hours  - roadblock.php' ) );

    if ( mysqli_num_rows( $sql_critical_res ) ) {

        $subTaskId = '0';

        while( $row = mysqli_fetch_assoc( $sql_critical_res ) ) {

            $subTaskid				 = $row['subTaskId'];

            $subTaskId =  $subTaskId.','.$subTaskid;

            $sql = "SELECT *, ui.ideaTitle as projectTitle, eu1.userName AS assignedby, eu2.userName AS assignedto FROM (SELECT ust.*, umt.ideaId  FROM `userSubTasks` AS ust 
				 INNER JOIN userMainTasks as umt ON umt.id= ust.mainTaskId) as t1 INNER JOIN userIdeas as ui ON ui.ideaId= t1.ideaId INNER JOIN emsUsers as eu1 ON eu1.empId= t1.assignedBy 
				 INNER JOIN emsUsers as eu2 ON eu2.empId= t1.assignedTo WHERE t1.subTaskId IN ($subTaskId)";

            $sql_res = mysqli_query( $con, $sql ) or ( logToFile( $logfile, '  list the all roadblocks which are not cleared after 24 hours based on SubtaskId - roadblock.php' ) );
            ;

            if ( mysqli_num_rows( $sql_res )>0 ) {

                $r = 0;

                while( $fet = mysqli_fetch_assoc( $sql_res ) )
                {

                    $data[$r] = $fet;
                    $r++;

                }
            }
        }

        $result['status'] = 'True';
        $result['message'] = 'Success';

        $result['data'] = $data;
    } else {
        $result['status'] = 'False';
        $result['message'] = 'No Data Available';
    }

}
//to list the roadblocks which are added and within 24 hours
if ( $action == 'noncritical' ) {

    $sql_noncritical = "SELECT ust.subTaskId, ust.taskTitle, roadBlockDescription, date(roadBlockDate) as createdDate FROM `roadBlocks` AS rb INNER JOIN userSubTasks AS ust 
			on ust.subTaskId= rb.subTaskId WHERE roadBlockdate >= now() - INTERVAL 1 DAY and rb.status='not solved' and ust.status='pending'";

    $sql_noncritical_res = mysqli_query( $con, $sql_noncritical ) or ( logToFile( $logfile, '  list the roadblocks which are added and within 24 hours  - roadblock.php' ) );
    ;

    if ( mysqli_num_rows( $sql_noncritical_res ) ) {

        $subTaskId = '0';

        while( $row = mysqli_fetch_assoc( $sql_noncritical_res ) ) {

            $subTaskid				 = $row['subTaskId'];

            $subTaskId =  $subTaskId.','.$subTaskid;

            $sql = "SELECT *, ui.ideaTitle as projectTitle, eu1.userName AS assignedby, eu2.userName AS assignedto FROM (SELECT ust.*, umt.ideaId  FROM `userSubTasks` AS ust 
				 INNER JOIN userMainTasks as umt ON umt.id= ust.mainTaskId) as t1 INNER JOIN userIdeas as ui ON ui.ideaId= t1.ideaId INNER JOIN emsUsers as eu1 ON eu1.empId= t1.assignedBy 
				 INNER JOIN emsUsers as eu2 ON eu2.empId= t1.assignedTo WHERE t1.subTaskId IN ($subTaskId)";

            $sql_res = mysqli_query( $con, $sql ) or ( logToFile( $logfile, '  list the roadblocks which are added and within 24 hours based on SubtaskId - roadblock.php' ) );

            if ( mysqli_num_rows( $sql_res )>0 ) {

                $r = 0;

                while( $fet = mysqli_fetch_assoc( $sql_res ) )
                {

                    $data[$r] = $fet;
                    $r++;

                }
            }

        }
        //
        $result['status'] = 'True';
        $result['message'] = 'Success';

        $result['data'] = $data;
    } else {
        $result['status'] = 'False';
        $result['message'] = 'No Data Available';
    }

}
//to make roadblock as solved
if ( $action == 'solved' ) {

    $sql_up1 = "UPDATE `roadBlocks` SET `status`='solved' where `subTaskId` = '$subtaskid' and `sno`='$sno'";
    $sql_res_update1 = mysqli_query( $con, $sql_up1 ) or ( logToFile( $logfile, '  make roadblock as solved - roadblock.php' ) );
    ;

    if ( $sql_res_update1 ) {
        $result['status'] = 'true';
        $result['message'] = 'success';

    } else {
        $result['status'] = 'false';
        $result['message'] = 'failure';
    }
}
// to create roadblock for a subtask
if ( $action == 'insert' ) {

    $sql_add_res = mysqli_query( $con, "INSERT INTO roadBlocks (subTaskId,roadBlockDescription,roadBlockDate) VALUES('$subtaskid','$roadblockdescription','$time')" );

    if ( $sql_add_res ) {
        $result['status'] = 'True';
        $result['message'] = 'Success';
    } else {

        $result['status'] = 'false';
        $result['message'] = 'fail';

    }
}

if ( $action == 'roadblockinfo' ) {

    // echo '1';
    // echo $empId;

    $sqlinfo = "SELECT DISTINCT (rb.`subTaskId`) FROM roadBlocks rb INNER JOIN userSubTasks ust ON ust.subTaskId= rb.subTaskId 
			WHERE ust.assignedTo='$empId' ";
    $sql_info_res = mysqli_query( $con, $sqlinfo ) or ( logToFile( $logfile, '  list the roadblocks - roadblock.php' ) );
    ;

    if ( mysqli_num_rows( $sql_info_res )>0 ) {
        $subTaskId = '0';

        while( $fet = mysqli_fetch_assoc( $sql_info_res ) )
        {
            $subTaskid				 = $fet['subTaskId'];
            $subTaskId =  $subTaskId.','.$subTaskid;

            $sql = "SELECT *, ui.ideaTitle as projectTitle, eu1.userName AS assignedby, eu2.userName AS assignedto FROM (SELECT ust.*, umt.ideaId  FROM `userSubTasks` AS ust 
				 INNER JOIN userMainTasks as umt ON umt.id= ust.mainTaskId) as t1 INNER JOIN userIdeas as ui ON ui.ideaId= t1.ideaId INNER JOIN emsUsers as eu1 ON eu1.empId= t1.assignedBy 
				 INNER JOIN emsUsers as eu2 ON eu2.empId= t1.assignedTo WHERE t1.status <> 'completed' and t1.subTaskId IN ($subTaskId)";

            $sql_res = mysqli_query( $con, $sql ) or ( logToFile( $logfile, '  list the roadblocks - roadblock.php' ) );

            if ( mysqli_num_rows( $sql_res )>0 ) {

                $r = 0;

                while( $fet = mysqli_fetch_assoc( $sql_res ) )
                {
                    $data[$r] = $fet;
                    $r++;

                }
            }
            $result['status'] = 'True';
            $result['message'] = 'Success';

            $result['data'] = $data;
        }

    } else {

        $result['status'] = 'False';
        $result['message'] = 'No Data Available';
    }

}

echo json_encode( $result );

?>