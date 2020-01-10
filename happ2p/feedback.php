<?php

/*Version :1.0.0
 * FileName:feedback.php
 *Purpose: To store the feedback given by driver
 *Developers Involved:mahesh
 */

	//connecting to server
	include'connect.php';
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
  
	/*original code starts*/
	if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET")
	{	//get data from android API
		$result['dbstatus'] = 'dbconnectionsuccess';
		$rating = $_REQUEST['rating'];
		$rideId = $_REQUEST['rideId'];
		$givenByRole = $_REQUEST['givenByRole'];
		
		//$rating = '4';
		//$rideId = '630';
		//$givenByRole = 'driver';
		
			//insert feedback into db
			$sql3="SELECT rideDetailId from rideDetail WHERE rideId='$rideId'";
			$res3=mysqli_query($con, $sql3);
			if($r3=mysqli_fetch_assoc($res3)){
				$rideDetailId = $r3['rideDetailId'];
			}
		$sql2 = "SELECT * FROM feedback WHERE rideDetailId='$rideDetailId'";
		$res2=mysqli_query($con, $sql2);
			if(mysqli_num_rows($res2) > 0){
				if($r2 = mysqli_fetch_assoc($res2)){
					$getDriverFeedback = $r2['driverRating'];
					if($getDriverFeedback == '-1'){
						$sql= "UPDATE feedback SET driverRating ='$rating' , `driverComments` = 'empty' WHERE rideDetailId = '$rideDetailId'";
					}else{
						$result['feedbackStatus'] = 'success';
					}
				}
			}else{
				//insert feedback
			$sql = "INSERT INTO feedback (`rideDetailId`,`driverRating`, `driverComments`) VALUES ('$rideDetailId', '$rating', 'empty')";
			}
			$res = mysqli_query($con, $sql) or (logToFile($logfile,"Insert feedback data into feedback table - driverFeedback.php"));
			if($res){
				$result['feedbackStatus'] = 'success';
			}else {	
				$result['feedbackStatus'] = 'False';
			}
      	/*$sql = "INSERT INTO feedback (`rideDetailId`,`givenByRole`,`rating`) 
				VALUES((SELECT rideDetailId from rideDetail where rideId = '$rideId' AND rideStatus = 'end'), '$givenByRole','$rating')";
				$res = mysqli_query($con, $sql) or (logToFile($logfile,"Insert feedback data feedback.php"));
      	if($res){
			$result['feedbackStatus'] = 'success';
		}else{
			$result['feedbackStatus'] = 'fail';
		}*/

	}
	else{// if db connection failed
        $result['dbstatus'] = 'fail';    //Send status as False
        logToFile($logfile,"DB connection failed feedback.php");
	}
	 header("Content-Type:application/json"); 
      echo json_encode($result);

?>
