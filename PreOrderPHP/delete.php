<?php
include 'DBconnection.php';
header("Content-Type:application/json");
if ($conn->connect_error) {
 die("Connection failed: " . $conn->connect_error);
} 
  $json = file_get_contents('php://input');
 $obj = json_decode($json,true);
  $orderid = $obj['orderid'];
//$sql = "DELETE FROM orderDetails where orderId='$orderid' ";
$sql = "update orderDetails set status='3' where orderId='$orderid'";
if(mysqli_query($conn,$sql)){
 
 // If the record inserted successfully then show the message.
 $MSG = 'Record Deleted Successfully.' ;
 
// Converting the message into JSON format.
$json = json_encode($MSG);
 
// Echo the message.
 echo $json ;
 
 } 

else {

 $MSG = 'Record Successfully.' ;
$json = json_encode($MSG);
echo $json;

}

//echo json_encode($obj);
$conn->close();
?>


