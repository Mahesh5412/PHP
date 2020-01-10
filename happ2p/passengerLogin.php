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
			/*$action = 'generate';
			$mobileNumber = '9912804571';
			$deviceId='02f2c6d440707b5e';
			$referralCode ='UINO4V';
			$referredBy = 'UQTA5O';*/
		//	$username ='ashish';
			//$otp = '3298';
			
			date_default_timezone_set('Asia/Kolkata');   //current time
			$time=date("Y-m-d H:i:s",time());
			 
			if($action == 'generate'){				//If action is generate OTP
				$sql = "SELECT * FROM `userRegistration` WHERE `deviceId` = '$deviceId'";  //Checking the user already register or not
				$res = mysqli_query($con,$sql);
				 $count=mysqli_num_rows($res);    //get the count of rows
				if($count > 0){				//If count is greater than 
					$result['referredByStatus'] = 'True';
					$pin = mt_rand(1000, 9999);	   //Random pin for otp	
					
					$sql1="UPDATE `userRegistration` SET otp = '$pin', mobile = '$mobileNumber',`fullName` = '$username',`tokenId` = '$tokenId' WHERE `deviceId` = '$deviceId'";		//Inserting the otp for mobilenumber		//without token id
					$ins = mysqli_query($con,$sql1) or (logToFile($logfile,"Update otp related to mobile number passengerLogin.php"));
					//sms();        //To send OTP to user mobile number
					if($ins){       //If profile updated successfully
						$result['status'] = 'True';	    //store status in result array
						//$result['data'] = $data; 		//store data in result array 
					}else{      //If profile is not updated
						$result['status'] = 'False';		//store data in result array 
					}  
				}else{			//If count is less than or equal to zero
					$result['status'] = 'false33';
					if($referredBy != 'NA'){ 
					$refcode_sql = "SELECT `referralCode` FROM `userRegistration` WHERE `referralCode`='$referredBy'";
					$count =mysqli_num_rows(mysqli_query($con,$refcode_sql));
					if($count > 0){ 
						$result['referredByStatus'] = 'True';
					$pin = mt_rand(1000, 9999);      //Random pin for otp
					
					$sql="INSERT INTO userRegistration(`mobile`,`fullName`,`otp`, `referralCode`, `deviceId`,`referredBy`,`tokenId`) VALUES 
										('$mobileNumber','$username','$pin', '$referralCode', '$deviceId', '$referredBy','$tokenId')";			//without token id
					$ins = mysqli_query($con,$sql) or (logToFile($logfile,"Insert otp and mobile number passengerLogin.php"));  //Inserting the otp for mobilenumber
					//sms();			//To send OTP to user mobile number
					if($ins){     //If profile updated successfully
						$result['status'] = 'True';     //store status in result array
						
						$sql3 = "SELECT * FROM `userRegistration` WHERE referralCode='$referredBy'";
						$res3 = mysqli_query($con, $sql3);
						 $referralCount = mysqli_num_rows($res3);
						if($referralCount > 0){
							$sql2 = "INSERT INTO userCoupons (userId, cId, earnedDate, expiryDate) VALUES (
									(SELECT userId FROM userRegistration WHERE referralCode ='$referredBy'), 
									(SELECT id FROM `couponTable` WHERE couponId LIKE '%REFER%'), '$time', 
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
				}else{
					$result['referredByStatus'] = 'False';
				}
			}else{
					$result['referredByStatus'] = 'True';
					$pin = mt_rand(1000, 9999);      //Random pin for otp
					/*$sql="INSERT INTO userRegistration(`mobile`,`otp`, `referralCode`, `deviceId`, `tokenId`,`fullName`) VALUES 
										('$mobileNumber','$pin', '$referralCode', '$deviceId', '$tokenId','$username')";*/   //with token id
					$sql="INSERT INTO userRegistration(`mobile`,`fullName`,`otp`, `referralCode`, `deviceId`,`referredBy`,`tokenId`) VALUES 
										('$mobileNumber','$username','$pin', '$referralCode', '$deviceId', '$referredBy','$tokenId')";			//without token id
					$ins = mysqli_query($con,$sql) or (logToFile($logfile,"Insert otp and mobile number passengerLogin.php"));  //Inserting the otp for mobilenumber
					//sms();			//To send OTP to user mobile number
					if($ins){     //If profile updated successfully
						$result['status'] = 'True';     //store status in result array
						
						$sql3 = "SELECT * FROM `userRegistration` WHERE referralCode='$referredBy'";
						$res3 = mysqli_query($con, $sql3);
						 $referralCount = mysqli_num_rows($res3);
						if($referralCount > 0){
							$sql2 = "INSERT INTO userCoupons (userId, cId, earnedDate, expiryDate) VALUES (
									(SELECT userId FROM userRegistration WHERE referralCode ='$referredBy'), 
									(SELECT id FROM `couponTable` WHERE couponId LIKE '%REFER%'), '$time', 
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
				}
			}
				
			}else if($action == 'validate'){
				$sql = "SELECT `otp`, `fullName`, `referralCode`, `regDate` FROM `userRegistration` WHERE `mobile` = '$mobileNumber'";		 //Getting the otp related to user mobile number
				$res = mysqli_query($con,$sql) or (logToFile($logfile,"Get otp from userRegistration table passengerLogin.php"));
				$result1 = mysqli_fetch_assoc($res);				//Execute sql statement and get the result
				$realotp = $result1['otp'];   		//Get the OTP and store it  
				$regDate = $result1['regDate'];
				
				//Validating the otp
				if($otp == $realotp){      //if user enetered otp and database otp is equal
                        if(new DateTime($regDate) ==  new DateTime('0000-00-00 00:00:00')){
						//status of user is changed while validate otp
						$sql1 = "UPDATE `userRegistration` SET `verification` = 'verified', `deviceId`='$deviceId', `regDate` = '$time' WHERE `mobile` = '$mobileNumber'";
					}else{
						$sql1 = "UPDATE `userRegistration` SET `verification` = 'verified', `deviceId`='$deviceId' WHERE `mobile` = '$mobileNumber'";
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
						$sql1 = "UPDATE `userRegistration` SET `verification` = 'pending' WHERE `mobile` = '$mobileNumber'";	
						$res =  mysqli_query($con,$sql1)  or (logToFile($logfile,"Update verification filed as pending passengerLogin.php"));		//execute query
						if($res1){
							$result['status'] = 'false';
						}else{
							$result['status'] = 'false';
						}		
				}
				
			}else if($action == 'checkingDeviceId'){/*checking deviceId*/
				$sql = "SELECT * FROM `userRegistration` WHERE `deviceId` = '$deviceId' AND `verification` = 'verified'";  //Checking the user already register or not
				$res = mysqli_query($con,$sql);
				 $count=mysqli_num_rows($res);    //get the count of rows
				if($count > 0){				//If count is greater than 0
					$result['status'] = 'True';	    
				}else{
                   $result['status'] = 'False';	    //store status in result array
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
