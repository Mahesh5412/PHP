<?php
//include 'DatabaseConfig1.php';
include 'DBconnection.php';
//$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);
// mysql_select_db($dbname, $conn);
 // Type your website name or domain name here.
 //$domain_name = "http://malothraju.000webhostapp.com/Restaurant/";
  $domain_name = "http://183.82.120.3:90/reactrest/phpfiles/";
 // Image uploading folder.
 $target_dir = "uploads";
 // Generating random image name each time so image name will not be same .
 $target_dir = $target_dir . "/" .rand() . "_" . time() . ".jpeg";
 // Receiving image tag sent from application.
 $username = $_POST["username"];
 $email = $_POST["email"];
 $number = $_POST["mobileNumber"];
 // Receiving image sent from Application	
 if((move_uploaded_file($_FILES['image']['tmp_name'], $target_dir)) == null || $username != null || $email != null )
 {
 $target_direc = $domain_name . $target_dir ;
// mysqli_query($conn,"insert into imageupload ( image_tag) VALUES('$img_tag' , '$target_dir')");
  mysqli_query($conn, "UPDATE loginTable SET imageLocation = '$target_direc', name='$username',email='$email'  WHERE mobileNumber = '$number'");
 }
else{
     mysqli_query($conn, "UPDATE loginTable SET imageLocation = '$target_direc',name='$username'  email='$email' WHERE mobileNumber = '$number'");
}
 $MESSAGE = "Image Uploaded Successfully." ;
 // Printing response message on screen after successfully inserting the image .	
 echo json_encode($MESSAGE);
?>






<?php
//include 'DatabaseConfig1.php';
include 'DBconnection.php';
//$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);
// mysql_select_db($dbname, $conn);
 // Type your website name or domain name here.
 //$domain_name = "http://malothraju.000webhostapp.com/Restaurant/";
  $domain_name = "http://183.82.120.3:90/reactrest/phpfiles/";
 // Image uploading folder.
 $target_dir = "uploads";
 // Generating random image name each time so image name will not be same .
 $target_dir = $target_dir . "/" .rand() . "_" . time() . ".jpeg";
 // Receiving image tag sent from application.
 $username = $_POST["username"];
 $email = $_POST["email"];
 $number = $_POST["mobileNumber"];
 $status = $_POST["status"];
 // Receiving image sent from Application
 if($status=='1'){
     if((move_uploaded_file($_FILES['image']['tmp_name'], $target_dir)) == null || $username != null || $email != null )
     {
     $target_direc = $domain_name . $target_dir ;
    // mysqli_query($conn,"insert into imageupload ( image_tag) VALUES('$img_tag' , '$target_dir')");
      mysqli_query($conn, "UPDATE loginTable SET imageLocation = '$target_direc', name='$username',email='$email'  WHERE mobileNumber = '$number'");
     }
 }
 else{
    //$target_direc = $domain_name . $target_dir ;
     mysqli_query($conn, "UPDATE loginTable SET name='$username',email='$email'  WHERE mobileNumber = '$number'");
 }
//  if((move_uploaded_file($_FILES['image']['tmp_name'], $target_dir)) == null || $username != null || $email != null )
//  {
//  $target_direc = $domain_name . $target_dir ;
// // mysqli_query($conn,"insert into imageupload ( image_tag) VALUES('$img_tag' , '$target_dir')");
//   mysqli_query($conn, "UPDATE loginTable SET imageLocation = '$target_direc', name='$username',email='$email'  WHERE mobileNumber = '$number'");
//  }
// else{
//      mysqli_query($conn, "UPDATE loginTable SET imageLocation = '$target_direc',name='$username'  email='$email' WHERE mobileNumber = '$number'");
// }

 $MESSAGE = "Image Uploaded Successfully." ;

 // Printing response message on screen after successfully inserting the image .
 echo json_encode($MESSAGE);
 
 
?>