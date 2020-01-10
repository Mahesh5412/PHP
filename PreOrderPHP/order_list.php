


<?php
//include 'DatabaseConfig1.php';
include 'DBconection.php';
date_default_timezone_set('Asia/Kolkata');
$time=date("Y-m-d H:i:s");
//$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
// $conn=mysqli_connect("localhost","root","","restuarant");

$g = eval($json);
$netJSON = json_encode($netflix);
	$g = $obj['cart'];


	$g = $obj['cart'];
	$g = $obj[0];
	$bb= $obj['id'];
		$g = $obj['cart'];
   $a = $g[0];
  $id= $obj['id'];



// $a=["1","1","20","22"];
// $id='8';
// echo $id;
  for($i=0; $i<sizeof($a); $i++)
  {

    $sel1=$conn->query("select * from item_list where `id`='$a[$i]'") or die("error 1");
                                                $i=0;
                                              while($row1=mysqli_fetch_assoc($sel1))
                                                  {
                                                    $item_name=$row1['item_name'];
                                                    $item_cost=$row1['item_cost'];
                                                    $item_qty=$row1['quantity'];
                                                    $item_ido=$row1['id'];
                                                    $res_name=$row1['res_name'];

                                                    $i++;

                                                  }

                                                $item_cost_4 = $i*$item_cost;
                                                $item_qty_4=$i*$item_qty;
echo $item_cost_4;
      $sel2=$conn->query("select * from final_order_list where item_id='$item_ido'") or die("error");
                                                    while($row2=mysqli_fetch_assoc($sel2))
                                                    {
                                                      $item_name1=$row2['item_name'];
                                                      $item_cost1=$row2['item_cost'];
                                                      $item_qty1=$row2['quantity'];
                                                      $item_ido1=$row2['id'];
                                                      $res_name1=$row2['res_name'];
                                                    }


if($item_ido ==  $item_ido1)
{
  $insert= $conn->query("insert into order_list(user_id,res_name,item_id,item_cost,item_qty)values ('$id','$res_name1','$tem_id1','$item_cost_4','$item_qty_4')") or die("error");
}
else{
  echo "data not sufficient to update";
}}

  ?>

