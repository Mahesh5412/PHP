<?php
/*FileName:passengerPaymentAck.php
 *Purpose:Acknowledge of payment by user to check payment is done by user or not
 *Developers Involved:Vineetha
 */
	//connecting to server
  	include 'connect.php';
  	include 'mailer.php';
    //for logs
	$logfile= 'log/logfiles/log_' .date('d-M-Y') . '.log'; 
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
	/*original code starts*/
	if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET")
	{
		//getting the details from payment api
		   $transactionid = $_REQUEST['transactionId'];
		   $paymentId = $_REQUEST['paymentId'];
		   $value = $_REQUEST['value'];
		   $cancellationChargesPaymentId = $_REQUEST['cancellationChargesPaymentId'];
			$cancellationCharges = $_REQUEST['cancellationCharges'];
			//Update the status of payment
			$mailes = new Mails(); // creating object for mails

            /*gettting soure, destination name from latitudes, longitudes starts here */
		function get_sourcelocation($lat,$lng){ //source location
                 $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&key=AIzaSyATaE4rWC3YNt0hd0x9TgkOJCN9RfzV6mE&sensor=true";
                  $sourceData = @file_get_contents($url);
                  $jsondata = json_decode($sourceData,true);
                  $sourceData = array();
                 foreach($jsondata['results']['3']['address_components'] as $element){
                     $sourceData[ implode(' ',$element['types']) ] = $element['long_name'];
                                 }
                  return sourceLocation(json_encode($sourceData['neighborhood political']),json_encode($sourceData['political sublocality sublocality_level_2']),json_encode($sourceData['political sublocality sublocality_level_1']), json_encode($sourceData['locality political']));  

                         }
                      function sourceLocation($local,$religion,$sublocality,$locality){ //converting json data
    	               	return trim($local, '"').",".trim($religion, '"').",".trim($sublocality, '"').",".trim($locality, '"');
    	                  }

        function get_destinationlocation($lat,$lng){// destination location
                 $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&key=AIzaSyATaE4rWC3YNt0hd0x9TgkOJCN9RfzV6mE&sensor=true";
                  $destinationData = @file_get_contents($url);
                  $jsondata = json_decode($destinationData,true);
                  $destinationData = array();
                 foreach($jsondata['results']['3']['address_components'] as $element){
                     $destinationData[ implode(' ',$element['types']) ] = $element['long_name'];
                                 }
                return destinationLocation(json_encode($destinationData['neighborhood political']),json_encode($destinationData['political sublocality sublocality_level_2']),json_encode($destinationData['political sublocality sublocality_level_1']), json_encode($destinationData['locality political'])); 
                              }
                 function destinationLocation($local,$religion,$sublocality,$locality){//converting json data
    	            return trim($local, '"').",".trim($religion, '"').",".trim($sublocality, '"').",".trim($locality, '"'); 
    	                  }
/*gettting soure, destination name from latitudes, longitudes ends here */

			/*$transactionid = '';
		   $paymentId = '330';
		   $cancellationChargesPaymentId='noId';
		   $value = 'digital';*/
		   
		   date_default_timezone_set('Asia/Kolkata');   //current time
			$time=date("Y-m-d H:i:s",time());
		   
		  /* if($cancellationChargesPaymentId == 'noId'){
			   
		   }else{		//update cancellation charges
			   $sql1 = "UPDATE payment SET paymentStatus='$value', razorPayId = '$transactionid' WHERE paymentId='$cancellationChargesPaymentId'";        
				$res1=mysqli_query($con,$sql1) or (logToFile($logfile,"Update payemnt status - passengerPaymentAck.php"));
		   }*/
			if($value == 'digital'){

				 $statement = "UPDATE payment SET paymentStatus='paid', `settlementstatus` = '1',razorPayId = '$transactionid', paymentDate='$time' WHERE paymentId='$paymentId'";        
				 $res=mysqli_query($con,$statement) or (logToFile($logfile,"Update payemnt status - passengerPaymentAck.php"));
				 if($res)
				{   
                      /*generting invoice starts from here*/
                      $selectrideinfo_sql = "SELECT rd.*, p.*, ur.mobile, ur.fullName passengerName, ur.email passengerEmail, dr.fullName driverName  FROM 
                      `rideDetail` rd INNER JOIN payment p ON p.rideDetailId= rd.rideDetailId INNER JOIN userRegistration ur ON ur.userId= rd.userId 
                      INNER JOIN driverRegistration dr ON dr.driverId= rd.driverId WHERE p.paymentId='$paymentId'" ;
                if($rideInfo_Row = mysqli_fetch_assoc(mysqli_query($con,$selectrideinfo_sql))){
                    	$startDate = $rideInfo_Row['rideStartTime'];
                        $endDate =  $rideInfo_Row['rideEndTime'];
                        $amount = $rideInfo_Row['totalpayment'];
                        $vehicleType = $rideInfo_Row['vehicleType'];
                    	$sourceLat = $rideInfo_Row['psourceLatitude'];
                    	$sourceLon = $rideInfo_Row['psourceLongitude'];
                    	$destinationLat = $rideInfo_Row['pdestiLatitude'];
                    	$destinationLon = $rideInfo_Row['pdestiLongitude'];
                    	$paymentType = $rideInfo_Row['paymentType'];
                    	$driverName = $rideInfo_Row['driverName'];
                    	$passengerName = $rideInfo_Row['passengerName'];
                    	$email = $rideInfo_Row['passengerEmail'];
                    	$rideStatus = $rideInfo_Row['rideStatus'];
                    	 if($email != "NA" && $rideStatus == "end"){
                    	$source = get_sourcelocation($sourceLat, $sourceLon);
                      	$destination = get_destinationlocation($destinationLat, $destinationLon);
                      	/*echo "soure".$source;
                      	echo $destination;*/
                        /*passing data to mail  function */
                    	$result1 = $mailes->sendRideInformation($startDate, $endDate,$amount, $driverName,$passengerName, $vehicleType,$paymentType,$email,$source, $destination);
                    }
                   } /*generting invoice ends from here*/

					//$fareSql="SELECT rideDetailId, totalpayment FROM payment WHERE paymentId='$paymentId' ";
					$fareSql = "SELECT p.rideDetailId, totalpayment, rd.rideStatus FROM payment AS p INNER JOIN rideDetail AS rd ON 
								rd.rideDetailId=p.rideDetailId WHERE paymentId='$paymentId'";
					$fareRes = mysqli_query($con, $fareSql) or (logToFile($logfile,"Get ride payment - passengerPaymentAck.php"));
					if($fareR1 = mysqli_fetch_assoc($fareRes)){
							$rideDetailId = $fareR1['rideDetailId'];
							$fare = $fareR1['totalpayment'];
							$result['rideStatus'] = $fareR1['rideStatus'];
							
							$cadSql="SELECT cadcoins FROM `staticValues`";
							$cadRes = mysqli_query($con, $cadSql);
							if($cadR1 = mysqli_fetch_assoc($cadRes)){
								 $cadCoinsRs = $cadR1['cadcoins'];
							}
							 $cadCoinsForPassenger = intval($fare/$cadCoinsRs);
					}
             		$cadCoinsSql="INSERT INTO cadcoins (rideDetailId, passengerCoins) VALUES('$rideDetailId', '$cadCoinsForPassenger')";
             		$cadCoinsRes = mysqli_query($con, $cadCoinsSql) or (logToFile($logfile,"Insert cad coins - passengerPaymentAck.php"));
					$result['paymentStatus'] = 'paymentSuccess';
				}
				else
				{
					$result['paymentStatus'] = 'paymentFailed';	
				}	 
			}else{
				$result['rideStatus'] = 'end';
				$result['paymentStatus'] = 'paymentSuccess';

			}  
	}
	else
	{
		$result['status'] = 'false';
		logToFile($logfile,"Data is not getting from API passengerPaymentAck.php");

	}
	 header("Content-Type:application/json"); 
     echo json_encode($result);
	/*original code ends*/
 
?>
