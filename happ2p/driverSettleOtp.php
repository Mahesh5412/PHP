<?php
/*Version :1.0.0
 * FileName:driverSettleOtp.php
 *Purpose:driver settlement and verify otp
 *Developers Involved:Vineetha
 */
	//connecting to server
 include 'connect.php';
     //for logs
	$logfile= 'log/logfiles/log_' .date('d-M-Y') . '.log';
    
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    /*original code starts*/
    if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET"){
		//getting the details from getOtp api
		$d_id = $_REQUEST['d_id'];
		$a_id= $_REQUEST['a_id'];
		$otp = $_REQUEST['otp'];
		
		$query = "INSERT into settlementDetails (`receiverId`,`payeeId`,`otp`) VALUES ('$a_id','$d_id','$otp')";
		$result1 = mysqli_query($con,$query) or (logToFile($logfile,"Insert settlement details - driverSettleOtp.php"));
		if($result1){
			 $result['status'] = 'True';    
		
		}else
		{
			$result['status'] = 'False';    
		}
	
                              
     }//if loop ends here
     else{
		 $result['status'] = 'False';
		 logToFile($logfile,"DB conne tion failed - driverSettleOtp.php");
         }
    header("Content-Type:application/json");
    echo json_encode($result);
    /*original code endss*/
   ?>
