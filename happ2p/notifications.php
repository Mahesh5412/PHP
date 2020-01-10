<?php
include "connect.php";
// include 'mk.php';	
					
// 					$gcm = new GCM(); 
				
// 					$msg["title"]= "Hapdriver Message";
//     				$msg["body"] = "you got a ride from ";
//     				$msg["heading"] = "you got a message from t";
//     				$message = $msg;	
    				// print_r($message);

    				  // $notification = [
          //   'title' =>'test notification',
          //   'body' => 'body of message.',
          //   'icon' =>'myIcon', 
          //   'sound' => 'mySound'
        // ];						
					// $sql_push = "select * from driverDetail where 
					// // (driverId='$driverId')";
					// 		$res_push = mysql_query($sql_push);
			
					// 		if($rowd = mysql_fetch_array($res_push))
					// 			{
					// 				$device_token = "dyIzdwQIBS0:APA91bGHNFZrfhL1ytrgOJwNk_c3aBZPFYHEelRCVGo2sEMDFI3M9o25dJ2bc5C2UpR1AR6rYLE-URyOBaRmqw5s_ashzfcJmnnrnuM4reC1dmP8fm90XyquZqzMevaC2CR_zRkx8dlU"; 
					// 				if(!empty($device_token))
					// 					{
					// 	   					$result1 = $gcm->sendGCM($device_token, $message);
						   					
					//         				//print_r($device_token.$message);
					// 					}
								//}

$sql_push = "select `deviceToken` from `driverDetail` where 
					driverId='DFAWO8'";
							$res_push = mysqli_query($con,$sql_push);
			
							if($row = mysqli_fetch_assoc($res_push))
								{
									$device_token = $row["deviceToken"]; 
									echo $device_token;
									/*$device_token = "dyIzdwQIBS0:APA91bGHNFZrfhL1ytrgOJwNk_c3aBZPFYHEelRCVGo2sEMDFI3M9o25dJ2bc5C2UpR1AR6rYLE-URyOBaRmqw5s_ashzfcJmnnrnuM4reC1dmP8fm90XyquZqzMevaC2CR_zRkx8dlU";*/
									if(!empty($device_token))
										{
						   					$result1 = $gcm->sendGCM($device_token, $notification);
						   					
					        				//print_r($result1);
										}
								}

?>