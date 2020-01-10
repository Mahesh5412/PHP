



<?php
//include 'DatabaseConfig1.php';
include 'DBconnection.php';
date_default_timezone_set('Asia/Kolkata');
$time=date("Y-m-d H:i:s");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$orderid= $obj['orderid'];
//$orderid = '214';
// start($orderid,$conn);
// function start($orderid,$conn){
// echo "first";
// //ehco $orderid;
// print_r($orderid);
// //print_r($conn);
// //echo $conn;
// $r = 0;
//   $sel=$conn->query("select * from orderDetails where `orderId`='$orderid'") or die("error in select ");
//   echo "aaa";
//             while($row[]=mysqli_fetch_array($sel))
//               {
//                 echo "fff";
//                 $item=$row;
//                // print_r($item);
//                 $r++;
//                 echo $r."loop";
//               }

//               if($item['adminMessage']==""){
//                 echo "again";
//                 start($orderid,$conn);
//               }

//               echo  "ggg";
//              // echo $item;
// }

// echo "end";
// echo $item;

$sel=$conn->query("select * from orderDetails where `orderId`='$orderid'") or die("error in select ");
            while($row=mysqli_fetch_array($sel))
              {
                $item['adminMessage']=$row['adminMessage'];
                $item['personsCount']=$row['personsCount'];
                $item['sno']=$row['sno'];
                $item['orderId']=$row['orderId'];
              }
             $Item['status']=true;
             $Item['data'] = $item;
             $json = json_encode($Item);
        echo $json;
?>
