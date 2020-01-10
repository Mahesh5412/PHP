<?php

/*Version :1.0.0
 * FileName:dPassengerCancel.php
 *Purpose: checking weather the passenger has cancelled or not till trip started
 *Developers Involved:mahesh
 */

	//connecting to server
	include 'connect.php';
	//for logs
	$logfile= 'log/log_' .date('d-M-Y') . '.log'; 
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
  
	/*original code starts*/
	if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET")
	{	//get data from android API
		$result['dbstatus'] = 'dbconnectionsuccess';
	
		$rideId = $_REQUEST['rideId'];
		//$rideId = '776';
		$driverId = $_REQUEST['driverId']; 
		
		$sql = "SELECT `rideStatus` FROM `rideDetail` WHERE `rideId` = '$rideId'";
		$res = mysqli_query($con, $sql) or (logToFile($logfile,"Get rideStatus from rideDetail table - dPassengerCancel.php"));
		if($row = mysqli_fetch_assoc($res)){
				$result['passengerCancelStatus'] = $row['rideStatus'];
		}else{
				$result['passengerCancelStatus'] = 'Fail';
		}
	}else{
        //If database connection failed
        $result['dbstatus'] = 'False';    //Send status as False
        logToFile($logfile,"DB connection failed - dPassengerCancel.php");
  	}
	 header("Content-Type:application/json"); 
      echo json_encode($result);

?>
