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
		$couponName = $_REQUEST['couponName'];
		
		
		//$action="fetchCoupons";
		//$appliedCouponId = '3';
		//$mobileNumber= '7893113119';
		//$deviceId = '8881691fe3413f7e';
		/*$numberOfRides = '1';
		$action = 'validateCoupon';
		$appliedCouponId = 'CADRAC';*/
		
	/*	$action = 'validateCoupon';
		$appliedCouponId = 'CADRAC';
		$deviceId = '8881691fe3413f7e';
		//$mobileNumber = '9700998829';
		* */

		if($action == "fetchCoupons"){		//get coupons from db
			
			/*$sql10 = "SELECT sno, couponId, couponType, description, t1.expiryDate FROM (SELECT sno, cId, expiryDate FROM `userCoupons` uc INNER JOIN 
					  userRegistration ur ON ur.userId= uc.userId LEFT JOIN payment p ON p.couponId= uc.sno WHERE mobile='$mobileNumber' AND deviceId='$deviceId' 
					  AND paymentId is null) t1 INNER JOIN couponTable ct ON ct.id= t1.cId ORDER BY t1.expiryDate ASC, sno ASC";*/
			$sql10 = "SELECT cId, discount, maxDiscount, description, sno, couponId, expiryDate FROM (SELECT cId, userId, sno FROM `userCoupons` uc 
					  LEFT JOIN (SELECT couponId, paymentId FROM `rideDetail` rd INNER JOIN payment p ON p.rideDetailId= rd.rideDetailId 
					  WHERE (rd.rideStatus='end' AND p.paymentStatus='paid') OR rd.rideStatus='passenger cancelled') t1 
					  ON t1.couponId= uc.sno WHERE paymentId IS null) t2 INNER JOIN couponTable ct on ct.id= t2.cId
					  INNER JOIN userRegistration ur ON ur.userId= t2.userId WHERE ur.mobile='$mobileNumber' ORDER BY `ct`.`expiryDate` DESC";
			$res10 = mysqli_query($con,$sql10);
			if(mysqli_num_rows($res10) > 0){
			while($row10 = mysqli_fetch_assoc($res10)){
				
				$temp['couponIdSNo'] = $row10['sno'];
						$temp['couponId'] = $row10['couponId'];
						$temp['description'] = $row10['description'];
						
						$data[]=$temp;
						
					}
					$result['data'] = $data;
		
		$result['couponsResultSuccess'] = 'CouponsSuccess';
			
		/*	$sql0 = "SELECT sno, couponId, couponType, description, t1.expiryDate FROM (SELECT sno, cId, expiryDate FROM `userCoupons` uc INNER JOIN 
					 userRegistration ur ON ur.userId= uc.userId LEFT JOIN payment p ON p.couponId= uc.sno WHERE mobile='$mobileNumber' AND 
					 deviceId='$deviceId' AND paymentId is null) t1 INNER JOIN couponTable ct ON ct.id= t1.cId 
					 WHERE couponType='installation' ORDER BY t1.expiryDate ASC, sno ASC LIMIT 1";
			$res0 = mysqli_query($con,$sql0);
			
			if(mysqli_num_rows($res0)>0){
					
			if($row0 = mysqli_fetch_assoc($res0)){
				$temp['couponIdSNo'] = $row0['sno'];
				$temp['couponId'] = $row0['couponId'];
					$temp['description'] = $row0['description'];
					$data[]=$temp;
				}
			}
			
			//if($row0 = mysqli_fetch_assoc($res0) > 0){
				
				
			
			$sql7 = "SELECT COUNT(rideDetail.userId) AS noOfRides FROM `rideDetail`  INNER JOIN userRegistration ON 
					rideDetail.userId = userRegistration.userId INNER JOIN payment ON rideDetail.rideDetailId=payment.rideDetailId 
					WHERE userRegistration.deviceId='02f2c6d440707b5e' AND rideDetail.rideStatus='end' AND payment.paymentStatus='paid'";
			$res7 = mysqli_query($con, $sql7);
			$row7 = mysqli_fetch_assoc($res7);
			$numOfRides = $row7['noOfRides'];
			$temp['noOfRides'] = $row7['noOfRides'];
			//echo  	$numOfRides;		
				if($numOfRides == '0'){
					$sql8 = "SELECT * FROM `couponTable` WHERE couponId='CADRAC' ORDER BY discount DESC LIMIT 0,1";
					$res8 = mysqli_query($con, $sql8);
					$row8 = mysqli_fetch_assoc($res8);
					}else  if($numOfRides == '1'){
					$sql8 = "SELECT * FROM `couponTable` WHERE couponId='CADRAC' ORDER BY discount DESC LIMIT 1,1";
					$res8 = mysqli_query($con, $sql8);
					$row8 = mysqli_fetch_assoc($res8);
					}else if($numOfRides == '2'){
					$sql8 = "SELECT * FROM `couponTable` WHERE couponId='CADRAC' ORDER BY discount DESC LIMIT 2,1";
					$res8 = mysqli_query($con, $sql8);
					$row8 = mysqli_fetch_assoc($res8);
					} else if($numOfRides == '3'){
					$sql8 = "SELECT * FROM `couponTable` WHERE couponId='CADRAC' ORDER BY discount DESC LIMIT 3,1";
					$res8 = mysqli_query($con, $sql8);
					$row8 = mysqli_fetch_assoc($res8);

					}
					
						$temp['couponIdSNo'] = $row0['sno'];
						$temp['couponId'] = $row0['couponId'];
						$temp['description'] = $row0['description'];
						
						$data[]=$temp;
						
						
				}				 
			$sql1 = "SELECT sno, couponId, couponType, description, t1.expiryDate FROM (SELECT sno, cId, expiryDate FROM `userCoupons` uc INNER JOIN 
					 userRegistration ur ON ur.userId= uc.userId LEFT JOIN payment p ON p.couponId= uc.sno WHERE mobile='$mobileNumber' AND 
					 deviceId='$deviceId' AND paymentId is null) t1 INNER JOIN couponTable ct ON ct.id= t1.cId 
					 WHERE couponType<>'installation' ORDER BY t1.expiryDate ASC, sno ASC";
			$res1 = mysqli_query($con,$sql1);
			if(mysqli_num_rows($res1)>0){
					
			while($row1 = mysqli_fetch_assoc($res1)){
				$temp['couponIdSNo'] = $row1['sno'];
				$temp['couponId'] = $row1['couponId'];
					$temp['description'] = $row1['description'];
					$data[]=$temp;
				}
			
		}
		*/
		
		
		
	}
		else{
			$result['couponsResultSuccess'] = 'CouponsFailed';
			}
		
			
		}else if($action == "validateCoupon"){			//validate coupons enetered by user
			
			
			if($appliedCouponId <> ''){
			
			$sql6="SELECT * FROM `couponTable` INNER JOIN userCoupons ON couponTable.id=userCoupons.cId WHERE userCoupons.sno='$appliedCouponId'";
			$otherCouponRes = mysqli_query($con, $sql6) or (logToFile($logfile,"Validate coupon enetered by user - applyCoupon.php"));
			if(mysqli_num_rows($otherCouponRes)>0){
				if($r6=mysqli_fetch_assoc($otherCouponRes)){
					$result['appliedCouponAmount'] = $r6['discount'];
					$result['amountOfCoupon'] = $r6['maxDiscount'];
				}
				$result['validateCouponResponse'] = 'validationSuccess';
			}else{
				$result['validateCouponResponse'] = 'validationFailed';
			}
		}else if($couponName <> ''){
			
			
			
			}
			
			/*
			
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
					$result['amountOfCoupon'] = $r2['maxDiscount'];
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
				$result['amountOfCoupon'] = $r5['maxDiscount'];
						 
				$result['validateCouponResponse'] = 'validationSuccess';
				
				}else{
					$result['appliedCouponAmount'] = '0';
				$result['validateCouponResponse'] = 'validationSuccess';
					}
			}else if($appliedCouponId == "CADRAC"){
			
				if($numberOfRides == '0'){
					$sql11 = "SELECT * FROM `couponTable` WHERE couponId='CADRAC' ORDER BY discount DESC LIMIT 0,1";
					$res11 = mysqli_query($con, $sql11);
					$row11 = mysqli_fetch_assoc($res11);
					}else  if($numberOfRides == '1'){
					$sql11 = "SELECT * FROM `couponTable` WHERE couponId='CADRAC' ORDER BY discount DESC LIMIT 1,1";
					$res11 = mysqli_query($con, $sql11);
					$row11 = mysqli_fetch_assoc($res11);
					}else if($numberOfRides == '2'){
					$sql11 = "SELECT * FROM `couponTable` WHERE couponId='CADRAC' ORDER BY discount DESC LIMIT 2,1";
					$res11 = mysqli_query($con, $sql11);
					$row11 = mysqli_fetch_assoc($res11);
					} else if($numberOfRides == '3'){
					$sql11 = "SELECT * FROM `couponTable` WHERE couponId='CADRAC' ORDER BY discount DESC LIMIT 3,1";
					$res11 = mysqli_query($con, $sql11);
					$row11 = mysqli_fetch_assoc($res11);
					}
				
				if($res11){
					$result['appliedCouponAmount'] = $row11['discount'];
					$result['amountOfCoupon'] = $row11['maxDiscount'];
					
					$result['validateCouponResponse'] = 'validationSuccess';
					}else{
						$result['validateCouponResponse'] = 'validationFailed';
						}
				
				}
			else{
				
				$sql6="SELECT * FROM `couponTable` WHERE couponId='$appliedCouponId'";
			$otherCouponRes = mysqli_query($con, $sql6) or (logToFile($logfile,"Validate coupon enetered by user - applyCoupon.php"));
			if(mysqli_num_rows($otherCouponRes)>0){
				if($r6=mysqli_fetch_assoc($otherCouponRes)){
					$result['appliedCouponAmount'] = $r6['discount'];
					$result['amountOfCoupon'] = $r6['maxDiscount'];
				}
				$result['validateCouponResponse'] = 'validationSuccess';
			}else{
				$result['validateCouponResponse'] = 'validationFailed';
			}
	
				//$result['validateCouponResponse'] = 'validationFailed';
				}
				*/
		}																		
	}else{ 			//database connection fails
  			$result['dbstatus'] = 'dbConnectionFailed';
  			logToFile($logfile,"DB connection failed - applyCoupon.php");
	}
	 header("Content-Type:application/json"); 
      echo json_encode($result);
/*original code ends here*/
?>
