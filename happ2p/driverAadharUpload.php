<?php
/*Version :1.0.1
 * FileName:driverAadharUpload.php
 *Purpose: To upload driver aadhar card
 *Developers Involved:vinetha
 */
	//connecting to server
	include 'connect.php';
	//for logs
	$logfile= 'log/logfiles/log_' .date('d-M-Y') . '.log';   
	   
	/*original code starts*/
	//Image data
	$ImageData=$_POST['image_data'];		//get image data from android API
	//mobileNumber			
	$ImageName= $_POST['image_tag'];		//get mobile number related to driver
	//image path	 
	$ImagePath = "uploads/".$ImageName."aadhar.jpeg";
	//server path 
	$ServerURL = "http://115.98.3.215:90/hapP2P/".$ImagePath;
	$sql1="SELECT driverId FROM driverRegistration WHERE mobile='$ImageName'";
		$res1=mysqli_query($con, $sql1);
		if($r1 = mysqli_fetch_assoc($res1)){
			$driverId=$r1['driverId'];
			//update the document location path
			$InsertSQL = mysqli_query($con,"UPDATE documentDetail SET documentPath='$ServerURL', eVerificationStatus='pending'
						WHERE id='$driverId' AND documentName='aadhar'") or (logToFile($logfile,"Update documentPath in documentDetail table - driverAadharUpload.php"));
			if($InsertSQL)
			{
				//Image uploading to uplods folder
				file_put_contents($ImagePath,base64_decode($ImageData));
				echo "sucess";
			}
			else
			{
				echo "failure1";
				logToFile($logfile,"Imagepath file failed to move - driverAadharUpload.php");
			}
		}else{
			echo "failure1";
		}
	
?>

