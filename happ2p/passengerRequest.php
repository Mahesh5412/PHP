<?php

/*Version :1.0.0
 * FileName:passengerRequest.php
 *Purpose: To insert the ride related information into db
 *Developers Involved:Vineetha
 */
	//connecting to server
	include'connect.php';
	include 'mk.php';

	 //for logs
	$logfile= 'log/log_' .date('d-M-Y') . '.log'; 
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
	/*original code starts*/
	if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET"){
		$result['dbstatus'] = 'dbconnectionsuccess';
		//$action = "assignDriver";
	     $gcm=new GCM();
		//Get the data from API
		$rideId = $_REQUEST['rideId'];
		$mobileNumber = $_REQUEST['mobileNumber'];
 		$sourceLat = $_REQUEST['sourceLatitude'];
		$sourceLon = $_REQUEST['sourceLongitude'];
		$destinationLat = $_REQUEST['destinationLatitude'];
		$destinationLon = $_REQUEST['destinationLongitude'];
		$rideType = $_REQUEST['rideType'];
		$noOfSeats = $_REQUEST['noOfSeats'];
		$vehType = $_REQUEST['vehType'];
		$fare = $_REQUEST['fare'];
		$action=$_REQUEST['action'];
		$paymentType = $_REQUEST['cashOrDigital'];
		$driverId = $_REQUEST['driverId'];
		$deviceId = $_REQUEST['deviceId']; 
		$appliedCouponId = $_REQUEST['appliedCouponId'];
		$mobileNumberToCheckActiveRide = $_REQUEST['mobileNumberToCheckActiveRide'];
		$sqlAction = $_REQUEST['sqlaction'];
		$discountAmount = $_REQUEST['discountAmount'];
		//static data
		//$cancellationChargesPaymentId = $_REQUEST['cancellationChargesPaymentId'];
		//$cancellationCharges = $_REQUEST['cancellationCharges']; 
		//$action="getDriverDetails";
		//$driverId = 'DFAWO8';
		/*$rideId = '374';
		$mobileNumber='9912804571';
		$deviceId = '02f2c6d440707b5e';
		$action = 'rideRequestInsert';
		$rideType = 'single';
		$vehType = 'auto';
		$noOfSeats ='3';
		$paymentType = 'cash';
		$fare = '300';
		$sourceLat = '17.4420792';
		$sourceLon ='78.3552187';
		$destinationLat ='17.516901400000002';
		$destinationLon ='78.3428304';
		$appliedCouponId ='0';
		$discountAmount ='0';*/

		
		$timeStamp = date("Y-m-d H:i:s");		//current time
		
		if($action == "rideRequestInsert"){			//to insert ride request

				//Insert data into rideDetail table
				$sql="INSERT INTO `rideDetail` (`rideId`,`rideType`,`userId`,`vehicleType`,`psourceLatitude`,`psourceLongitude`,`pdestiLatitude`,`pdestiLongitude`,
					`passengerCount`,`rideStatus`,`bookingTime`) VALUES ('$rideId','$rideType',(SELECT userId FROM `userRegistration` WHERE mobile='$mobileNumber'
					 AND deviceId='$deviceId'),'$vehType','$sourceLat','$sourceLon','$destinationLat','$destinationLon',
					'$noOfSeats','pending','$timeStamp')";
					
				$rideInfo = mysqli_query($con,$sql) or (logToFile($logfile,"Insert ride information - passengerRequest.php"));
				
				$sql2="INSERT INTO payment (rideDetailId, individualFare, paymentType, totalpayment, discountAmount, couponId) VALUES
				 ((SELECT rideDetailId from rideDetail WHERE rideId='$rideId'), '$fare','$paymentType', '$fare','$discountAmount', '$appliedCouponId')";
				 
				 $sql3 = "SELECT  driverId, 111.111 * DEGREES(ACOS(LEAST(COS(RADIANS($sourceLat)) * COS(RADIANS(driverLatitude)) * COS(RADIANS($sourceLon - driverLongitude))
				+ SIN(RADIANS($sourceLat)) * SIN(RADIANS(driverLatitude)), 1.0))) AS distance_in_km FROM driverDetail WHERE driverStatus='online'
				HAVING distance_in_km < 1 ORDER BY `distance_in_km` ASC LIMIT 1";
					
				if($rideInfo){   //If query executed successfully
					$paymetIfoRes = mysqli_query($con, $sql2) or (logToFile($logfile,"Insert payment information - passengerRequest.php"));
					if($paymetIfoRes){
						$result['rideReqStatus'] = 'rideReqInserted';
						$sqlRes3 = mysqli_query($con, $sql3);
						if(mysqli_num_rows($sqlRes3) > 0){
							if($resRow3 = mysqli_fetch_assoc($sqlRes3)){
								 $driverIdRes = $resRow3['driverId'];
							}
							  
							$sql4 = "UPDATE `rideDetail` SET `driverId`='$driverIdRes', rideStatus = 'assigned' WHERE rideId='$rideId'";
							
							$sql5 = "UPDATE `driverDetail` SET `driverStatus` ='assigned' WHERE driverId='$driverIdRes'";
							$resSql5 = mysqli_query($con, $sql5);
							if($resSql5){
								$resSql4 = mysqli_query($con, $sql4) or die("error");
								$result['driverId'] = $driverIdRes;
								$result['rideAssignStatus'] = 'driverAssignedSuccess';
							}else{
								$result['rideAssignStatus'] = 'driverAssignedFailed';
							}
						}else{
							$result['rideAssignStatus'] = 'driverAssignedFailed';
						}
						
					}else{
						$result['rideReqStatus'] = 'rideReqFailed';
					}			
				}else{
					$result['rideReqStatus'] = 'rideReqFailed';
				}	 

		}else if($action =="assignDriver"){			//assign driver to request
			if($driverId == "findDriver"){
				 $sql3 = "SELECT  driverId, 111.111 * DEGREES(ACOS(LEAST(COS(RADIANS($sourceLat)) * COS(RADIANS(driverLatitude)) * COS(RADIANS($sourceLon - driverLongitude))
				+ SIN(RADIANS($sourceLat)) * SIN(RADIANS(driverLatitude)), 1.0))) AS distance_in_km FROM driverDetail WHERE driverStatus='online'
				HAVING distance_in_km < 1 ORDER BY `distance_in_km` ASC LIMIT 1";
						$sqlRes3 = mysqli_query($con, $sql3);
						if(mysqli_num_rows($sqlRes3) > 0){
							if($resRow3 = mysqli_fetch_assoc($sqlRes3)){
								 $driverIdRes = $resRow3['driverId'];
							}
							
							$sql4 = "UPDATE rideDetail SET driverId ='$driverIdRes', rideStatus = 'assigned' WHERE rideId='$rideId'";
							$resSql4 = mysqli_query($con, $sql4);
							$sql5 = "UPDATE driverDetail SET driverStatus ='assigned' WHERE driverId='$driverIdRes'";
							$resSql5 = mysqli_query($con, $sql5);
							if($resSql5){
								/*$notification = [
            						'title' =>'Hapdriver',
            						'body' => 'passenger has Requested a ride, kindly accept ride or reject the ride',
            						'icon' => 'myIcon', 
            						'sound' => 'mySound'
       								 ];
					$sql_push = "select `deviceToken` from `driverDetail` where 
					`driverId` ='$driverId'";
							$res_push = mysqli_query($con,$sql_push);
			
							if($row = mysqli_fetch_assoc($res_push))
								{
									$device_token = $row["deviceToken"]; 
									/*$device_token = "dyIzdwQIBS0:APA91bGHNFZrfhL1ytrgOJwNk_c3aBZPFYHEelRCVGo2sEMDFI3M9o25dJ2bc5C2UpR1AR6rYLE-URyOBaRmqw5s_ashzfcJmnnrnuM4reC1dmP8fm90XyquZqzMevaC2CR_zRkx8dlU";*/
									/*if(!empty($device_token))
										{
						   					$result1 = $gcm->sendGCM($device_token, $notification);
						   					
					        				//print_r($result1);
										}
								}*/
								$result['driverId'] = $driverIdRes;
								$result['driverAssign'] = 'driverAssignedSuccess';
							}else{
								$result['driverAssign'] = 'driverAssignedFailed';
							}
						}else{
							$result['driverAssign'] = 'driverAssignedFailed';
						}
			}
			/*$sql3="UPDATE rideDetail set driverId='$driverId', rideStatus='assigned' WHERE rideId='$rideId'";
			$driverAssignRes=mysqli_query($con, $sql3) or (logToFile($logfile,"assign drievr ro ride request - passengerRequest.php"));
			if($driverAssignRes){
				
    				 $notification = [
            						'title' =>'Hapdriver',
            						'body' => 'passenger has Requested a ride, kindly accept ride or reject the ride',
            						'icon' => 'myIcon', 
            						'sound' => 'mySound'
       								 ];
					$sql_push = "select `deviceToken` from `driverDetail` where 
					`driverId` ='$driverId'";
							$res_push = mysqli_query($con,$sql_push);
			
							if($row = mysqli_fetch_assoc($res_push))
								{
									$device_token = $row["deviceToken"]; 
									/*$device_token = "dyIzdwQIBS0:APA91bGHNFZrfhL1ytrgOJwNk_c3aBZPFYHEelRCVGo2sEMDFI3M9o25dJ2bc5C2UpR1AR6rYLE-URyOBaRmqw5s_ashzfcJmnnrnuM4reC1dmP8fm90XyquZqzMevaC2CR_zRkx8dlU";*/
									/*if(!empty($device_token))
										{
						   					$result1 = $gcm->sendGCM($device_token, $notification);
						   					
					        				//print_r($result1);
										}
								}
				$result['driverAssign']='driverAssignedSuccess';
			
			}else{
				$result['driverAssign']='driverAssignedFailed'; 
			}
			*/
			
			
		}else if($action == "getDriverResponse"){			//to get driver response
			$sql4 = "SELECT rideDetail.rideStatus,payment.paymentType,payment.paymentStatus FROM rideDetail INNER JOIN payment ON rideDetail.rideDetailId = payment.rideDetailId 
					WHERE rideDetail.rideId = '$rideId'";
			$driverResponse = mysqli_query($con, $sql4) or (logToFile($logfile,"Get driver response - passengerRequest.php"));
			if($r4=mysqli_fetch_assoc($driverResponse)){
				$result['driverAcceptStatus'] = $r4['rideStatus'];
				$result['passPaymentType'] = $r4['paymentType'];
				$result['paymentStatus'] = $r4['paymentStatus'];
				$result['driverAcceptResponse'] = 'driverResponseSuccess';
			}else{
				$result['driverAcceptStatus'] = 'failed';
				$result['driverAcceptResponse'] = 'driverResponseFailed';
			}
				
		}else if($action == "rideRejected"){			//to intimate to user when ride is rejected
			$sql8="UPDATE rideDetail SET rideStatus='time out to accept' WHERE rideId='$rideId' AND (rideStatus = 'pending' OR rideStatus = 'assigned')";
			$res8=mysqli_query($con, $sql8) or (logToFile($logfile,"Update ride reject status - passengerRequest.php"));
			if($res8){
				$result['driverAcceptStatus'] = 'rejected';
				$result['driverAcceptResponse'] = 'driverResponseSuccess';
			}else{
				$result['driverAcceptStatus'] = 'failed';
				$result['driverAcceptResponse'] = 'driverResponseFailed';
			}
			
		}else if($action == "getDriverDetails"){		//to get driver details
			$sql5="SELECT dd.vehicleId, vd.vehicleNo, vd.vehicleType, vd.vehicleName, vd.seatCount, dr.fullName, dr.mobile, docd.documentPath FROM driverDetail as dd 
					INNER JOIN vehicleDetail AS vd ON dd.vehicleId = vd.vehicleId INNER JOIN driverRegistration dr ON dr.driverId = dd.driverId INNER JOIN documentDetail docd 
					ON docd.id=dr.driverId  WHERE dd.driverId='$driverId' AND docd.documentName='photo'";
			$driverDetailsRes = mysqli_query($con, $sql5) or (logToFile($logfile,"Get driver details - passengerRequest.php"));
			if(mysqli_num_rows($driverDetailsRes) >0){
				if($r5 = mysqli_fetch_assoc($driverDetailsRes)){
					$sql_otp = "SELECT `otp` FROM `rideDetail` WHERE `driverId` = '$driverId' AND `rideId` = '$rideId'";
					$otpRes = mysqli_query ($con , $sql_otp) or (logToFile($logfile,"Get ridedetail otp - passengerRequest.php"));
					if($r6 =mysqli_fetch_assoc($otpRes)){
						$result['rideOTP'] = $r6['otp'];
					} 
					$result['vehicleNumber'] = $r5['vehicleNo'];
					$result['vehicleType'] = $r5['vehicleType'];
					$result['vehicleName'] = $r5['vehicleName'];
					$result['seatCount']  = $r5['seatCount'];	
					$result['driverName'] = $r5['fullName'];
					$result['driverMobileNumber'] = $r5['mobile'];
					$result['profileImage']  = $r5['documentPath'];
					$result['driverDetailsResponse'] = 'driverDetailsSuccess';	
				
					$sqlfb = "SELECT COUNT(feedback.rideDetailId) AS noOfRides, IFNULL(SUM(feedback.passengerRating), 0) AS dFeedback FROM `rideDetail` INNER JOIN 
						feedback ON rideDetail.rideDetailId = feedback.rideDetailId WHERE rideDetail.driverId = '$driverId'";
					$driverfbres = mysqli_query($con, $sqlfb) or (logToFile($logfile,"Get dirver rating - passengerRequest.php"));
					if(mysqli_num_rows($driverfbres) > 0){
						if($rfb = mysqli_fetch_assoc($driverfbres)){		//taking average of feedback gievn by passengers
							$rides = $rfb['noOfRides'];
							$driverFeedback = $rfb['dFeedback'];
							if ($rides == '0'){
								$result['driverFeedback'] = $driverFeedback;
							}else{
								$result['driverFeedback'] = $driverFeedback / $rides; 
							}	
						}else{
							$result['driverFeedback'] ='0';
						}
					}else{
						$result['driverFeedback'] ='0';
					}	
			}else{
				$result['driverDetailsResponse'] = 'driverDetailsFailed';
			}
		}else{
			$result['driverDetailsResponse'] = 'driverDetailsFailed';
		}
		}else if($action == "checkPassengerActiveRides"){			//to check passenger active rides
			
			$sql7="SELECT userId FROM `userRegistration` WHERE mobile='$mobileNumberToCheckActiveRide' AND deviceId='$deviceId'";
				$rideIdResult7 = mysqli_query($con, $sql7) or (logToFile($logfile,"Get user id - passengerRequest.php"));
				if($r7=mysqli_fetch_assoc($rideIdResult7)){
					$userId=$r7['userId'];
				}
			/*$sql6="SELECT * from userRegistration as ur INNER JOIN rideDetail as rd ON ur.userId = rd.userId WHERE ur.mobile='$mobileNumberToCheckActiveRide'
			 AND rd.rideStatus='accepted' OR rd.rideStatus = 'started' OR rd.rideStatus = 'trip started' OR rd.rideStatus='end'";*/
			 $sql6="SELECT * from userRegistration as ur INNER JOIN rideDetail as rd ON ur.userId = rd.userId INNER JOIN payment as p ON p.rideDetailId=rd.rideDetailId 
             WHERE rd.userId='$userId' AND (rd.rideStatus='pending' OR rd.rideStatus='assigned' OR rd.rideStatus='accepted' OR rd.rideStatus = 'started' 
             OR rd.rideStatus = 'trip started' OR (rd.rideStatus='end' AND p.paymentStatus='pending'))";
			 $fetchActiveRidesResult = mysqli_query($con, $sql6) or (logToFile($logfile,"Get active ride status - passengerRequest.php"));
			 
			 if(mysqli_num_rows($fetchActiveRidesResult) > 0){			
				if($r6 = mysqli_fetch_assoc($fetchActiveRidesResult)){
					$result['activePassRideId'] = $r6['rideId'];
					$result['activePassDriverId'] = $r6['driverId'];
					$result['activePassVehicleType'] = $r6['vehicleType'];
					$result['activePassRideStatus'] = $r6['rideStatus'];
					$result['paymentStatus'] = $r6['paymentStatus']; 
					$result['paymentType'] = $$r6['paymentType'];
					$result['activePassSourceLatitude'] = $r6['psourceLatitude'];
					$result['activePassSourceLongitude'] = $r6['psourceLongitude'];
					$result['activePassDestinationLatitude'] = $r6['pdestiLatitude'];
					$result['activePassDestinationLongitude'] = $r6['pdestiLongitude'];	
					$result['passengerActiveRideResponse']= 'activeRideSuccess';				
				}else{
					$result['passengerActiveRideResponse']= 'NoActiveRides';
				} 
			 }else{
					$result['passengerActiveRideResponse']= 'NoActiveRides';
				} 
			
			$sql7=	"SELECT * from userRegistration INNER JOIN rideDetail ON userRegistration.userId = rideDetail.userId INNER JOIN payment ON 
					rideDetail.rideDetailId = payment.rideDetailId WHERE userRegistration.mobile='$mobileNumberToCheckActiveRide' AND 
					rideDetail.rideStatus='end' AND payment.paymentStatus='paid' ORDER BY rideDetail.rideDetailId DESC LIMIT 1";
			$res7=mysqli_query($con, $sql7) or (logToFile($logfile,"Get feedback response - passengerRequest.php"));
			if($row7 = mysqli_fetch_assoc($res7)){
				
				$rideDetailId = $row7['rideDetailId'];
				//echo $rideDetailId;
			
				$result['rideId'] = $row7['rideId'];
				
				$sql8 = "SELECT * FROM `feedback` WHERE rideDetailId = '$rideDetailId'";
				$res8 = mysqli_query($con, $sql8);
				//echo 1234;
				if(mysqli_num_rows($res8) > 0){
					//echo 321;
					if($r8 = mysqli_fetch_assoc($res8)){
						//$result['passengerFeedback'] = $r8['passengerRating'];
						
						$passengerFeedbackVal = $r8['passengerRating'];
						//echo $passengerFeedbackVal;
						if($passengerFeedbackVal == '-1'){
							$result['feedbackResponse'] = 'pendingFeedback';
						}else{
							$result['feedbackResponse'] = 'noPendingFeedback';
						}
					}else{
						$result['feedbackResponse'] = 'pendingFeedback';
					}
				}else {
					$result['feedbackResponse'] = 'pendingFeedback';
				}
			}else{
				$result['feedbackResponse'] = 'noPendingfeedback';
			}	 
		}else{
			$result['dbstatus'] = 'dbConnectionFailed';
		}																		
	}else{ 			//database connection fails
		
  			$result['dbstatus'] = 'dbConnectionFailed';
  			logToFile($logfile,"DB connection failed - passengerRequest.php");
	}
	 header("Content-Type:application/json"); 
      echo json_encode($result);
/*original code ends here*/
?>
