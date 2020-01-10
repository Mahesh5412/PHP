<?php

		/*
		FileName:reactlogin.php
		Version:1.0.1
		Purpose:for logging purpose using md5 encryption
		Devloper:Rishitha,naveen
		*/

session_start();

$json = file_get_contents( 'php://input' );
$obj = json_decode( $json, true );

$corp_code = $_POST['corp'];

$_SESSION['corp_code'] = $corp_code;

include 'connect.php';

$user = $_POST['username'];
$pass = $_POST['password'];
$utype = $_POST['utype'];

$pwd      = md5( $pass );

if ( $utype == 'admin' ) {

    $sql = "select * from emsUsers where binary(userName) = '$user' && password = '$pwd' && role = '$utype'";
    $res_sel = mysqli_query( $con, $sql );

    if ( mysqli_num_rows( $res_sel ) > 0 )
    {
        $row = mysqli_fetch_array( $res_sel );

        $result['empId'] = $row['empId'];
        $result['userName'] = $row['userName'];
        $result['fullName'] = $row['fullName'];
        $result['role'] = $row['role'];
		$passcode=$row['password'];
        if (  mysqli_num_rows( $res_sel2 ) > 0  ) {

			if($passcode==strtoupper($pwd)){
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
}
else if ( $utype == 'user' ) {

    $sql2 = "select * from emsUsers where userName = '$user'  && password = '$pwd' && role<> 'admin'";
    $res_sel2 = mysqli_query( $con, $sql2 );

    if ( mysqli_num_rows( $res_sel2 ) > 0 )
    {
        $row = mysqli_fetch_array( $res_sel2 );
		$passcode=$row['password'];
        if ( mysqli_num_rows( $res_sel2 ) > 0 ) {
		if($passcode==strtoupper($pwd)){
            $result['status'] = 'TRUE';
            $result['message'] = 'Success';
                   $_SESSION['empId'] = $result['empId'] = $row['empId'];
        $_SESSION['userName'] =  $result['userName'] = $row['userName'];
        $_SESSION['fullName'] = $result['fullName'] = $row['fullName'];
        $_SESSION['role'] = $result['role'] = $row['role'];
            echo '<script>window.location.href="add.php"</script>';
		}else{
			  $result['status'] = 'False';
			  $result['message'] = 'Username password doesnot exists!';
			  echo '<script>alert("'.$result['message'].'");window.location.href="index.php"</script>';
        }
        }

    } else {
        $result['status'] = 'False';
        $result['message'] = 'Username password doesnot exists!';
		echo '<script>alert("'.$result['message'].'");window.location.href="index.php"</script>';
    }
}

?>
