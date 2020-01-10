<?php

/*Version :1.0.0
 * FileName:rideConfirmOtp.php
 *Purpose: Get OTP to confirm ride
 *Developers Involved: Sriknath
 */

	//connecting to server
	include 'connect.php';
	//include 'functions.php';
	 //for logs
	$logfile= 'log/log_' .date('d-M-Y') . '.log'; 
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
	/*original code starts*/
	if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET")
		{	//get data from android API
			$result['status'] = 'success';			//db connection 
			
				$mobileNumber = $_REQUEST['mobile'];
				$rideId = $_REQUEST['rideId'];
				$action = $_REQUEST['action'];
				$otpNumber = $_REQUEST['otpNumber'];
				
				
				/*$mobileNumber = '9441323340';
				  $rideId = '897';
				$action = 'check';
				$otpNumber = '8485';  */
				
				
				if($action == 'generate')
				{  		//to generate OTP	
					$sql4 = "SELECT * from userRegistration where mobile = '$mobileNumber'";
					$res = mysqli_query($con, $sql4);
					
					if(mysqli_num_rows($res) > 0){
					
						$pin = mt_rand(1000, 9999);	   //Random pin for otp	
						$sql1="UPDATE rideDetail SET otp = '$pin' WHERE rideId = '$rideId'";			
						$ins = mysqli_query($con,$sql1) or (logToFile($logfile,"Update OTP in rideDetail - rideConfirmOtp.php"));  //update the otp for mobilenumber
						//sms();        //To send OTP to user mobile number
						if($ins){       
							$result['sentOtpStatus'] = 'otpSentSuccess';	    
						}else{      //If profile is not updated
							$result['sentOtpStatus'] = 'otpSentFailed';		//store data in result array 
						} 
						
					}else{
						$result['sentOtpStatus'] = 'notMatched';
					}

				}else if($action == 'check')
				{			
						$sql="SELECT otp FROM `rideDetail` WHERE rideId = '$rideId'";			
						$ins1 = mysqli_query($con,$sql) or (logToFile($logfile,"Get OTP from rideDetail - rideConfirmOtp.php"));  
							       
						if($ins1){       
								if($row = mysqli_fetch_assoc($ins1)){		
									$otp = $row['otp'];
									if($otp == $otpNumber){
										$result['otpValidate'] = 'validationSuccess';	    
									}else{      //If profile is not updated
										$result['otpValidate'] = 'validationFailed';		
									} 
								}
						}else{      //If profile is not updated
								$result['otpValidate'] = 'validationFailed';		
							} 
				}	 
	}
	else{		//If database connection failed
  			$result['status'] = 'False';		//Send status as False
  			logToFile($logfile,"DB connection failed - rideConfirmOtp.php");
	}
	 header("Content-Type:application/json"); 
     echo json_encode($result);
/*original code ends*/
?>
