<?php

/*Version :1.0.0
 *FileName:Location.php
 *Purpose: Getting the area names basaed on user location information
 *Developers Involved:TulasiRao.
 */

//connction to server
include 'DBconnection.php';
//including log creation file 
include("log/log.php"); 
//creataing file
$logfile= 'log/log_' .date('d-M-Y') . '.log';
//logToFile($logfile,"Started logging first",1);
header("Content-Type:application/json");
$json = file_get_contents('php://input');
// decoding the received JSON and store into $obj variable
$obj = json_decode($json,true);
// getting lat,lan from Home.js file in array and store into $lat and $lon
$lat = $obj['lat'];
$lon = $obj['lon'];
// executing SQL command to fetch all records from Table.
$sql = "SELECT *,SQRT(
    POW(111.2 * (lat - $lat), 2) +
    POW(111.2 * ($lon - lng) * COS($lat / 57.3), 2)) AS distance
FROM hotelList GROUP BY areaName HAVING distance < 25 ORDER BY distance";
//$result = $conn->query($sql);
 $res = mysqli_query($conn,$sql)  or (logToFile($logfile,"query not seleted in Location.php"+$lat+'lat'+$lon+'lon',1));
if (mysqli_num_rows($res) > 0) {
        $r=0;
        //fetching the details in array format
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
