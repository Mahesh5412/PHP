<?php

/*Version :1.0.0
 * FileName:driverProfile.php
 *Purpose: To get driver information
 *Developers Involved: Srikanth
 */

	//connecting to server
	include 'connect.php';
	// for logs
	$logfile = 'log/log_' .date('d-M-Y') . '.log';
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
	
	// original code starts
	if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD']=="GET")
	{	//get data form android API
	    $driverId = $_REQUEST['driverId'];
	    $action = $_REQUEST['action'];
	    
	   // $driverId = 'DMYTCN';
	    //$action = 'profile';

		if($action == 'profile')
		{
			  $sql1 = "SELECT vehicleId from `driverRegistration` where driverId = '$driverId'";
			  $res1 = mysqli_query($con,$sql1) or (logToFile($logfile,"Get vehcile id - driverProfile.php"));
			  
			  $fet = mysqli_fetch_assoc($res1);
			  $vehicleId = $fet['vehicleId'];
				//get driver information
			$sql = "SELECT * from `driverRegistration` INNER JOIN `vehicleDetail` ON driverRegistration.vehicleId = vehicleDetail.vehicleId 
					where driverRegistration.vehicleId = '$vehicleId'";
			$res = mysqli_query($con, $sql) or (logToFile($logfile,"Get driver details - driverProfile.php"));
			
			if($row = mysqli_fetch_assoc($res)){		//fetch dirver information
				$result['driverName'] = $row['fullName'];
				$result['mobileNumber'] = $row['mobile'];
				$result['vehicleName'] = $row['vehicleName'];
				$result['vehicleNumber'] = $row['vehicleNo'];
				
				$sql2="SELECT documentPath FROM `documentDetail` WHERE id='$driverId' AND documentName='photo'";
				$res2=mysqli_query($con, $sql2) or (logToFile($logfile,"Get driver profile image - driverProfile.php"));
				
				if($r2=mysqli_fetch_assoc($res2)){
						$result['profileImage'] = $r2['documentPath'];
				}
				 $result['profileStatus'] = 'True';
			}else{
				$result['profileStatus'] = 'False';
			}
		
		}else {	
			$result['profileStatus'] = 'False';
		}
	}else {		// if database connection failed
		$result['status'] = 'False';	// send status false
		logToFile($logfile,"DB connection failed - driverProfile.php");
	}
	 header("Content-Type:application/json");
	 echo json_encode($result);
	// original code ends

?>
