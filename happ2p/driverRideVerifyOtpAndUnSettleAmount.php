<?php
/*Version :1.0.1
 * FileName:driverRideVerifyOtpAndUnSettleAmount.php
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
	if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET")
		{
          //getting the details from getOtp api        
		 $paying_amount= $_REQUEST['paying_amount'];
		 $otp= $_REQUEST['otp'];
		 $payee= $_REQUEST['payee'];
		 $receiver= $_REQUEST['receiver'];
		 $ridesList=explode(",",$_REQUEST['rideList']);
		
		 
		 /*$paying_amount= '-401';
		 $otp= '2090';
		 $payee= 'DGEE8I';
		 $receiver= 'AM6XU6';		 
		 $ridesList= [132, 134];*/
		 $pAmount = abs($paying_amount);
		
		
         $status='1';
         $sql = "SELECT * FROM `settlementDetails` WHERE otp='$otp' AND receiverId='$receiver' AND payeeId='$payee'";
         $res=mysqli_query($con,$sql)  or (logToFile($logfile,"Getting the otp from payment details driverVerifyOtpSettle.php"));
		 if(mysqli_num_rows($res)>0)
		{
			$res1=mysqli_query($con,"update `settlementDetails` set `amountPaid`='$pAmount',`payeeId`='$payee',`receiverId`='$receiver',`status`='$status' 
					WHERE `otp`='$otp'and`payeeId`='$payee' and `receiverId`='$receiver'")  or (logToFile($logfile,"Update the payment details driverVerifyOtoSettle.php"));
			if($res1)
			{
				for($i=1; $i<=sizeof($ridesList); $i++){
					$res2=mysqli_query($con,"UPDATE `payment` INNER JOIN rideDetail ON payment.rideDetailId = rideDetail.rideDetailId SET `settlementStatus` = '1' 
					 WHERE date(`paymentDate`) = '$ridesList[$i]' AND driverId = '$payee'")
					or (logToFile($logfile,"Update the payment settlement status driverVerifyOtoSettle.php"));	
				}
				$result['status']="True";
			}
		}
		else{
			$result['status']="false";
		}     
   } 
   else{
	    $result['status'] = 'Fail';
      	logToFile($logfile,"DB connection failed driverVerifyOtpSettle.php");
      }  
     header("Content-Type:application/json"); 
     echo json_encode($result);
	 /*original code ends*/
?>

