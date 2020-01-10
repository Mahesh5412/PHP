<?php

/*Version :1.0.0
 * FileName:driverLocations.php
 *Purpose: To get the drivres locations 
 *Developers Involved:Mahesh
 */

	//connecting to server
	include 'connect.php';
	 //for logs
	$logfile= 'log/log_' .date('d-M-Y') . '.log'; 
	header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
	/*original code starts*/
	if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET"){	   
		
		$vehicleType = $_REQUEST['vehicleType'];
		                                       
		  if($vehicleType == "not mentioned"){		//get driver information who are in online
			  $sql = "SELECT * FROM driverDetail INNER JOIN driverRegistration ON driverDetail.driverId = driverRegistration.driverId INNER JOIN vehicleDetail ON 
				driverRegistration.vehicleId=vehicleDetail.vehicleId WHERE driverDetail.driverStatus='online' AND driverRegistration.verifiedStatus='verified'";		
				//Get driver related latitude and longitude
		  }else{
			  $sql = "SELECT * FROM driverDetail INNER JOIN driverRegistration ON driverDetail.driverId = driverRegistration.driverId INNER JOIN vehicleDetail ON 
				driverRegistration.vehicleId=vehicleDetail.vehicleId WHERE vehicleDetail.vehicleType='$vehicleType' AND driverDetail.driverStatus='online' AND
				driverRegistration.verifiedStatus='verified' AND ((SELECT value from hardCodeValues WHERE type='settlementDays') >= driverDetail.settlementDays)";
						//Get driver related latitude and longitude
		  }                                                               
		   $res = mysqli_query($con,$sql)  or (logToFile($logfile,"Get driver latitude and longitude driverLocations.php"));		//Execute query
		  $result['driverCount']=mysqli_num_rows($res);
		  if(mysqli_num_rows($res)){
				while($row = mysqli_fetch_assoc($res)){		//Fetch the query related result
					$temp['driverId'] = $row['driverId'];	
					$temp['latitude'] = $row['driverLatitude'];	
					$temp['longitude'] = $row['driverLongitude'];	
					$data[]=$temp;
				}
		   
		   $sql2="SELECT drRequestRadius FROM `staticValues`";		//To get driver radius
		   $res2 =mysqli_query($con, $sql2) or (logToFile($logfile, "Get drivers radius from staticValues in driverLocations.php"));
		   if(mysqli_num_rows($rres2)> 0){
			   if($r2 = mysqli_fetch_assoc($res2)){
				   $result['driversRadius'] = $r2['drRequestRadius'];
			   }else{
				   $result['driversRadius'] = 1;
			   }
		   }else{
				   $result['driversRadius'] = 1;
		   }
		   $result['status'] = 'True';
		   $result['data'] = $data;	
	   }
		else{
  				$result['status'] = 'False';    //Send status as False
		 }
	}else{		//If database connection failed
  			$result['status'] = 'False';		//Send status as False
  			logToFile($logfile,"Db connection failed driverLocations.php");
	}
	 header("Content-Type:application/json"); 
     echo json_encode($result);
/*original code ends*/
?>
