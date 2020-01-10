<?php
/*Version:1.0.0
 * FileName:costDetails.php
 *Purpose: Get the cost details from database
 *Developers Involved: Vineetha
 */
	
  	include 'connect.php';  		//connecting to server
  	//for logs
	$logfile= 'log/log_' .date('d-M-Y') . '.log'; 
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
	/*original code starts*/
	if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET")
	{
		if($action == "rideRequestInsert"){			//to insert ride request
				$sql1="";
				$rideIdResult = mysqli_query($con, $sql1) or (logToFile($logfile,"Get user id - passengerRequest.php"));
				if($r1=mysqli_fetch_assoc($rideIdResult)){
					$userId=$r1['userId'];
				}
				mysqli_autocommit($con,FALSE);//For Payment and rideDetails Checking
				
				//Insert data into rideDetail table
				$sql="INSERT INTO `rideDetail` (`rideId`,`rideType`,`userId`,`vehicleType`,`psourceLatitude`,`psourceLongitude`,`pdestiLatitude`,`pdestiLongitude`,
					`passengerCount`,`rideStatus`,`bookingTime`) VALUES ('$rideId','$rideType',(SELECT userId FROM `userRegistration` WHERE mobile='$mobileNumber'),
					'$vehType','$sourceLat','$sourceLon','$destinationLat','$destinationLon',
					'$noOfSeats','pending','$timeStamp')";
					
				$rideInfo = mysqli_query($con,$sql) or (logToFile($logfile,"Insert ride information - passengerRequest.php"));
				
				$sql2="INSERT INTO payment (rideDetailId, individualFare, paymentType, totalpayment, discountAmount, couponId) VALUES
				 ((SELECT rideDetailId from rideDetail WHERE rideId='$rideId'), '$fare','$paymentType', '$fare','$discountAmount', '$appliedCouponId')";
	
				if($rideInfo){   //If query executed successfully
					$paymetIfoRes = mysqli_query($con, $sql2) or (logToFile($logfile,"Insert payment information - passengerRequest.php"));
					
					//Commit if Succes payment details inserted
					if($paymetIfoRes){
						mysqli_commit($con);
						$result['rideReqStatus'] = 'rideReqInserted';
					}
					//Rollback if faild payment details inserted
					else{
						mysqli_rollback($con);
						$result['rideReqStatus'] = 'rideReqFailed';
					}
					
					//$result['rideReqStatus'] = 'rideReqInserted';		
				}else{
					$result['rideReqStatus'] = 'rideReqFailed';
				}	 

		}
				 
    }  
      else{
		 $result['status'] = 'False123';  //If database connection failed send response as False
		 logToFile($logfile,"DB connection failed - costDetails.php");
	 }
     header("Content-Type:application/json"); 
     echo json_encode($result);
     /*original code ends*/
?>
