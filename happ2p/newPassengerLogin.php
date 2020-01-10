<?php
/*Version:1.0.0
 * FileName:passengerLogin.php
 *Purpose: Sending the otp and also for validationg the user registration
 *Developers Involved: Amith
 */

	include 'connect.php';    //connecting to server
	include 'functions.php';   //To use sms function 
	 //for logs
	$logfile= 'log/log_' .date('d-M-Y') . '.log';
	
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
	/*original code starts*/
	if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET"){
			$result['dbstatus'] = 'connected';
		//Getting the mobile number from generateOTP api
			$mobileNumber= $_REQUEST['mobileNumber'];
			$username = $_REQUEST['username'];
			$referredBy = $_REQUEST['referredBy'];
			$action = $_REQUEST['action'];
			$otp   =  $_REQUEST['otp'];
			$referralCode = $_REQUEST['referralCode'];
			$deviceId = $_REQUEST['deviceId'];
			$tokenId = $_REQUEST['tokenId'];
			$referredBy = $_REQUEST['referredBy'];
			$action = 'generate';
			$mobileNumber = '8099159124';
			$deviceId='02f2c6d440707b5e';
			$referralCode ='UINO4V';
			$referredBy = '';
		//	$username ='ashish';
			//$otp = '3298';
			
			date_default_timezone_set('Asia/Kolkata');   //current time
			$time=date("Y-m-d H:i:s",time());
			 
			if($action == 'generate'){				//If action is generate OTP
				$sql1 = "SELECT IFNULL(SUM(CASE WHEN mobile='$mobileNumber' THEN 1 ELSE 0 END), 0) mobileNumberCount, 
				IFNULL(SUM(CASE WHEN deviceId='$deviceId' THEN 1 ELSE 0 END), 0) deviceIdCount ,
				IFNULL(SUM(CASE WHEN mobile='$mobileNumber' AND deviceId='$deviceId' THEN 1 ELSE 0 END), 0) deviceIdAndMobCount FROM `userRegistration`";  //Checking the user already register or not
				$res1 = mysqli_query($con,$sql1);
				if($r1 = mysqli_fetch_assoc($res1)){
					$mobileNumberCount = $r1['mobileNumberCount'];
					$deviceIdCount = $r1['deviceIdCount'];
					$deviceIdAndMobCount = $r1['deviceIdAndMobCount'];
					$pin = mt_rand(1000, 9999);	   //Random pin for otp	
				}
				 
				if($mobileNumberCount == 0 && $deviceIdCount == 0){         	//new user registration
					$sql="INSERT INTO userRegistration(`mobile`,`fullName`,`otp`, `referralCode`, `deviceId`,`referredBy`,`tokenId`) VALUES 
										('$mobileNumber','$username','$pin', '$referralCode', '$deviceId', '$referredBy','$tokenId')";			//without token id
					$ins = mysqli_query($con,$sql) or (logToFile($logfile,"Insert otp and mobile number passengerLogin.php"));  //Inserting the otp for mobilenumber
					if($ins){     //If profile updated successfully
						$result['status'] = 'True';     //store status in result array
						$sql4 = "INSERT INTO userCoupons (`sno`, `userId`, `cId`, `earnedDate`, `expiryDate`) (SELECT null, 
								(SELECT userId FROM userRegistration WHERE mobile ='$mobileNumber' AND deviceId = '$deviceId'), id , '$time',
								expiryDate FROM couponTable WHERE couponType='installation' AND status='active')";
						$res4 = mysqli_query($con, $sql4);
						
						$sql3 = "SELECT * FROM `userRegistration` WHERE referralCode='$referredBy'";
						$res3 = mysqli_query($con, $sql3);
						 $referralCount = mysqli_num_rows($res3);
						if($referralCount > 0){
							$sql2 = "INSERT INTO userCoupons (userId, cId, earnedDate, expiryDate) VALUES (
									(SELECT userId FROM userRegistration WHERE referralCode ='$referredBy'), 
									(SELECT id FROM `couponTable` WHERE couponType = 'referral'), '$time', 
									('$time' + INTERVAL (SELECT value FROM `hardCodeValues` WHERE type='referralCouponExpiryDays' AND status = 'active') day))";
							$res2 = mysqli_query($con, $sql2);
							if($res2){
								$result['ssss']='success';
							}else{
								$result['ssss']='false';
							}
						}
						//$result['data'] = $data;		//store data in result array
					}
					else{   		//If profile is not updated
						$result['status'] = 'False';	//store data in result array 
					}
					
				}else if($mobileNumberCount > 0 && $deviceIdCount > 0){			//existing user with same mobile number and same device
					if($deviceIdAndMobCount > 0){
						$sql = "UPDATE userRegistration SET otp = '$pin' WHERE mobile='$mobileNumber' AND deviceId = '$deviceId'";
						$ins = mysqli_query($con, $sql);
						if($ins){     //If profile updated successfully
							$result['status'] = 'True';     //store status in result array
						}
						else{   		//If profile is not updated
							$result['status'] = 'False123';	//store data in result array 
						}
					}else{ 
						$sql="INSERT INTO userRegistration(`mobile`,`fullName`,`otp`, `referralCode`, `deviceId`,`referredBy`,`tokenId`) VALUES 
										('$mobileNumber','$username','$pin', (SELECT referralCode FROM userRegistration WHERE mobile='$mobileNumber'),
										 '$deviceId', '$referredBy','$tokenId')";			//without token id
						$ins = mysqli_query($con,$sql) or (logToFile($logfile,"Insert otp and mobile number passengerLogin.php"));  //Inserting the otp for mobilenumber
						if($ins){     //If profile updated successfully
							$result['status'] = 'True';     //store status in result array
						}
						else{   		//If profile is not updated
							$result['status'] = 'False';	//store data in result array 
						}
					}  
					
				}else if($mobileNumberCount == 0 && $deviceIdCount > 0){		//existing user with different mobile number
					$sql="INSERT INTO userRegistration(`mobile`,`fullName`,`otp`, `referralCode`, `deviceId`,`referredBy`,`tokenId`) VALUES 
										('$mobileNumber','$username','$pin', '$referralCode', '$deviceId', '$referredBy','$tokenId')";			//without token id
					$ins = mysqli_query($con,$sql) or (logToFile($logfile,"Insert otp and mobile number passengerLogin.php"));  //Inserting the otp for mobilenumber
					if($ins){     //If profile updated successfully
 						$result['status'] = 'True';     //store status in result array
					}
					else{   		//If profile is not updated
						$result['status'] = 'False';	//store data in result array 
					}
					
				}else if($mobileNumberCount > 0 && $deviceIdCount == 0){		//existing user with different device
					$sql="INSERT INTO userRegistration(`mobile`,`fullName`,`otp`, `referralCode`, `deviceId`,`referredBy`,`tokenId`) VALUES 
										('$mobileNumber','$username','$pin', (SELECT referralCode FROM userRegistration WHERE mobile='$mobileNumber'),
										 '$deviceId', '$referredBy','$tokenId')";			//without token id
					$ins = mysqli_query($con,$sql) or (logToFile($logfile,"Insert otp and mobile number passengerLogin.php"));  //Inserting the otp for mobilenumber
					if($ins){     //If profile updated successfully
						$result['status'] = 'True';     //store status in result array
					}
					else{   		//If profile is not updated
						$result['status'] = 'False';	//store data in result array 
					}
				}
				
			}else if($action == 'validate'){
				$sql = "SELECT `otp`, `fullName`, `referralCode`, `regDate` FROM `userRegistration` WHERE `mobile` = '$mobileNumber' AND deviceId = '$deviceId'";		 //Getting the otp related to user mobile number
				$res = mysqli_query($con,$sql) or (logToFile($logfile,"Get otp from userRegistration table passengerLogin.php"));
				$result1 = mysqli_fetch_assoc($res);				//Execute sql statement and get the result
				$realotp = $result1['otp'];   		//Get the OTP and store it  
				$regDate = $result1['regDate'];
				
				//Validating the otp
				if($otp == $realotp){      //if user enetered otp and database otp is equal
                    if(new DateTime($regDate) ==  new DateTime('0000-00-00 00:00:00')){
						//status of user is changed while validate otp
						$sql1 = "UPDATE `userRegistration` SET `verification` = 'verified', `regDate` = '$time' WHERE `mobile` = '$mobileNumber' AND deviceId = '$deviceId'";
					}else{
						$sql1 = "UPDATE `userRegistration` SET `verification` = 'verified' WHERE `mobile` = '$mobileNumber' AND deviceId = '$deviceId'";
					}
						$res1 =  mysqli_query($con,$sql1)  or (logToFile($logfile,"Update verified otp passengerLogin.php"));			//execute query
						if($res1){        //If query executed successfully
							$result['username'] = $result1['fullName'];
							$result['referralCode'] = $result1['referralCode'];
							$result['status'] = 'true';	 //set status as true
						}else{		//If query execution failed
							$result['status'] = 'false';		//set status as false
						}
				}
				else{		//If database OTP and user enetered OTp is not matched
						//status of user is changed while validate otp
						$sql1 = "UPDATE `userRegistration` SET `verification` = 'pending' WHERE `mobile` = '$mobileNumber' AND deviceId = '$deviceId'";	
						$res =  mysqli_query($con,$sql1)  or (logToFile($logfile,"Update verification filed as pending passengerLogin.php"));		//execute query
						if($res1){
							$result['status'] = 'false';
						}else{
							$result['status'] = 'false';
						}		
				}
				
			}
			else{
				$result['status'] = 'false';		//set status as false
				logToFile($logfile,"condition failed- passengerLogin.php");
			}		
			
	}		
	else{		//If db connection fails
			$result['dbstatus'] = 'failed';		//set status as false
			logToFile($logfile,"DB connection failed - passengerLogin.php");
		}	  
		
	header("Content-Type:application/json");
	echo json_encode($result);
	/*original code ends*/
?>
