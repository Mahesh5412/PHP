<?php

/*Version :1.0.0
 * FileName:driverFeedback.php
 *Purpose: To insert feedback given by passenger
 *Developers Involved:Srikanth
 */

	//connecting to server
	include 'connect.php';
	// for logs
	$logfile = 'log/log_' .date('d-M-Y') . '.log';
	header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
	// original code starts
	if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD']=="GET")
	{	//get data from android API
	    $rideId = $_REQUEST['rideId'];
	    $driverRating = $_REQUEST['driverRating'];
	    $action = $_REQUEST['action'];
	    
	   //$rideId = '279';
	   // $driverRating = '3';
	   // $action = 'feedback';

		if($action == 'feedback'){			//get rideDetailId related to rideId
			$sql2="SELECT rideDetailId from rideDetail WHERE rideId='$rideId'";
			$res2=mysqli_query($con, $sql2);
			if($r2=mysqli_fetch_assoc($res2)){
				$rideDetailId = $r2['rideDetailId'];
			}
			
			$sql4 = "SELECT * FROM feedback WHERE rideDetailId='$rideDetailId'";
			$res4=mysqli_query($con, $sql4);
			if(mysqli_num_rows($res4) > 0){
				if($r4 = mysqli_fetch_assoc($res4)){
					$getPassengerFeedback = $r4['passengerRating'];
					if($getPassengerFeedback == '-1'){
						$sql= "UPDATE feedback SET passengerRating ='$driverRating' , `passengerComments` = 'empty' WHERE rideDetailId = '$rideDetailId'";
					}else{
						$result['status'] = 'true';
					}
				}
			}else{
				//insert feedback
			$sql = "INSERT INTO feedback (`rideDetailId`,`passengerRating`, `passengerComments`) VALUES ('$rideDetailId', '$driverRating', 'empty')";
			}
			$res = mysqli_query($con, $sql) or (logToFile($logfile,"Insert feedback data into feedback table - driverFeedback.php"));
			if($res){
				$result['status'] = 'true';
			}else {	
				$result['status'] = 'False';
			}
		} else if($action == 'getDriverDetails') {		// getting driver details to passenger feedback activity
			
			$sql4 = "SELECT driverRegistration.fullName,vehicleDetail.vehicleNo,documentDetail.documentPath, rideDetail.psourceLatitude, 
					 rideDetail.psourceLongitude, rideDetail.pdestiLatitude, rideDetail.pdestiLongitude from driverRegistration INNER JOIN 
					 rideDetail ON driverRegistration.driverId = rideDetail.driverId INNER JOIN vehicleDetail 
					 ON vehicleDetail.vehicleId = driverRegistration.vehicleId INNER JOIN documentDetail ON 
					 documentDetail.id = driverRegistration.driverId WHERE rideDetail.rideId='$rideId' AND documentDetail.documentName = 'photo'";
			$res4 = mysqli_query($con, $sql4);
			if($row = mysqli_fetch_assoc($res4)){
				$result['driverName'] = $row['fullName'];
				$result['vehicleNumber'] = $row['vehicleNo'];
				$result['driverPhoto'] = $row['documentPath'];
				$result['sourceLat'] = $row['psourceLatitude'];
				$result['sourceLng'] = $row['psourceLongitude'];
				$result['destinationLat'] = $row['pdestiLatitude'];
				$result['destinationLng'] = $row['pdestiLongitude'];
				$result['driverDetailStatus'] = 'true';
				} else {
					$result['driverDetailStatus'] = 'false';
					}		 
			
			
			}else {
			$result['status'] = 'False';
		}
	}else {		// if database connection failed
		$result['status'] = 'False';	// send status false
		logToFile($logfile,"DB connection failed - driverFeedback.php");
	}
	 header("Content-Type:application/json");
	 echo json_encode($result);
	// original code ends

?>
