<?php

/*
FileName:manage_ideas.php
Version:1.0.1
Purpose:to perform multiple actions on project( add, modify, delete, verify )
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
$action = $obj['action'];

//to add project or to request a project

if ( $action == 'add' ) {
    $proj_title  		 = $obj['proj_title'];
    $proj_desc      	 = $obj['proj_desc'];
    $empId     	        = $obj['empId'];

    $sql_add = "insert into userIdeas(`empId`,`ideaTitle`,`ideaDesc`,`createdDate`,`modifiedDate`) values ('$empId','$proj_title','$proj_desc','$time','$time')";

    $sql_add_res = mysqli_query( $con, $sql_add ) or (logToFile($logfile," to add project or to request a project  - manage_ideas.php"));
    
if( $sql_add_res ){
    $result['status'] = 'True';
    $result['message'] = 'Success';

    $result['yes'] = 'yes';
    
}
 else {
        $result['status'] = 'false';
        $result['message'] = 'Something went wrong';
    }
}

//to make project approved
else if ( $action == 'accept' ) {
    $empId     	        = $obj['empId'];
    $idea_id			 = $obj['idea_id'];

    $sql = "update userIdeas set `acceptedBy`='$empId',`acceptedDate`='$time',`approvalStatus`='approved' where ideaId = $idea_id";
    $sql_res = mysqli_query( $con, $sql )  or (logToFile($logfile," to make project approved - manage_ideas.php"));
if(  $sql_res ){
    $result['status'] = 'True';
    $result['message'] = 'Success';
}
else{
	 $result['status'] = 'false';
        $result['message'] = 'Something went wrong';
}

}

//to reject a project
else if ( $action == 'reject' ) {
    $empId     	        = $obj['empId'];
    $idea_id			 = $obj['idea_id'];
    $rejected_reason	 = $obj['rejectReason'];

    $sql = "UPDATE `userIdeas` SET `approvalStatus`= 'rejected',`rejectedReason`= '$rejected_reason',`rejectedBy`= '$empId',`rejectedDate`= '$time' WHERE  `ideaId` =$idea_id ";

    $sql_res = mysqli_query( $con, $sql )  or (logToFile($logfile," to reject a project - manage_ideas.php"));
    
if( $sql_res ){
    $result['status'] = 'True';
    $result['message'] = 'Success';
} else {
	 $result['status'] = 'false';
        $result['message'] = 'Something went wrong';
}


}

//to modify project
else if ( $action == 'modify' ) {
    $proj_title  		 = $obj['proj_title'];
    $proj_desc      	 = $obj['proj_desc'];
    $empId     	        = $obj['empId'];
    $idea_id			 = $obj['idea_id'];

    $sql = "UPDATE  `userIdeas` SET  `ideaTitle` ='$proj_title ',`ideaDesc`= '$proj_desc',`empId`= '$empId',`modifiedDate`= '$time',`approvalStatus`='pending' WHERE  `ideaId` =$idea_id ";

    $sql_res = mysqli_query( $con, $sql )  or (logToFile($logfile," to modify project - manage_ideas.php"));

if( $sql_res ){
    $result['status'] = 'True';
    $result['message'] = 'Success';
} else {
        $result['status'] = 'false';
        $result['message'] = 'Something went wrong';
}
}

//to request again rejected project
else if ( $action == 'modifyrejectedidea' ) {
    $proj_title  		 = $obj['proj_title'];
    $proj_desc      	 = $obj['proj_desc'];
    $empId     	        = $obj['empId'];
    $idea_id			 = $obj['idea_id'];

    $sql = "UPDATE  `userIdeas` SET  `ideaTitle` ='$proj_title ',`ideaDesc`= '$proj_desc',`empId`= '$empId',`modifiedDate`= '$time',`approvalStatus`='pending' WHERE  `ideaId` =$idea_id ";

    $sql_res = mysqli_query( $con, $sql )   or (logToFile($logfile,"to request again rejected project - manage_ideas.php"));
    
if(  $sql_res ){
    $result['status'] = 'True';
    $result['message'] = 'Success';
} else {
        $result['status'] = 'false';
        $result['message'] = 'Something went wrong';
}
}

//to delete project, you can not delete a project if project has maintasks
else if ( $action == 'delete' ) {

    $empId     	        = $obj['empId'];
    $idea_id			 = $obj['idea_id'];

    $sql = "select sum(case when completeStatus in ('pending','completed') then 1 else 0 end) as remaining,
										sum(case when completeStatus='verified' then 1 else 0 end) as verified from userMainTasks where ideaId=$idea_id";

    $sql_res = mysqli_query( $con, $sql )   or (logToFile($logfile," to delete project, you can not delete a project if project has maintasks - manage_ideas.php"));

    $fet = mysqli_fetch_assoc( $sql_res );

    if ( $fet['remaining'] == 0 and $fet['verified'] == 0 ) {

        $sql = "UPDATE `userIdeas` SET `reopenStatus`= 'deleted' WHERE  `ideaId` =$idea_id ";

        $sql_res = mysqli_query( $con, $sql )   or (logToFile($logfile," to delete project, you can not delete a project if project has maintasks - manage_ideas.php"));

        $result['status'] = 'True';
        $result['message'] = 'Success';

    } else {
        $result['status'] = 'false';
        $result['message'] = 'Having maintasks,you cannot delete this project';
    }

}

//to set release owner for project
else if ( $action == 'ro' )	 {

    $empId     	        = $obj['empId'];
    $idea_id			 = $obj['ideaId'];

    $sql = "UPDATE `userIdeas` SET `releaseOwner`= '$empId' WHERE  `ideaId` =$idea_id ";

    $sql_res = mysqli_query( $con, $sql )   or (logToFile($logfile," to set release owner for project - manage_ideas.php"));
if(  $sql_res ){
    $result['status'] = 'True';
    $result['message'] = 'Success';
} else {
        $result['status'] = 'false';
        $result['message'] = 'Having maintasks,you cannot delete this project';
    }

}

//to verify a project, you can verify a project only when all maintasks under project are verified
else if ( $action == 'verify' ) {

    $empId     	        = $obj['empId'];
    $idea_id			 = $obj['idea_id'];

    $sql = "select sum(case when completeStatus in ('pending','completed') then 1 else 0 end) as remaining,
										sum(case when completeStatus='verified' then 1 else 0 end) as verified from userMainTasks where ideaId='$idea_id'";

    $sql_res = mysqli_query( $con, $sql )  or (logToFile($logfile,"to verify a project, you can verify a project only when all maintasks under project are verified - manage_ideas.php"));

    $fet = mysqli_fetch_assoc( $sql_res );

    if ( $fet['remaining'] == 0 and $fet['verified']>0 ) {
        $completed = 'completed';
        $sql = "update `userIdeas` set ideaStatus='$completed' where ideaId='$idea_id'";
        $sql_res = mysqli_query( $con, $sql );
        $result['status'] = 'True';
        $result['message'] = 'Success';

    } elseif ( $fet['remaining'] == 0 and $fet['verified'] == 0 ) {

        $result['status'] = 'false';
        $result['message'] = 'you cannot verify unless you had main tasks';
    } else {
        $result['status'] = 'false';
        $result['message'] = 'some maintasks are remained unverified';
    }
}
else {
    $result['status'] = 'false';
    $result['message'] = 'Something went wrong';
}

header( 'Content-Type:application/json' );
echo json_encode( $result );

?>
