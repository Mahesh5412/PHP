<?php

/*Version :1.0.0
 *FileName:ProfileInfo.php.
 *Purpose: Getting the user profile detailes .
 *Developers Involved:Raju.
 */
//connecting to server
include 'DBconnection.php';
header("Content-Type:application/json");
//including log creation file 
include("log/log.php"); 
//creataing file
$logfile= 'log/log_' .date('d-M-Y') . '.log';
$json = file_get_contents('php://input');
 // decoding the received variable and store into $obj variable.
 $obj = json_decode($json,true);
// getting user_id from drawerContentComponent.js file  and store into $id
$id = $obj['user_id'];
 // Creating SQL command to fetch all records from Table.
$sql = "SELECT * FROM loginTable where mobileNumber='$id'";
$result = $conn->query($sql) or (logToFile($logfile,"query not seleted in ProfileInfo.php"+$id+"user_id",1));

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
    } 
    else 
    {
      $item['status'] = "fail";
      $json = json_encode($item);
    }
    echo $json;
    //echo json_encode($obj);
    $conn->close();
?>
