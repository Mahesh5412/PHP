<?php

/*Version :1.0.0
 * FileName:forgetPasswordOtp.php
 *Purpose: forget the password
 *Developers Involved: Srikanth
 */
	//connecting to server
	include 'connect.php';
	include 'functions.php';
	 //for logs
	$logfile= 'log/log_' .date('d-M-Y') . '.log';
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST, GET, OPTIONS'); 
	
	/*original code starts*/
	if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET")
		{
				$result['status'] = 'success';	
				//get data from android API
				$mobileNumber = $_REQUEST['phoneNumber'];
				$driverId = $_REQUEST['driverId'];																	
				$action = $_REQUEST['action'];
				$userPin = $_REQUEST['userPin'];
				$newPassword= $_REQUEST['newPassword'];
				//static data
				/*$mobileNumber = '7893113119';
				$driverId = 'D1234';																	
				$action = 'update';
				$userPin = '3679';
				$newPassword= 'srikanth';*/
				
				if($action == 'generate'){  	//generate OTP
					$sql4 = "SELECT * from `driverRegistration` where mobile = '$mobileNumber'";
					$res = mysqli_query($con, $sql4) or (logToFile($logfile,"Driver mobile number validation - forgetPasswordOtp.php"));
					//$fet = mysqli_fetch_assoc($res);
					//$phone = $fet['mobile'];
					if(mysqli_num_rows($res) > 0){
					
						$pin = mt_rand(1000, 9999);	   //Random pin for otp	
						$sql1="UPDATE `driverRegistration` SET otp = '$pin' WHERE `mobile` = '$mobileNumber'";			
						$ins = mysqli_query($con,$sql1) or (logToFile($logfile,"Update Otp value - forgetPasswordOtp.php")); //Inserting the otp for mobilenumber
						sms();        //To send OTP to user mobile number
						if($ins){       //If profile updated successfully
							$result['otpSentStatus'] = 'otpSentSuccess';	    //store status in result array
							$result['otpToCheck'] = $data; 		//store data in result array 
						}else{      //If profile is not updated
							$result['otpSentStatus'] = 'otpSentFailed';		//store data in result array 
						} 
						
						
					}else{
						$result['mobiStatus'] = 'notMatched';
						}

				}else if($action == 'check')		//validate OTP
				{			
						$sql="SELECT otp FROM `driverRegistration` WHERE mobile = '$mobileNumber'";			//Inserting the otp for mobilenumber
						$ins1 = mysqli_query($con,$sql) or (logToFile($logfile,"Get otp value - forgetPasswordOtp.php"));  
							       //To send OTP to user mobile number
						if($ins1){       //If profile updated successfully
								
								if($row = mysqli_fetch_assoc($ins1)){		//Fetch the query related result
									$otp = $row['otp'];
									
									if($otp == $userPin){
										$result['otpValidate'] = 'validationSuccess';	    //store status in result array
										//store data in result array 
									}else{      //If profile is not updated
										$result['otpValidate'] = 'validationFailed';		//store data in result array 
									} 
								}
							}else{      //If profile is not updated
								$result['otpValidate'] = 'validationFailed';		//store data in result array 
							} 
			
				}else if($action == 'update'){		//update OTP
						$sql1="UPDATE `driverRegistration` SET password = '$newPassword' WHERE `driverId` = '$driverId'";
						$ins2 = mysqli_query($con,$sql1) or (logToFile($logfile,"Get otp value - forgetPasswordOtp.php"));
						if($ins2){
							$result['newPwdUpdate'] = 'success';
						}
						else{
							$result['newPwdUpdate'] = 'failed';
						}
			}	
	}
	else{		//If database connection failed
  			$result['status'] = 'False';		//Send status as False
  			logToFile($logfile,"DB connection failed - forgetPasswordOtp.php");
	}
	 header("Content-Type:application/json"); 
     echo json_encode($result);
/*original code ends*/
?>
