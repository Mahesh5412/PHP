<?php

/*Version :1.0.0
 * FileName:unsettledAmount.php
 *Purpose: To get the unsettledAmount of driver
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
	{	  
			//get data from android API 
            $driverId = $_REQUEST['driverId'];
           // $driverId = 'DFAWO8';

            $unsettledAmountsql = "SELECT IFNULL (SUM(totalpayment),0) AS totalpayment FROM `payment` INNER JOIN `rideDetail` ON payment.rideDetailId = rideDetail.rideDetailId WHERE rideDetail.driverId = '$driverId' AND payment.paymentStatus = 'paid' AND payment.settlementstatus = '0'";
            $res = mysqli_query($con, $unsettledAmountsql) or (logToFile($logfile,"Get agentsList - agentsList.php"));		//Execute query

            	if($row = mysqli_fetch_assoc($res)){
            		$result['unsettledAmount'] = $row['totalpayment'];
            		$result['status'] = 'True';
            		 $opt_sql = "SELECT `otp` FROM `settlementDetails` WHERE `payeeId` = '$driverId' AND `status` = '0' ORDER BY sno DESC LIMIT 1";
            $otp_res = mysqli_query($con, $opt_sql) or (logToFile($logfile,"Get OTP - agentsList.php"));
            if($otp_row = mysqli_fetch_assoc($otp_res)){
            	$result['otp'] = $otp_row['otp'];
            }
            	}		
	}else{		//If database connection failed
  			$result['status'] = 'False';		//Send status as False
  			logToFile($logfile,"DB connection failed - rideHistory.php");
	}
	 header("Content-Type:application/json"); 
     echo json_encode($result);
/*original code ends*/
?>
