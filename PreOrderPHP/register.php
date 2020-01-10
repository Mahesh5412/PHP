<?php
 
//include 'DatabaseConfig1.php';
include 'DBconnection.php';

date_default_timezone_set('Asia/Kolkata'); 
$time= date("Y-m-d H:i:s");

// Create connection
//$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);
 
$json = file_get_contents('php://input');
 
$obj = json_decode($json,true);
 
$username = $obj['username'];
$email = $obj['email'];
$number = $obj['number'];
//$password = $obj['password'];
//$phonenumber = $obj['phonenumber'];
//$cpassword = $obj['cpassword'];


// $username = 'dsdsds';
// $email = 'stej@gmail.com';
// $password = '098765432';
// $phonenumber = '8876543234';
// $cpassword = '098765432';


 
$CheckSQL = "SELECT * FROM loginTable WHERE email='$email'";
 

$check = mysqli_fetch_array(mysqli_query($conn,$CheckSQL));

 if(isset($check)){
 
 $EmailExistMSG = 'Email Already Exist, Please Try Again !!!';
 
$EmailExistJson = json_encode($EmailExistMSG);
 
 echo $EmailExistJson ; 
  
 }
 else{

//$Sql_Query = "insert into loginTable (name,email,createdDate) values ('$username','$email','$time')";
 
$Sql_Query = "update loginTable set name='$username',email='$email',modifiedDate='$time' where mobileNumber='$number'";

//$Sql_Query = "insert into loginTable (name,email,password,mobileNumber,createdDate) values ('$username','$email','$password','$phonenumber','$time')";
 
 
 if(mysqli_query($conn,$Sql_Query)){
 
$MSG = 'success' ;
 
$json = json_encode($MSG);

 echo $json ;
 
 }
 else{
 
 echo 'Try Again';
 
 }
 }
 mysqli_close($conn);
?>
