<?php

/*Version :1.0.0
 * FileName:insertrefcode.php
 *Purpose: To store the refcode who refered
 *Developers Involved:mahesh
 */

	//connecting to server
	include'connect.php';
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
  
	/*original code starts*/
	if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET")
	{	//get data from android API
          $result['dbstatus'] = 'dbconnectionsuccess';
          $userName = $_REQUEST['userName'];
         // $userName = "jjj";
		  $email = $_REQUEST['email'];
		  $deviceId = $_REQUEST['deviceId'];
		  $mobile = $_REQUEST['mobileNumber'];
		  //$mobile = '9441323340';

		  /*$refcode = 'U5CBAR';
		  $mobile = '6300938965';*/
        if($email == "null"){
        		$update_sql = "UPDATE `userRegistration` SET  `fullName` = '$userName' WHERE mobile = '$mobile' AND deviceId = '$deviceId' AND verification = 'verified'";
							$update_sql_query = mysqli_query($con, $update_sql);
							if($update_sql_query){
								$result['refcodeStatus'] = "success";
							}else{
								$result['refcodeStatus'] = "fail";

							}
        }else{
		//$sql="select * from `userRegistration` WHERE referralCode='$refcode'";
				$sql = "select * from `userRegistration` WHERE `mobile` = '$mobile'";
		$sql_res=mysqli_query($con,$sql);
		if(mysqli_num_rows($sql_res) > 0){

							$update_sql = "UPDATE `userRegistration` SET `email` = '$email', `fullName` = '$userName' 
											WHERE mobile = '$mobile' AND deviceId = '$deviceId' AND verification = 'verified'";
							$update_sql_query = mysqli_query($con, $update_sql);
							if($update_sql_query){
								$result['refcodeStatus'] = "success";
							}else{
								$result['refcodeStatus'] = "fail";

							}
						}/*else{
							$result['refcodeStatus'] = "notmatched";
						}*/
					}
		
	}else{// if db connection failed
        $result['dbstatus'] = 'fail';    //Send status as False
        logToFile($logfile,"DB connection failed insertrefcode.php");
	}
	 header("Content-Type:application/json"); 
     echo json_encode($result);

?>
