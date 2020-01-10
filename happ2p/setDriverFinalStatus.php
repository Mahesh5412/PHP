<?php
/*FileName:setDriverFinalStatus.php
 *Purpose: set status as verified for driver or agent
 *Developers Involved: vineetha
 */
	//connecting to server
  	include 'connect.php';
       //for logs
	$logfile= 'log/logfiles/log_' .date('d-M-Y') . '.log'; 
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
	 /*original code starts*/
	if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET")
		{	//get data from android API
			$id=$_REQUEST['dId'];
			$action=$_REQUEST['action'];
			$deviceId = $_REQUEST['deviceId'];
			$deviceToken = $_REQUEST['deviceToken'];
			//$id='DFAWO8';
			//$action='driver';
			
			if($action == 'driver') {		//set status for driver
				$sql="Update `driverRegistration` set `verifiedStatus`= 'verified', workingStatus='active' WHERE `driverId`= '$id'";	//update status in driver registeration
				$res=mysqli_query($con,$sql) or (logToFile($logfile,"Update driver reg verified status setDriverFinalStatus.php"));
				if($res){
					$sql3="SELECT vehicleId FROM `driverRegistration` WHERE driverId='$id'";		//get vehicle of driver
					$res3=mysqli_query($con, $sql3);
					if($r3=mysqli_fetch_assoc($res3)){
						$vehicleId = $r3['vehicleId'];
					}
					$sql2="INSERT `driverDetail` (`driverId`, `deviceId`, `deviceToken`, `vehicleId`, `driverStatus`) VALUES('$id',
								' $deviceId', '$deviceToken','$vehicleId', 'offline')";			//insert data in driverDetail table
					$res2=mysqli_query($con, $sql2) or (logToFile($logfile,"Insert driver details - setDriverFinalStatus.php"));
					$result['finalStatus']='true';
				}else{
					$result['finalStatus']='false';
				}
			}else if($action == 'agent'){		//set status for agent
				$sql1="Update `agentRegistration` set `verifiedStatus`= 'verified', workingStatus='active' where `aid`= '$id'";
				$res1=mysqli_query($con, $sql1) or (logToFile($logfile,"Update status of agent - setDriverFinalStatus.php"));
				if($res1){
					$result['finalStatus']='true';
				}else{
					$result['finalStatus']='false';
				}
			}else{
				$result['statuselse']='fail';
			}
		 }else{
			$result['finalStatus'] = 'false';
			logToFile($logfile,"DB connection failed - setDriverFinalStatus.php");
		}
     header("Content-Type:application/json"); 
     echo json_encode($result);
     /*original code ends*/
?>
 
