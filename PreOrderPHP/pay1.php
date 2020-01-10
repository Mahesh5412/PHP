<?php
 
//include 'DatabaseConfig1.php';
include 'DBconnection.php';
date_default_timezone_set('Asia/Kolkata');
$time=date("Y-m-d H:i:s");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$orderid= $obj['orderid'];
 
//Creating SQL command to fetch all records from Table.

//$sql = "SELECT * FROM ordersTable WHERE userId='$user_id' ";
//$sql="SELECT ot.orderId, GROUP_CONCAT(ml.recipeName) AS recipeNames FROM `ordersTable` AS ot INNER JOIN menuList AS ml ON ot.recipeId=ml.recipeId WHERE userId='$user_id' GROUP BY orderId ";


//$sql="SELECT ot.orderId, GROUP_CONCAT(ml.recipeName) AS recipeNames, SUM(ot.totalAmount) as totalSum, date(ot.orderedDate) as date, DATE_FORMAT(ot.orderedDate, "%H:%i") as time FROM `ordersTable` AS ot INNER JOIN menuList AS ml ON ot.recipeId=ml.recipeId WHERE userId='$user_id' GROUP BY orderId";


//$sql = "SELECT ot.orderId, GROUP_CONCAT(ml.recipeName) AS recipeNames, SUM(ot.totalAmount) AS totalSum, date(ot.orderedDate) AS date, DATE_FORMAT(ot.orderedDate,"%H:%i") AS time FROM `ordersTable` AS ot INNER JOIN menuList AS ml ON ot.recipeId=ml.recipeId WHERE `userId`='$user_id' GROUP BY orderId";

$sel=$conn->query("SELECT orderDetails.orderId,menuList.recipeId, adminMessage ,recipeName,price FROM `menuList` INNER JOIN ordersTable ON ordersTable.recipeId=menuList.recipeId INNER JOIN orderDetails ON orderDetails.orderId=ordersTable.orderId WHERE orderDetails.orderId='$orderid'") or die("error in select ");
$r=0;
while($row=mysqli_fetch_array($sel))
              {
                $item['adminMessage']=$row['adminMessage'];
                $item['recipeName']=$row['recipeName'];
                $item['price']=$row['price'];
                $item['orderId']=$row['orderId'];
		
		$data[$r]=$item;
		$r++;
              }
             $Item['status']=true;
             $Item['data'] = $data;
             $json = json_encode($Item);
        echo $json;
?>
