<?php
/*Version :1.0.0
 *FileName:RestaurantList.php
 *Purpose: Getting the list of reataurant list related to the specific area.
 *Developers Involved:Raju,TulasiRao
 */
//connecting to server
include 'DBconnection.php';
//including log creation file 
include("log/log.php"); 
//creataing file
$logfile= 'log/log_' .date('d-M-Y') . '.log';
//logToFile($logfile,"Started logging first",1);
 // Getting the received JSON into $json variable.
 $json = file_get_contents('php://input');
 // decoding the received JSON and store into $obj variable.
 $obj = json_decode($json,true);
 // getting id from Rest_List.js file in array and store into $ID.
$ID = $obj['id'];
//Fetching the selected record.
$CheckSQL = "SELECT * FROM hotelList WHERE `areaName`='$ID'";
$result = $conn->query($CheckSQL)  or (logToFile($logfile,"query not seleted in RestaurantList.php"+$ID+"id",1));
if ($result->num_rows >0) {
        while($row[] = $result->fetch_assoc()) {
                $Item = $row;
                $json = json_encode($Item);
        }
}
else {
    $Item['status'] = "fail";
    $json = json_encode($Item);
}
echo $json;
$conn->close();
?>
