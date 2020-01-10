<?php
//include 'DatabaseConfig1.php';
include 'DBconnection.php';
// Create connection

// $conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);
$json = file_get_contents('php://input'); 	
	$obj = json_decode($json,true);
	$email = $obj['email'];
	
	$password = $obj['password'];



	
	if($conn){	
        	
		$result= $conn->query("SELECT * FROM loginTable where `email`='$email' and `password`='$password'");

		// $r=0;
					while($row = mysqli_fetch_assoc($result)) {
					
					
					
					$item['email'] = $row['email'];
					
					$item['id'] = $row['id'];
					
					
						$result1['data']=$item;
        
				}
		
	

	//	$result1['data']=$;
		$result1['status']=true;

	}
	else{
	    
	    	$result1['status']=false;
	  echo json_encode($result1);
	  
	  
	}

	// $result1['data']=$data;
	// $result1['status']=true;
	
	 echo json_encode($result1);	
?>

