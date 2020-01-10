<?php
/*FileName:submitDriverDetails.php
 *Purpose: Insert dirver registration details into db
 *Developers Involved: Srikanth, Vineetha
 */
	include 'connect.php';		//connect to server
	//for logs
	$logfile= 'log/logfiles/log_' .date('d-M-Y') . '.log'; 
	
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
	
	if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET")
		{	//get data from android API
			//driver reg details
			$result['driverRegDbConnection']  = 'connected';
			$fullName = $_REQUEST['driverName'];
			$mobileNumber = $_REQUEST['mobileNumber'];
			$vehicleNumber = $_REQUEST['vehicleNumber'];
			$vehicleName = $_REQUEST['vehicleName'];
			$rcNumber = $_REQUEST['rcNumber'];
			$licenseNumber = $_REQUEST['licenseNumber'];
			$aadharNumber = $_REQUEST['aadharNumber'];
			$insuranceNumber  =  $_REQUEST['insuranceNumber'];
			$pollutionNumber  =  $_REQUEST['pollutionNumber'];
			$panCardNumber  =  $_REQUEST['panNumber'];
			$seats  =  $_REQUEST['seats'];
			$vehicleType = $_REQUEST['vehicleType'];
			$driverId = $_REQUEST['driverId'];
			$password = $_REQUEST['password'];
			$referralCode = $_REQUEST['referralCode'];
			$referByDriverCode = $_REQUEST['referByDriverCode'];
			$action  =  $_REQUEST['action'];
			//static data
			/*$fullName = 'ttuewyriu';
			$mobileNumber = '937367328';
			$vehicleNumber = 'ts6576';
			$vehicleName = 'jjdsff';
			$rcNumber = '37267832';
			$licenseNumber = '36783246';
			$aadharNumber = '83764278';
			$insuranceNumber  =  '832647328';
			$pollutionNumber  =  '3643284623';
			$panCardNumber  =  '82648264';
			$seats  =  '3';
			$vehicleType = 'auto';
			$driverId = 'D174637';
			$password = '2343';
			$action  =  'regDetails';*/
			
			//driver bank details
			$accountantName = $_REQUEST['accountantName'];
			$accountNumber = $_REQUEST['accountNumber'];
			$ifscCode = $_REQUEST['ifscCode'];
			$bankName = $_REQUEST['bankName'];
			$branchName = $_REQUEST['branchName'];
			$driverNumber = $_REQUEST['driverNumber'];
			
			$timeStamp = date("Y-m-d H:i:s");		//current date and time
			
			if($action == 'regDetails'){			//to insert registeration details
				//inserting data into vehicleDetail
				$sql = "INSERT into `vehicleDetail` (`vehicleType`, `vehicleNo`, `vehicleName`, `seatCount`) VALUES ('$vehicleType', '$vehicleNumber', '$vehicleName', '$seats')";
				$res = mysqli_query($con,$sql) or (logToFile($logfile,"Insert vehicle data - submitDriverDetails.php"));			
				if($res){
					$sql1 = "select vehicleId from `vehicleDetail` where vehicleNo = '$vehicleNumber'";  //get vehicleId
					$res1 = mysqli_query($con,$sql1) or (logToFile($logfile,"Get vehicle Id - submitDriverDetails.php"));
					
					$fetch = mysqli_fetch_assoc($res1);
					$vehId = $fetch['vehicleId'];
					if($res1){				//insert data into driverRegistration table
						$sql2 = "INSERT into `driverRegistration` (`fullName`, `mobile`, `driverId`, `password`, `referralCode`, `vehicleId`, `regDate`)
									VALUES ('$fullName', '$mobileNumber', '$driverId', '$password', '$referralCode', '$vehId', '$timeStamp')";
						$res2 = mysqli_query($con,$sql2)  or (logToFile($logfile,"Insert driver registration data- submitDriverDetails.php"));
						if($res2){			//insert data into documentDetail table
							$sql3 = "INSERT INTO documentDetail(`id`, `documentName`, `documentNo`) VALUES('$driverId', 'rc', '$rcNumber'), ('$driverId', 'license',
							 '$licenseNumber'),('$driverId', 'insurance', '$insuranceNumber'), ('$driverId', 'pollution', '$pollutionNumber'), ('$driverId', 'pan',
							  '$panCardNumber'), ('$driverId', 'aadhar', '$aadharNumber'), ('$driverId', 'photo', '')";
							$res3 = mysqli_query($con, $sql3)  or (logToFile($logfile,"Insert document data - submitDriverDetails.php"));
							if($res3){
								$sql4="UPDATE driverRegistration SET verifiedStatus='details inserted' WHERE driverId='$driverId'";
								$res4=mysqli_query($con, $sql4)  or (logToFile($logfile,"Update verifiedStatus in driverRegistration table - submitDriverDetails.php"));
							//srikanth
							if($res4){
								if($referByDriverCode != ""){
									$sql5 = "SELECT driverId FROM `driverRegistration` WHERE referralCode = '$referByDriverCode'";
									$res5 = mysqli_query($con, $sql5);
									$fetchDid = mysqli_fetch_assoc($res5);
									$refDriverId = $fetchDid['driverId'];
									if($res5){
										
										$sql6 = "INSERT INTO referralAmount (driverId, referredBy) VALUES ('$driverId', '$refDriverId')";
										$res6 = mysqli_query($con, $sql6);
										if($res6){
											$result['registrationDetails'] = 'success';
											}else {
												$result['registrationDetails'] = 'fail';
												}
										
										}else{
										$result['registrationDetails'] = 'fail';}
									
									} else {
									$result['registrationDetails'] = 'success';
									}
								
								
								}else{
									$result['registrationDetails'] = 'fail';
									}
								
								// srikanth
								//$result['registrationDetails'] = 'success';
							
							
							}else{
								$result['registrationDetails'] = 'fail';
							}
						}else{
							$result['registrationDetails'] = 'fail';
						}
					}else{
						$result['registrationDetails'] = 'fail';
					}
				}else{
					$result['registrationDetails'] = 'fail';
				}	
				
				
			}else if($action == 'submitBankDetails'){
				$sql1="SELECT driverId FROM driverRegistration WHERE mobile='$driverNumber'";
				$res1=mysqli_query($con, $sql1);
				if($r1 = mysqli_fetch_assoc($res1)){
					 $driverIdNew=$r1['driverId'];
					 $sql3 = "UPDATE driverRegistration SET bankAccount ='yes' WHERE driverId = '$driverIdNew'";
					 $res3 = mysqli_query($con, $sql3);
					 if($res3){
						$sql2 = "INSERT INTO bankDetails (id, accountantName, accountNumber, ifsc, bankName, branchName) VALUES 
										('$driverIdNew', '$accountantName', '$accountNumber', '$ifscCode', '$bankName', '$branchName')";
						$res2 = mysqli_query($con, $sql2);
						if($res2){
							$result['driverBankDetsils'] = 'success';
						}else{
							$result['driverBankDetsils'] = 'fail';
						}
					}else{
						$result['driverBankDetsils'] = 'fail';
					}
				}else{
					$result['driverBankDetsils'] = 'fail';
				}
			}else if($action == 'noBankAccount'){
				$sql1="SELECT driverId FROM driverRegistration WHERE mobile='$driverNumber'";
				$res1=mysqli_query($con, $sql1);
				if($r1 = mysqli_fetch_assoc($res1)){
					 $driverIdNew=$r1['driverId'];
					 $sql3 = "UPDATE driverRegistration SET bankAccount ='no' WHERE driverId = '$driverIdNew'";
					 $res3 = mysqli_query($con, $sql3);
					 if($res3){
						 $result['driverBankDetsils'] = 'success';
					 }else{
						 $result['driverBankDetsils'] = 'fail';
					 } 
				}else{
					$result['driverBankDetsils'] = 'fail';
				}
			}
		}else{
			$result['driverRegDbConnection']  = 'failed';
			logToFile($logfile,"DB connection failed - submitDriverDetails.php");
		}
	header("Content-Type:application/json"); 
    echo json_encode($result);
?>
