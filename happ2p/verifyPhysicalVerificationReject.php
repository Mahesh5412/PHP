<?php
/*FileName:verifyPhysicalVerificationReject.php
 *Purpose:To update accepted documents
 *Developers Involved: Srikanth
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
			//Getting the details from  reject api
			$agentId = $_REQUEST['agentId'];
			$driverId = $_REQUEST['driverId'];
			$rejectReason = $_REQUEST['reason']; 
			$docType = $_REQUEST['docType']; 
					//update rejected documents
            $sql = "UPDATE documentDetail SET pVerificationStatus = 'reject',rejectReason = '$rejectReason' WHERE id = '$driverId' AND verifier = '$agentId'
					and documentName = '$docType'"; 
            $res = mysqli_query($con,$sql) or (logToFile($logfile,"update the physicalverification reject verifyPhysicalVerificationReject.php"));
            if($res)
            {
				$result['rejectStatus'] = 'True';
 			}else{
                  $result['rejectStatus'] = 'False';
            }
                          
     }
     else{
			$result['status'] = 'false';
			logToFile($logfile,"DB connection failed verifyPhysicalVerificationReject.php");
    }
    header("Content-Type:application/json");
    echo json_encode($result);
    /*original code ends*/
   ?>
