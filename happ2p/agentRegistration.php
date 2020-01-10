<?php
/*FileName:agentRegistration.php
 *Purpose: insert agent details in db and getting qualification details
 *Developers Involved:vineetha
 */
  	include 'connect.php';		//connect to server
    //for logs
	$logfile= 'log/logfiles/log_' .date('d-M-Y') . '.log';  
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
	
	if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET")
		{	//get data from android
			$action = $_REQUEST['action'];
			$fullName= $_REQUEST['fullName'];
			$phone= $_REQUEST['phone'];   
			$bike= $_REQUEST['bike'];    
			$role= $_REQUEST['role'];   
			$qualification= $_REQUEST['qualification'];     
			$phoneNum= $_REQUEST['phoneNum'];
			$id= $_REQUEST['id'];
			$password= $_REQUEST['password'];
			$referalcode=$_REQUEST['referalcode'];
			$refid=$_REQUEST['refid'];
			
			//current time
			date_default_timezone_set('Asia/Kolkata');
			$createdDate=date("Y-m-d H:i:s",time());
			
			if($action == 'qualification'){		//getting qualification details
			$sql="SELECT qualification FROM `qualificationDetails` ORDER by id LIMIT 3";
			$sql_res=mysqli_query($con,$sql);
			if($sql_res){
					$r=0;
					while($fet=mysqli_fetch_assoc($sql_res))
					{
						$temp['qualification']=$fet['qualification'];
				
						$data[$r]=$temp;
						$r++;
					}
					$result['qualificationStatus'] = 'True';
					$result['qualificationData'] = $data;	  
			}
		}else if($action == "agentDetailsInsert"){		//insert agent details
			$sql = "SELECT * FROM `agentRegistration` WHERE mobileNumber='$phone'";
			$res = mysqli_query($con, $sql);
			if(mysqli_num_rows($res) == 0){
				$sql2="INSERT INTO agentRegistration(aid, password, name, mobileNumber, referralCode, qualification, smartphone, bike, createdDate, verifiedStatus) VALUES 
							('$id',  '$password' ,'$fullName', '$phoneNum', '$referalcode', '$qualification', '$phone', '$bike', '$createdDate', 'details inserted')";
				$res2=mysqli_query($con, $sql2) or (logToFile($logfile,"Insert agent registartion deatils - agentRegistration.php"));
				if($res2){
					$result['agentRegStatus'] = "success";
				}else{
					$result['agentRegStatus'] = "failed";
				}
			}else{
				$result['agentRegStatus'] = "already registered";
			}
			
		}else if($action == "proofs"){		//get government proofs from db
			$sql2="SELECT documentType FROM `qualificationDetails` WHERE documentType != ''";
					$res2=mysqli_query($con, $sql2) or (logToFile($logfile,"Get documenttype from qualificationDetails table - agentRegistration.php"));
					if($res2){
						$a=0;
						while($r1=mysqli_fetch_array($res2)){
							$temp1['documentType']=$r1['documentType'];
				
							$d[$a]=$temp1;
							$a++;
						}
						$result['documentStatus'] = 'True';
						$result['documentData'] = $d;
					}	
		}else{
			$result['statuselse']='failed';
			logToFile($logfile,"condition failed in agentRegistration.php");
		}			  
			
	}else{
		$result['statusdb']='failed';
		logToFile($logfile,"DB connection failed in agentRegistration.php");
	}
	header("Content-Type:application/json"); 
	echo json_encode($result);
?>
