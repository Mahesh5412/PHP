<?php
/*Version :1.0.0
 * FileName:passengerUpload.php
 *Purpose:To upload images catured by passenger
 *Developers Involved:Mahesh
 */
	//connecting to server
	include 'connect.php';
	//for logs
	$logfile= 'log/logfiles/log_' .date('d-M-Y') . '.log';   
	/*original code starts*/
		//Image data
		$ImageData=$_POST['image_data'];		//get image data form API
		//with time		
		$ImageName= $_POST['image_tag'];		//get mobile number from API
		$phoneNumber = $_POST['phoneNumber'];
		$latitude = $_POST['latitude'];
		$longitude = $_POST['longitude'];
		//image path 
		$ImagePath = "uploads/".$ImageName.".jpeg";
		//server path 
		$ServerURL = "http://115.98.3.215:90/hapP2P/".$ImagePath;
		
		date_default_timezone_set('Asia/Kolkata');
		$date = date( 'Y-m-d h:i:s A', time () );
		//insert data into db
		$InsertSQL = mysqli_query($con,"INSERT INTO `passengerUpload`(`uploadedBy`, `documentName`, `documentPath`,`latitude`,`longitude` ,`date`) 
			VALUES ('$phoneNumber','$ImageName','$ServerURL','$latitude','$longitude','$date')") or (logToFile($logfile,"Insert user captured image - passengerUpload.php"));
			if($InsertSQL)
			{
				//Image uploading
				file_put_contents($ImagePath,base64_decode($ImageData));
				echo "sucess";
			}
			else
			{
				echo "failure";
				logToFile($logfile,"Image file transfer failed - passengerUpload.php");
			}
		
		
	/*original code ends*/
?>
