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
  $number = $_POST["mobileNumber"];
 $username = $_POST["username"];
 $useremail = $_POST["email"];

    $query= mysqli_query($conn,"select * from loginTable WHERE mobileNumber = '$number'");
    while($r = mysqli_fetch_array($query)) {	
					$name= $r['name'];
					$email = $r['email'];
					$imageLocation = $r['imageLocation'];
				}
$target_direc = $domain_name.$target_dir ;	
If($name != $username || $name == '')
{
	$q= mysqli_query($conn, "UPDATE loginTable SET name = '$username' WHERE mobileNumber = '$number'");
}
If($email != $useremail || $email == '')
{
	$q= mysqli_query($conn, "UPDATE loginTable SET email = '$useremail' WHERE mobileNumber = '$number'");
}

If($imageLocation == '' || $imageLocation != $target_direc)
{
	 move_uploaded_file($_FILES['image']['tmp_name'], $target_dir);
	 mysqli_query($conn, "UPDATE loginTable SET imageLocation = '$target_direc' WHERE mobileNumber = '$number'");
}





	
 
$MESSAGE = "Image Uploaded Successfully." ;
  echo json_encode($MESSAGE);
?>

