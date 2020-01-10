<?php

/*Version :1.0.0
 * FileName:riderAccept.php
 *Purpose: To know the driver ride accepted or rejected 
 *Developers Involved:Mahesh
 */

	//connecting to server
	include 'connect.php';
	 //for logs
	$logfile= 'log/log_' .date('d-M-Y') . '.log'; 
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
	
	/*original code starts*/
	if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET"){	                                                                            
		   $rideId = $_REQUEST['rideId'];
		   $cancelledReason = $_REQUEST['cancelledReason'];
		   $driverId = $_REQUEST['driverId'];
		   $rideStatus = $_REQUEST['rideStatus'];
		  
		/*$rideId = "645";
		  $rideStatus = "accepted";*/
           
           if($rideStatus == "accepted"){
		  $sql = "update `rideDetail` set `rideStatus` = '$rideStatus' where `rideId` = '$rideId'";//Update rideStatus in db
				$res=mysqli_query($con, $sql) or (logToFile($logfile,"Update ride status - rideAccept.php"));		//Execute query
				if($res){	
					//If query executed successfully  
				$sql1 = "SELECT userRegistration.mobile , userRegistration.fullName from `userRegistration` INNER JOIN `rideDetail` 
				ON userRegistration.userId = rideDetail.userId where `rideId` = '$rideId'"; //selecting rider name, mobile number from userregistration 
                $res1 = mysqli_query($con,$sql1) or (logToFile($logfile,"Get user data - rideAccept.php"));		//Execute query
                if($row=mysqli_fetch_assoc($res1)){

                    $result['riderPhoneNumber'] = $row['mobile'];
                    $result['riderName'] = $row['fullName'];
                	$result['status'] = 'True';		//set status as true

                }else{//If query execution failed

						$result['status'] = 'False';	//set status as false
                }

				}else{			//If query execution failed
						$result['status'] = 'False';	//set status as false
				}
			}else if($rideStatus == "cancelled"){

				$selectstatus_sql = "SELECT `rideStatus` FROM `rideDetail` WHERE `rideId` = '$rideId'"; //checking status before cancell
				$selectstatus_res = mysqli_query($con, $selectstatus_sql) or (logToFile($logfile,"selecting rideStatus rideAccept.php"));		//Execute query
				if($selectstatus_row = mysqli_fetch_assoc($selectstatus_res)){
					$result['rideStatus'] = $selectstatus_row['rideStatus'];
					$rideStatus = $selectstatus_row['rideStatus'];
				}
                 if($rideStatus == 'accepted' || $rideStatus == 'started'){
				 $sql2 = "update `rideDetail` set `rideStatus` = '$rideStatus' where `rideId` = '$rideId'";//Update rideStatus in db
				$res2=mysqli_query($con, $sql2)  or (logToFile($logfile,"Update rideStatus rideAccept.php"));		//Execute query

				if($res2){ //inserting into cancelled rides
					$sql3 = "update `driverDetail` set `driverStatus` = 'online' where `driverId` = '$driverId'";//Update rideStatus in db
				   $res3=mysqli_query($con, $sql3)  or (logToFile($logfile,"Update rideStatus rideAcceptCancel.php"));		//Execute query
					$sql3 = "INSERT INTO cancelledRides(`rideDetailId`, `cancelledBy`, `reason`) VALUES 
					((SELECT `rideDetailId` FROM `rideDetail` WHERE `rideId` = '$rideId'), 
					(SELECT `driverId` FROM `rideDetail` WHERE `rideId` = '$rideId'), '$cancelledReason');";
					  $cancelledInfo = mysqli_query($con, $sql3)  or (logToFile($logfile,"Insert cancelled ride details - riderAccept.php"));
					  if($cancelledInfo){
					  	$sql10 = "Update `driverDetail` set `driverStatus` = 'online' WHERE `driverId` =(SELECT `driverId` FROM `rideDetail` WHERE `rideId` = '$rideId')";
						$driverStatus = mysqli_query($con, $sql10) or (logToFile($logfile,"Update driver status - riderAccept.php"));
					 $result['status'] = 'cancelled';	
					 }	//set status as cancelled

				}else{
					$result['status'] = 'False';	//set status as false
				}
			}else{
				$result['rideStatus'] = 'passenger_already_cancelled';
			}

			}

			else if($rideStatus == "started"){

				$sql2 = "update `rideDetail` set `rideStatus` = '$rideStatus' where `rideId` = '$rideId'";//Update rideStatus in db
				$res2=mysqli_query($con, $sql2)  or (logToFile($logfile,"Update rideStatus rideAcceptCancel.php"));		//Execute query
				if($res2){
					$result['status'] = 'started';
				}else{
					$result['status'] = 'False';	//set status as false
				}

			}
			else if($rideStatus == "tripStarted"){
                $sql2 = "update `rideDetail` set `rideStatus` = '$rideStatus' where `rideId` = '$rideId'";//Update rideStatus in db
				$res2=mysqli_query($con, $sql2)  or (logToFile($logfile,"Update rideStatus rideAcceptCancel.php"));		//Execute query
				if($res2){
					$result['status'] = 'tripStarted';
				}else{
					$result['status'] = 'False';	//set status as false
				}
			}

			else if($rideStatus == "end"){
                $sql2 = "update `rideDetail` set `rideStatus` = '$rideStatus' where `rideId` = '$rideId'";//Update rideStatus in db
				$res2=mysqli_query($con, $sql2)  or (logToFile($logfile,"Update rideStatus rideAcceptCancel.php"));		//Execute query
				if($res2){
					$result['status'] = 'end';
				}else{
					$result['status'] = 'False';	//set status as false
				}
			}
			else{
				$sql = "update `rideDetail` set `rideStatus` = '$rideStatus' where `rideId` = '$rideId'";//Update rideStatus in db
				$res=mysqli_query($con, $sql)  or (logToFile($logfile,"Update rideStatus riderAcceptCancel.php"));		//Execute query
				if($res){		//If query executed successfully
						$result['status'] = 'True';		//set status as true
				}else{			//If query execution failed
						$result['status'] = 'False';	//set status as false
				}
			}

	}else{		//If database connection failed
  			$result['status'] = 'False';		//Send status as False
  			logToFile($logfile,"DB connection failed - riderAccept.php");
	}
	 header("Content-Type:application/json"); 
     echo json_encode($result);
/*original code ends*/
?>
