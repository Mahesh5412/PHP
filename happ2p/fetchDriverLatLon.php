<?php

/*Version :1.0.0
 * FileName:fetchDriverLatLon.php
 *Purpose: Get the driver latitude and longitude from db
 *Developers Involved:Vineetha
 */
	//connecting to server
	include'connect.php';
	 //for logs
	$logfile= 'log/log_' .date('d-M-Y') . '.log'; 
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
	/*original code starts*/
	if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET")
	{
		$result['dbstatus'] = 'dbconnectionsuccess';
		//Get the data from API
		$action = $_REQUEST['action'];
		$rideId = $_REQUEST['rideId'];
		$mobileNumberToCheckActiveRide = $_REQUEST['mobileNumToGetCancelRides'];
		$cancelStatus = $_REQUEST['statusOfCancellation'];
		$deviceId = $_REQUEST['deviceId'];
		//$action ='cancelRide';
		//$mobileNumberToCheckActiveRide = '9912804571';
		//$cancelStatus = 'passenger cancelled';
		//$rideId = '774';

		if($action == "fecthDriverlatlon"){			//get driver latitude and longitude
			$sql1="SELECT driverDetail.driverLatitude, driverDetail.driverLongitude, driverDetail.driverId FROM driverDetail INNER JOIN rideDetail ON 
					rideDetail.driverId = driverDetail.driverId WHERE rideDetail.rideId='$rideId'";
			$driverLatLonRes = mysqli_query($con, $sql1) or (logToFile($logfile,"Get latitude and longitude of driver - fetchDriverLatLon.php"));
			if($r1=mysqli_fetch_assoc($driverLatLonRes)){
				$result['driverLatitude'] = $r1['driverLatitude'];
				$result['driverLongitude'] = $r1['driverLongitude'];
				$result['driverId'] = $r1['driverId'];
				$result['driverLatLonResult'] = 'latLonSuccess';
			}else{
				$result['driverLatLonResult'] = 'latLonFailed';
			}

		}else if($action == "getActiveRideStatus"){		//get active ride status
			$sql2="SELECT * FROM `rideDetail` INNER JOIN payment ON rideDetail.rideDetailId = payment.rideDetailId WHERE rideDetail.rideId='$rideId'";
			$activeRideRes = mysqli_query($con, $sql2) or (logToFile($logfile,"Get ride status and payment type - fetchDriverLatLon.php"));
			if($r2 = mysqli_fetch_assoc($activeRideRes)){
				$result['activeRideStatus'] = $r2['rideStatus'];
				$result['activePaymentType'] = $r2['paymentType'];
			}else{
				$result['activeRideStatus'] = 'failed';
			}
		}else if($action == "fareDetails"){			//get fare details realated to driver
			$sql3="SELECT * FROM `rideDetail` INNER JOIN payment ON rideDetail.rideDetailId = payment.rideDetailId WHERE rideDetail.rideId='$rideId'";
			$fareDetailsRes = mysqli_query($con, $sql3) or (logToFile($logfile,"Get fare details of ride - fetchDriverLatLon.php"));
			if($r3 = mysqli_fetch_assoc($fareDetailsRes)){
				$result['fareValue'] = $r3['individualFare'];
				$result['paymentId'] = $r3['paymentId'];
				$result['paymentDetailsRes'] ='detailsSuccess';
			}else{
				$result['paymentDetailsRes'] ='detailsFailed';
			}
			
			//to get cancelled ride pending payment
		$sql5="SELECT * from userRegistration as ur INNER JOIN rideDetail as rd ON ur.userId = rd.userId INNER JOIN payment as p ON p.rideDetailId=rd.rideDetailId 
			 WHERE (ur.mobile='$mobileNumberToCheckActiveRide' AND ur.deviceId = '$deviceId' AND rd.rideStatus='passenger cancelled' AND p.paymentStatus='pending')";
			 $res5=mysqli_query($con, $sql5) or (logToFile($logfile,"Get cancellation charges of user - fetchDriverLatLon.php"));
			 if($r5 = mysqli_fetch_assoc($res5)){
				 $result['cancelPayId'] = $r5['paymentId'];
				 $result['cancelCharges'] = $r5['individualFare'];
				 $result['cancelRideRes'] = 'cancelRideSuccess';
			 }else{
				 $result['cancelRideRes'] = 'cancelRideFailed';
			 }
		}
		else if($action == "passenger cancelled"){		
			$sql4="UPDATE rideDetail SET rideStatus='$cancelStatus' WHERE rideId='$rideId' AND (rideStatus = 'accepted' OR rideStatus='started')";
			$passengerCancelRes = mysqli_query($con, $sql4) or (logToFile($logfile,"Update cancel ride - fetchDriverLatLon.php"));
			if($passengerCancelRes){
				
					$result['cancellationResult'] = 'cancellationSuccess';
			}else{
				$result['cancellationResult'] = 'cancellationFailed2';
			}
		}else if($action == "passenger cancelled before accepted"){		
			$sql4="UPDATE rideDetail SET rideStatus='$cancelStatus' WHERE rideId='$rideId' AND (rideStatus = 'pending' OR rideStatus='assigned')";
			$passengerCancelRes = mysqli_query($con, $sql4) or (logToFile($logfile,"Update cancel ride - fetchDriverLatLon.php"));
			if($passengerCancelRes){
				$sql6 ="UPDATE driverDetail SET driverStatus='online' WHERE driverId = (SELECT driverId FROM rideDetail WHERE rideId ='$rideId')";
				$res6 = mysqli_query($con, $sql6);
				if($res6){
					$result['cancellationResult'] = 'cancellationSuccess';
				}else{
					$result['cancellationResult'] = 'cancellationFailed1';
				}
			}else{
				$result['cancellationResult'] = 'cancellationFailed2';
			}
		}																			
	}else{ 			//database connection fails
		
  			$result['dbstatus'] = 'dbConnectionFailed';
  			logToFile($logfile,"DB connection failed - fetchDriverLatLon.php");
  	
	}
	 header("Content-Type:application/json"); 
      echo json_encode($result);
/*original code ends here*/
?>
