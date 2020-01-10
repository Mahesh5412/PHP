<?php
 
//include 'DatabaseConfig1.php';
include 'DBconnection.php';
// Create connection
//$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);
 

    $json = file_get_contents('php://input'); 	
	$obj = json_decode($json,true);
	
	$email = $obj['email'];
	
	$password = $obj['password'];

// $email ="abc@gmail.com";
	
// 	$password = "111";
	
	if($conn){	
	
	$result= $conn->query("SELECT * FROM loginTable where email='$email' and password='$password'");

		if($result->num_rows!=0){
		    
		     $r=0;
					while($row = mysqli_fetch_array($result)) {	
					$item['email'] = $row['email'];
					$item['id'] = $row['userId'];
					$item['username'] = $row['name'];
					$item['profile_image'] = $row['imageLocation'];
				}
				
		$result1['data']=$item;
		$result1['status']="true";
		
		echo json_encode($result1);	
		    
		}
		else{		
		    	$result1['status']="false";
		echo json_encode($result1);				
		}
	}	
	else{
	  		
		echo json_encode($result1);				
	}
	
?>
