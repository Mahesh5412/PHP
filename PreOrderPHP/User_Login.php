<?php
include("log/log.php"); 
$logfile= 'log/log_' .date('d-M-Y') . '.log';

logToFile($logfile,"Started logging first",1);

//echo $log;

$json = file_get_contents('php://input');
$obj = json_decode($json,true);
include 'DBconnection.php';
$number = $obj['number'];
//$number = '8111224444';
			$pin = mt_rand(100000,999999);
			$Sql_Query1 = "SELECT * FROM loginTable WHERE mobileNumber='$number'";
			$done=mysqli_query($conn,$Sql_Query1);
	//	print_r($done);
			if(mysqli_num_rows($done))
			{
				//echo "start1";
				$ins = mysqli_query($conn,"UPDATE loginTable SET `otp`='$pin' WHERE mobileNumber='$number'") or die("ERORR1");
				logToFile($logfile,"Started logging second",1);
				$ins2 = mysqli_query($conn,"SELECT * FROM loginTable WHERE mobileNumber='$number'") or die("ERORR2");

				//$result11['data']=$ins2;
				logToFile($logfile,"Account Created Succesfully",1);
				$check = mysqli_fetch_array($ins2);

				$check['option']="update";
			}
			else
			{

			//	echo "done";
				$ins3 = mysqli_query($conn,"INSERT INTO loginTable(`mobileNumber`,`otp`) VALUES ('$number','$pin')") or die("ERORR3");
				//$result11['data']=$ins3;
			//	echo "no";
			$ins4 = mysqli_query($conn,"SELECT * FROM loginTable WHERE mobileNumber='$number'") or die("ERORR2");
				$check = mysqli_fetch_array($ins4);

				$check['option']="insert";
			}
           // $ins = mysqli_query($con,"INSERT INTO loginTable (`mobileNmuber`,`otp`) VALUES ('$number','$pin')") or die("ERORR1");
            
    /*
                    // Authorisation details.
                    $username = "sms@novisync.com";
                    $hash = "b84caa55f9b0516e4b07b1da86ad93192adc84a42db39590284fbac6daf95e25";

                    // Config variables. Consult http://api.textlocal.in/docs for more info.
                    $test = "0";

                    // Data for text message. This is the text message data.
                    $sender = "CADRAC"; // This is who the message appears to be from.
                    $numbers = $number; // A single number or a comma-seperated list of numbers
                    $message = 'Thankyou for Registering in C- WALET '.$pin;
                    // 612 chars or less
                    // A single number or a comma-seperated list of numbers
                    $message = urlencode($message);
                    $data = "username=".$username."&hash=".$hash."&message=".$message."&sender=".$sender."&numbers=".$numbers."&test=".$test;
                    $ch = curl_init('http://api.textlocal.in/send/?');
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $result = curl_exec($ch); // This is the result from the API
                    curl_close($ch);
*/


		//$Sql_Query1 = "SELECT * FROM Login_opt WHERE number='$number' ";  


		//$check = mysqli_fetch_array(mysqli_query($con,$Sql_Query1));




//$number='8121198994';


/*

$apiKey = urlencode('z0JOtZXyYT8-oYQV68jEGVJHiET1CqAOocVV8Jses2');
	
	// Message details
	$numbers = array(8121198994,8790808344);
	$sender = urlencode('same');
	$message = rawurlencode('This is your message');
 
	$numbers = implode(',', $numbers);
 
	// Prepare data for POST request
	$data = array("apikey" => $apiKey, "numbers" => $numbers, "sender" => $sender, "message" => $message);
 
	// Send the POST request with cURL
	$ch = curl_init('https://api.txtlocal.com/send/');
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($ch);
	curl_close($ch);
	
	// Process your response here
	echo $response;
*/
/*
	$apiKey = urlencode('z0JOtZXyYT8-oYQV68jEGVJHiET1CqAOocVV8Jses2');
				
     $Textlocal = new Textlocal(false, false, $apiKey);
                
       $numbers1 = array(8121198994);
      $sender = 'SAME';
      $otp = rand(100000, 999999);
                //$_SESSION['session_otp'] = $otp;
                
     $message = "Thankyou for Registering in C- same " . $otp;
           
           try{
			   $response = $Textlocal->sendSms($numbers1, $message, $sender);
			   print_r($response);
			   
			   }catch(Exception $e){
				   
				   die('error'.$e->getMessage());
				   }     
		//$response = $Textlocal->sendSms($numbers1, $message, $sender);

 echo $response;
*/
 //$Sql_Query = "SELECT * FROM tbl_ems_users WHERE username='$username' AND password='$password' ";  


//$Sql_Query = "INSERT INTO Login_opt(number) VALUES('812839399')";


//$Sql_Query = "INSERT INTO `Login_opt` number='$number'";  

//insert into Login_opt number='$number'


//$res = mysqli_query($con,$Sql_Query)or die('error in method');


//$statement = mysqli_prepare($con, "INSERT INTO Login_opt(number,opt) VALUES (?,?)");
 //  mysqli_stmt_bind_param($statement, "ii",$number,$otp);
 //  mysqli_stmt_execute($statement);

/*
if($statement)
echo "success";
else
echo "error";
*/


if(isset($check)){

//$SuccessLoginMsg['ok'] = $check['mobile'];


//$SuccessLoginMsg['mobile'] = $check['mobile'];
//$SuccessLoginMsg['full_name'] = $check['full_name'];
//$SuccessLoginMsg['email'] = $check['email'];
//$SuccessLoginMsg['name'] = 'tulasi rao';
//$SuccessLoginMsg['next'] = 'Data Matched';



//echo $check;

//$check['status']='success';
//$SuccessLoginMsg = $check;



//$SuccessLoginMsg['opt'] = $check['opt'];

$check['status']='success';
$SuccessLoginMsg = $check;

//$SuccessLoginMsg = 'success';

$SuccessLoginJson = json_encode($SuccessLoginMsg);
echo $SuccessLoginJson ; 

}
 
 else{
 
$InvalidMSG = 'failure' ;
$InvalidMSGJSon = json_encode($InvalidMSG);
 echo $InvalidMSGJSon ;
 
 }

mysqli_close($conn);



/*
if($cropcode == 'ems2')
{
include 'DBConfig.php';
$Sql_Query = "SELECT * FROM tbl_ems_users WHERE username='$username' AND password='$password' ";
}
else if($cropcode == 'ems')
{
    include 'DBConn.php';
    $Sql_Query = "SELECT * FROM tbl_ems_users WHERE username='$username' AND password='$password' ";  
}

$check = mysqli_fetch_array(mysqli_query($con,$Sql_Query));
*/
//if(isset($check)){
/*
$result1 = array();

if(mysqli_num_rows($check) > 0)
				{
					$r = 0;
					while($row = mysqli_fetch_array($check))
						{
							
							
							$temp['id'] = $row['id'];
							$temp['full_name'] = $row['full_name'];
							$temp['email'] = $row['email'];
							$temp['mobile'] = $row['mobile'];
							$temp['alt_phoneno'] = $row['alt_phoneno'];
							$temp['username'] = $row['username'];
							$temp['password'] = $row['password'];
							$temp['user_type'] = $row['user_type'];
							$temp['status'] = $row['status'];
							$temp['designation'] = $row['designation'];
							$temp['estatus'] = $row['Estatus'];
							$data[$r] = $temp;
							
							$r++;
						}
						$result['status'] = 'True';
						$result['message'] = 'Success';	
						$result['data'] = $data;
						
					$result1 = json_encode($result);
echo $result1 ;	
						
				}
			 
		*/	
			/*
						$result['status'] = 'True';
						$result['message'] = 'Success';	
						$result['data'] = $data;
			
$result1 = json_encode($result);
echo $result1 ; 

*/

/*
if(isset($check)){

//$SuccessLoginMsg['ok'] = $check['mobile'];


//$SuccessLoginMsg['mobile'] = $check['mobile'];
//$SuccessLoginMsg['full_name'] = $check['full_name'];
//$SuccessLoginMsg['email'] = $check['email'];
//$SuccessLoginMsg['name'] = 'tulasi rao';
//$SuccessLoginMsg['next'] = 'Data Matched';



echo $check;

$check['status']='success';
$SuccessLoginMsg = $check;

$SuccessLoginJson = json_encode($SuccessLoginMsg);
echo $SuccessLoginJson ; 

}
 
 else{
 
$InvalidMSG = 'Invalid Username or Password Please Try Again' ;
$InvalidMSGJSon = json_encode($InvalidMSG);
 echo $InvalidMSGJSon ;
 
 }
*/
 //mysqli_close($con);
?>
