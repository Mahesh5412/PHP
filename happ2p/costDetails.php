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
		//Recieve data from API
		
		$mobileNumberToCheckActiveRide = $_REQUEST['mNumberToGetCancelledRide'];
		
		//get cost details related to car and auto
		/*$sql = "select vehicleType, seatCount, baseFare, basePriceKms, costPerKm from `costDetails` WHERE
					vehicleType='car' OR vehicleType='auto' ORDER BY id";	*/ //getting the vehicle information 
		$sql = "select vehicleType, basePrice, perKmCost, perMinCost, vehMaintenanceCost from priceTable WHERE
					vehicleType='car' OR vehicleType='auto' AND status = 'active' ORDER BY vehicleType DESC"; 
		$res = mysqli_query($con,$sql) or (logToFile($logfile,"Get cost details from costDetails table - costDetails.php"));    //Execute the sql statement
		$r=0;
		if(mysqli_num_rows($res)>0){
        while($row = mysqli_fetch_assoc($res)){	//Fetch data from db related to that query
			$result1['vehicleType']=$row['vehicleType'];
			$result1['basePrice']=$row['basePrice'];
			$result1['perKmCost']=$row['perKmCost'];
			$result1['perMinCost']=$row['perMinCost'];
			$result1['vehMaintenanceCost']=$row['vehMaintenanceCost'];
			//$result1['seatsCount']=$row['seatCount'];
			$result['status'] = 'success';	    //Send status true as response
			
			$data[$r]=$result1;
			$r++;
		}//fetch data ends 
		$result['data']=$data;
		}
		else{          //If there is no data related to that query
			$result['status'] = 'False';      //Send status as False
		}
			//get cancellation details related to passenger
		$sql2="SELECT * from userRegistration as ur INNER JOIN rideDetail as rd ON ur.userId = rd.userId INNER JOIN payment as p ON p.rideDetailId=rd.rideDetailId 
			 WHERE ur.mobile='$mobileNumberToCheckActiveRide' AND (rd.rideStatus='passenger cancelled' AND p.paymentStatus='pending')";
			 $res2=mysqli_query($con, $sql2) or (logToFile($logfile,"Get cancellation charges of passenger - costDetails.php"));
			 if($r2 = mysqli_fetch_assoc($res2)){
				 //$result['cancelPayId'] = $r2['paymentId'];
				 //$result['cancelCharges'] = $r2['individualFare'];
				 $result['cancelRideRes'] = 'cancelRideFailed';
			 }else{
				 $result['cancelRideRes'] = 'cancelRideFailed';
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
