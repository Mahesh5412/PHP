<?php
/*Version :1.0.0
 *FileName:Auth.php
 *Purpose: Authentication of user and genarating otp. 
 *Developers Involved:TulasiRao.
 */
 //including log creation file 
include("log/log.php"); 
//creataing file
$logfile= 'log/log_' .date('d-M-Y') . '.log';
//logToFile($logfile,"Started logging first",1);
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
//connecting to server
include 'DBconnection.php';
//getting number from mobile.js file and stored in $number 
$number = $obj['number'];
//generating a random six digit otp number
			$pin = mt_rand(100000,999999);
			//this SQL command will checks entered mobileNumber exit or not
			$Sql_Query1 = "SELECT * FROM loginTable WHERE mobileNumber='$number'";
			$done=mysqli_query($conn,$Sql_Query1) or (logToFile($logfile,"query not seleted in Auth.php"+$number+"number",1));
	//	print_r($done);
			if(mysqli_num_rows($done))
			{
			
				//this SQL command will executes if mobileNumber exit it will updates the otp
				$ins = mysqli_query($conn,"UPDATE loginTable SET `otp`='$pin' WHERE mobileNumber='$number'") or 
				(logToFile($logfile,"query not updating in Auth.php"+$number+"number",1));
				
		

				if($ins>0){
					
					//this SQL command will send otp to same mobileNumber
					$ins2 = mysqli_query($conn,"SELECT * FROM loginTable WHERE mobileNumber='$number'") or 
					(logToFile($logfile,"query not seleted in Auth.php"+$number+"number",1));
					if(mysqli_num_rows($ins2) > 0) {
							//$result11['data']=$ins2;
							//logToFile($logfile,"Account Created Succesfully",1);
							$check = mysqli_fetch_array($ins2);
							$check['option']="update";
					}
				}
			}
			else
			{

			//	this SQL command will executes for new Registration
				$ins3 = mysqli_query($conn,"INSERT INTO loginTable(`mobileNumber`,`otp`) VALUES ('$number','$pin')")  
				or (logToFile($logfile,"query not inseted in Auth.php"+$number+"number",1));
				if($ins3){
						//	this SQL command will send OTP to entered mobileNumber
						$ins4 = mysqli_query($conn,"SELECT * FROM loginTable WHERE mobileNumber='$number'") or 
						(logToFile($logfile,"query not selected in Auth.php"+$number+"number",1));

						if(mysqli_num_rows($ins4) > 0) {
							$check = mysqli_fetch_array($ins4);

							$check['option']="insert";
						}
				}
		
			}
			//print_r($check);
			if(isset($check)){
				//message for success result
				$check['status']='success';
				$SuccessLoginMsg = $check;
				//$SuccessLoginMsg = 'success';
				$SuccessLoginJson = json_encode($SuccessLoginMsg);
				echo $SuccessLoginJson ; 
			}
			else{
				//message for failuer result
				$InvalidMSG['status']='fail';
				$InvalidMSGJSon = json_encode($InvalidMSG);
				echo $InvalidMSGJSon ;
			}
			mysqli_close($conn);
?>
