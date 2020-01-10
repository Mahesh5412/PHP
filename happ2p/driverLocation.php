	<?php

	/*Version :1.0.0
	 * FileName:driverLocation.php
	 *Purpose: To store the updated driver location and driver status
	 *Developers Involved:mahesh
	 */
	 
		include 'connect.php';	//connecting to server
		//for logs
		$logfile= 'log/log_' .date('d-M-Y') . '.log'; 
		header('Access-Control-Allow-Origin: *');
	    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
		/*original code starts*/
		if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET")
		{
			//Get the data from API
			$latitude = $_REQUEST['latitude'];
			$longitude = $_REQUEST['longitude'];
			$driverId = $_REQUEST['driverId'];
			$currentDate = $_REQUEST['currentDate'];
			$status = $_REQUEST['status'];
			//$latitude = '6272.9987';
			//$longitude = '3423.987';
			//$driverId = 'DFAWO8';
			//$status = 'online';
			//$currentDate = '2019-11-05';
			/*$selectSettleDayssql = "SELECT `value` FROM `hardCodeValues` WHERE `type` = 'settlementDays' AND `status` = 'active'";
			$res1 = mysqli_query($con,$selectSettleDayssql) or (logToFile($logfile,"Get driver latitude and longitude - driverLocation.php"));*/

			$sql = "select `driverLatitude` , `driverLongitude` ,`currentDate` from `driverDetail` where `driverId` = '$driverId'";	//Get the driver related latitude and longitude		
			$res = mysqli_query($con,$sql) or (logToFile($logfile,"Get driver latitude and longitude - driverLocation.php"));			//Execute query 		
	        if(mysqli_num_rows($res) > 0){	
	        	//If number of rows for that response is greater than zero
	        	if($row = mysqli_fetch_assoc($res)){
	        		$dbDate = $row['currentDate'];

	        	}
	                if(new DateTime($dbDate) == new DateTime($currentDate)){	
	        		if($status=="online"){
					$sql1 = "update `driverDetail` set `driverLatitude` = '$latitude', `driverLongitude` = '$longitude', `currentDate` = '$currentDate', `driverStatus` = '$status'
								where `driverId` = '$driverId'";//Update latitude and longitude in db
					$res=mysqli_query($con, $sql1)  or (logToFile($logfile,"Update driver latitude and longitude driverLocation.php"));		//Execute query
				}
				else if($status=="offline"){
					$sql1 = "update `driverDetail` set `driverLatitude` = '$latitude', `driverLongitude` = '$longitude', `currentDate` = '$currentDate',`driverStatus` = '$status' 
								where `driverId` = '$driverId'";//Update latitude and longitude in db
					$res=mysqli_query($con, $sql1)  or (logToFile($logfile,"Update driver latitude and longitude driverLocation.php"));		//Execute query
				}else{
					$sql1 = "update `driverDetail` set `driverLatitude` = '$latitude', `driverLongitude` = '$longitude' where `driverId` = '$driverId'";//Update latitude and longitude in db
					$res=mysqli_query($con, $sql1)  or (logToFile($logfile,"Update driver latitude and longitude driverLocation.php"));		//Execute query
				}
				if($res){		//If query executed successfully
							$result['status'] = 'True';		//set status as true
					}else{			//If query execution failed
							$result['status'] = 'False';	//set status as false
							$result['driverStatusFromDb'] = 'offline';
					}
			}else{
				$result['status'] = 'dateNotMatched';


			/*selecting settlement days starts here*/
			$selectSettleDayssql = "SELECT `value` FROM `hardCodeValues` WHERE `type` = 'settlementDays' AND `status` = 'active'";
			$res1 = mysqli_query($con,$selectSettleDayssql) or (logToFile($logfile,"Selecting value from hardCodeValues table - driverLocation.php"));//Execute query
			if($row1 = mysqli_fetch_assoc($res1)){
				$value = $row1['value'];
			}
			/*selecting settlement days ends here*/

			/*selecting settlement daysfrom driver detail starts here*/
			$selectSettleDayssql1 = "SELECT `settlementDays` FROM `driverDetail` WHERE `driverId` = '$driverId'";
			$res2 = mysqli_query($con,$selectSettleDayssql1) or (logToFile($logfile,"Selecting settlement days from driverDetail table - driverLocation.php"));//Execute query
			if($row2 = mysqli_fetch_assoc($res2)){
				$settlementDays = $row2['settlementDays'];
			}
			/*selecting settlement days from driver detail ends here*/
	     	if($settlementDays < $value){
	     		$Days = $settlementDays + 1;
	     	//$result['settlementstatus'] = 'completed';
				if($status=="online"){
					$sql1 = "update `driverDetail` set `driverLatitude` = '$latitude', `driverLongitude` = '$longitude', `currentDate` = '$currentDate', `driverStatus` = '$status',`settlementDays` = '$Days'
								where `driverId` = '$driverId'";//Update latitude and longitude in db
					$res=mysqli_query($con, $sql1)  or (logToFile($logfile,"Update driver latitude and longitude driverLocation.php"));		//Execute query
				}
				else if($status=="offline"){
					$sql1 = "update `driverDetail` set `driverLatitude` = '$latitude', `driverLongitude` = '$longitude', `currentDate` = '$currentDate',`driverStatus` = '$status', `settlementDays` = '$Days',
								where `driverId` = '$driverId'";//Update latitude and longitude in db
					$res=mysqli_query($con, $sql1)  or (logToFile($logfile,"Update driver latitude and longitude driverLocation.php"));		//Execute query
				}else{
					$sql1 = "update `driverDetail` set `driverLatitude` = '$latitude', `driverLongitude` = '$longitude',`currentDate` = '$currentDate',`settlementDays` = '$Days'
								where `driverId` = '$driverId'";//Update latitude and longitude in db
					$res=mysqli_query($con, $sql1)  or (logToFile($logfile,"Update driver latitude and longitude driverLocation.php"));		//Execute query
				}
				if($res){		//If query executed successfully
							$result['status'] = 'True';		//set status as true
					}else{			//If query execution failed
							$result['status'] = 'False';	//set status as false
							$result['driverStatusFromDb'] = 'offline';
					}
	        			
	        }//settlement count end if
	        else{
	        	$settlement_sql = "SELECT count(settlementstatus) as settlecount FROM `payment` INNER JOIN `rideDetail` ON payment.rideDetailId = rideDetail.rideDetailId WHERE driverId = '$driverId' and payment.settlementstatus ='0' AND payment.paymentStatus = 'paid'"; // checking settlemet status of particular driver
				$res = mysqli_query($con,$settlement_sql) or (logToFile($logfile,"Get settlementstatus - driverLocation.php"));// executing query

				      if($row = mysqli_fetch_assoc($res)){
	        		$settlementstatus = $row['settlecount'];
	     if($settlementstatus==0){//settlement count start if
	     	$result['settlementstatus'] = 'completed';

				if($status=="online"){
					$sql1 = "update `driverDetail` set `driverLatitude` = '$latitude', `driverLongitude` = '$longitude', `currentDate` = '$currentDate', `settlementDays` = '1',`driverStatus` = '$status'
								where `driverId` = '$driverId'";//Update latitude and longitude in db
					$res=mysqli_query($con, $sql1)  or (logToFile($logfile,"Update driver latitude and longitude driverLocation.php"));		//Execute query
				}
				else if($status=="offline"){
					$sql1 = "update `driverDetail` set `driverLatitude` = '$latitude', `driverLongitude` = '$longitude', `currentDate` = '$currentDate',`settlementDays` = '1',`driverStatus` = '$status' 
								where `driverId` = '$driverId'";//Update latitude and longitude in db
					$res=mysqli_query($con, $sql1)  or (logToFile($logfile,"Update driver latitude and longitude driverLocation.php"));		//Execute query
				}else{
					$sql1 = "update `driverDetail` set `driverLatitude` = '$latitude', `driverLongitude` = '$longitude'
								where `driverId` = '$driverId'";//Update latitude and longitude in db
					$res=mysqli_query($con, $sql1)  or (logToFile($logfile,"Update driver latitude and longitude driverLocation.php"));		//Execute query
				}
				if($res){		//If query executed successfully
							$result['status'] = 'True';		//set status as true
					}else{			//If query execution failed
							$result['status'] = 'False';	//set status as false
							$result['driverStatusFromDb'] = 'offline';
					}
	        			
	        }//settlement count end if
	        else{
					$offline_sql = "update `driverDetail` set `driverLatitude` = '$latitude', `driverLongitude` = '$longitude', `driverStatus` = 'offline' where `driverId` = '$driverId'";//Update latitude and longitude in db
					$res=mysqli_query($con, $offline_sql)  or (logToFile($logfile,"Update driver latitude and longitude and status as offlinedriverLocation.php"));	
					if($res){
					   $result['settlementstatus'] = 'pending';
                             }	 
                         }       
	        	}
	        }  	
			}
			}else{			//If number of rows is less than or equal to zero
					$sql2 = "insert into `driverDetail` (`driverLatitude`, `driverLongitude`,`driverId`,`currentDate`,`settlementDays`, `vehicleId` ) values
							('$latitude', '$longitude','$driverId','$currentDate','1',(SELECT vehicleId FROM driverRegistration WHERE `driverId` = '$driverId'))";   //Insert latitude and longitude
					$res=mysqli_query($con, $sql2)  or (logToFile($logfile,"Insert driver latitude and longitude driverLocation.php"));			//Execute query
					if($res){		//If query executed successfully
							$result['status'] = 'True';		//set status as true
					}else{			//If query execution failed
							$result['status'] = 'False';	//set status as false
					}
			}

		}
		else{			//If database connection failed
				$result['status'] = 'False';			//Set status as false
				logToFile($logfile,"DB connection failed- driverLocation.php");
		}
		 header("Content-Type:application/json"); 
	      echo json_encode($result);		//Print the result
		/*original code ends*/
	?>
