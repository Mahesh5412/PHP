<?php

/*Version :1.0.0
 * FileName:changePassword.php
 *Purpose: changing the password
 *Developers Involved: Amith
 */

	//connecting to server
	include 'connect.php';
	 //for logs
	$logfile= 'log/log_' .date('d-M-Y') . '.log'; 
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
	/*original code starts*/
	if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET")
	{		//get data form android API
		  $driverId = $_REQUEST['driverId'];																				
		  $currentPassword = $_REQUEST['currentPassword'];
		  $confirmPassword = $_REQUEST['confirmPassword'];
		  
		 /* $driverId = 'DMYTCN';
		  $currentPassword = '010101';
		  $confirmPassword = '0424';*/
		  

		  $sql = "SELECT `password` FROM `driverRegistration` WHERE driverId = '$driverId'";//Get driver related latitude and longitude
		  $res = mysqli_query($con,$sql)  or (logToFile($logfile,"Get password from driverRegistration table - changePassword.php"));		//Execute query
		  
          if($row = mysqli_fetch_assoc($res)){		//Fetch the query related result
				$password = $row['password'];
				
				if($password == $currentPassword){
					
					  $sql1 = "UPDATE driverRegistration SET password = '$confirmPassword' WHERE driverId = '$driverId'";//Get driver related latitude and longitude
					  $res1 = mysqli_query($con,$sql1)  or (logToFile($logfile,"Update passowrd in driverRegistration table - changePassword.php"));		//Execute query
					  
					  if($res1){
							$result['status'] = 'True';
						}else{
							$result['status'] = 'false1';
						}
				}else{
					$result['status'] = 'false2';
				}
		  }else {
			  $result['status'] = 'false3';
		  }
	}else{		//If database connection failed
  			$result['status'] = 'False4';		//Send status as False
  			logToFile($logfile,"DB connection failed - changePassword.php");
	}
	 header("Content-Type:application/json"); 
     echo json_encode($result);
/*original code ends*/
?>
