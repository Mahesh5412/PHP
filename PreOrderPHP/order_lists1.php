<?php
 
//include 'DatabaseConfig1.php';
include 'DBconnection.php';
// Create connection
//$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);
    $json = file_get_contents('php://input'); 	
	$obj = json_decode($json,true);
$user_id=$obj['result'];
 //$user_id='109';
if ($conn->connect_error) {
 
 die("Connection failed: " . $conn->connect_error);
} 
 
//Creating SQL command to fetch all records from Table.

//$sql = "SELECT * FROM ordersTable WHERE userId='$user_id' ";
//$sql="SELECT ot.orderId, GROUP_CONCAT(ml.recipeName) AS recipeNames FROM `ordersTable` AS ot INNER JOIN menuList AS ml ON ot.recipeId=ml.recipeId WHERE userId='$user_id' GROUP BY orderId ";


//$sql="SELECT ot.orderId, GROUP_CONCAT(ml.recipeName) AS recipeNames, SUM(ot.totalAmount) as totalSum, date(ot.orderedDate) as date, DATE_FORMAT(ot.orderedDate, "%H:%i") as time FROM `ordersTable` AS ot INNER JOIN menuList AS ml ON ot.recipeId=ml.recipeId WHERE userId='$user_id' GROUP BY orderId";


//$sql = "SELECT ot.orderId, GROUP_CONCAT(ml.recipeName) AS recipeNames, SUM(ot.totalAmount) AS totalSum, date(ot.orderedDate) AS date, DATE_FORMAT(ot.orderedDate,"%H:%i") AS time FROM `ordersTable` AS ot INNER JOIN menuList AS ml ON ot.recipeId=ml.recipeId WHERE `userId`='$user_id' GROUP BY orderId";

//SELECT ot.orderId, GROUP_CONCAT(ml.recipeName) AS recipeNames, SUM(ot.totalAmount) AS totalSum, date(ot.orderedDate) AS date, od.status FROM `ordersTable` AS ot INNER JOIN menuList AS ml ON ot.recipeId=ml.recipeId INNER JOIN orderDetails as od ON od.orderId= ot.orderId WHERE userId='$user_id' GROUP BY orderId
$sql = "SELECT ot.orderId, GROUP_CONCAT(ml.recipeName) AS recipeNames, SUM(ot.totalAmount) AS totalSum, date(ot.orderedDate) AS date, od.status FROM `ordersTable` AS ot INNER JOIN menuList AS ml ON ot.recipeId=ml.recipeId INNER JOIN orderDetails as od ON od.orderId= ot.orderId WHERE userId='$user_id' GROUP BY orderId";


$result = $conn->query($sql);
//  ehco "adcd";
// print_r($result->num_rows);


if ($result->num_rows > 0) {
 
 
 while($row[] = $result->fetch_array()) {
 
 $item = $row;
 
 $json = json_encode($item);
 
}
 
 } 
 else {
    
    $json = json_encode($item);
  //echo "No Results Found.";

 }
 echo $json;
//echo json_encode($obj);
$conn->close();
?>
