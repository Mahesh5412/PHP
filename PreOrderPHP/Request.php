<?php
/*Version :1.0.0
 *FileName:RouteList.php
 *Purpose: Getting the Admin message 
 *Developers Involved:TulasiRao
 */
//connecting to server
include 'DBconnection.php';
//getting default date and time
date_default_timezone_set('Asia/Kolkata');
$time=date("Y-m-d H:i:s");
//including log creation file 
include("log/log.php"); 
//creataing file
$logfile= 'log/log_' .date('d-M-Y') . '.log';
//logToFile($logfile,"Started logging first",1);
$json = file_get_contents('php://input');
// decoding the received variable and store into $obj variable
$obj = json_decode($json,true);
 //getting orderid from payment.js.js file and store into $orderid
$orderid= $obj['orderid'];
//SQL command for select orderDetails based on orderId
$sel=$conn->query("select * from orderDetails where `orderId`='$orderid'") or
 (logToFile($logfile,"query not seleted in RestaurantList.php"+$orderid+"orderid",1));

 if(mysqli_num_rows($sel) > 0) {
      //fetching result in array format
        while($row=mysqli_fetch_array($sel))
        {
              $item['adminMessage']=$row['adminMessage'];
              $item['personsCount']=$row['personsCount'];
              $item['sno']=$row['sno'];
              $item['orderId']=$row['orderId'];
        }
        $Item['status']="true";
        $Item['data'] = $item;
 }
 else
  {
        $Item['status']="fail";
        $json = json_encode($Item);
 }

        $json = json_encode($Item);
        echo $json;
?>
