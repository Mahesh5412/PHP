<?php

/*
FileName:add_edit_employee.php
Version:1.0.1
Purpose:to add and update the employee
Devloper:krishna tulasi
*/

session_start();

$json = file_get_contents( 'php://input' );
$obj = json_decode( $json, true );

$corp_code 		 = $obj['crop'];
$_SESSION['corp_code'] = $corp_code;

include 'connect.php';

// for Logs 
$logfile = 'log/log' .date('d-M-Y') . '.log';

date_default_timezone_set( 'Asia/Kolkata' );

$time = date( 'Y-m-d H:i:s' );

$action  		 = $obj['action'];

//to check the employee added or not
if ( $action == 'check' ) {

    $username  		 = $obj['userName'];
    $e_mail  		 = $obj['email'];

    $email      = mysqli_real_escape_string( $con, $e_mail );
    $user_name  = mysqli_real_escape_string( $con, $username );

    $sql = "select * from emsUsers where (email = '$email' || userName = '$user_name')";
    $res = mysqli_query( $con, $sql ) or (logToFile($logfile," to check the employee added or not - add_edit_employee.php"));

    if ( mysqli_num_rows( $res ) > 0 ) {
        $result['status'] = 'False';
        $result['message'] = 'User Details already registered';
    } else {
        $result['status'] = 'True';
        $result['message'] = 'no user with such username';

    }

} else {
    $result['status'] = 'False';
    $result['message'] = 'no action';

}
//to add employee
if ( $action == 'save' ) {
    $full_name_str  		 = $obj['fullname'];
    $email_str  		    = $obj['email'];
    $mobile  		    = $obj['mobile'];
    $username_str  		 = $obj['username'];
    $password_str  		 = $obj['password'];
    $user_type_str    	 = $obj['userType'];
    $status  		    = $obj['user_status'];
    $designation_str  	 = $obj['designation'];
    $team_str  		    = $obj['team'];
    $created_by  		 = $obj['created_by'];
    $empId  	     	    = $obj['empId'];

    $fullname = mysqli_real_escape_string( $con, $full_name_str );
    $email = mysqli_real_escape_string( $con, $email_str );
    $username = mysqli_real_escape_string( $con, $username_str );
    $pass = mysqli_real_escape_string( $con, $password_str );
    $password = md5( $pass );
    $usertype = mysqli_real_escape_string( $con, $user_type_str );
    $designation = mysqli_real_escape_string( $con, $designation_str );
    $team = mysqli_real_escape_string( $con, $team_str );

    $sql_ins = "insert into `emsUsers`
								(`empId`,`fullName`,`email`,`mobileNumber`,`userName`,`password`,`role`,`workingStatus`,`designation`,`team`,`createdBy`,`createdDate`,`modifiedBy`,`modifieddate`)
						  values('$empId','$fullname','$email','$mobile','$username','$password','$usertype','$status','$designation','$team','$created_by','$time','$created_by','$time')";

    $res_ins = mysqli_query( $con, $sql_ins ) or (logToFile($logfile," to add employee - add_edit_employee.php"));

    if ( $res_ins ) {
        $result['status'] = 'True';
        $result['message'] = 'Success';
    } else {
        $result['status'] = 'False';
        $result['message'] = 'Error in registertion';
    }

}
//to update employee details
if ( $action == 'update' ) {

    $full_name_str  		 = $obj['fullname'];
    $email_str  		    = $obj['email'];
    $mobile  		    = $obj['mobile'];
    $username_str  		 = $obj['username'];
    $password_str  		 = $obj['password'];
    $user_type_str    	 = $obj['userType'];
    $status  		    = $obj['user_status'];
    $designation_str  	 = $obj['designation'];
    $team_str  		    = $obj['team'];
    $created_by  		 = $obj['created_by'];
    $empId  	     	    = $obj['empId'];

    $fullname = mysqli_real_escape_string( $con, $full_name_str );
    $email = mysqli_real_escape_string( $con, $email_str );
    $username = mysqli_real_escape_string( $con, $username_str );
    $pass = mysqli_real_escape_string( $con, $password_str );
    $password = md5( $pass );
    $usertype = mysqli_real_escape_string( $con, $user_type_str );
    $designation = mysqli_real_escape_string( $con, $designation_str );
    $team = mysqli_real_escape_string( $con, $team_str );

    $sql_update = "update `emsUsers` set `fullName` = '$fullname', `email` = '$email', `mobileNumber`='$mobile', `userName`= '$username', `password`='$password',
								`role`='$usertype', `workingStatus`='$status' ,`designation`= '$designation',`team`='$team',`createdBy`='$created_by',`createdDate`='$time',
								`modifiedBy`='$created_by',`modifieddate`='$time' where `empId` = '$empId'";

    $res_update = mysqli_query( $con, $sql_update ) or (logToFile($logfile," to update employee details - add_edit_employee.php"));

    if ( $res_update ) {
        $result['status'] = 'True';
        $result['message'] = 'Success';
    } else {
        $result['status'] = 'False';
        $result['message'] = 'Error in registertion';
    }

}
//  else {
// 				$result['status']='FALSE';
// 				$result['message']='Request method wrong!';
// 			}

header( 'Content-Type:application/json' );
echo json_encode( $result );

?>

