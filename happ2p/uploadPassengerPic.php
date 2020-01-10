<?php
/*FileName:uploadPassengerPic.php
 *Purpose: To upload passenger profile image in db
 *Developers Involved: Srikanth
 */
	include 'connect.php';     //connect to server
  //for logs
	$logfile= 'log/logfiles/log_' .date('d-M-Y') . '.log';
         //get data from android API
		$ImageData=$_REQUEST['image_data'];
			
		$ImageName=$_REQUEST['image_tag'];
				
		
		$ImagePath = "uploads/profiles/".$ImageName."passenger.jpeg";

		//path to upload image
		$ServerURL = "http://115.98.3.215:90/hapP2P/".$ImagePath;

			//update the document location path
			$InsertSQL = mysqli_query($con,"UPDATE userRegistration SET profilePic = '$ServerURL' WHERE mobile = '$ImageName'")
						or (logToFile($logfile,"Update passenger profile image - uploadPassengerPic.php"));
			if($InsertSQL)
			{
				//Image uploading
				file_put_contents($ImagePath,base64_decode($ImageData));
				echo "sucess";
			}
			else
			{
				echo "failure1";
				logToFile($logfile,"passenger profile image file transfer failed - uploadPassengerPic.php");
			}
?>
