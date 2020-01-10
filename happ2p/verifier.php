<?php
/*FileName:verifier.php
 *Purpose:get driver documents to verify
 *Developers Involved:Srikanth
 */
 //connecting to server
 include 'connect.php';
     //for logs
	//include("log/log.php");
	$logfile= 'log/logfiles/log_' .date('d-M-Y') . '.log';
    
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    /*original code starts*/
    if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET")
        {
			//Getting the details from  rc1 api
           $agentId=$_REQUEST['agentId'];
           $driverId=$_REQUEST['driverId'];
           
           //static data
        //  $agentId = 'A1';
        //  $driverId = 'DSYH6R';
                  //get data from documentDetail table
            $sql = "SELECT documentPath, documentName FROM documentDetail WHERE verifier = '$agentId' AND id = '$driverId'";  
            $res = mysqli_query($con,$sql) or (logToFile($logfile,"Getting the details from driverRegistration and physicalVerification verifier.php"));

			if(mysqli_num_rows($res)>0){
				while($row = mysqli_fetch_assoc($res))
                 {
						$documentName=$row['documentName'];
					 
						if($documentName =='rc'){
							$result['rc']=$row['documentPath'];
							}else if($documentName == 'license'){
								$result['license']=$row['documentPath'];
							}else if($documentName =='insurance'){
								$result['insurance']=$row['documentPath'];
							}else if($documentName == 'pollution'){
								$result['pollution']=$row['documentPath'];
							}else if($documentName == 'pan'){
								$result['pan']=$row['documentPath'];
							}else if($documentName == 'aadhar'){
								$result['aadhar']=$row['documentPath'];
							}else if($documentName == 'photo'){
								$result['photo']=$row['documentPath'];
							}
							 
                 }
                 $result['status'] = 'True'; 
			 }else{
				 $result['status'] = 'False';
			 }  					                                  
     }
      else
    {
			$result['status'] = 'false';
            logToFile($logfile,"DB connection failed - verifier.php");
    }
    header("Content-Type:application/json");
    echo json_encode($result);
    	/*original code ends*/
   ?>
	
