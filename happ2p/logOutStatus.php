<?php
/*Version:1.0.0
 * FileName:logOutStatus.php
 *Purpose: To set login and logout status of user
 *Developers Involved: Vineetha
 */
  	include 'connect.php';
    
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
	
	if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET")
		{
          $result['logoutDbConnection'] = 'connected';
		  $userName=$_REQUEST['userName'];
		  $action=$_REQUEST['action'];
		  $offlineString = 'offline';
		  
		 // $userName='D464576';
		 // $action='logout';
		 
           if($action=='logout'){  
			   $sql1= "SELECT * FROM `driverRegistration` WHERE driverId='$userName'"; 
			   $res1= mysqli_query($con, $sql1)  or (logToFile($logfile,"Get driver details - logOutStatus.php"));
			   $count=mysqli_num_rows($res1);
			     
			   if($count>0){
				   if($row1 = mysqli_fetch_assoc($res1)){
					   $vehicleId = $row1['vehicleId'];
					   $sql2 = "UPDATE `vehicleDetail` set activeStatus = '$offlineString' WHERE vehicleId='$vehicleId'";
					   $res2= mysqli_query($con, $sql2)  or (logToFile($logfile,"Update active status in vehicle detail - logOutStatus.php"));
					   if($res2){
						   $result['logoutStatus'] = 'logout success';
					   }else{
						   $result['logoutStatus'] = 'logout failed';
					   }
				   }
			   }else{
				   $result['logoutStatus'] = 'logout failed';
			   } 	
			}else{
			}       	  
      }  
       else{
			$result['logoutDbConnection'] = 'failed';
			logToFile($logfile,"DB connection failed - logOutStatus.php");
      }  
     header("Content-Type:application/json"); 
     echo json_encode($result);
    ?>
