<?php
/*FileName:verifierDriversList.php
 *Purpose:to get drivers list to verify documents
 *Developers Involved:Srikanth
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
        //Getting the details from  driverList api
         $agentId=$_REQUEST['agentId'];
         
       //  $agentId = 'A1';
					//get dirvers list
            $sql="SELECT DISTINCT driverRegistration.driverId FROM driverRegistration INNER JOIN documentDetail ON driverRegistration.driverId=documentDetail.id
				WHERE driverRegistration.verifiedStatus='e-verification completed' AND documentDetail.verifier='$agentId'";
            $res = mysqli_query($con,$sql) or (logToFile($logfile,"verification process verifierDriversList.php"));
			if(mysqli_num_rows($res)>0){
				while($row = mysqli_fetch_assoc($res))
                 {
						$temp['driverId'] = $row['driverId'];
						$data[]=$temp;		 
                 }
                 $result['data']=$data;
					$result['status'] = 'True';
			 }
              else{
					 $result['status'] = 'false';
			  }                                     
     }
      else
    {
			$result['status'] = 'false';
            logToFile($logfile,"DB connection failed verifierDriversList.php");
    }
    header("Content-Type:application/json");
    echo json_encode($result);
    /*original code ends here*/	
   ?>
	
