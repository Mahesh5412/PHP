<?php

/*
FileName:getEmployeeInfo.php
Version:1.0.1
Purpose:to list the employees Project Info
Devloper:Naveen,rishitha
*/

session_start();

$json = file_get_contents( 'php://input' );
$obj = json_decode( $json, true );

$corp_code 		 = $obj['crop'];
$_SESSION['corp_code'] = $corp_code;

include 'connect.php';
// for Logs 
$logfile = 'log/log' .date('d-M-Y') . '.log';
$empId=$obj['empId'];
//$empId='77';
if($empId!=null){
//For count of Ideas
$sql1="SELECT COUNT(ideaId) as IdeaCount FROM `userIdeas` where (empId='$empId' || releaseOwner='$empId') and ideaStatus='pending' and (approvalStatus='approved' || approvalStatus='pending') and reopenStatus<>'deleted'";
$sql_res1 = mysqli_query( $con, $sql1 ) or (logToFile($logfile," for getting empolyee details - getEmployeeInfo.php"));
if(mysqli_num_rows($sql_res1)>0){
					
				if($fet1=mysqli_fetch_assoc($sql_res1))
				{
					 $response['IdeaCount'] = $fet1['IdeaCount'];
				}
			}
//For count of Subtask
$sql2="SELECT COUNT(subTaskId) as subTaskCount FROM `userSubTasks` where assignedTo='$empId' and status='pending'";
$sql_res2 = mysqli_query( $con, $sql2 ) or (logToFile($logfile," for getting empolyee details - getEmployeeInfo.php"));
if(mysqli_num_rows($sql_res2)>0){
				
				if($fet2=mysqli_fetch_assoc($sql_res2))
				{
					 $response['subTaskCount'] = $fet2['subTaskCount'];
				}
			}
//For count of Maintask
$sql3="SELECT COUNT(id) as mainTaskCount FROM `userMainTasks` where assignedTo='$empId' and completeStatus='pending'";
$sql_res3 = mysqli_query( $con, $sql3 ) or (logToFile($logfile," for getting empolyee details - getEmployeeInfo.php"));
if(mysqli_num_rows($sql_res3)>0){
				
				if($fet3=mysqli_fetch_assoc($sql_res3))
				{
					 $response['mainTaskCount'] = $fet3['mainTaskCount'];
				}
			}

//For RoadBlocck count
/*$sql4="SELECT COUNT(subTaskId) as RoadBlockCount FROM `roadBlocks` WHERE status='not solved'";
$sql_res4 = mysqli_query( $con, $sql4 ) or (logToFile($logfile," for getting empolyee details - getEmployeeInfo.php"));
if(mysqli_num_rows($sql_res4)>0){
				
				if($fet4=mysqli_fetch_assoc($sql_res4))
				{
					 $response['RoadBlockCount'] = $fet4['RoadBlockCount'];
				}
			}*/
			$sql4="SELECT COUNT(DISTINCT(ust.subTaskId)) noofRoadBlocks FROM roadBlocks rb INNER JOIN userSubTasks ust ON ust.subTaskId= rb.subTaskId WHERE ust.assignedTo='$empId' and ust.status <> 'completed'";
$sql_res4 = mysqli_query( $con, $sql4 );
if(mysqli_num_rows($sql_res4)>0){
			
			
				if($fet4=mysqli_fetch_assoc($sql_res4))
				{
					 $response['RoadBlockCount'] = $fet4['noofRoadBlocks'];

				}
			}
			 $response['status'] = 'True';
    $response['message'] = 'Success';
    
}
else{
	 $response['status'] = 'False';
    $response['message'] = 'Failed';
}
header( 'Content-Type:application/json' );
echo json_encode( $response );

?>
