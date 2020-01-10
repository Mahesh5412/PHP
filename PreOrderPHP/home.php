<?php
//include 'DatabaseConfig1.php';
include 'DBconnection.php';
header("Content-Type:application/json");

// Create connection
//$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);

if ($conn->connect_error) {
 
 die("Connection failed: " . $conn->connect_error);
} 
 
// Creating SQL command to fetch all records from Table.
$sql = "SELECT * FROM hotelList where `city`='hyderabad'";
 
//$result = $conn->query($sql);
 
 
 $res = mysqli_query($conn,$sql);
 
if (mysqli_num_rows($res) > 0) {
 
 $r=0;
 while($row = mysqli_fetch_array($res)) {
 

 
  $item['id'] = $row['areaName'];
 
 $item['name'] = $row['areaName'];
 
 
 $data[$r] = $item;

 
 $r++;
 }
 

 $result['data'] = $data;
 
} else {
 echo "No Results Found.";
}



$json = json_encode($result);
 echo $json;
$conn->close();
?>
