<?php
/*Version :1.0.0
 * FileName:applyCoupon.php
 *Purpose: fetch Coupons from db
 *Developers Involved:Vineetha
 */
	//connecting to server
	include'connect.php';
	 //for logs
	$logfile= 'log/log_' .date('d-M-Y') . '.log'; 
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
	/*original code starts*/
	if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET"){
		$result['dbstatus'] = 'dbconnectionsuccess';
		//Get the data from API
		$action=$_REQUEST['action'];
		$appliedCouponId = $_REQUEST['appliedCouponId'];
		$deviceId = $_REQUEST['deviceId'];
		$mobileNumber = $_REQUEST['mobileNumber'];
		//$action="fetchCoupons";
		
		/*$action = 'validateCoupon';
		$appliedCouponId = 'CD1234';
		$deviceId = '8881691fe3413f7e';
		$mobileNumber = '9700998829';*/

		if($action == "fetchCoupons"){		//get coupons from db
				$sql1="SELECT * FROM couponTable WHERE expiryDate >= CURRENT_DATE() ORDER BY expiryDate";
				$fetchCouponsRes=mysqli_query($con, $sql1) or (logToFile($logfile,"Get coupons from couponTable - applyCoupon.php"));
				if(mysqli_num_rows($fetchCouponsRes)>0){
					$result['couponsResultSuccess'] = 'CouponsSuccess';
					while($r1=mysqli_fetch_array($fetchCouponsRes)){
						 $temp['couponId']=$r1['couponId'];
						 $temp['description'] = $r1['description'];
						 //$temp['discount']=$r1['discount']; 
						 $data[]=$temp;
					}
					$result['data'] = $data;
				}else{
					$result['couponsResultSuccess'] = 'CouponsFailed';
				}

		}else if($action == "validateCoupon"){			//validate coupons enetered by user
			
			if($appliedCouponId == "HAPFREE"){
			
			$sql3 = "SELECT * FROM rideDetail INNER JOIN payment ON rideDetail.rideDetailId = payment.rideDetailId INNER JOIN userRegistration ON
					 userRegistration.userId = rideDetail.userId
					WHERE userRegistration.mobile = '$mobileNumber' AND userRegistration.deviceId = '$deviceId' AND payment.couponId = '$appliedCouponId'";
			$r3 = mysqli_query($con, $sql3);
			if(mysqli_num_rows($r3) == 0){
			$sql4 = "SELECT payment.couponId FROM rideDetail INNER JOIN payment ON rideDetail.rideDetailId = payment.rideDetailId 
					WHERE payment.couponId = '$appliedCouponId' AND (rideDetail.rideStatus='trip started' OR rideDetail.rideStatus = 'end') 
					AND rideDetail.rideStartTime = curdate()";
			$r4 = mysqli_query($con, $sql4);
			if(mysqli_num_rows($r4) <= 10){
					
			$sql2="SELECT * FROM `couponTable` WHERE couponId='$appliedCouponId'";
			$validateCouponRes = mysqli_query($con, $sql2) or (logToFile($logfile,"Validate coupon enetered by user - applyCoupon.php"));
			if(mysqli_num_rows($validateCouponRes)>0){
				if($r2=mysqli_fetch_assoc($validateCouponRes)){
					$result['appliedCouponAmount'] = $r2['discount'];
				}
				$result['validateCouponResponse'] = 'validationSuccess';
			}else{
				$result['validateCouponResponse'] = 'validationFailed';
			}
		}else{
			$result['validateCouponResponse'] = 'dailylimitEnded';
			}
		
		}else {
			$result['validateCouponResponse'] = 'validationFailed';
			}
			
		}else if($appliedCouponId == "BOGO"){
			
			$sql5 = "SELECT * FROM rideDetail INNER JOIN payment ON rideDetail.rideDetailId = payment.rideDetailId INNER JOIN userRegistration ON
					 userRegistration.userId = rideDetail.userId WHERE userRegistration.mobile = '$mobileNumber' AND 
					 userRegistration.deviceId = '$deviceId' AND payment.couponId = '$appliedCouponId' AND payment.paymentStatus='paid' AND rideDetail.rideStatus='end'";
			$r5 = mysqli_query($con, $sql5);
			if(mysqli_num_rows($r5) == 1){
				
				$result['appliedCouponAmount'] = '100';
						 
				$result['validateCouponResponse'] = 'validationSuccess';
				
				}else{
					$result['appliedCouponAmount'] = '0';
				//$result['discountAmount'] = '0';

						
				$result['validateCouponResponse'] = 'validationSuccess';
					}
			}else{
				
				$sql6="SELECT * FROM `couponTable` WHERE couponId='$appliedCouponId'";
			$otherCouponRes = mysqli_query($con, $sql6) or (logToFile($logfile,"Validate coupon enetered by user - applyCoupon.php"));
			if(mysqli_num_rows($otherCouponRes)>0){
				if($r6=mysqli_fetch_assoc($otherCouponRes)){
					$result['appliedCouponAmount'] = $r6['discount'];
				}
				$result['validateCouponResponse'] = 'validationSuccess';
			}else{
				$result['validateCouponResponse'] = 'validationFailed';
			}
	
				//$result['validateCouponResponse'] = 'validationFailed';
				}
		}																		
	}else{ 			//database connection fails
  			$result['dbstatus'] = 'dbConnectionFailed';
  			logToFile($logfile,"DB connection failed - applyCoupon.php");
	}
	 header("Content-Type:application/json"); 
      echo json_encode($result);
/*original code ends here*/
?>
