<?php
 
//include 'DatabaseConfig1.php';
include 'DBconnection.php';

date_default_timezone_set('Asia/Kolkata'); 
$time= date("Y-m-d H:i:s");


// Create connection
//$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);
 
$json = file_get_contents('php://input');
 
$obj = json_decode($json,true);
 

$name = $obj['username'];
 
$email = $obj['email'];
 $cpassword = $obj['cpassword'];
$password = $obj['password'];
$phone = $obj['phonenumber'];

 
$CheckSQL = "SELECT * FROM login_details WHERE email='$email' AND password='$password'";
 

$check = mysqli_fetch_array(mysqli_query($conn,$CheckSQL));
 
 
if(isset($check)){
 
 $EmailExistMSG = 'Email Already Exist, Please Try Again !!!';
 
 

$EmailExistJson = json_encode($EmailExistMSG);
 
 echo $EmailExistJson ; 
  
 }
    elseif($password != $cpassword){
         $msg = "passwords doesn't match";
         $phoneJson = json_encode($msg);
         echo $phoneJson ;
         }
 else{
 
$Sql_Query = "insert into login_details (username,email,password,phonenumber,created_date) values ('$name','$email','$password','$phone','$time')";
 
 
 if(mysqli_query($conn,$Sql_Query)){
 
$MSG = 'User Registered Successfully' ;
 
$json = json_encode($MSG);

 echo $json ;
 
 }
 else{
 
 echo 'Try Again';
 
 }
 }
 mysqli_close($conn);
?>
