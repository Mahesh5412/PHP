<?php
/*Version :1.0.0
 * FileName:driverLogin.php
 *Purpose: To validate driver and agent login credentials
 *Developers Involved: Vineetha, Mahesh
 */
 
  	include 'connect.php';   //connect to server
  	include 'functions.php';	//to use methods
      //for logs
	$logfile= 'log/logfiles/log_' .date('d-M-Y') . '.log';     
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
	
	if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET")
		{	//get data from android API
			$result['loginDbConnection'] = 'connected';
			$userName = $_REQUEST['userName'];
			$password = $_REQUEST['password'];
			$action = $_REQUEST['action'];
			$deviceId = $_REQUEST['deviceId'];
			$deviceToken = $_REQUEST['deviceToken'];
			//static values
			/*$userName = 'A1';
			$password = '123456';
			$action = 'login';*/
			
			if($action == 'login'){
				if(startsWith($userName,"D")){		//driver login validation
					$devIdSql = "UPDATE driverDetail SET deviceId='$deviceId', deviceToken = '$deviceToken' WHERE driverId='$userName'";
					$devRes = mysqli_query($con, $devIdSql) or (logToFile($logfile,"Update device id and token - driverLogin.php"));
				//$sql1= "SELECT * FROM `driverRegistration` WHERE driverId='$userName' AND password='$password'";
				$sql1= "SELECT * FROM `driverRegistration` WHERE driverId='$userName'";
				$res1= mysqli_query($con, $sql1);
				$count1=mysqli_num_rows($res1);
				
				if($count1>0){
					if($row1 = mysqli_fetch_assoc($res1)){		//fetch driver related data
						$passcode = $row1['password'];
						if($passcode == $password){
						$verificationStatus=$row1['verifiedStatus'];
						$result['mobileNumber'] = $row1['mobile'];
						$result['driverName'] = $row1['fullName'];
						$result['referralCode'] = $row1['referralCode'];
						$result['driverVerificationStatus'] = $row1['verifiedStatus'];
						$result['loginStatus'] = 'success';
						$sql2="SELECT * from `driverDetail` WHERE driverId='$userName'";
						$res2=mysqli_query($con, $sql2) or (logToFile($logfile,"Get driver status - driverLogin.php"));
						if(mysqli_num_rows($res2)>0){
							if($row2=mysqli_fetch_assoc($res2)){
								$result['driverStatus']=$row2['driverStatus'];
							}else{
								$result['driverStatus']='offline';
							}
						}else{
							$result['driverStatus']='offline';
						}
					}else{
						$result['loginStatus'] = 'Username and password is invalid';
					}
					}
				}else{
					$result['loginStatus'] = 'Username and password is invalid';
				}
			}else if(startsWith($userName,"A")){		//agent login validation
				//$sql1= "SELECT * FROM `agentRegistration` WHERE aid='$userName' AND password='$password'";
				$sql1= "SELECT * FROM `agentRegistration` WHERE aid='$userName'";
				$res1= mysqli_query($con, $sql1) or (logToFile($logfile,"Get agent details - driverLogin.php"));
				$count1=mysqli_num_rows($res1);
				
				if($count1>0){
					if($row1 = mysqli_fetch_assoc($res1)){		//fetch agent related data
						$passcode = $row1['password'];
						if($passcode == $password){
						$verificationStatus=$row1['verifiedStatus'];
						$result['mobileNumber'] = $row1['mobileNumber'];
						$result['driverName'] = $row1['name'];
						$result['referralCode'] = $row1['referralCode'];
						$result['driverVerificationStatus'] = $row1['verifiedStatus'];
						$result['loginStatus'] = 'success';
					}else{
						$result['loginStatus'] = 'Username and password is invalid';
					}
					}
				}else{
					$result['loginStatus'] = 'Username and password is invalid';
				}
				
			}
				
			}else{
			}
			
		}else{
			$result['loginDbConnection'] = 'failed';
			logToFile($logfile,"DB connection failed - driverLogin.php");
		}
	  header("Content-Type:application/json"); 
     echo json_encode($result);
?>
