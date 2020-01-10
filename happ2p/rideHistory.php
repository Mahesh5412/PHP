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
		  $mobile = $_REQUEST['mobile'];
		  $deviceId = $_REQUEST['deviceId'];
		  $action = $_REQUEST['action'];
		  //static values
		 // $driverId = 'DMYTCN';
		 // $mobile = '9399925333';
			//$action = 'getCouponNotifications';
			date_default_timezone_set('Asia/Kolkata');   //current time
			$time=date("Y-m-d H:i:s",time());
		  
		  if($action == 'passenger')		//passenger ride history
		  {

				$sql = "SELECT t2.*, IFNULL(f.passengerRating, '0') passengerRating FROM (SELECT t1.*, vd.vehicleNo, vd.vehicleName FROM 
						(SELECT rd.rideDetailId, rd.driverId, rd.vehicleType, rd.rideType, rd.rideEndTime, rd.userId,
						 psourceLatitude, psourceLongitude, pdestiLatitude, pdestiLongitude, rideStatus, bookingTime, 
						 p.paymentStatus, p.paymentType, p.totalpayment, dd.documentPath, dd.documentName, dr.vehicleId, dr.fullName AS 
						driverName FROM `rideDetail` AS rd INNER JOIN payment AS p ON p.rideDetailId= rd.rideDetailId 
						INNER JOIN documentDetail AS dd ON dd.id= rd.driverId INNER JOIN driverRegistration AS dr ON 
						dr.driverId= rd.driverId) AS t1 INNER JOIN userRegistration AS ur ON ur.userId= t1.userId INNER JOIN 
						vehicleDetail AS vd ON vd.vehicleId= t1.vehicleId WHERE ur.mobile='$mobile' AND t1.documentName='photo' 
						AND ((t1.rideStatus='end' AND t1.paymentStatus='paid') OR (t1.rideStatus='passenger cancelled')))
						 AS t2 LEFT JOIN feedback f ON f.rideDetailId= t2.rideDetailId
						 ORDER BY rideEndTime DESC";//Get driver ride related information
					
			$res = mysqli_query($con,$sql)  or (logToFile($logfile,"Get ride history of passenger - rideHistory.php"));		//Execute query
			if(mysqli_num_rows($res)>0){
				$result['rideHistoryFound']='success';
				while($row = mysqli_fetch_assoc($res)){		//Fetch the query related result
				
						$temp['driverName'] = $row['driverName'];
						$temp['vehicleNumber'] = $row['vehicleNo'];
						$temp['vehicleType'] = $row['vehicleType'];
						$temp['vehicleName'] = $row['vehicleName'];
						$temp['paymentType'] = $row['paymentType'];	
						$temp['rideStatus'] = $row['rideStatus'];
						$temp['totalFare'] = $row['totalpayment'];
						$temp['sLat'] = $row['psourceLatitude'];
						$temp['sLng'] = $row['psourceLongitude'];
						$temp['dLat'] = $row['pdestiLatitude'];
						$temp['dLng'] = $row['pdestiLongitude'];
						$temp['rating'] = $row['passengerRating'];
						$temp['documentPath'] = $row['documentPath'];
						$timestamp = $row['bookingTime'];	
						$splitTimeStamp = explode(" ",$timestamp);
						$temp['rideDate'] = $splitTimeStamp[0];
						$temp['rideTime'] = $splitTimeStamp[1];	

						$data[]=$temp;
				}
				if($res){
					$result['status'] = 'True';
					$result['data'] = $data;	
				}
				
		}else{
		   $result['rideHistoryFound']='no history';
		}
		   
	   } else if($action == 'driver'){			//driver ride history
		   
		   $sql = "SELECT * from `userRegistration` INNER JOIN `rideDetail` ON userRegistration.userId = rideDetail.userId INNER JOIN payment 
					ON rideDetail.rideDetailId=payment.rideDetailId where rideDetail.driverId = '$driverId' AND ((rideDetail.rideStatus='end'
					 AND payment.paymentStatus='paid') OR (rideDetail.rideStatus='passenger cancelled') OR (rideDetail.rideStatus='cancelled'))
					  ORDER BY rideDetail.bookingTime DESC";//Get passenger ride related information
		  $res = mysqli_query($con,$sql) or (logToFile($logfile,"Get passenger data - rideHistory.php"));		//Execute query
		  if(mysqli_num_rows($res)>0){
          while($row = mysqli_fetch_assoc($res)){		//Fetch the query related result
			  
				$temp['passengerName'] = $row['fullName'];	
				$temp['rideStatus'] = $row['rideStatus'];
				$temp['fare'] = $row['totalpayment'];
				$temp['sLat'] = $row['psourceLatitude'];
				$temp['sLng'] = $row['psourceLongitude'];
				$temp['dLat'] = $row['pdestiLatitude'];
				$temp['dLng'] = $row['pdestiLongitude'];

				$timestamp = $row['bookingTime'];	
				$splitTimeStamp = explode(" ",$timestamp);
				$temp['rideDate'] = $splitTimeStamp[0];
				$temp['rideTime'] = $splitTimeStamp[1];
				
				$data[]=$temp;
		  }
				$result['status'] = 'True';
				$result['data'] = $data;
	  }else{
		  $result['status'] = 'False';
	  }	   
	}else if($action == 'getCouponNotifications'){
		$sql = "SELECT couponId, couponType, description, t1.expiryDate FROM (SELECT cId, expiryDate FROM `userCoupons` uc 
					INNER JOIN userRegistration ur ON ur.userId= uc.userId LEFT JOIN payment p ON p.couponId= uc.sno WHERE mobile='$mobile' AND
					deviceId='$deviceId' AND paymentId is null) t1 INNER JOIN couponTable ct ON ct.id= t1.cId ORDER BY t1.expiryDate ASC";
		$res = mysqli_query($con, $sql);
		if(mysqli_num_rows($res) > 0){
			while($r1 = mysqli_fetch_array($res)){
				$temp['couponCode'] = $r1['couponId'];
				$temp['couponDescription'] = $r1['description'];
				$temp['expiryDate'] = $r1['expiryDate'];
				$data[]=$temp;
			}
				$result['couponStatus'] = 'True';
				$result['couponNotifications'] = $data; 
		}else{
			$result['couponStatus'] = 'false';
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
