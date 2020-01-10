<?php
/*FileName:driverDocumentStatus.php
 *Purpose: get physical verification documents status realted to driver
 *Developers Involved:Srikanth
 */
 //connecting to server
 include 'connect.php';
     //for logs
	//include("log/log.php");
	$logfile= 'log/logfiles/log_' .date('d-M-Y') . '.log';
    
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    /*original code starts*/
    if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET")
        {
			//Getting the details from  android API
           $agentId=$_REQUEST['agentId'];
           $driverId=$_REQUEST['driverId'];
           
         // $agentId = 'A1';
         // $driverId = 'DSYH6R';
                  //get physical verification documents status
            $sql = "SELECT pVerificationStatus, documentName FROM documentDetail WHERE id = '$driverId' AND verifier = '$agentId'";  
            $res = mysqli_query($con,$sql) or (logToFile($logfile,"Getting pVerificationStatus and documentName documentDetail - driverDocumentStatus.php"));
			if(mysqli_num_rows($res)>0){
					while($row = mysqli_fetch_assoc($res))
					{
						$documentName=$row['documentName'];
					 
						if($documentName == 'rc'){
							$temp['rcStatus']=$row['pVerificationStatus'];
							}else if($documentName == 'license'){
								$temp['licenseStatus']=$row['pVerificationStatus'];
							}else if($documentName == 'insuranse'){
								$temp['insuranseStatus']=$row['pVerificationStatus'];
							}else if($documentName == 'pollution'){
								$temp['pollutionStatus']=$row['pVerificationStatus'];
							}else if($documentName == 'pan'){
								$temp['panStatus']=$row['pVerificationStatus'];
							}else if($documentName == 'aadhar'){
								$temp['aadharStatus']=$row['pVerificationStatus'];
							}else if($documentName == 'photo'){
								$temp['photoStatus']=$row['pVerificationStatus'];
							}
                   }
          
					$result['documentVerificationStatus'] = 'True';  
			}else{
				$result['documentVerificationStatus'] = 'false';
				logToFile($logfile,"pVerification query failed, no  data - driverDocumentStatus.php");
			}          
                              
     }
      else
    {
			$result['status'] = 'false';
            logToFile($logfile,"DB connection failed - driverDocumentStatus.php");
    }
    header("Content-Type:application/json");
    echo json_encode($result);
    	/*original code ends*/
   ?>
	
