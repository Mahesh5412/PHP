<?php

/*Version :1.0.0
 * FileName:driverStatus.php
 *Purpose: checking whether the driver is offline or online in splash
 *Developers Involved:Mahesh
 */

	//connecting to server
	include 'connect.php';
	 //for logs
	$logfile= 'log/log_' .date('d-M-Y') . '.log'; 
	header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
	/*original code starts*/
	if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET")
	{	  //get adta form android API                                                                          
		  $driverId = $_REQUEST['driverId']; 
		    //$driverId = 'D464576';
        $sql = "select * from `driverDetail` where `driverId` = '$driverId'";
        $res = mysqli_query($con,$sql)  or (logToFile($logfile,"Get driverStatus from driverDetail driverStatus.php"));  //Execute query 
        if($row=mysqli_fetch_assoc($res))
        {	             
                    $result['driverStatus'] = $row['driverStatus'];
                    $driverStatus = $row['driverStatus'];
                     $result['status'] = 'True';		//set status as true
                    if($driverStatus == 'online' || $driverStatus == 'riding'){		//When driver status is online or riding

                    /* $sql1 = "select * from `rideDetail` INNER JOIN `userRegistration` ON rideDetail.userId=userRegistration.userId INNER JOIN payment ON 
							rideDetail.rideDetailId=payment.rideDetailId where rideDetail.driverId = '$driverId' AND (rideDetail.rideStatus = 'accepted' OR 
							rideDetail.rideStatus = 'started' OR rideDetail.rideStatus = 'trip started' OR rideDetail.rideStatus = 'end')";*/

                      $sql1="SELECT * from `rideDetail` INNER JOIN `userRegistration` ON rideDetail.userId=userRegistration.userId INNER JOIN payment ON 
              rideDetail.rideDetailId=payment.rideDetailId where rideDetail.driverId = '$driverId' AND (rideDetail.rideStatus = 'accepted' OR 
              rideDetail.rideStatus = 'started' OR rideDetail.rideStatus = 'trip started' OR (rideDetail.rideStatus = 'end' AND payment.paymentStatus='pending'))";

                       $res1 = mysqli_query($con,$sql1)  or (logToFile($logfile,"Get ridedetails from rideDetail driverStatus.php"));  //Execute query 
                       if($row1= mysqli_fetch_assoc($res1)){		//fetch driver information
                       	$result['rideDetailId'] = $row1['rideDetailId'];
                       	$result['rideId'] = $row1['rideId'];
                       	$result['userId'] = $row1['userId'];
                       	$result['psourceLatitude'] = $row1['psourceLatitude'];
                       	$result['psourceLongitude'] = $row1['psourceLongitude'];
                       	$result['pdestiLatitude'] = $row1['pdestiLatitude'];
                       	$result['pdestiLongitude'] = $row1['pdestiLongitude'];
                       	$result['rideStatus'] = $row1['rideStatus'];
                       	$result['riderPhoneNumber'] = $row1['mobile'];
						$result['riderName'] = $row1['fullName'];
                       	$result['statusInfo'] = 'statusInfo';
                        $rideStatus = $row1['rideStatus'];
                        $rideId = $row1['rideId'];
                      
                      if($rideStatus == 'end'){   	//when ride status  is end  
                        $sql6 = "Update rideDetail set rideStatus='$rideStatus' , `rideEndTime` = '$timeStamp' WHERE rideId='$rideId'";//Update rideStatus in db
                        //$sql11 = "Update `driverDetail` set `driverStatus` = 'offline' WHERE `driverId` ='$driverId'";
                        //$driverStatus = mysqli_query($con, $sql11);
                       $res6=mysqli_query($con, $sql6);    //Execute query

                       if($res6){
                            $sql12 = "SELECT `totalpayment`, `paymentType`, `paymentStatus` FROM `payment` WHERE rideDetailId =(SELECT rideDetail.rideDetailId FROM 
                            `rideDetail` INNER JOIN `payment` ON rideDetail.rideDetailId = payment.rideDetailId WHERE rideDetail.rideId = '$rideId')";
                            $res12 = mysqli_query($con, $sql12) or (logToFile($logfile,"Get payment details driverStatus.php"));
                            if($r12 = mysqli_fetch_assoc($res12)){
                              if($r12['paymentStatus'] == 'pending'){
                                  $result['paymentMode'] = $r12['paymentType'];
                                  $result['totalAmount'] = $r12['totalpayment'];
                                  $result['amountStatus'] = 'totalAmount';
                                }else{
                                  $result['totalAmount'] = $r12['totalpayment'];
                                  $result['amountStatus'] = 'alreadyPaid';
                                }
                            }else{
                                   $result['amountStatus'] = 'False';
                                  }
                        }else{
                             $result['rideAcceptstatus'] = 'False';  //set status as false
                             }
                          }
                      	
                       }else{
                       	$result['statusInfo'] = 'False';
                       }

                    }

                }else{//If query execution failed

						$result['status'] = 'False';	//set status as false
                }

	}else{		//If database connection failed
  			$result['status'] = 'False';		//Send status as False
  			logToFile($logfile,"DB connection failed driverStatus.php");
	}
	 header("Content-Type:application/json"); 
     echo json_encode($result);
/*original code ends*/
?>
