<?php
/*Version:1.0.0
 * FileName:functions.php
 *Purpose: Function definitions to use in another file
 *Developers Involved: Amith
 */
	function sms(){			//Send sms to user mobile number
			// Authorisation details.
			$username = "sms@novisync.com";
			$hash = "b84caa55f9b0516e4b07b1da86ad93192adc84a42db39590284fbac6daf95e25";
			// Config variables. Consult http://api.textlocal.in/docs for more info.
			$test = "0";
			// Data for text message. This is the text message data.
			$sender = "CADRAC"; // This is who the message appears to be from.
			$numbers = $mobile; // A single number or a comma-seperated list of numbers
			$pin='1234';
			$message = 'Thank You for Registering in Hap Ride. %nYour Login OTP : '.$pin;    //Message format to send OTP
			// 612 chars or less
			// A single number or a comma-seperated list of numbers
			$message = urlencode($message);		//Encode message
			$data = "username=".$username."&hash=".$hash."&message=".$message."&sender=".$sender."&numbers=".$numbers."&test=".$test;
			$ch = curl_init('http://api.textlocal.in/send/?');
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch); // This is the result from the API
			curl_close($ch);
	}
	function startsWith ($string, $startString) 
	{ 
    $len = strlen($startString); 
    return (substr($string, 0, $len) === $startString); 
	} 

?>
