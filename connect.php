<?php

/*
FileName:healthcheck.php
Version:1.1
Purpose:to check health condition of a server and database
Devloper:rishitha
*/

$db_host = '192.168.0.26';
$db_user = 'qptms';

$db_password = 'qptms@123';

 $db_name = $_SESSION["corp_code"];
   //Generating logs
include("log/log.php");
$logfile= 'log/log_' .date('d-M-Y') . '.log';
$myip="-----Log Start for ";
$myip.=get_client_ip();
$myip.="------";

//logToFileIP($logfile,$myip); 

$dir =getcwd();
$dir.='';
/* cycle through all files in the directory */
foreach (glob($dir."*.log") as $file) {
	/* if file is 24 hours (86400 seconds) old then delete it */
	if(time() - filectime($file) > 86400*7){
		unlink($file);
    }
}


$con = mysqli_connect( $db_host, $db_user, $db_password, $db_name );



/*if ( $con ) {
	logToFile($logfile,"Login Suuceesss"); 
    $status = 'True';

} else {
	logToFile($logfile,"Login Failed"); 
    $status = 'False';

}
$result['status'] = $status;

echo json_encode( $result );
*/

?>
