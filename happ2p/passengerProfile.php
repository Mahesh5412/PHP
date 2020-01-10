<?php
	/*Version:1.0.0
	* FileName:passengerProfile.php
	*Purpose: get passenger related information
	*Developers Involved: Srikanth
	*/
	include 'connect.php';		//To connect to server
     //for logs
	$logfile= 'log/log_' .date('d-M-Y') . '.log'; 
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
	/*original code starts*/
	if($_SERVER['REQUEST_METHOD'] == "POST" || $_SERVER['REQUEST_METHOD'] == "GET")
	{
			//Get the data from API
			$mobileNumber = $_REQUEST['mobileNumber'];
			$deviceId = $_REQUEST['deviceId'];
			$action = $_REQUEST['action'];
			
			//$mobileNumber = '9441323340';
			//$action = 'profile';
			
			if($action == 'profile'){
				$sql1 = "SELECT * FROM `userRegistration` WHERE `mobile` = '$mobileNumber'";
				$res1 = mysqli_query($con, $sql1) or (logToFile($logfile,"Get passenger profile data - passengerProfile.php"));
				if($row = mysqli_fetch_assoc($res1)){
					$result['profileImage'] = $row['profilePic'];
					$result['passengerName'] = $row['fullName'];
					$result['referralCode'] = $row['referralCode'];
					$result['email'] = $row['email'];

					$sql = "SELECT COUNT(rd.rideDetailId) AS rideCount FROM `rideDetail` rd INNER JOIN payment p ON p.rideDetailId= rd.rideDetailId 
							INNER JOIN userRegistration ur ON ur.userId= rd.userId WHERE ur.mobile='$mobileNumber' AND rd.rideStatus='end' 
							AND p.paymentStatus='paid'";	
					$res = mysqli_query($con, $sql) or (logToFile($logfile,"Get passenger profile data - passengerProfile.php"));
					if($fet = mysqli_fetch_assoc($res)){ 
						$result['completedRides'] = $fet['rideCount'];			
						$result['passengerProfileStatus'] = 'True';
					}else{
						$result['passengerProfileStatus'] = 'False';
					}
			}else{
					$result['passengerProfileStatus'] = 'False';
			}	
				
		} else{
					$result['passengerProfileStatus'] = 'False';
		}	
	}else{
		$result['status'] = 'False';
		logToFile($logfile,"DB connection failed - passengerProfile.php");
	}
header("Content-Type:application/json");
echo json_encode($result);
 /*original code ends*/
?>
