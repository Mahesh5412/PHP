<?php
/*Version :1.0.0
 *FileName:OrderHistory.php
 *Purpose: Getting the user all order history 
 *Developers Involved:TulasiRao,Raju
 */
   //connecting to server
   include 'DBconnection.php';
    $json = file_get_contents('php://input');
    // decoding the received variable and store into $obj variable.
    $obj = json_decode($json,true);
    //getting result from Yourride.js file and store into $user_id
    $user_id=$obj['result'];

//Creating SQL command to fetch all records from Table

$sql = mysqli_query($conn,"SELECT ot.orderId, GROUP_CONCAT(ml.recipeName) AS recipeNames, SUM(ot.totalAmount) AS totalSum,
date(ot.orderedDate) AS date, od.status FROM `ordersTable` AS ot INNER JOIN menuList AS ml
 ON ot.recipeId=ml.recipeId INNER JOIN orderDetails as od ON od.orderId= ot.orderId
  WHERE userId='$user_id' GROUP BY orderId") or 
	(logToFile($logfile,"query not selected in Auth.php"+$number+"number",1));
	if(mysqli_num_rows($sql) > 0) {
         while($row[] = $sql->fetch_array()) {
                  $item = $row; 
                  $json = json_encode($item);
            }
 } 
 else {
   $item = "fail"; 
    $json = json_encode($item);
 }
 echo $json;
$conn->close();
?>
