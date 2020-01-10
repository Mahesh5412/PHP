<?php
/*

 * Name : log.php

 * Purpose:  This page is for creating log

 */

/* original code section starts*/
	
	function logToFile($filename,$msg)
	{
		//include "logmode.php";
		
		$myip=get_client_ip();
			 $fd = fopen($filename, "a"); 
			date_default_timezone_set('Asia/Kolkata');
			 $str = "[" . date("Y/m/d h:i:s", mktime()) . " IP ADD:" . $myip." ] " . $msg . "\n"; 
			fwrite($fd,$str . "\n");
			fclose($fd); 
			
		
		 
	}
	function logToFileIP($filename,$msg)
	{
			$fd = fopen($filename, "a"); 
			date_default_timezone_set('Asia/Kolkata');
			$str = "[" . date("Y/m/d h:i:s", mktime()) . "] " . $msg . "\n";  
			fwrite($fd,$str . "\n");
			fclose($fd); 
	}	
		
	function get_client_ip() 
	{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
	}
	
	
	
	
?>
