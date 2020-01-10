<?php
/*Version :1.0.0
 * FileName:driversUnsettleList.php
 *Purpose:to ger drivers unsettled amount list
 *Developers Involved: vineetha
*/
  	include 'connect.php';		//connect to server	
      //for logs
	$logfile= 'log/logfiles/log_' .date('d-M-Y') . '.log';  
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
	
	if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET")
		{	//get dat from android API
			$driverId = $_REQUEST['driverId'];
			//$driverId = 'D464576';
				//get unsettled amount of a driver
		    $sql = "SELECT * FROM payment INNER JOIN rideDetail ON rideDetail.rideDetailId=payment.rideDetailId WHERE rideDetail.driverId='$dirverId' AND 
					payment.settlementstatus='0'";	
            $res = mysqli_query($con,$sql) or (logToFile($logfile,"Getting the location details destinationLocations.php"));
             
            while($row = mysqli_fetch_assoc($res))
			{
				$temp['rideId']    = $row['rideDetailId'];
				$temp['driverId'] = $row['driverId'];
				if($row['paymentType'] == "cash"){
					$cash = $row['totalpayment'];
				}else{
					$cash = 0;
				}
			
				$totalFare = $row['totalpayment'];
				$driverAmount = (80 * $totalFare)/100;
				$partialAmount = $cash - $driverAmount;	
				$temp['cash'] = round($partialAmount);
				$temp['cash'] = round($partialAmount);
                $data[]=$temp;	
			}
			     $result['status'] = 'True';
				  $result['data'] = $data;	
      }else{		//if db connection failed
      	 $result['status'] = 'Fail';
      	 logToFile($logfile,"DB connection failed - driversUnsettleList.php");
      }  
     header("Content-Type:application/json"); 
     echo json_encode($result);
    ?>
