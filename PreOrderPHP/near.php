<?php
//include 'DatabaseConfig1.php';
include 'DBconnection.php';
header("Content-Type:application/json");
$json = file_get_contents('php://input');
 
// decoding the received JSON and store into $obj variable.
$obj = json_decode($json,true);

// Populate ID from JSON $obj array and store into $ID.
$lat = $obj['lat'];
$lon = $obj['lon'];


// $lat = '17.4375';
// $lon = '78.4482';

// 17.4375
// 78.4482
// Creating SQL command to fetch all records from Table.

// SELECT *,SQRT(
//     POW(111.2 * (lat - $lat), 2) +
//     POW(111.2 * (78.4482 - $lon) * COS($lat / 57.3), 2)) AS distance
// FROM hotelList HAVING distance < 25 ORDER BY distance

$sql = "SELECT *,SQRT(
    POW(111.2 * (lat - $lat), 2) +
    POW(111.2 * ($lon - lng) * COS($lat / 57.3), 2)) AS distance
FROM hotelList GROUP BY areaName HAVING distance < 25 ORDER BY distance";
 
//$result = $conn->query($sql);
 
 $res = mysqli_query($conn,$sql);
 
if (mysqli_num_rows($res) > 0) {
 
 $r=0;
 while($row = mysqli_fetch_array($res)) {
 
  $item['id'] = $row['areaName'];
 $item['name'] = $row['areaName'];
 $item['hotelId'] = $row['hotelId'];
 $item['distance'] = $row['distance'];
 $data[$r] = $item;
 $r++;

 }
 
 $result['status'] = "true";
 $result['data'] = $data;
 
}
 else {
    $result['status'] = "false";
    $result['data'] = "No results found";
   // echo "No Results Found.";
}

$json = json_encode($result);
 echo $json;
$conn->close();
?>
