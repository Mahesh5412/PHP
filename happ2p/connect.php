<?php
/*Version:1.0.0
 * FileName:connect.php
 *Purpose: To connect to database
 *Developers Involved: Vineetha, Mahesh, Srikanth
 */
   //Generating logs
include("log/log.php");
$logfile= 'log/log_' .date('d-M-Y') . '.log';
$myip="-----Log Start for ";
$myip.=get_client_ip();
$myip.="------";

logToFileIP($logfile,$myip); 

$dir =getcwd();
$dir.='';
/*** cycle through all files in the directory ***/
foreach (glob($dir."*.log") as $file) {
	/*** if file is 24 hours (86400 seconds) old then delete it ***/
	if(time() - filectime($file) > 86400*7){
		unlink($file);
    }
}	
	
	/*$db_host = '192.168.0.150';			//Database host
	$db_user = 'hapride';				//Database username
	$db_password = 'Novi@123'; 			//Database password
	$db_name ='happ2p';		*/			//Database name
	
	$db_host = '192.168.0.26';
$db_user = 'qptms';

$db_password = 'qptms@123';

 $db_name = 'happ2p';

	$con =mysqli_connect($db_host, $db_user, $db_password,$db_name) or (logToFile($logfile,"Get the user realated data connect.php")) ;		//To establish connection
	

?>
