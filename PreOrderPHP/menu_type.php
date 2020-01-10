<?php
//include 'DatabaseConfig1.php';

include 'DBconnection.php';
header("Content-Type:application/json");

// Create connection
//$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);

if ($conn->connect_error) {
 
 die("Connection failed: " . $conn->connect_error);
} 
 
  $json = file_get_contents('php://input');
 
 // decoding the received JSON and store into $obj variable.
 $obj = json_decode($json,true);
 
 // Populate ID from JSON $obj array and store into $ID.
//  $email= $obj['email'];
//$id = $obj["Bahar Cafe"];
 //$id = 'Bahar Cafe';
 
$area_name = $obj["area_name"];
$hotel_name = $obj["hotel_name"];
$hotel_id = $obj["hotel_id"];

 // Creating SQL command to fetch all records from Table.
$sql = "SELECT * FROM menuTypes where hotelId='$hotel_id'";
$result = $conn->query($sql);
if ($result->num_rows >0) {
while($row[] = $result->fetch_array()) {
 $item = $row;
 $json = json_encode($item);
 }
 
} else {
 echo "No Results Found.";
}
echo $json;
//echo json_encode($obj);
$conn->close();
?>
