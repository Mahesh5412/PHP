<?php
/*FileName:uploadAgentProof.php
 *Purpose: To upload agent document into db
 *Developers Involved: Vineetha
 */
	include 'connect.php';		//to connect to server
	//for logs
	$logfile= 'log/logfiles/log_' .date('d-M-Y') . '.log';
    header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
	
	// original code starts
	if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD']=="GET")
	{
		$ImageData=$_REQUEST['image_data'];		//get data from android API
			
		$ImageName=$_REQUEST['image_tag'];
		
		$doctype=$_REQUEST['doctype'];
		
		$ImagePath = "uploads/".$ImageName."agentvoter.jpeg";
		//image path to upload
		$ServerURL = "http://115.98.3.215:90/hapP2P/".$ImagePath;

		$sql1="SELECT aid FROM `agentRegistration` WHERE mobileNumber='$ImageName'";		//get agent id 
		$res1=mysqli_query($con, $sql1) /*or (logToFile($logfile,"Get agent id - uploadAgentProof.php"))*/;
		
		if($r1 = mysqli_fetch_assoc($res1)){
			$agentId=$r1['aid'];
			//update the document location path
			$InsertSQL = mysqli_query($con,"INSERT INTO `documentDetail`(`id`, `documentName`, `documentPath`, `eVerificationStatus`) 
					VALUES ('$agentId', '$doctype', '$ServerURL', 'pending')") /*or (logToFile($logfile,"Insert agent document proof - uploadAgentProof.php"))*/;
			if($InsertSQL)
			{
				//Image uploading
				file_put_contents($ImagePath,base64_decode($ImageData));
				echo "sucess";
			}
			else
			{
				echo "failure1";
				/*logToFile($logfile,"Agent doc file transfer failed - uploadAgentProof.php");*/
			}
		}else{
			echo "failure1";
		}
	}else {		// if database connection failed
		//$result['status'] = 'False';	// send status false
		logToFile($logfile,"DB connection failed - driverProfile.php");
	}
	 header("Content-Type:application/json");
	 //echo json_encode($result);

 
?>
