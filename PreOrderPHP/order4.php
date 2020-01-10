<?php
//include 'DatabaseConfig1.php';
include 'DBconnection.php';
date_default_timezone_set('Asia/Kolkata');
$time=date("Y-m-d H:i:s");
//$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
//$con=mysqli_query("localhost","root","","restuarent");

//$g = eval($json);
//$netJSON = json_encode($netflix);
// 	$g = $obj['cart'];


// 	$g = $obj['cart'];
//	$g = $obj[0];
// 	$bb= $obj['id'];


// 		$g = $obj['cart'];
//         	$a = $g[0];
//         $id= $obj['id'];



$a=["1","8","20","22"];
$id="17";
  if(count($a)>0){
for($i=0;$i<sizeof($a);$i++)
{
$sel1=$conn->query("select * from item_list where `id`='$a[$i]'") or die("error 1");
while($row1=mysqli_fetch_assoc($sel1)){
  $item_name=$row1['item_name'];
  $item_cost=$row1['item_cost'];
  $item_qty=$row1['quantity'];

  if(count($item_name)>0)
  {
         for($i=0;$i<sizeof($a);$i++)
            {
              $item_qty3=$item_qty1+1;
              $item_cost3=$item_cost1+$item_cost;
              $update=$conn->query("UPDATE `order_list` SET `item_qty`='$item_qty3',`item_cost`='$item_cost3' WHERE `item_name`='$item_name' AND `id`='$item_id1'") or die("error");
              if($update)
              {
                echo "updated";
              }
              else{
                echo "not updated";
              }
            }
          }

else{
            $result= $conn->query("INSERT INTO `order_list`(`user_id`,`item_name`,`item_cost`,`item_qty`,`created_date`) VALUES('$id','$item_name','$item_cost','1','$time')") or die("error in insert");

            if($result){
              echo "data inserted successfully";
            }
          }
        }
      }
}
          ?>

