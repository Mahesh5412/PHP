<?php
	include'connect.php';
	
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
	/*original code starts*/
	if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET"){
		$sourceLat = '17.4420792';
		$sourceLon = '78.3552187';
		$sql3 = "SELECT  driverId, 111.111 * DEGREES(ACOS(LEAST(COS(RADIANS(17.4420792)) * COS(RADIANS(driverLatitude)) * COS(RADIANS(78.3552187 - driverLongitude))
				+ SIN(RADIANS(17.4420792)) * SIN(RADIANS(driverLatitude)), 1.0))) AS distance_in_km FROM driverDetail WHERE driverStatus='online'
				HAVING distance_in_km < 1 ORDER BY `distance_in_km` ASC LIMIT 1";
		$res3 = mysqli_query($con, $sql3);
		if(mysqli_num_rows($res3) > 0){
			if($r1 = mysqli_fetch_assoc($res3)){
				echo $r1['driverId'];
			}
		}
	}
?>
