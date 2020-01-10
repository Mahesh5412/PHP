<?php
/*Version :1.0.0
 * FileName:driverPollutionUpload.php
 *Purpose: To upload pollution document of dirver
 *Developers Involved:Vineetha
 */
	include 'connect.php';		//connect to server

	//for logs
	$logfile= 'log/logfiles/log_' .date('d-M-Y') . '.log';   
	
		$ImageData=$_POST['image_data'];		//get image data from API
				
		$ImageName= $_POST['image_tag'];		//get mobile number from API
		 
		$ImagePath = "uploads/".$ImageName."pollution.jpeg";
		//server path 
		$ServerURL = "http://115.98.3.215:90/hapP2P/".$ImagePath;
		
		$sql1="SELECT driverId FROM driverRegistration WHERE mobile='$ImageName'";
		$res1=mysqli_query($con, $sql1);
		if($r1 = mysqli_fetch_assoc($res1)){
			$driverId=$r1['driverId'];
			//update the document location path
			$InsertSQL = mysqli_query($con,"UPDATE documentDetail SET documentPath='$ServerURL', eVerificationStatus='pending' 
			WHERE id='$driverId' AND documentName='pollution'") or (logToFile($logfile,"Update pollution image path - driverPollutionUpload.php"));
			if($InsertSQL)
			{
				//Image uploading
				file_put_contents($ImagePath,base64_decode($ImageData));
				echo "sucess";
			}
			else
			{
				echo "failure1";
				logToFile($logfile,"pollution image transfer failed - driverPollutionUpload.php");
			}
		}else{
			echo "failure1";
			logToFile($logfile,"DB connection failed - driverPollutionUpload.php");
		}
		
?>

