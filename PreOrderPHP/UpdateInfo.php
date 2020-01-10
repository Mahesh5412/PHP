<?php
/*Version :1.0.0
 *FileName:RouteList.php
 *Purpose: updating the use profile datailes 
 *Developers Involved:Raju,TulasiRao
 */
//connecting to server
include 'DBconnection.php';
//including log creation file 
include("log/log.php"); 
//creataing file
$logfile= 'log/log_' .date('d-M-Y') . '.log';
//creating url for storing image
$domain_name = "http://183.82.120.3:90/reactrest/phpfiles/";
 // Image uploading folder.
 $target_dir = "uploads";
 // Generating random image name each time so image name will not be same .
 $target_dir = $target_dir . "/" .rand() . "_" . time() . ".jpeg";
 // getting username,email,mobilenumber and status from Update_profile.js
  and stored in $username,$email,$number and $status
 $username = $_POST["username"];
 $email = $_POST["email"];
 $number = $_POST["mobileNumber"];
 $status = $_POST["status"];
 // Receiving image sent from Application
 if($status=='1'){
        if((move_uploaded_file($_FILES['image']['tmp_name'], $target_dir)) == null 
        || $username != null || $email != null )
        {
              $target_direc = $domain_name . $target_dir ;
              // this SQL command will execute all fields are contans data
                $ins = mysqli_query($conn, "UPDATE loginTable SET 
                imageLocation = '$target_direc', name='$username',email='$email'  WHERE mobileNumber = '$number'")
                 or 
                 (logToFile($logfile,"query not updating in updateprofile.php"+$username+"number",1));
                 if(mysqli_affected_rows($ins) >0 ){
                         $MESSAGE = "Image Uploaded Successfully." ;
                }
        }
 }
 else{
        //this SQL command will execute only certains fields contains data
        $ins = mysqli_query($conn, "UPDATE loginTable SET name='$username',email='$email'  WHERE mobileNumber = '$number'")
        or 
        (logToFile($logfile,"query not updating in updateprofile.php"+$username+"number",1));
        if(mysqli_affected_rows($ins) >0 ){
               $MESSAGE = "Image Uploaded Successfully." ;
        }
 }
 // Printing response message on screen after successfully inserting the image .
 echo json_encode($MESSAGE);
?>