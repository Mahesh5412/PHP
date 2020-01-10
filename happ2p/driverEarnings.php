<?php

/*Version :1.0.0
 * FileName:driverEarnings.php
 *Purpose: This file is used to get dirver earnings 
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
	{	//get data from android API
		
	    $driverId = $_REQUEST['driverId'];
	    $action = $_REQUEST['action'];
	    
	   // $driverId='DFAWO8';
	    //$action='earnings';
	
		if($action == 'earnings'){	//get today driver earnings
			
			$sql = "SELECT rd.driverId, IFNULL(SUM(p.totalpayment), 0) AS totalpayment, COUNT(rd.rideDetailId) AS totalRides FROM `rideDetail` rd 
					INNER JOIN payment p ON rd.rideDetailId= p.rideDetailId WHERE rd.driverId ='$driverId' AND p.paymentStatus = 'paid' 
					AND date(rd.rideStartTime) = DATE(NOW())";
			$res = mysqli_query($con, $sql) or (logToFile($logfile,"Get today driver earnings - driverEarnings.php"));
			if($row = mysqli_fetch_assoc($res)){
				$result['dayRides'] = $row['totalRides'];
				 $dayOriginalAmount = $row['totalpayment'];
				$result['dayAmount'] = ($dayOriginalAmount * 80)/100;			
			}
			//get one week driver earnings
		  $sql1 = "SELECT rd.driverId, IFNULL(SUM(p.totalpayment), 0) AS totalpayment, COUNT(rd.rideDetailId) AS totalRides FROM `rideDetail` rd
					INNER JOIN payment p ON rd.rideDetailId= p.rideDetailId WHERE rd.driverId ='$driverId' AND p.paymentStatus = 'paid'
					AND date(rd.rideStartTime) >= DATE(NOW()) - INTERVAL 7 DAY";
			$res1 = mysqli_query($con, $sql1) or (logToFile($logfile,"Get one week driver earnings - driverEarnings.php"));
			if($row1 = mysqli_fetch_assoc($res1)){
				$result['weekRides'] = $row1['totalRides'];
				$weekOriginalAmount = $row1['totalpayment'];
				$result['weekAmount'] = ($weekOriginalAmount * 80)/100;	
			}
				//get one month driver earnings
		  $sql2 = "SELECT rd.driverId, IFNULL(SUM(p.totalpayment), 0) AS totalpayment, COUNT(rd.rideDetailId) AS totalRides FROM `rideDetail` rd 
					INNER JOIN payment p ON rd.rideDetailId= p.rideDetailId WHERE rd.driverId ='$driverId' AND p.paymentStatus = 'paid' 
					AND date(rd.rideStartTime) >= DATE(NOW()) - INTERVAL 30 DAY";
			$res2 = mysqli_query($con, $sql2) or (logToFile($logfile,"Get one month driver earnings - driverEarnings.php"));
			if($row2 = mysqli_fetch_assoc($res2)){
				$result['monthRides'] = $row2['totalRides'];
				$monthOriginalAmount = $row2['totalpayment'];
				$result['monthAmount'] = ($weekOriginalAmount * 80)/100;	
			}
		  
		  $result['status'] = 'True';
		  
			
		}else if($action == 'getDriverOtp'){		//get OTP to settle amount
			$sql="SELECT otp FROM settlementDetails WHERE payeeId='$driverId' and status='0' ORDER BY sno DESC LIMIT 1";
			$res=mysqli_query($con, $sql) or (logToFile($logfile,"Get OTP to settle amount to agent - driverEarnings.php"));
			if($r1=mysqli_fetch_assoc($res)){
				$result['driverOtp'] = $r1['otp'];
				$result['driverOtpResult']="success";
			}else{
				$result['driverOtpResult']="fail";
			}
		}else {	
			$result['status'] = 'False';
		}
	}else {		// if database connection failed
		$result['status'] = 'False';	// send status false
		logToFile($logfile,"DB connection failed - driverEarnings.php");
	}
	 header("Content-Type:application/json");
	 echo json_encode($result);
	// original code ends

?>
