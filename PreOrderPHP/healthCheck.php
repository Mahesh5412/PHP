<?php

$db_host = '192.168.0.140';
$db_user = 'reactrest';
$db_password = 'reactrest'; 
$db_name ='reactrest';
$con =mysqli_connect($db_host, $db_user, $db_password,$db_name);
if($con){
	//echo 'Database is Connected Successfully';
	$result['status']='true';
}
else{
	//echo 'Database is not Connected';
	$result['status']='false';
}
 header("Content-Type:application/json"); 
     echo json_encode($result);

?>
