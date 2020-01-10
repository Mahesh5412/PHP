<?php
/*Version :1.0.0
 *FileName:MenuType.php
 *Purpose: Getting the Menu types list of secific hotel.
 *Developers Involved:Raju.
 */
 //connectiong to servet
include 'DBconnection.php';
header("Content-Type:application/json");
//including log creation file 
include("log/log.php"); 
//creataing file
$logfile= 'log/log_' .date('d-M-Y') . '.log'; 
  $json = file_get_contents('php://input');
 // decoding the received variable and store into $obj variable.
 $obj = json_decode($json,true);
 // getting area_name,hotel_name and hote
 $hotel_id= $obj["hotel_id"];
$area_name = $obj["area_name"];
$hotel_name = $obj["hotel_name"];
$hotel_id = $obj["hotel_id"];
 // Creating SQL command to fetch all records from Table.
$sql = "SELECT * FROM menuTypes where hotelId='$hotel_id'";
$result = $conn->query($sql)  or (logToFile($logfile,"query not seleted in MenuType.php"
+$area_name+"area_name"+$hotel_name+"hotel_name"+$hotel_id+"hotel_id",1));
    if ($result->num_rows >0) {
        while($row[] = $result->fetch_array()) {
            $item = $row;
            $json = json_encode($item);
       }
    
    } 
    else {
      $item['status'] = "fail";
      $json = json_encode($item);
    }
echo $json;
//echo json_encode($obj);
$conn->close();
?>
