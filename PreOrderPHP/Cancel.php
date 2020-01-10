<?php
/*Version :1.0.0
 *FileName:Cancel.php
 *Purpose: Canceling the requested .
 *Developers Involved:Raju
 */
//connecting to server
include 'DBconnection.php';
header("Content-Type:application/json");
//including log creation file 
include("log/log.php"); 
//creataing file
$logfile= 'log/log_' .date('d-M-Y') . '.log';
//logToFile($logfile,"Started logging first",1);
  $json = file_get_contents('php://input');
 $obj = json_decode($json,true);
 //getting orderid from Cancel.js file
  $orderid = $obj['orderid'];
// executing SQL command to  Cancel the order based on orderid
$sql = "update orderDetails set status='3' where orderId='$orderid'";
$ins = mysqli_query($conn,$sql) or (logToFile($logfile,"query not updated 
in Cancel.php"+ $orderid+'orderid',1));
if(mysqli_affected_rows($ins) >0 ){
      // If the record inserted successfully then show the message.
      $MSG = 'Record Deleted Successfully.' ;
      // Converting the message into JSON format.
      $json = json_encode($MSG);
      // Echo the message.
      echo $json ;
 } 
else
 {
        $MSG = 'Record Successfully.' ;
        $json = json_encode($MSG);
        echo $json;
}
//echo json_encode($obj);
$conn->close();
?>


