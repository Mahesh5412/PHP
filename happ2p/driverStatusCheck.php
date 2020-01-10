<?php
/*Version :1.0.0
 * FileName:driverStatusCheck.php
 *Purpose:to check driver status in physical verification
 *Developers Involved:vineetha
*/
//connecting to server
include 'connect.php';
//for logs
	$logfile= 'log/logfiles/log_' .date('d-M-Y') . '.log';   
	   
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    /*original code starts*/	
if($_SERVER['REQUEST_METHOD'] == "POST" || $_SERVER['REQUEST_METHOD'] == "GET")
   {
	//Getting the details from  statuschecking api
	$agentId = $_REQUEST['agentId'];
	$driverId = $_REQUEST['driverId'];
	
	$query = "SELECT pVerificationStatus, documentName FROM documentDetail WHERE id = '$driverId' AND verifier = '$agentId'";
	$res = mysqli_query($con,$query) or (logToFile($logfile,"Getting the details in physicalVerifivation driverStatusCheck.php"));
		
	while($row = mysqli_fetch_assoc($res))
	{
		$documentName=$row['documentName'];
					 
						if($documentName == 'rc'){
							$temp['rcStatus']=$row['pVerificationStatus'];
							}else if($documentName == 'license'){
								$temp['licenseStatus']=$row['pVerificationStatus'];
							}else if($documentName == 'insurance'){
								$temp['insuranceStatus']=$row['pVerificationStatus'];
							}else if($documentName == 'pollution'){
								$temp['pollutionStatus']=$row['pVerificationStatus'];
							}else if($documentName == 'pan'){
								$temp['panStatus']=$row['pVerificationStatus'];
							}else if($documentName == 'aadhar'){
								$temp['aadharStatus']=$row['pVerificationStatus'];
							}else if($documentName == 'photo'){
								$temp['photoStatus']=$row['pVerificationStatus'];
							}
								
								$temp['documentVerificationStatus'] = "True";
		}
		//if all documents status is accept
		if($temp['rcStatus'] == "accept" && $temp['licenseStatus'] == "accept" && $temp['insuranceStatus'] == "accept" && $temp['pollutionStatus'] == "accept"
							&& $temp['aadharStatus'] == "accept" && $temp['photoStatus'] == "accept"){
								
							
							$res1234=mysqli_query($con,"UPDATE `driverRegistration` set `verifiedStatus`='physicalVerificationCompleted' WHERE driverId = '$driverId'")
							 or (logToFile($logfile,"Update the status driverStatusCheck.php")); 
							if($res1234){
								
								//$verified = 5;
							}
						
	
	}else{
	$temp['status'] = "False";	
		}
	}//if loop Ends Here
		else
	{
			$temp['status'] = 'False';
		logToFile($logfile,"DB connection failed - driverStatusCheck.php");

		}//else loop ends


	header("Control-Type:application/json");
		echo json_encode($temp);
		/*original code starts*/	
?>
