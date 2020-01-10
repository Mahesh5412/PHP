<?php
 /*Version:1.2
 * FileName:PtmsLog.php
 *Purpose: To store  application logs
 *Developers Involved:rishitha
 */
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
	
	$json = file_get_contents( 'php://input' );
	$obj = json_decode( $json, true );

	if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET")
		{
			$time = date( 'Y-m-d H:i:s' );

		  $log_msg	= $obj['logMessage'];
		  $log_mode	= $obj['logMode'];

		   $log=$time.'   '.$log_mode.'   '.$log_msg;
		
		   $log_filename = "PtmsLogs";
		    
		    if (!file_exists($log_filename)) 
		    {
			// create directory/folder uploads.
			//echo "folder";
			mkdir($log_filename, 0777, true);		
		    }
		    $log_file_data = $log_filename.'/log_' . date('d-M-Y') . '.log';
		    file_put_contents($log_file_data,$log. "\n", FILE_APPEND);
		    $result['status']='true';
		   	
	  }else     
		{
			$result['status']='FALSE';
			$result['message']='Request method wrong!';
		}
     header("Content-Type:application/json"); 
     echo json_encode($result);
    ?>
