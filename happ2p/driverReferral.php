<?php

/*Version :1.0.0
 * FileName:rideHistory.php
 *Purpose: To get the ride history 
 *Developers Involved: Srikanth
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
		  $refDriverId = $_REQUEST['refDriverId'];
		  $action = $_REQUEST['action'];
		  //static values
		  $driverId = 'DFAWO8';
		 $refDriverId = 'DMYTCN';
		  $action = 'driver';
		  
		  if($action == 'driver')		//passenger ride history
		  {
			  $sql3 = "SELECT type FROM `hardCodeValues` WHERE userType='driver' AND status='active'";
			  $res3 = mysqli_query($con, $sql3);
			  while($row3 = mysqli_fetch_assoc($res3)){ 
				  $refType = $row3['type']; 
			  
			  if($refType == 'noOfRidesForReferralAmt'){
				  $sql4 = "SELECT type, value FROM `hardCodeValues` WHERE type='referralAmount' OR type='noOfRidesForReferralAmt'";
				  $res4 = mysqli_query($con, $sql4);
				  while($row4 = mysqli_fetch_assoc($res4)){
					  $type1 = $row4['type'];
					  if($type1 == 'referralAmount'){
						  $driverRefAmount = $row4['value'];
						  $temp['refAmount'] = $row4['value'];
						  
						  }else{
							  $maxRides = $row4['value'];
							  }
						  }

			$sql = "SELECT * FROM `referralAmount`  WHERE referralAmount.referredBy = '$driverId'";//Get driver ride related information		
			$res = mysqli_query($con,$sql)  or (logToFile($logfile,"Get ride history of passenger - rideHistory.php"));		//Execute query
			while($row = mysqli_fetch_assoc($res)){		//Fetch the query related result
				
						$temp['driverId'] = $row['driverId'];
						$temp['refAmountStatus'] = $row['status'];
						$did = $row['driverId'];
						
						$sql1 = "SELECT COUNT(*) AS noOfRides FROM `rideDetail` INNER JOIN payment ON rideDetail.rideDetailId=payment.rideDetailId 
								 WHERE rideDetail.driverId = '$did' AND rideDetail.rideStatus = 'end' and payment.paymentStatus = 'paid'";
						$res1 = mysqli_query($con, $sql1);
						while($row1 = mysqli_fetch_assoc($res1)){
							
							$temp['noOfRides'] = $row1['noOfRides'];
							$completedRides = $row1['noOfRides'];
							$temp['ridesLeft'] = $maxRides - $completedRides;
							}	
						$data[]=$temp;
				}
				if($res){
					$result['refRideCompStatus'] = 'True';
					$result['data'] = $data;	
				}
				
				$result['referralAmountEarningRideStatus'] = 'True';
			
			}else if($refType == 'earningsToGetReferralAmount'){
				$sql5 = "SELECT type, value FROM `hardCodeValues` WHERE type='referralAmount' OR type='earningsToGetReferralAmount'";
				$res5 = mysqli_query($con, $sql5);
				while($row5 = mysqli_fetch_assoc($res5)){
					 $type2 = $row5['type'];
					  if($type2 == 'referralAmount'){
						  $driverRefAmount = $row5['value'];
						  $temp['refAmount'] = $row5['value'];
						  }else{
							  $minAmount = $row5['value'];
							  }
					}
					
					$sql6 = "SELECT * FROM `referralAmount`  WHERE referralAmount.referredBy = '$driverId'";
					$res6 = mysqli_query($con, $sql6);
					while($row6 = mysqli_fetch_assoc($res6)){
						$temp['driverId'] = $row6['driverId'];
						$temp['refAmountStatus'] = $row6['status'];
						$did1 = $row6['driverId'];
						
						$sql7 = "SELECT SUM(totalpayment) as ernedAmount FROM `rideDetail` INNER JOIN payment ON rideDetail.rideDetailId=payment.rideDetailId 
								 WHERE rideDetail.driverId = '$did1' AND rideDetail.rideStatus = 'end' and payment.paymentStatus = 'paid'";
						$res7 = mysqli_query($con, $sql7);
						while($row7 = mysqli_fetch_assoc($res7)){
							$driverEarnedAmount = $row7['ernedAmount'];
							if($driverEarnedAmount >= $minAmount){
								echo $driverEarnedAmount;
								$result['refEarnedStatus'] = 'True';
								}else{
									$result['refEarnedStatus'] = 'False';
									}
							}
							$data[]=$temp;
							if($res6){
								
								$result['status'] = 'True';
								$result['data'] = $data;
								
								}
						}
						
						$result['referralAmountEarningTargetStatus'] = 'True';
				
				}
			}
			
		
	   } else if($action == 'redeem'){
		   
		   $sql2 = "UPDATE referralAmount SET status = '1' WHERE driverId = '$refDriverId' AND referredBy = '$driverId'";
		   $res2 = mysqli_query($con, $sql2);
		   if($res2){
					$result['status'] = 'True';
				}else {
					$result['status'] = 'Flase';
					}
		   
		   
		   } 
	   else{
		$result['status'] = 'Flase';
	}
		
	}else{		//If database connection failed
  			$result['status'] = 'False';		//Send status as False
  			logToFile($logfile,"DB connection failed - rideHistory.php");
	}
	 header("Content-Type:application/json"); 
     echo json_encode($result);
/*original code ends*/
?>
