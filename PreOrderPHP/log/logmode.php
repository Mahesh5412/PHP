<?php
/*

 * Name : logmode.php

 * Purpose:  This page is designed for getting logmode.

 */

/* original code section starts*/

		$log=parse_ini_file("properties.ini",true);
		if ($log['debug_mode']==1 && $log['error_mode']==0)
				$logmode=1;
		else if ($log['debug_mode']==0 && $log['error_mode']==1 )
				$logmode=0;
		else
				$logmode="Wrong logmode";
			
				
?>
