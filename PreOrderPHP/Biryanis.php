<?php

//include 'DatabaseConfig1.php';
include 'DBconnection.php';

// Create connection
//$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);

 // Getting the received JSON into $json variable.
 $json = file_get_contents('php://input');

 // decoding the received JSON and store into $obj variable.
 $obj = json_decode($json,true);


$area_name=$obj['area_name'];
$hotel_name=$obj['hotel_name'];


// $area_name='lingampa';
// $hotel_name='Paradise';

$CheckSQL = "SELECT * FROM item_list WHERE item_type='biryani' AND area_name='$area_name' AND res_name='$hotel_name'";
//$CheckSQL="SELECT restarent_name FROM imageupload group by restarent_name having count(*) >= 2";

$result = $conn->query($CheckSQL);

//if ($result->num_rows >0) {

 while($row[] = $result->fetch_array()) {

 $Item = $row;
 $json = json_encode($Item);

 }

// }else {

//  echo "No Results Found.";

// }
echo $json;

$conn->close();
?>

