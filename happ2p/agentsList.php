<?php

/*Version :1.0.0
 * FileName:agentsList.php
 *Purpose: To get the agents List
 *Developers Involved: Mahesh
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
	 		$sql = "SELECT `name`, `cLatitude`, `cLongitude`, `mobileNumber` FROM `agentRegistration` WHERE `workingStatus` = 'active'";		
			$res = mysqli_query($con,$sql)  or (logToFile($logfile,"Get agentsList - agentsList.php"));		//Execute query
			if(mysqli_num_rows($res)>0){

				while($row = mysqli_fetch_assoc($res)){		//Fetch the query related result				
						$temp['name'] = $row['name'];
						$temp['phoneNumber'] = $row['mobileNumber'];
						$temp['latitude'] = $row['cLatitude'];
						$temp['longitude'] = $row['cLongitude'];
						$data[]=$temp;
				}
				if($res){
					$result['status'] = 'True';
					$result['data'] = $data;	
				}
				
		}else{
		   $result['status']='no agents';
		}
		
	}else{		//If database connection failed
  			$result['status'] = 'False';		//Send status as False
  			logToFile($logfile,"DB connection failed - rideHistory.php");
	}
	 header("Content-Type:application/json"); 
     echo json_encode($result);
/*original code ends*/
?>
