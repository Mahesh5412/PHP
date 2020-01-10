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
$id = $obj['user_id'];
// $id = '3';
 // Creating SQL command to fetch all records from Table.
$sql = "SELECT * FROM loginTable where mobileNumber='$id'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
 while($row = $result->fetch_assoc()) {
 $item['userId'] = $row['userId'];
 $item['name'] = $row['name'];
 $item['email'] = $row['email'];
 $item['mobileNumber'] = $row['mobileNumber'];
 $item['password'] = $row['password'];
 $item['otp'] = $row['otp'];
 $item['imageLocation'] = $row['imageLocation'];
 $item['createdDate'] = $row['createdDate'];
 $item['modifiedDate'] = $row['modifiedDate'];
 $json = json_encode($item);
 }
} else {
 echo "No Results Found.";
}
echo $json;
//echo json_encode($obj);
$conn->close();
?>
