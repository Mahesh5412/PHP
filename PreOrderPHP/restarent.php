<?php
 
//include 'DatabaseConfig.php';
include 'DBconnection.php';
// Create connection
//$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);
 
 // Getting the received JSON into $json variable.
 $json = file_get_contents('php://input');
 
 // decoding the received JSON and store into $obj variable.
 $obj = json_decode($json,true);
 
 // Populate ID from JSON $obj array and store into $ID.
$ID = $obj['name'];

//$ID = 'mehadhipatnam';
 
//Fetching the selected record.
$CheckSQL = "SELECT * FROM imageupload WHERE image_tag='$ID'";
//$CheckSQL="SELECT restarent_name FROM imageupload group by restarent_name having count(*) >= 2";
 
$result = $conn->query($CheckSQL);
 
//if ($result->num_rows >0) {
 
 while($row = $result->fetch_assoc()) {
 
 $Item = $row;
 
 $json = json_encode($Item);
 
 }
 
// }else {
 
//  echo "No Results Found.";
 
// }
 
echo $json;
 
$conn->close();
?>
