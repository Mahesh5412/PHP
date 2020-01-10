<?php
/*Version:1.0.0
 * FileName:healthCheck.php
 *Purpose: To check the database connection
 *Developers Involved: Vineetha
 */
	$db_host = '192.168.0.150';			//Database host
	$db_user = 'hapride';				//Database username
	$db_password = 'Novi@123'; 			//Database password
	$db_name ='happ2p';					//Database name
	
	$con =mysqli_connect($db_host, $db_user, $db_password,$db_name);		//To establish connection

	if($con){   //If connection established successfully
		$result['status']='true';			//set status as true
	}
	else{		//If connection establishment fails
		$result['status']='false';			//set status as false
	}

	header("Content-Type:application/json"); 
    echo json_encode($result);
?>
