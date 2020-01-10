<?php
/*Version :1.0.1
 * FileName:getDriverLoginDetails.php
 *Purpose: provides login details
 *Developers Involved:Vineetha
 */
	//connecting to server
  	include 'connect.php';
      //for logs
	$logfile= 'log/logfiles/log_' .date('d-M-Y') . '.log';     
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
		/*original code starts*/
	if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET")
	{
		//getting the details from driverLoginDetails api
		$mobile=$_REQUEST['mobile'];
		$action=$_REQUEST['action'];
		
	    if($action == 'driver'){		//get driver login credentials
			$sql="SELECT driverId, password FROM driverRegistration WHERE mobile='$mobile'";
		}
		else{				//get agent login credentials
			$sql="SELECT aid as driverId, password FROM agentRegistration WHERE mobileNumber='$mobile'";
		}
		
		$res=mysqli_query($con,$sql)  or (logToFile($logfile,"Getting the details gerDriverLoginDetails.php"));
		if($row=mysqli_fetch_assoc($res)){
			
			$result['did']=$row['driverId'];
			$result['password']=$row['password'];
			$result['status']='true';
			
		}else{
			$result['status']='false';
		}
	} 
	 else{

      	 $result['status'] = 'Fail';
      	logToFile($logfile,"DB connection failed - getDriverLoginDetails.php");

      }   
     header("Content-Type:application/json"); 
     echo json_encode($result);
     	/*original code ends*/
?>
