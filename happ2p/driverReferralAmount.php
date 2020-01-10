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
		 // $driverId = 'DFAWO8';
		// $refDriverId = 'DMTXPZ';
		//  $action = 'redeem';
		  
		  if($action == 'driver')		//driver referral Amount
		  {
			  $sql1 = "SELECT type FROM `hardCodeValues` WHERE userType='driver' AND status='active'";
			  $res1 = mysqli_query($con, $sql1);
			  while($row1 = mysqli_fetch_assoc($res1)){ 
				  $refType = $row1['type']; 
				  
				  if($refType == 'earningsToGetReferralAmount'){
					  $temp['earnAmountStatus'] = 'earningsToGetReferralAmount';
					  $sql2 = "SELECT type, value FROM `hardCodeValues` WHERE type='referralAmount' OR type='earningsToGetReferralAmount'";
						$res2 = mysqli_query($con, $sql2);
						while($row2 = mysqli_fetch_assoc($res2)){
							$type1 = $row2['type'];
					  if($type1 == 'referralAmount'){
						  $driverRefAmount = $row2['value'];
						  $temp['refAmount'] = $row2['value'];
						  }else{
							  $minAmount = $row2['value'];
							  }
							}
							
							$sql3 = "SELECT * FROM `referralAmount`  WHERE referralAmount.referredBy = '$driverId'";
					$res3 = mysqli_query($con, $sql3);
					while($row3 = mysqli_fetch_assoc($res3)){
						$temp['driverId'] = $row3['driverId'];
						$temp['refAmountDriver'] = $row3['refAmount'];
						$temp['refAmountStatus'] = $row3['status'];
						$did1 = $row3['driverId'];
						
						$sql4 = "SELECT SUM(totalpayment) as ernedAmount FROM `rideDetail` INNER JOIN payment ON rideDetail.rideDetailId=payment.rideDetailId 
								 WHERE rideDetail.driverId = '$did1' AND rideDetail.rideStatus = 'end' and payment.paymentStatus = 'paid'";
						$res4 = mysqli_query($con, $sql4);
						while($row4 = mysqli_fetch_assoc($res4)){
							$driverEarnedAmount = $row4['ernedAmount'];
							$temp['driverEarnedAmount'] = $row4['ernedAmount'];
							if($driverEarnedAmount >= $minAmount){
								//echo $driverEarnedAmount;
								//echo $minAmount;
								
								$temp['refTargetStatus'] = 'True';
								}else{
									$temp['refTargetStatus'] = 'False';
									}
							}
							$data[]=$temp;
							if($res3){
								$result['status'] = 'True';
								$result['data'] = $data;
								
								}
						}
		
					  }else if($refType == 'noOfRidesForReferralAmt'){		// referral amount by rides
						  $temp['earnAmountStatus'] = 'noOfRidesForReferralAmt';
						  $sql5 = "SELECT type, value FROM `hardCodeValues` WHERE type='referralAmount' OR type='noOfRidesForReferralAmt'";
				  $res5 = mysqli_query($con, $sql5);
				  while($row5 = mysqli_fetch_assoc($res5)){
					  $type2 = $row5['type'];
					  if($type2 == 'referralAmount'){
						  $driverRefAmount = $row5['value'];
						  $temp['refAmount'] = $row5['value'];
						  
						  }else{
							  $maxRides = $row5['value'];
							  }
						  }
						  
						  $sql6 = "SELECT * FROM `referralAmount`  WHERE referralAmount.referredBy = '$driverId'";//Get driver ride related information		
			$res6 = mysqli_query($con,$sql6)  or (logToFile($logfile,"Get ride history of passenger - rideHistory.php"));		//Execute query
			while($row6 = mysqli_fetch_assoc($res6)){		//Fetch the query related result
				
						$temp['driverId'] = $row6['driverId'];
						$temp['refAmountStatus'] = $row6['status'];
						$did = $row6['driverId'];
						
						$sql7 = "SELECT COUNT(*) AS noOfRides FROM `rideDetail` INNER JOIN payment ON rideDetail.rideDetailId=payment.rideDetailId 
								 WHERE rideDetail.driverId = '$did' AND rideDetail.rideStatus = 'end' and payment.paymentStatus = 'paid'";
						$res7 = mysqli_query($con, $sql7);
						while($row7 = mysqli_fetch_assoc($res7)){
							
							$temp['noOfRides'] = $row7['noOfRides'];
							$completedRides = $row7['noOfRides'];
							$temp['ridesLeft'] = $maxRides - $completedRides;
							
							if($completedRides >= $maxRides){
								$temp['refTargetStatus'] = 'True';
								}else{
									$temp['refTargetStatus'] = 'False';
									}
							
							}	
						$data[]=$temp;
				}
				if($res6){
					$result['status'] = 'True';
					$result['data'] = $data;	
				}
			
						  
						  }
			  
			 
			}//while1
			
		
	   } else if($action == 'redeem'){
		   
		   $sql8 = "SELECT * FROM `hardCodeValues` WHERE status='active' AND userType = 'driver'";
		   $res8 = mysqli_query($con,$sql8);
		   while($row8 = mysqli_fetch_assoc($res8)){
			    $type3 = $row8['type'];
			    if($type3 == 'referralAmount'){
					$redeemAmount = $row8['value'];
					}else if($type3 == 'referral bonus no of members'){
						$referalMembers = $row8['value'];
						}
					else if($type3 == 'bonusAmount'){
						$bonusAmount = $row8['value'];
						}
			   }
			   
			$sql9 = "SELECT COUNT(referralAmount.referredBy) AS noOfReferrals FROM `referralAmount` WHERE referredBy = '$driverId' AND refAmount='$redeemAmount'";
			$res9 = mysqli_query($con,$sql9);
			$row9 = mysqli_fetch_assoc($res9);
			$noOfDriverRef = $row9['noOfReferrals'];
			$noOfDriverRef = '19';
			
			$noOfDriverRef1 = $noOfDriverRef + 1;
			if(($noOfDriverRef1 % $referalMembers) == 0){
				
				$refBonus = $bonusAmount + $redeemAmount;
				
			$sql = "UPDATE referralAmount SET status = '1', refAmount = '$refBonus' WHERE driverId = '$refDriverId' AND referredBy = '$driverId'";
		    $res = mysqli_query($con, $sql);
				}else {
					$sql = "UPDATE referralAmount SET status = '1', refAmount = '$redeemAmount' WHERE driverId = '$refDriverId' AND referredBy = '$driverId'";
					$res = mysqli_query($con, $sql);
				
					}
					if($res){
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
