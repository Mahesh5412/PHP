<?php
/*FileName:verifyPhysicalVerificationAccept.php
 *Purpose: To update accepted documents
 *Developers Involved:Srikanth
 */
 //connecting to server
 include 'connect.php';
     //for logs
	$logfile= 'log/logfiles/log_' .date('d-M-Y') . '.log';
    
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        /*original code starts*/
    if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET")
        {
			//Getting the details from  accept api
        $agentId = $_REQUEST['agentId'];
        $driverId = $_REQUEST['driverId'];
        $documentType = $_REQUEST['type'];

        
			//update status of documents
            $sql2 = "UPDATE documentDetail SET pVerificationStatus = 'accept' WHERE verifier = '$agentId' AND id ='$driverId' AND documentName ='$documentType'";
            $res = mysqli_query($con,$sql2) or (logToFile($logfile,"update the physicalverification accept verify_physicalverification_accept.php"));
            
            $sql3 = "SELECT pVerificationStatus, documentName FROM documentDetail WHERE verifier = '$agentId' AND id = '$driverId'";
            $res1 = mysqli_query($con,$sql3) or (logToFile($logfile,"physicalverification accept verifyPhysicalVerificationAccept.php"));
           
            if(mysqli_num_rows($res1) >0){
				while($row = mysqli_fetch_array($res1))			//get status of document
				{
					$documentName=$row['documentName'];
					 
						if($documentName == 'rc'){
								$rcStatus=$row['pVerificationStatus'];
							}else if($documentName == 'license'){
								 $licenseStatus=$row['pVerificationStatus'];
							}else if($documentName == 'insurance'){
								 $insuranseStatus=$row['pVerificationStatus'];
							}else if($documentName == 'pollution'){
								 $pollutionStatus=$row['pVerificationStatus'];
							}else if($documentName == 'pan'){
								 $panStatus=$row['pVerificationStatus'];
							}else if($documentName == 'aadhar'){
								 $aadharStatus=$row['pVerificationStatus'];
							}else if($documentName == 'photo'){
								 $photoStatus=$row['pVerificationStatus'];
							}
								
					$result['acceptStatus'] = "True";
					$accept = 'accept';		
				}
			}else{
				$result['acceptStatus'] = "False";
			}
				//if all documents are accepted by verifier then change the verified status
				if($rcStatus == $accept and $licenseStatus == $accept and $pollutionStatus == $accept and $aadharStatus == $accept and $insuranseStatus == $accept and $photoStatus == $accept)
						{
							  $sql1 = "UPDATE `driverRegistration` set `verifiedStatus`='physical verification completed' WHERE driverId = '$driverId'";  
							  $res = mysqli_query($con,$sql1) or (logToFile($logfile,"update the driverRegistration verifyPhysicalVerificationAccept.php"));
							  $result['acceptedStatus'] = 'True';   
							
						}else{
							$result['acceptedStatus'] = 'False';
						}					  						                     
     }
     else{
                  $result['status'] = 'False';
                  logToFile($logfile,"DB connection failed verifyPhysicalVerificationAccept.php");
            }
    header("Content-Type:application/json");
    echo json_encode($result);
    	/*original code ends*/
   ?>
