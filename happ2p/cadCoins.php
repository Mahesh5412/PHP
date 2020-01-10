<?php

/*Version :1.0.0
 * FileName:cadCoins.php
 *Purpose: To get cadcoins of user
 *Developers Involved: Vineetha
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
			$result['dbStatus'] = 'dbConnectionSuccess';		//db connecttion success																
		 
		  $mobile = $_REQUEST['mobileNumber'];
		  $deviceId = $_REQUEST['deviceId'];
		  $action = $_REQUEST['action'];
		  //static values
		 // $mobile = '7893113119';
		 // $action = 'passenger';
		  
		  if($action == 'passenger')		//passenger ride history
		  { 
			  $cadCoinsSql = "SELECT t1.* FROM (SELECT rd.userId, SUM(cc.passengerCoins) AS total FROM `rideDetail` AS rd INNER JOIN cadcoins AS cc
								ON cc.rideDetailId=rd.rideDetailId GROUP BY rd.userId) AS t1 INNER JOIN userRegistration AS ur ON ur.userId= t1.userId 
								WHERE ur.mobile='$mobile' AND ur.deviceId='$deviceId'";
			   $cadCoindRes = mysqli_query($con, $cadCoinsSql) or (logToFile($logfile,"Get passenger Cadcoins from cadcoins table - cadCoins.php"));
			   
			   if($cadCoindRes){ 
					if($r1 = mysqli_fetch_assoc($cadCoindRes)){
						$result['cadCoins'] = $r1['total'];
						$result['cadCoinsResult'] = 'success';
					}else{
						$result['cadCoinsResult'] = 'fail';
					}
				}else{
					$result['cadCoinsResult'] = 'fail';
				}
		   
		  }else if($action = 'driver'){			//driver ride history
			
		  }
		
	}else{		//If database connection failed
  			$result['dbStatus'] = 'dbConnectionFailed';		//db connection failed
  			logToFile($logfile,"DB connection failed - cadCoins.php");
	}
	 header("Content-Type:application/json"); 
     echo json_encode($result);
/*original code ends*/
?>
