
//File Name : 


<?php
include 'DBconnection.php';

$json = file_get_contents('php://input');
$obj = json_decode($json,true);
 
//$itemId = $obj['itemId'];
 
$itemId = '3';
$sql = "select * from menuList where itemId='$itemId'";
 
$result = $conn->query($sql);
 
if ($result->num_rows >0) {
 
 $r=0;
 while($row = $result->fetch_array()) {
 
$tem['itemId'] = $row['itemId'];
$tem['recipeId'] = $row['recipeId'];
$tem['recipeName'] = $row['recipeName'];
$tem['type'] = $row['type'];
$tem['quantityId'] = $row['quantityId'];
$tem['description'] = $row['description'];
$tem['price'] = $row['price'];
$tem['quantity'] = 1;
 
$data[$r]=$tem;

 $r++;
 
 }

 $json = json_encode($data);
}
 else {
 echo "No Results Found.";
}
 echo $json;
$conn->close();
?>
