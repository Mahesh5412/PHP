<?php

		/*
		FileName:reactlogin.php
		Version:1.0.2
		Purpose:for logging purpose using md5 encryption
		Devloper:Rishitha,naveen defId-1
		*/

session_start();

$json = file_get_contents( 'php://input' );
$obj = json_decode( $json, true );

$corp_code 		 = $obj['crop'];
$_SESSION['corp_code'] = $corp_code;

include 'connect.php';

// for Logs 
$logfile = 'log/log' .date('d-M-Y') . '.log';

$user = $obj['username'];
$pass = $obj['password'];
$utype = $obj['utype'];

$pwd      = md5( $pass );

if ( $utype == 'admin' ) {

    $sql = "select * from emsUsers where binary(userName) = '$user' && password= '$pwd' && role = '$utype' && workingStatus='Active'";//Checking with workingStatus also defId-1
    
	// Log for selecting Admin
      $res_sel=mysqli_query($con, $sql) or (logToFile($logfile,"  Admin Login Failed - reactlogin.php"));	
    	
if($res_sel){
	
    if ( mysqli_num_rows( $res_sel ) > 0 )
    {
        $row = mysqli_fetch_array( $res_sel );

        $result['empId'] = $row['empId'];
        $result['userName'] = $row['userName'];
        $result['fullName'] = $row['fullName'];
        $result['role'] = $row['role'];
		$passcode=$row['password'];
        if ( $res_sel ) {
			if(strcasecmp($passcode,$pwd)===0){
            $result['status'] = 'TRUE';
            $result['message'] = 'Success';
		}else{
			  $result['status'] = 'False';
			  $result['message'] = 'Username password doesnot exists!';
        }

    } else {
        $result['status'] = 'False';
        $result['message'] = 'Username password doesnot exists!';

    }
}
else{
	 $result['status'] = 'False';
        $result['message'] = 'Username password doesnot exists!';
}
}
else{
	 $result['status'] = 'False';
        $result['message'] = 'Username password doesnot exists!';
        
}


}
else if ( $utype == 'user' ) {

    $sql2 = "select * from emsUsers where userName = '$user'  && password = '$pwd' && workingStatus='Active' && role<> 'admin'";//Checking with workingStatus also defId-1
    //Logs for User
    $res_sel2 = mysqli_query( $con, $sql2 ) or (logToFile($logfile,"User Login  Failed - reactlogin.php"));
    if($res_sel2){

    if ( mysqli_num_rows( $res_sel2 ) > 0 )
    {
        $row = mysqli_fetch_array( $res_sel2 );

        $result['empId'] = $row['empId'];
        $result['userName'] = $row['userName'];
        $result['fullName'] = $row['fullName'];
        $result['role'] = $row['role'];
		$passcode=$row['password'];
        if ( $res_sel2 ) {
		if(strcasecmp($passcode,$pwd)===0){
            $result['status'] = 'TRUE';
            $result['message'] = 'Success';
		}else{
			  $result['status'] = 'False';
			  $result['message'] = 'Username password doesnot exists!';
        }
        }
     else {
        $result['status'] = 'False';
        $result['message'] = 'Username password doesnot exists!';

    }
}
    else{
	 $result['status'] = 'False';
        $result['message'] = 'Username password doesnot exists!';
}
} else {
        $result['status'] = 'False';
        $result['message'] = 'Username password doesnot exists!';
        
    }
}
 else {
				$result['status']='FALSE';
				$result['message']='Request method wrong!';
			}
echo json_encode( $result );

?>
