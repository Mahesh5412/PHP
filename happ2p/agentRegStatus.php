<?php
/*FileName:agentregstatus.php
 *Purpose:for checking agent registration status with 4 steps details, documents upload, documents verification,admin approval
 *Developers Involved:vineetha
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
           //getting the details from agentRegStatus api   
		   $id        = $_REQUEST['id'];
			
           
		   $sql = "select `verifiedStatus` from `agentRegistration` where `aid`='$id'";			//get agent verification status
		   $res = mysqli_query($con,$sql);
		   
           if($row = mysqli_fetch_assoc($res))
		   {
				 $verifiedStatus=$row['verifiedStatus'];
				//Registration details are matched then update the status                    
				if($verifiedStatus == "details inserted")
				{
						$temp['verifiedStatus']="details inserted";
						$temp['regStatus'] = 'True'; 
							
						$updateAgent=mysqli_query($con,"UPDATE `agentRegistration` set `verifiedStatus`='login into account' where `aid`='$id'")
									or (logToFile($logfile,"Update status to login into account in agentRegistration table - agentRegStatus.php"));	//update verification status
						if($updateAgent)
						{
							$verifyingStatus="login into account";
						}
								
				}
				//when he is login into his account for checking documentLocation is not null then update the status
				if($verifiedStatus=="login into account")
				{
					$sql1 =mysqli_query($con,"SELECT agentRegistration.verifiedStatus, documentDetail.documentName, documentDetail.documentPath FROM 
							`agentRegistration` INNER JOIN documentDetail ON agentRegistration.aid=documentDetail.id WHERE agentRegistration.aid='$id'")
							or (logToFile($logfile,"To get document location in agentRegistration - agentRegStatus.php"));	
								//getting document path and verification status
					if($row=mysqli_fetch_assoc($sql1))
					{
								$temp['verifiedStatus']=$row['verifiedStatus'];
								$temp['documentLocation']=$row['documentPath'];
								$temp['regStatus'] = 'True'; 
					}
							
					if($temp['documentLocation']!="")	
					{				//update verification status
							$updateAgent1=mysqli_query($con,"UPDATE `agentRegistration` set `verifiedStatus`='document uploaded' where `aid`='$id'")
											or (logToFile($logfile,"To update status to document upload in agentRegistration - agentRegStatus.php"));
							if($updateAgent1){
								$verifiedStatus="document uploaded";
							}
					}
				}
				//Document is uploaded successfully then update the status
				if($verifiedStatus=="document uploaded")
				{
						$sql1 =mysqli_query($con,"SELECT agentRegistration.verifiedStatus, documentDetail.eVerificationStatus FROM 
							`agentRegistration` INNER JOIN documentDetail ON agentRegistration.aid=documentDetail.id WHERE agentRegistration.aid='$id'")
								or (logToFile($logfile,"To get document status in agentRegistration - agentRegStatus.php"));	
								//get data related to e-verification
						if($row=mysqli_fetch_assoc($sql1))
						{
								$temp['eVerificationStatus']=$row['eVerificationStatus'];
								$temp['verifiedStatus']=$row['verifiedStatus'];
								$temp['regStatus'] = 'True'; 
						}
						if($temp['eVerificationStatus'] == "accept")
						{		//update verificatiosn status
							$updateAgent1=mysqli_query($con,"UPDATE `agentRegistration` set `verifiedStatus`='e-verification completed' where `aid`='$id'")
							 or (logToFile($logfile,"To update status to e-verification completed in - agentRegStatus.php"));
							if($updateAgent1)
							{
								$verifyingStatus="e-verification completed";
							}
						}
							
				 }
				//Documents is accepted by admin--E-Verification
				if($verifiedStatus == "e-verification completed")
				{
						$temp['verifiedStatus']="e-verification completed";
						$temp['regStatus'] = 'True'; 
				}else{
					$temp['status'] = 'false'; 
				}
       
		 }
		 else
		 { 
		    $temp['status'] = 'false'; 
		 } 
		     	
      }
      else{
		  $temp['status'] = 'false'; 
		  logToFile($logfile,"DB connection failed in agentregstatus.php");

	  } 
     header("Content-Type:application/json"); 
     echo json_encode($temp);
     /*original code ends*/
 ?>
