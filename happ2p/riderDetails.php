<?php

/*Version :1.0.0
 *FileName:riderDetails.php
 *Purpose: To get the rider details 
 *Developers Involved:Mahesh
 */

	//connecting to server
	include 'connect.php';
	 //for logs
	$logfile= 'log/log_' .date('d-M-Y') . '.log'; 
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
	
	/*original code starts*/
	if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET")
	{	  //get data from android API                                                                  
		  $driverId = $_REQUEST['driverId'];

		  $sql = "select `psourceLatitude` , `psourceLongitude`, `rideId`, `userId` ,`pdestiLatitude`, `pdestiLongitude` from `rideDetail` where 
					`driverId` = '$driverId' AND `rideStatus` = 'assigned'";		//Get rider latitude and longitude
		  $res = mysqli_query($con,$sql)  or (logToFile($logfile,"Get driver latitude and longitude riderDetails.php"));		//Execute query
		  
		  if(mysqli_num_rows($res)>0){
			 
			if($row = mysqli_fetch_assoc($res)){		//Fetch the query related result
				$result['riderLatitude'] = $row['psourceLatitude'];	
				$result['riderLongitude'] = $row['psourceLongitude'];	
				$result['rideId'] = $row['rideId'];
				$result['riderDestinationLatitude'] = $row['pdestiLatitude'];
				$result['riderDestinationLongitude'] = $row['pdestiLongitude'];
				$userId = $row['userId'];

			//	$data[]=$temp;
				  $result['status'] = 'True';
		
		  }
			 /* $result['status'] = 'True';
			  $result['data'] = $data;*/
		  	
		  } 
		  else{
  				$result['status'] = 'False';    //Send status as False
		  }
	}else{		//If database connection failed
  			$result['status'] = 'False';		//Send status as False
  			logToFile($logfile,"DB connection failed - riderDetails.php");
	}
	 header("Content-Type:application/json"); 
     echo json_encode($result);
/*original code ends*/
?>
