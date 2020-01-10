<?php
/*FileName:agentTotalAmount.php
 *Purpose:This is used to total amount of agent
 *Developers Involved:vineetha
 */
	include 'connect.php';    		//Connect to server
    //for logs
	$logfile= 'log/logfiles/log_' .date('d-M-Y') . '.log';  		// to store log files  
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    
    if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET")
        {	//get data from android api
			$result['dbConnection'] = 'dbconnectionSuccess';
            $a_id = $_REQUEST['aid'];
            $action = $_REQUEST['action'];
            $driverId = $_REQUEST['driverId'];
			//$a_id = 'A1';
			//$action = "fetchDriverUnsettledAmount";
			//$driverId ='DFAWO8';
			
			if($action == "getAgentTotalAmount"){		//get agent total amount
				//agent recieved amount from supervisor
				$sql1="SELECT SUM(amountPaid) AS amountRecieved FROM settlementDetails WHERE receiverId='$a_id'";
				$res1 = mysqli_query($con,$sql1) or (logToFile($logfile,"agent recieved amount from supervisor agentTotalAmount.php"));
				
				//agent paid amount
				$sql2="SELECT SUM(amountPaid) AS amountPaid FROM settlementDetails WHERE payeeId='$a_id'";
				$res2=mysqli_query($con, $sql2) or (logToFile($logfile,"agent paid amount agentTotalAmount.php"));
				if($r1 = mysqli_fetch_assoc($res1)){
					 $recievedAmount=$r1['amountRecieved'];	
					if($r2=mysqli_fetch_assoc($res2)){
						$payeeAmount = $r2['amountPaid'];
					}
					$result['totalAmount']= ($recievedAmount) -$payeeAmount;	
					$result['agentTotalAmount'] = 'success';					
				}else{
					$result['agentTotalAmount'] = 'fail';	
				}
				 
			}else if($action == "fetchDriverUnsettledAmount"){		//get unsettled amount of driver
				$sql = "SELECT date(p.paymentDate) date, COUNT(rd.rideDetailId) noofRides, SUM(p.totalpayment) totalpayment FROM  `payment` p
				 INNER JOIN rideDetail rd ON rd.rideDetailId= p.rideDetailId WHERE settlementstatus='0' AND driverId='$driverId' GROUP BY date(paymentDate)";	
				$res = mysqli_query($con,$sql) or (logToFile($logfile,"Getting the location details destinationLocations.php"));
				if(mysqli_num_rows($res)>0){
					 while($row = mysqli_fetch_assoc($res)){
						$temp['date']    = $row['date'];
						$temp['noofRides'] = $row['noofRides'];
						$temp['totalpayment'] = $row['totalpayment'];
						
                        $data[]=$temp;	
					}
			        $result['driverUnsettleAmountRes'] = 'success';
					$result['data'] = $data;
				}else{
					$result['driverUnsettleAmountRes'] = 'fail';
				}
		 }
	 }
	else{
		$result['dbConnection'] = 'dbConnectionFailed';
		logToFile($logfile,"Data is not getting from API agentTotalAmount.php");
	}  

	header("Content-Type:application/json");
    echo json_encode($result);
?>

