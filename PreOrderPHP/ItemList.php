<?php
/*Version :1.0.0
 *FileName:ItemList.php
 *Purpose: Getting the list of all item selected menu type .
 *Developers Involved:Raju,TulasiRao
 */
 //connecting to server
include 'DBconnection.php';
//including log creation file 
include("log/log.php"); 
//creataing file
$logfile= 'log/log_' .date('d-M-Y') . '.log';
//logToFile($logfile,"Started logging first",1);
$json = file_get_contents('php://input');
// decoding the received JSON and store into $obj variable.
$obj = json_decode($json,true);
//getting itemId from Item.js file
$itemId = $obj['itemId'];
//$itemId = '3';
// executing SQL command  for slecting menuList based on itemId
$sql = "select * from menuList where itemId='$itemId'";
$result = $conn->query($sql)  or (logToFile($logfile,"query not seleted in 
ItemList.php"+$itemId+'itemId',1));
if ($result->num_rows >0) {
        $r=0;
        //fetching the menuList datails from particular itemId in array format
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
    $data['status'] = "fail";
    $json = json_encode($data);
}
 echo $json;
$conn->close();
?>
