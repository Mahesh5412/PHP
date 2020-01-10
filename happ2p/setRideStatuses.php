<?php
/*FileName:setRideStatuses.php
 *Purpose: set rideStatus for ride
 *Developers Involved: Mahesh
 */
	//connecting to server
	include 'connect.php';
	include 'passengerNotification.php';
	include 'mailer.php';

	 //for logs
	$logfile= 'log/log_' .date('d-M-Y') . '.log'; 
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
	
	/*original code starts*/
	if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET")
	{	 
		$result['dbstatus'] = 'dbconnectionsuccess';	//db connection
		$mailes = new Mails(); // creating object for mails
		//get data from android API
		$rideStatus = $_REQUEST['rideStatus'];
		//$rideStatus = "paid";
		$rideId = $_REQUEST['rideId'];
		//$rideId  = '704';
		$driverId = $_REQUEST['driverId'];
		//$driverId = 'DFAWO8';
		$cancelledReason = $_REQUEST['cancelledReason']; 

		date_default_timezone_set('Asia/Kolkata');
		$date = date( 'Y-m-d h:i:s A', time () );

		$passengerNotification = new PassengerGCM(); // object for notification
		//static data
		// $rideStatus = 'accepted';
		// $rideId = '304';
		// $driverId = 'DMYTCN';

		                        /*gettting soure, destination name from latitudes, longitudes starts here */
function get_sourcelocation($lat,$lng){ //source location
                 $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&key=AIzaSyATaE4rWC3YNt0hd0x9TgkOJCN9RfzV6mE&sensor=true";
                  $sourceData = @file_get_contents($url);
                  $jsondata = json_decode($sourceData,true);
                  $sourceData = array();
                 foreach($jsondata['results']['3']['address_components'] as $element){
                     $sourceData[ implode(' ',$element['types']) ] = $element['long_name'];
                                 }
                  return sourceLocation(json_encode($sourceData['neighborhood political']),json_encode($sourceData['political sublocality sublocality_level_2']),json_encode($sourceData['political sublocality sublocality_level_1']), json_encode($sourceData['locality political']));  

                         }
                      function sourceLocation($local,$religion,$sublocality,$locality){ //converting json data
    	               	return trim($local, '"').",".trim($religion, '"').",".trim($sublocality, '"').",".trim($locality, '"');
    	                  }

        function get_destinationlocation($lat,$lng){// destination location
                 $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&key=AIzaSyATaE4rWC3YNt0hd0x9TgkOJCN9RfzV6mE&sensor=true";
                  $destinationData = @file_get_contents($url);
                  $jsondata = json_decode($destinationData,true);
                  $destinationData = array();
                 foreach($jsondata['results']['3']['address_components'] as $element){
                     $destinationData[ implode(' ',$element['types']) ] = $element['long_name'];
                                 }
                return destinationLocation(json_encode($destinationData['neighborhood political']),json_encode($destinationData['political sublocality sublocality_level_2']),json_encode($destinationData['political sublocality sublocality_level_1']), json_encode($destinationData['locality political'])); 
                              }
                 function destinationLocation($local,$religion,$sublocality,$locality){//converting json data
    	            return trim($local, '"').",".trim($religion, '"').",".trim($sublocality, '"').",".trim($locality, '"'); 
    	                  }
/*gettting soure, destination name from latitudes, longitudes ends here */

		$passMobNumSql ="SELECT userRegistration.mobile from `userRegistration` INNER JOIN `rideDetail` ON 
												userRegistration.userId = rideDetail.userId where `rideId` = '$rideId'";
		$passMobResult = mysqli_query($con, $passMobNumSql) or (logToFile($logfile,"Get user mobile number - setRideStatuses.php"));
		if($mobNum = mysqli_fetch_assoc($passMobResult)){
			$passengerMobileNum = $mobNum['mobile'];
		}
		
		$timeStamp = date("Y-m-d H:i:s");		//current time

		if($rideStatus == 'accepted'){			//when rideStatus is accepted
             /*checking ride Staus before accepet */
			$checkRideStatus_sql = "SELECT `rideStatus` FROM `rideDetail` WHERE `rideId` = '$rideId'";
			$checkRideStatus_sql_execute = mysqli_query($con, $checkRideStatus_sql) or 
			(logToFile($logfile,"Select rideStatus - setRideStatuses.php"));
			if($checkRideStatus_sql_result = mysqli_fetch_assoc($checkRideStatus_sql_execute) ){
             $checkRideStatus = $checkRideStatus_sql_result['rideStatus'];
			}
			/*checking ride Staus before accepet */
            if($checkRideStatus == 'assigned'){//if ridestatus equals to assiugned starts here
			$pin = mt_rand(1000, 9999);	   //Random pin for otp	
			$sql1="Update `rideDetail` set `rideStatus` = '$rideStatus', `otp`='$pin' WHERE `rideId` = '$rideId'";		//update ride status
			$acceptRideRes = mysqli_query($con, $sql1) or (logToFile($logfile,"Get ride status of ride - setRideStatuses.php"));
			$sql9 = "Update `driverDetail` set `driverStatus` = 'riding' WHERE `driverId` ='$driverId'";
			$driverStatus = mysqli_query($con, $sql9) or (logToFile($logfile,"Update driver status - setRideStatuses.php"));
			
			 if($acceptRideRes){
				$sql2 = "SELECT userRegistration.mobile , userRegistration.fullName from `userRegistration` INNER JOIN `rideDetail` ON 
												userRegistration.userId = rideDetail.userId 
				where `rideId` = '$rideId'"; //selecting rider name, mobile number from userregistration 
                $passengerRideRes = mysqli_query($con,$sql2) or (logToFile($logfile,"Get user data - setRideStatuses.php"));
                if($r2=mysqli_fetch_assoc($passengerRideRes)){		//get passenger name and number
					$result['passengerName']=$r2['fullName'];
					$result['passengerNumber'] = $r2['mobile'];
					$passengerMobileNumber = $r2['mobile'];
					/*notification starts here*/
				       /* $notification = [
            						'title' =>'Hap Passenger',
            						'body' => 'Your Ride has Accepted',
            						'icon' =>'myIcon', 
            						'sound' => 'mySound'
       								 ];
					$sql_push = "select `tokenId` from `userRegistration` where `mobile` = '$passengerMobileNumber'";
							$res_push = mysqli_query($con,$sql_push);
			
							if($row = mysqli_fetch_assoc($res_push))
								{
									$device_token = $row["tokenId"]; 
								
									if(!empty($device_token))
										{
						   					$result1 = $passengerNotification->sendPassengerGCM($device_token, $notification);
						   					
										}
								}*/
								/*notification ends here*/
							$result['rideAcceptstatus'] = 'rideAccepted';

					//$result['riderDetailsResponse']='responseSuccess';
				}else{
					//$result['riderDetailsResponse']='responseFailed';
				}
			}else{
				$result['rideAcceptstatus'] = 'rideAcceptFailed';
			}
		}//if ridestatus equals to assigned ends here
		else{
          $result['rideAcceptstatus'] = 'TimeoutAccepted';
		}   
		} 
		else if($rideStatus == 'cancelled'){  			//when rideStatus is cancelled
			
			$sql3="Update rideDetail set rideStatus='$rideStatus' WHERE rideId='$rideId'";		//update ride status
			$cancelRideRes = mysqli_query($con, $sql3) or (logToFile($logfile,"Update ride status of ride - setRideStatuses.php"));
			$sql10 = "Update `driverDetail` set `driverStatus` = 'online' WHERE `driverId` ='$driverId'";
			$driverStatus = mysqli_query($con, $sql10) or (logToFile($logfile,"Update status of driver - setRideStatuses.php"));
			
			if($cancelRideRes){ 				//inserting into cancelledRides
					$result['rideAcceptstatus'] = 'rideCancelled';
					$result['rideAcceptstatus'] = 'rideAccepted';
				 
					$sql4 = "INSERT INTO cancelledRides(`rideDetailId`, `cancelledBy`, `reason`) VALUES ((SELECT `rideDetailId` FROM `rideDetail` WHERE `rideId` = '$rideId'),
						(SELECT `driverId` FROM `rideDetail` WHERE `rideId` = '$rideId'), '$cancelledReason');";
					  $cancelRideReason = mysqli_query($con, $sql4) or (logToFile($logfile,"Insert cancel ride details - setRideStatuses.php"));
					  if($cancelRideReason){
			
					  /*notification starts here*/
				     /*   $notification = [
            						'title' =>'Hap Passenger',
            						'body' => 'Your Ride has cancelled',
            						'icon' =>'myIcon', 
            						'sound' => 'mySound'
       								 ];
					$sql_push = "select `tokenId` from `userRegistration` where `mobile` = '$passengerMobileNum'";
							$res_push = mysqli_query($con,$sql_push);
			
							if($row = mysqli_fetch_assoc($res_push))
								{
									$device_token = $row["tokenId"]; 
								
									if(!empty($device_token))
										{
						   					$result1 = $passengerNotification->sendPassengerGCM($device_token, $notification);
						   					
										}
								}*/
								/*notification ends here*/
							$result['rideAcceptstatus'] = 'rideCancelled';
								
					  }	//set status as cancelled

				}else{
					$result['rideAcceptstatus'] = 'cancelFailed';	//set status as false
				}
		}else if($rideStatus == 'started'){						//when rideStatus is started
				$selectstatus_sql = "SELECT `rideStatus` FROM `rideDetail` WHERE `rideId` = '$rideId'"; //checking status before cancell
				$selectstatus_res = mysqli_query($con, $selectstatus_sql) or (logToFile($logfile,"selecting rideStatus setRideStatuses.php"));		//Execute query
				if($selectstatus_row = mysqli_fetch_assoc($selectstatus_res)){
					$result['rideStatus'] = $selectstatus_row['rideStatus'];
					$rideStatus = $selectstatus_row['rideStatus'];
				}
                 if($rideStatus == 'accepted' || $rideStatus == 'started'){
				$sql5 = "Update `rideDetail` set `rideStatus` = '$rideStatus' WHERE rideId='$rideId'";			//Update rideStatus in db
				$sql12 = "Update `driverDetail` set `driverStatus` = 'riding' WHERE `driverId` ='$driverId'";
				$startRideRes = mysqli_query($con, $sql5) or (logToFile($logfile,"Update ride status - setRideStatuses.php"));//Execute query
				$driverStatus = mysqli_query($con, $sql12) or (logToFile($logfile,"Update driver status - setRideStatuses.php"));
				if($startRideRes){


								/*notification starts here*/
				       /* $notification = [
            						'title' =>'Hap Passenger',
            						'body' => 'Driver is on the way to your location',
            						'icon' =>'myIcon', 
            						'sound' => 'mySound'
       								 ];
					$sql_push = "select `tokenId` from `userRegistration` where `mobile` = '$passengerMobileNum'";
							$res_push = mysqli_query($con,$sql_push);
			
							if($row = mysqli_fetch_assoc($res_push))
								{
									$device_token = $row["tokenId"]; 
								
									if(!empty($device_token))
										{
						   					$result1 = $passengerNotification->sendPassengerGCM($device_token, $notification);
						   					
										}
								}*/
								/*notification ends here*/

					$result['rideAcceptstatus'] = 'started';
				}else{
					$result['rideAcceptstatus'] = 'False';	//set status as false
				}
			}else{
                 $result['rideStatus'] = 'passenger_cancelled_ride';
			}
			
		}else if($rideStatus == 'end'){						//when rideStatus is end
				$sql6 = "Update rideDetail set rideStatus='$rideStatus' , `rideEndTime` = '$timeStamp' WHERE rideId='$rideId'";//Update rideStatus in db
				$res6=mysqli_query($con, $sql6) or (logToFile($logfile,"Update ride status and end time - setRideStatuses.php"));		//Execute query

				if($res6){
					$result['rideAcceptstatus'] = 'end';
					$sql12 = "SELECT `totalpayment`, `paymentType` ,`paymentStatus` FROM `payment` WHERE rideDetailId =
					(SELECT rideDetail.rideDetailId FROM `rideDetail` INNER JOIN `payment` ON rideDetail.rideDetailId = payment.rideDetailId
					 WHERE rideDetail.rideId = '$rideId')";
					$res12 = mysqli_query($con, $sql12) or (logToFile($logfile,"Get payment details - setRideStatuses.php"));

					  if($r12 = mysqli_fetch_assoc($res12)){
					  	if($r12['paymentStatus'] == 'pending'){
					  	$result['paymentMode'] = $r12['paymentType'];
                         $result['totalAmount'] = $r12['totalpayment'];
                         $result['amountStatus'] = 'totalAmount';
                     }else {
                     	$result['totalAmount'] = $r12['totalpayment'];
                     	$result['amountStatus'] = 'alreadyPaid';
                     }

                         /*notification starts here*/
				        /*$notification = [
            						'title' =>'Hap Passenger',
            						'body' => 'Your Ride has ended',
            						'icon' =>'myIcon', 
            						'sound' => 'mySound'
       								 ];
					$sql_push = "select `tokenId` from `userRegistration` where `mobile` = '$passengerMobileNum'";
							$res_push = mysqli_query($con,$sql_push);
			
							if($row = mysqli_fetch_assoc($res_push))
								{
									$device_token = $row["tokenId"]; 
								
									if(!empty($device_token))
										{
						   					$result1 = $passengerNotification->sendPassengerGCM($device_token, $notification);
						   					
										}
								}*/
								/*notification ends here*/


					  }else{
					  	$result['amountStatus'] = 'False';
					  }
				}else{
					$result['rideAcceptstatus'] = 'False';	//set status as false
				}
		}else if($rideStatus == 'rejected'){				//when rideStatus is rejected
                 /*checking ride Staus before accepet */
			$checkRideStatus_sql = "SELECT `rideStatus` FROM `rideDetail` WHERE `rideId` = '$rideId'";
			$checkRideStatus_sql_execute = mysqli_query($con, $checkRideStatus_sql) or 
			(logToFile($logfile,"Select rideStatus - setRideStatuses.php"));
			if($checkRideStatus_sql_result = mysqli_fetch_assoc($checkRideStatus_sql_execute) ){
             $checkRideStatus = $checkRideStatus_sql_result['rideStatus'];
			}
			/*checking ride Staus before accepet */

			if($checkRideStatus == 'assigned'){//if ridestatus equals to assiugned starts here
				$sql7 = "Update rideDetail set rideStatus='$rideStatus' WHERE rideId='$rideId'";//Update rideStatus in db
				$res7=mysqli_query($con, $sql7) or (logToFile($logfile,"Update ride status of ride - setRideStatuses.php"));		//Execute query
				if($res7){
					$result['rideAcceptstatus'] = 'rejected';
					/*notification starts here*/
				       /* $notification = [
            						'title' =>'Hap Passenger',
            						'body' => 'No drivers avaliable pls try again',
            						'icon' =>'myIcon', 
            						'sound' => 'mySound'
       								 ];
					$sql_push = "select `tokenId` from `userRegistration` where `mobile` = '$passengerMobileNum'";
							$res_push = mysqli_query($con,$sql_push);
			
							if($row = mysqli_fetch_assoc($res_push))
								{
									$device_token = $row["tokenId"]; 
								
									if(!empty($device_token))
										{
						   					$result1 = $passengerNotification->sendPassengerGCM($device_token, $notification);
						   					
										}
								}*/
								/*notification ends here*/
				}else{
					$result['rideAcceptstatus'] = 'False';	//set status as false
				}
			}else{
                   $result['rideAcceptstatus'] = 'TimeoutRejected';
			}
		}else if($rideStatus == "trip started"){			//when rideStatus is trip started
			    $sql = "SELECT `rideStartTime` FROM `rideDetail` WHERE `rideId` = '$rideId'";
			   $res = mysqli_query($con,$sql) or (logToFile($logfile,"Get rideStartTime from ridedetail table setRideStatuses.php"));
				$result = mysqli_fetch_assoc($res);				//Execute sql statement and get the result
				$rideStartTime = $result['rideStartTime'];   		//Get the rideStartTime   
				if(new DateTime($rideStartTime) ==  new DateTime('0000-00-00 00:00:00')){
                $sql8 = "Update rideDetail set rideStatus='$rideStatus', `rideStartTime` = '$timeStamp' WHERE `rideId`='$rideId'";//Update rideStatus in db
            }else{
            	$sql8 = "Update rideDetail set rideStatus='$rideStatus' WHERE `rideId`='$rideId'";
              //Update rideStatus in db
            }

                $sql13 = "Update `driverDetail` set `driverStatus` = 'riding' WHERE `driverId` ='$driverId'";
                $driverStatus = mysqli_query($con, $sql13) or (logToFile($logfile,"Update ride status - setRideStatuses.php"));
				$res8=mysqli_query($con, $sql8)  or (logToFile($logfile,"Update rideStatus rideAcceptCancel.php"));		//Execute query
				if($res8){
					/*notification starts here*/
				      /*  $notification = [
            						'title' =>'Hap Passenger',
            						'body' => 'Your Ride has started',
            						'icon' =>'myIcon', 
            						'sound' => 'mySound'
       								 ];
					$sql_push = "select `tokenId` from `userRegistration` where `mobile` = '$passengerMobileNum'";
							$res_push = mysqli_query($con,$sql_push);
			
							if($row = mysqli_fetch_assoc($res_push))
								{
									$device_token = $row["tokenId"]; 
								
									if(!empty($device_token))
										{
						   					$result1 = $passengerNotification->sendPassengerGCM($device_token, $notification);
						   					
										}
								}*/
								/*notification ends here*/
					$result['rideAcceptstatus'] = 'trip started';
				}else{
					$result['rideAcceptstatus'] = 'False';	//set status as false
				}
			}
			/*for payment updation in payment table here i am using payment staus as ride status*/
        else if($rideStatus == "paid"){		//when rideStatus is paid
           	$paymetQuery = "UPDATE payment p INNER JOIN rideDetail r ON p.rideDetailId= r.rideDetailId SET paymentStatus='$rideStatus', paymentType = 'cash'
						WHERE r.rideId = '$rideId'";//Update payment status in paymenttable when he collected cash 
             $paymentResult = mysqli_query($con, $paymetQuery) or (logToFile($logfile,"Update payment status - setRideStatuses.php"));
             if($paymentResult){ 
             		$result['rideAcceptstatus'] = 'paid';
                    /*generting invoice starts from here*/
                   // $selectrideinfo_sql = "SELECT `psourceLatitude`, `psourceLongitude` FROM rideDetail WHERE `rideId` = '$rideId'";
             		$selectrideinfo_sql = "SELECT rd.*, p.*, dr.fullName AS driverName,rd.vehicleType, ur.mobile, ur.fullName AS passengerName, ur.email FROM `rideDetail` rd INNER JOIN driverRegistration dr ON dr.driverId= rd.driverId INNER JOIN payment p ON p.rideDetailId= rd.rideDetailId INNER JOIN userRegistration ur ON ur.userId= rd.userId WHERE rd.rideId='$rideId'";
                    if($rideInfo_Row = mysqli_fetch_assoc(mysqli_query($con,$selectrideinfo_sql))){
                    	$startDate = $rideInfo_Row['rideStartTime'];
                        $endDate =  $rideInfo_Row['rideEndTime'];
                        $amount = $rideInfo_Row['totalpayment'];
                        $vehicleType = $rideInfo_Row['vehicleType'];
                    	$sourceLat = $rideInfo_Row['psourceLatitude'];
                    	$sourceLon = $rideInfo_Row['psourceLongitude'];
                    	$destinationLat = $rideInfo_Row['pdestiLatitude'];
                    	$destinationLon = $rideInfo_Row['pdestiLongitude'];
                    	$paymentType = $rideInfo_Row['paymentType'];
                    	$driverName = $rideInfo_Row['driverName'];
                    	$passengerName = $rideInfo_Row['passengerName'];
                    	$email = $rideInfo_Row['email'];
                    	 if($email != "NA" ){
                    	$source = get_sourcelocation($sourceLat, $sourceLon);
                      	$destination = get_destinationlocation($destinationLat, $destinationLon);
                      	/*echo "soure".$source;
                      	echo $destination;*/
                        /*passing data to mail  function */
                    	$result1 = $mailes->sendRideInformation($startDate, $endDate,$amount, $driverName,$passengerName, $vehicleType,$paymentType,$email,$source, $destination);
                    }
                   }
                    /*generting invoice ends here*/
                    
             
                   //for cadcoins
             		$fareSql="SELECT rideDetail.rideDetailId,payment.totalpayment FROM rideDetail INNER JOIN payment ON rideDetail.rideDetailId = payment.rideDetailId 
							WHERE rideDetail.rideId = '$rideId'";
					$fareRes = mysqli_query($con, $fareSql) or (logToFile($logfile,"Get rideDetail id and payment - setRideStatuses.php"));
					if($fareR1 = mysqli_fetch_assoc($fareRes)){
							$rideDetailId = $fareR1['rideDetailId'];
							$fare = $fareR1['totalpayment'];
							
							$cadSql="SELECT cadcoins FROM `staticValues`";
							$cadRes = mysqli_query($con, $cadSql);
							if($cadR1 = mysqli_fetch_assoc($cadRes)){
								 $cadCoinsRs = $cadR1['cadcoins'];
							}
							 $cadCoinsForPassenger = intval($fare/$cadCoinsRs);
					}
             		$cadCoinsSql="INSERT INTO cadcoins (rideDetailId, passengerCoins) VALUES('$rideDetailId', '$cadCoinsForPassenger')";
             		$cadCoinsRes = mysqli_query($con, $cadCoinsSql) or (logToFile($logfile,"Insert cad coins - setRideStatuses.php"));
				}else{
					$result['rideAcceptstatus'] = 'False';	//set status as false
				}

        }
        /*upadate payment type digital when click on online payment*/
        else if($rideStatus == "digitalPayment"){ 
         	$updatePaymetQuery = "UPDATE payment p INNER JOIN rideDetail r ON p.rideDetailId= r.rideDetailId SET `paymentType` = 'digital' WHERE r.rideId = '$rideId'";//Update payment status in paymenttable when he collected cash 
             $paymentResult = mysqli_query($con, $updatePaymetQuery) or (logToFile($logfile,"Update payment status - setRideStatuses.php"));
             if($paymentResult){
             		$result['rideAcceptstatus'] = 'digitalPaymentUpdated';
				}else{
					$result['rideAcceptstatus'] = 'digitalUpdatedFailed';	//set status as update failed
				}
        }
        else if($rideStatus == "digital"){// for checking weather the payment has done or not
            $checkpayment = "SELECT `paymentStatus` FROM `payment` WHERE `paymentStatus` = 'paid' AND rideDetailId =(SELECT rideDetail.rideDetailId FROM `rideDetail` INNER JOIN `payment` ON rideDetail.rideDetailId = payment.rideDetailId WHERE rideDetail.rideId = '$rideId')";
				 $res13 = mysqli_query($con, $checkpayment) or (logToFile($logfile,"Update payment status - setRideStatuses.php"));
				  if($r13 = mysqli_fetch_assoc($res13)){
					  	$result['paymentStatus'] = $r13['paymentStatus'];
                      
                         $result['rideAcceptstatus'] = 'onlinePayment';
					  }else{
					  	$result['rideAcceptstatus'] = 'notpaid';
					  }
        }
			else{
				//$result['dbstatus'] = 'False';
			}                                                    
	}	
	else{		//If database connection failed
  			$result['dbstatus'] = 'False';		//Send status as False
  			logToFile($logfile,"DB connection failed - setRideStatuses.php");
	}

	 header("Content-Type:application/json"); 
     echo json_encode($result);
/*original code ends*/
?>
