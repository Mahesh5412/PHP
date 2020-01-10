<?php
/*Version :1.0.0
 *FileName:driverRegVerification.php
 *Purpose: To get driver registration verification status
 *Developers Involved:Vineetha
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
			//getting the details from senddriverid api
			$d_id = $_REQUEST['driverId'];
		
		    $sql = "SELECT verifiedStatus FROM `driverRegistration` WHERE driverId = '$d_id'";  
            $res = mysqli_query($con,$sql) or (logToFile($logfile,"Getting the verifyingStatus  driverRegVerification.php"));

             if($row = mysqli_fetch_assoc($res)) 
             {       

				 $verified=$row['verifiedStatus'];
			 // if Registration details are inserted then update the status
			 if($verified == "details inserted")
			 {
				 $temp['verifyingStatus'] = "deatils inserted";
				 $temp['status']="success";
				 
				 $res13=mysqli_query($con,"UPDATE driverRegistration set verifiedStatus='login into account' WHERE driverId = '$d_id'") 
				 or (logToFile($logfile,"Update the verifyingStatus  driverRegVerification.php")); 
				if($res13){
					$verified = 2;
					 $temp['verifyingStatus'] = "login into account";
					//echo "sdsad";
				}

			  }
			  //when he login into his account check the documents uploaded or not
			  if($verified == "login into account")
			  {
				 $sql3 = "SELECT documentDetail.documentName, documentDetail.documentPath, driverRegistration.verifiedStatus FROM documentDetail INNER JOIN 
						driverRegistration on driverRegistration.driverId=documentDetail.id WHERE documentDetail.id='$d_id'";  
				 $res1 = mysqli_query($con,$sql3) or (logToFile($logfile,"Getting the driverRegistration details driverRegVerification.php"));
            
					while($row = mysqli_fetch_array($res1))
					{	
						if($row['documentName'] == "rc"){
							$temp['rc']=$row['documentPath'];
						}else if($row['documentName'] == "license"){
							$temp['license']=$row['documentPath'];
						}else if($row['documentName'] == "insurance"){
							$temp['insurance']=$row['documentPath'];
						}else if($row['documentName'] == "pollution"){
							$temp['pollution']=$row['documentPath'];
						}else if($row['documentName'] == "aadhar"){
							$temp['aadhar']=$row['documentPath'];
						}else if($row['documentName'] == "pan"){
							$temp['pan']=$row['documentPath'];
						}else if($row['documentName'] == "photo"){
							$temp['photo']=$row['documentPath'];
						}
						$temp['verifyingStatus']=$row['verifiedStatus'];
						$temp['status']="success";
     	
					} 
					//If all documents are uploaded status will be changed
					if($temp['rc']!="" && $temp['license']!="" &&  $temp['insurance']!="" && $temp['pollution']!="" && $temp['aadhar']!="" && $temp['photo']!="")
					{
						$res=mysqli_query($con,"UPDATE driverRegistration SET verifiedStatus='documents uploaded' WHERE driverId='$d_id'")
						 or (logToFile($logfile,"Update details verifyingStatus in driverRegistration driverRegVerification.php")); 
						if($res)
						{
							$verified = "documents uploaded";
						}
				
					}
			 }
			 //All documents are uploaded successfully
		     if($verified == "documents uploaded")
			 {
				$sql7 = "SELECT documentDetail.documentName, documentDetail.eVerificationStatus, driverRegistration.verifiedStatus FROM documentDetail INNER JOIN 
						driverRegistration on driverRegistration.driverId=documentDetail.id WHERE documentDetail.id='$d_id'";
				$res7 = mysqli_query($con,$sql7) or (logToFile($logfile,"Getting the driverRegistration details driverRegVerification.php"));
				
				
				while($row = mysqli_fetch_array($res7))
				{
					if($row['documentName'] == "rc"){
							$temp['rc']=$row['eVerificationStatus'];
						}else if($row['documentName'] == "license"){
							$temp['license']=$row['eVerificationStatus'];
						}else if($row['documentName'] == "insurance"){
							$temp['insurance']=$row['eVerificationStatus'];
						}else if($row['documentName'] == "pollution"){
							$temp['pollution']=$row['eVerificationStatus'];
						}else if($row['documentName'] == "aadhar"){
							$temp['aadhar']=$row['eVerificationStatus'];
						}else if($row['documentName'] == "pan"){
							$temp['pan']=$row['eVerificationStatus'];
						}else if($row['documentName'] == "photo"){
							$temp['photo']=$row['eVerificationStatus'];
						}
					$temp['verifyingStatus']=$row['verifiedStatus'];
					$temp['status']="success";
           				
				}
			}
			//All documents are accepted by admin--E-Verification
			if($verified == "e-verification completed")
			{
				$sql8="SELECT documentDetail.documentName, documentDetail.pVerificationStatus, driverRegistration.verifiedStatus,documentDetail.verifier FROM documentDetail INNER JOIN 
						driverRegistration on driverRegistration.driverId=documentDetail.id WHERE documentDetail.id='$d_id'";
				$res12 = mysqli_query($con,$sql8) or (logToFile($logfile,"Getting the physicalVerification details driverRegVerification.php"));
				while($row=mysqli_fetch_array($res12))
				{		
						if($row['documentName'] == "rc"){
							$temp['rc']=$row['pVerificationStatus'];
						}else if($row['documentName'] == "license"){
							$temp['license']=$row['pVerificationStatus'];
						}else if($row['documentName'] == "insurance"){
							$temp['insurance']=$row['pVerificationStatus'];
						}else if($row['documentName'] == "pollution"){
							$temp['pollution']=$row['pVerificationStatus'];
						}else if($row['documentName'] == "aadhar"){
							$temp['aadhar']=$row['pVerificationStatus'];
						}else if($row['documentName'] == "pan"){
							$temp['pan']=$row['pVerificationStatus'];
						}
						$temp['status']="success";
						$agentId=$row['verifier'];
						
						$temp['verifyingStatus'] =$row['verifiedStatus'];
						$temp['verifier'] = $row['verifier'];
				 }
					$res123 = mysqli_query($con, "SELECT * FROM `agentRegistration` WHERE aid='$agentId'") or 
					(logToFile($logfile,"Getting the  details in userlogin and agentsRegistration driverRegVerification.php"));
					if($row1=mysqli_fetch_assoc($res123))
					{
						$temp['mobileNumber'] =$row1['mobileNumber'];
						//$temp['location'] = $row1['source'];
						$temp['userName'] = $row1['name'];
					}
			 }
			 //All documents are accepted in physical verification--Original doc verification
			 if($verified == "physical verification completed")		
			 {                   
					$temp['verifyingStatus'] = "physical verification completed";        
					$temp['status']="success";
			 }           
                   
           }                            
                $data[] =$temp;    
				$result['status'] = 'True';
				$result['data'] = $data;                           
     }
      else{

      	 $result['status'] = 'Fail';
      	logToFile($logfile,"DB connection failed - driverRegVerification.php");

      }  
    header("Content-Type:application/json");
    echo json_encode($temp);
    /*original code starts*/
   ?>
