	<?php
		/*
		FileName:get_subtasks.php
		Version:1.1
		Purpose:To list all subtasks related to maintasks
		Devloper:Rishitha,Naveen,krishna tulasi,jagadeesh
		*/

		session_start();

		$json = file_get_contents('php://input');
		$obj = json_decode($json,true);

		  $corp_code 		= $obj['crop'];
		  $_SESSION["corp_code"] = $corp_code;
		 
		 	include 'connect.php';

        // for Logs 
        $logfile = 'log/log' .date('d-M-Y') . '.log';

		
		  date_default_timezone_set('Asia/Kolkata'); 
		  $time= date("Y-m-d H:i:s");


				$userType	        	= $obj['userType'];
				$mainTaskId        	 	= $obj['mainTaskId'];
				$task_id  	    		= $obj['task_id'];
				$task_status     		= $obj['task_status'];
				$task_status_desc 		= $obj['task_status_desc'];
				$task_complete_status  	= $obj['task_complete_status'];
				$subTask  	    		= $obj['sub_taskId'];
				$action  	    		= $obj['action'];
				$empId  	    		= $obj['empId'];
				
			//to list the all pending subtasks of maintask based on particular employee	
					
				if($action == 'pending')
				{
				$sql=" SELECT ust1.subTaskId, ust1.taskTitle as taskTitle, umt.taskTitle as mainTaskTitle,
				 umt.id as mainTaskid,ust1.taskDesc as subTaskDesc, eu3.username as assignedBy, ust1.assignedDate, 
				 ust1.targetDate, ust1.taskEndDate, eu1.username as assignedTo, ust1.taskStatus, ust1.status, ust1.dependencyId,
				  ust2.taskTitle as dependencyTitle, eu2.username AS dependencyUser, ust1.modifiedDate, ust1.taskStatusDesc, 
				  ust1.activeStatus FROM `userSubTasks` AS ust1 INNER JOIN userMainTasks AS umt ON ust1.mainTaskId = umt.id INNER JOIN 
				  emsUsers AS eu1 ON ust1.assignedTo = eu1.empId LEFT JOIN userSubTasks AS ust2 ON ust1.dependencyId = ust2.subTaskId LEFT JOIN 
				  emsUsers AS eu2 ON ust2.assignedTo = eu2.empId inner join emsUsers as eu3 on ust1.assignedBy=eu3.empId 
				  where ust1.status='pending' and ust1.assignedTo='$empId' order by ust1.modifiedDate DESC,ust1.subTaskId DESC";
	 
				$sql_res=mysqli_query($con,$sql)  or (logToFile($logfile," to list the all pending subtasks of maintask based on particular employee	 - get_subtasks.php"));
				if(mysqli_num_rows($sql_res)>0){
					$r=0;
				while($fet=mysqli_fetch_assoc($sql_res))
				{
					$dependencyId=$fet['dependencyId'];
					$dependencyUser=$fet['dependencyUser'];
					$targetTime=$fet['targetDate'];
					$taskId=$fet['subTaskId'];
				
				if($dependencyId=='NA'){
					
					$fet['dependencyTitle']='NA';
					$fet['dependencyUser']='NA';
					
				}
						  $time= date("Y-m-d H:i:s");

						//timeleft calculation starts
						  $targetcompare=strtotime($targetTime);
						  $currentdate=strtotime($time);
				
							$ssq="SELECT CONCAT(
							FLOOR(HOUR(TIMEDIFF('$targetTime','$time')) / 24), ' days, ',
							MOD(HOUR(TIMEDIFF('$targetTime','$time')), 24), ' hours, ',
							mod(minute(TIMEDIFF('$targetTime','$time')),60), ' minutes ')
							AS timeLeft FROM userSubTasks";
								 
									$ssq_res=mysqli_query($con,$ssq)  or (logToFile($logfile," timeleft calculation starts - get_subtasks.php"));
									
									while($row1=mysqli_fetch_assoc($ssq_res)){
										
										 $timeLt  = $row1['timeLeft'];
									}
									
									if($targetcompare>$currentdate){
										
											$fet['timeLeft']=$timeLt;
										}else{
											
											$fet['timeLeft']='-'.$timeLt;
											}
							//timeleft calculation ends
									$fet['today']=$time;

				$sql_chat="SELECT * FROM `chat_count` WHERE `taskId`= '$taskId'";
						   $sql_res_chat=mysqli_query($con,$sql_chat) or (logToFile($logfile," timeleft calculation ends - get_subtasks.php"));
								
							$count='0';
							while($fet1=mysqli_fetch_assoc($sql_res_chat)){
								$count=$fet1['ncount'];
								}
								
							$fet['count']=$count;
							$fet['cDate']=$time;
							
					$data[$r]=$fet;
					$r++;
					}
							$response['status'] = 'True';
							$response['message'] = 'Success';	
							$response['data'] = $data;
						}
						else if(mysqli_num_rows($sql_res)==0){
							$response['status'] = 'NoData';
							$response['message'] = 'Success';	
							}
						else
						{
							$response['status'] = 'false1';
							$response['message'] = $action;	
							
							}
						}
						
						// to clear the chat count 
							
						if($action == 'resetCount'){
								
								$sql_chat1="UPDATE `chat_count` SET `ncount`='0' where `taskId`= '$subTask' ";
						   $sql_res_chat1=mysqli_query($con,$sql_chat1) or (logToFile($logfile," to clear the chat count  - get_subtasks.php"));
						   if($sql_res_chat1){
						   $response['status']='true';
						  $response['message']='success';
								
							}	
								else{
									$response['status']='false';
						  			$response['message']='failure';
									}
								}
								
						//to set dependency for particular subtask						
					if($action=='setdependency'){
					 
					 $sql="SELECT ust. * , eu1.username AS assignedByName, eu2.username AS assignedToName
						   FROM  `userSubTasks` AS ust
						   INNER JOIN emsUsers AS eu1 ON ust.assignedBy = eu1.empId
						   INNER JOIN emsUsers AS eu2 ON ust.assignedTo = eu2.empId
						   WHERE  `status` =  'pending'";
					 
					 $sql_res=mysqli_query($con,$sql) or (logToFile($logfile," to set dependency for particular subtask  - get_subtasks.php"));
					 
				
					   if(mysqli_num_rows($sql_res)>0){
						   
							$r=0;
						   
							while($fet=mysqli_fetch_assoc($sql_res)){
								
							  $temp['taskTitle']  = $fet['taskTitle'];
							  $temp['name']  = $fet['taskDesc'];
							  $temp['username']  = $fet['assignedToName'];
							  $temp['id']  = $fet['subTaskId'];
							  $temp['maintaskid']  = $fet['mainTaskId'];
							  
								$data[$r]=$temp;
							 $r++;
							}
						   
						  $response['status']='true';
						  $response['message']='success';
						 $response['data']=$data;
						   
						   }else{
						 
						  $response['status']='false';
						  $response['message']='no data';
						 }
					 
					 }
					 //to list the all pending and completed subtasks of maintasks
					 
					 if($action=='getsubtasks'){
						 
					$sql="SELECT ust1.subTaskId,ust1.estimatedHours,ust1.assignedTo as assignedid,ust1.activeStatus,ust1.taskTitle AS taskTitle, umt.taskTitle AS mainTaskTitle,umt.id AS mainTaskid, 
					ust1.taskDesc AS subTaskDesc, eu3.username AS assignedBy, ust1.assignedDate, ust1.targetDate, ust1.taskEndDate, eu1.username AS assignedTo, ust1.taskStatus, ust1.status, ust1.dependencyId, IFNULL( ust2.taskTitle, 'NA' ) AS dependencyTitle, IFNULL( eu2.username, 'NA' ) AS dependencyUser, ust1.modifiedDate, ust1.taskStatusDesc
						FROM `userSubTasks` AS ust1
						INNER JOIN userMainTasks AS umt ON ust1.mainTaskId = umt.id
						INNER JOIN emsUsers AS eu1 ON ust1.assignedTo = eu1.empId
						LEFT JOIN userSubTasks AS ust2 ON ust1.dependencyId = ust2.subTaskId
						LEFT JOIN emsUsers AS eu2 ON ust2.assignedTo = eu2.empId
						INNER JOIN emsUsers AS eu3 ON ust1.assignedBy = eu3.empId
						WHERE ust1.mainTaskId ='$mainTaskId' and ust1.status<>'deleted' order by ust1.modifiedDate DESC,ust1.subTaskId DESC";
						 
						  $sql_res=mysqli_query($con,$sql)  or (logToFile($logfile,"to list the all pending and completed subtasks of maintasks - get_subtasks.php"));
					 
					   if(mysqli_num_rows($sql_res)>0){
						   
							$r=0;
						   
							while($fet=mysqli_fetch_assoc($sql_res)){
								
							   $temp['assignedid']        = $fet['assignedid'];
							 $temp['subTaskId']        = $fet['subTaskId'];
							  $temp['mainTaskTitle']      = $fet['mainTaskTitle'];
								$temp['mainTaskId']      = $fet['mainTaskid'];
							  $temp['moduleId']        = $fet['moduleId'];
							  $temp['taskTitle']       = $fet['taskTitle'];
							  $temp['taskDesc']        = $fet['subTaskDesc'];
							  $temp['assignedBy']      = $fet['assignedBy'];
							  $temp['assignedTo']      = $fet['assignedTo'];
							  $temp['taskStatusPercentage']  = $fet['taskStatus'];
							  $temp['assignedDate']    = $fet['assignedDate'];
							  $temp['targetDate']      = $fet['targetDate'];
							  $temp['taskEndDate']     = $fet['taskEndDate'];
							  $temp['taskstatus']      = $fet['status'];
							  $temp['estimatedHours']     = $fet['estimatedHours'];
							  $temp['modifiedDate']    = $time;
							  $temp['maintaskid']   = $fet['mainTaskTitle'];
							  $temp['dependencyId']           = $fet['dependencyTitle'];
							  $temp['dependencyname']           = $fet['dependencyUser'];
							  $temp['activeStatus']           = $fet['activeStatus'];
							$temp['cDate']		=$time;
							  $targetTime=$fet['targetDate'];
							   $targetdate=$fet['targetDate'];
							   $taskenddate=$fet['taskEndDate'];
							   $taskId=$temp['subTaskId'];
							
							$targetTime=$fet['targetDate'];
							
							 $targetcompare=strtotime($targetTime);
							 $currentdate=strtotime($time);
							
							  $ssq="SELECT CONCAT(
									FLOOR(HOUR(TIMEDIFF('$targetTime','$time')) / 24), ' days, ',
									MOD(HOUR(TIMEDIFF('$targetTime','$time')), 24), ' hours, ',
									mod(minute(TIMEDIFF('$targetTime','$time')),60), ' minutes ')
									AS timeLeft FROM userSubTasks";
							 
								$ssq_res=mysqli_query($con,$ssq)  or (logToFile($logfile,"to list the all pending and completed subtasks of maintasks - get_subtasks.php"));
								
								while($row1=mysqli_fetch_assoc($ssq_res)){
									
									 $timeLt  = $row1['timeLeft'];
								}

						   if($targetcompare>$currentdate){
									
										$temp['timeLeft']=$timeLt;
									}else{
										
										$temp['timeLeft']='-'.$timeLt;
										}

							$time= date("Y-m-d H:i:s");
							
							 $taskendedtime=strtotime($taskenddate);
							 $currentdate=strtotime($targetTime);

			  // extra hours calculation starts
							  $ssq="SELECT CONCAT(
									FLOOR(HOUR(TIMEDIFF('$taskenddate','$targetTime')) / 24), ' days, ',
									MOD(HOUR(TIMEDIFF('$taskenddate','$targetTime')), 24), ' hours, ',
									mod(minute(TIMEDIFF('$taskenddate','$targetTime')),60), ' minutes ')
									AS extratime FROM userSubTasks";
							 
								$ssq_res=mysqli_query($con,$ssq) or (logToFile($logfile," extra hours calculation starts - get_subtasks.php"));
								
								while($row1=mysqli_fetch_assoc($ssq_res)){
									
									 $timeLt  = $row1['extratime'];
								}
										
							 if($taskendedtime>$currentdate){
									
										$temp['extraHours']=$timeLt;
									}else{
										
										$temp['extraHours']='-'.$timeLt;
										}
				 // extra hours calculation ends
				 
							$sql_chat="SELECT * FROM `chat_count` WHERE `taskId`= '$taskId'";
						   $sql_res_chat=mysqli_query($con,$sql_chat)  or (logToFile($logfile," extra hours calculation ends - get_subtasks.php"));
								
							$count='0';
							while($fet1=mysqli_fetch_assoc($sql_res_chat)){
								$count=$fet1['ncount'];
								}
								
							$temp['count']=$count;
							
								$data[$r]=$temp;
							 
							 $r++;
							}
						   
						  $response['status']='true';
						  $response['message']='success';
						  $response['currentDate']=$time;
						 $response['data']=$data;
						   
						   }else{
						 
						  $response['status']='false';
						  $response['message']='no data';
						 }
						 
						 }	
						 
						 //to update the subtasks by checking the dependency of subtask
						 if($action=='update')
						 {
							 if($task_complete_status=='1'){
								 
					 $sql="update userSubTasks set `taskStatus`='$task_status ',`taskStatusDesc`='$task_status_desc',
						 `status`='completed',`modifiedDate`='$time',`taskEndDate`='$time',activeStatus='0' where `subTaskId`='$task_id'";
						 
						 $sql_res=mysqli_query($con,$sql)  or (logToFile($logfile," to update the subtasks by checking the dependency of subtask - get_subtasks.php"));
						 
						 if($sql_res){
							 
							 $sql1="update userSubTasks set `dependencyId`='NA' where `dependencyId`='$task_id' ";
							 
							 $sql_res1=mysqli_query($con,$sql1)  or (logToFile($logfile," to update the subtasks by checking the dependency of subtask - get_subtasks.php"));
							 
							 if($sql_res1){
							 $response['status'] = 'True';
							 $response['message'] = 'Success';
						 }
						 else{
							 $response['status']='false';
							 $response['message']='faied ';
							 }
							 }else{
								 
								 $response['status']='false';
								 $response['message']='no data';	
								 }
				 
						 }
						 else{
						  $sql="update userSubTasks set `taskStatus`='$task_status ',`taskStatusDesc`='$task_status_desc',
						  
						 `modifiedDate`='$time' where `subTaskId`='$task_id'";
						 
						 $sql_res=mysqli_query($con,$sql) or (logToFile($logfile," to update the subtasks by checking the dependency of subtask - get_subtasks.php"));
						 
						 if( $sql_res){
							 
							  $response['status'] = 'True';
							  $response['message'] = 'Success';
							
							 }else{
								 
								 $response['status']='false';
								 $response['message']='no data';	
								 }	 
							 }
							 
							  //taskenddate starts here
							
				$sqlenddate="SELECT MAX(taskEndDate) as taskEndDate,mainTaskId FROM  `userSubTasks` WHERE `subTaskId`='$task_id'";
							
				$sqlenddate_res=mysqli_query($con,$sqlenddate)  or (logToFile($logfile," taskenddate starts here - get_subtasks.php"));
							
				if($rowend=mysqli_fetch_assoc($sqlenddate_res)){
								
				$updatedtaskdate  = $rowend['taskEndDate'];
				$maintaskid		  = $rowend['mainTaskId'];
							
				$sqltasks="UPDATE  `userMainTasks` SET  `taskEndDate` = '$updatedtaskdate' WHERE  `id` ='$maintaskid'";
						   
				$sqltasks_res=mysqli_query($con,$sqltasks)   or (logToFile($logfile," taskenddate starts here - get_subtasks.php"));
							
				}
				//taskenddate ends here 
									 
				 $sqlauto="SELECT * FROM `userSubTasks` WHERE `mainTaskId`='$mainTaskId'";
				 
				 $sqlauto_res=mysqli_query($con,$sqlauto)   or (logToFile($logfile," taskenddate ends here - get_subtasks.php"));
							
				$subtaskscount=mysqli_num_rows($sqlauto_res);
				
				$completedsubtasksscount=0;
				
					 if(mysqli_num_rows($sqlauto_res)>0){
						 
							 while($fet=mysqli_fetch_assoc($sqlauto_res)){
														  
								if($fet['taskStatus']=='100'){
									
									$completedsubtasksscount++;
									
									}else{
										
										$completedsubtasksscount=0;
										
										}	
										
								if($subtaskscount==$completedsubtasksscount){
									
									  $sqltaskupdate="UPDATE  `userMainTasks` SET  `taskStatus` = '100',`completeStatus` = 'completed' WHERE  `id` ='$mainTaskId' ";
									  $sqltaskupdate_res=mysqli_query($con,$sqltaskupdate)   or (logToFile($logfile," taskenddate starts here - get_subtasks.php"));
									  
									  if(mysqli_query($con,$sqltaskupdate)){
										  
										   $response['status'] = 'True';
										   $response['message'] = 'Success';
										  
										  }else{
											  
											   $response['status']='false';
											   $response['message']='no data';
											  
											  }
									}								  
							 }
						 
						 }	
						 
							 }
							 
							 
			//to list the all completed subtasks of maintask based on particular employee	
							 
					 if($action =='completed')
									  {
								
						$sql=" SELECT ust1.subTaskId, ust1.taskTitle as taskTitle, umt.taskTitle as mainTaskTitle, ust1.taskDesc as subTaskDesc, 
								eu3.username as assignedBy, ust1.assignedDate, ust1.targetDate, ust1.taskEndDate, eu1.username as assignedTo,
							   ust1.taskStatus, ust1.status, ust1.dependencyId, ust2.taskTitle as dependencyTitle, eu2.username AS dependencyUser, 
						 ust1.modifiedDate, ust1.taskStatusDesc FROM `userSubTasks` AS ust1 INNER JOIN userMainTasks AS umt ON ust1.mainTaskId = umt.id
						 INNER JOIN emsUsers AS eu1 ON ust1.assignedTo = eu1.empId LEFT JOIN userSubTasks AS ust2 ON ust1.dependencyId = ust2.subTaskId
						 LEFT JOIN emsUsers AS eu2 ON ust2.assignedTo = eu2.empId inner join emsUsers as eu3 on ust1.assignedBy=eu3.empId where
						 ust1.status='completed' and ust1.assignedTo='$empId'order by ust1.subTaskId DESC,ust1.modifiedDate DESC";
		 
				$sql_res=mysqli_query($con,$sql)   or (logToFile($logfile," to list the all completed subtasks of maintask based on particular employee - get_subtasks.php"));
				
				
				if(mysqli_num_rows($sql_res)>0)
				{
					$r=0;
				
				while($fet=mysqli_fetch_assoc($sql_res))
				{
					$dependencyId=$fet['dependencyId'];
					$dependencyUser=$fet['dependencyUser'];
					$targetTime=$fet['targetDate'];
					$modifiedDate=$fet['modifiedDate'];
					 $taskenddate=$fet['taskEndDate'];
				
				if($dependencyId=='NA'){
					
					$fet['dependencyTitle']='NA';
					$fet['dependencyUser']='NA';
					
				}
				
			 //Extra hours starts
							$time= date("Y-m-d H:i:s");
							 $taskendedtime=strtotime($taskenddate);
							 $currentdate=strtotime($targetTime);
							
							  $ssq="SELECT CONCAT(
									FLOOR(HOUR(TIMEDIFF('$taskenddate','$targetTime')) / 24), ' days, ',
									MOD(HOUR(TIMEDIFF('$taskenddate','$targetTime')), 24), ' hours, ',
									mod(minute(TIMEDIFF('$taskenddate','$targetTime')),60), ' minutes ')
									AS extratime FROM userSubTasks";
							 
								$ssq_res=mysqli_query($con,$ssq)   or (logToFile($logfile," to list the all completed subtasks of maintask based on particular employee extra hours starts- get_subtasks.php"));
								
								while($row1=mysqli_fetch_assoc($ssq_res)){
									
									 $timeLt  = $row1['extratime'];
								}
							 
							 if($taskendedtime>$currentdate){
									
										$fet['extraHours']=$timeLt;
									}else{
										
										$fet['extraHours']='-'.$timeLt;
										}
							
							//extra hours ends
									
					$data[$r]=$fet;
					$r++;
							
					}
							$response['status'] = 'True';
							$response['message'] = 'Success';	
							$response['data'] = $data;
						}
						else if(mysqli_num_rows($sql_res)==0){
							$response['status'] = 'NoData';
							$response['message'] = 'Success';	}
						
						else
						{
							$response['status'] = 'false';
							$response['message'] = $empId;	
							}
							 
							}
					//to chnage the status of active subtask		
							
					if($action =='changeStatus' ){
						
					 $sql="update userSubTasks set `status`='verified',`modifiedDate`='$time',`taskEndDate`='$time' where `subTaskId`='$task_id'";
						 
						 $sql_res=mysqli_query($con,$sql)   or (logToFile($logfile," to chnage the status of active subtask - get_subtasks.php"));
						 
						 if($sql_res){
							 
							 
							 $response['status'] = 'True';
							$response['message'] = 'Success';
							 }
						 
						 else{
							 $response['status'] = 'false';
							$response['message'] = 'no data';	
							
							  }
						
							}	
							
			//to list the all verified subtasks of maintask based on particular employee	
							if($action =='verified')
					  {
				
		$sql=" SELECT ust1.subTaskId, ust1.taskTitle as taskTitle, umt.taskTitle as mainTaskTitle, ust1.taskDesc as subTaskDesc, 
				eu3.username as assignedBy, ust1.assignedDate, ust1.targetDate, ust1.taskEndDate, eu1.username as assignedTo,
			   ust1.taskStatus, ust1.status, ust1.dependencyId, ust2.taskTitle as dependencyTitle, eu2.username AS dependencyUser, 
		 ust1.modifiedDate, ust1.taskStatusDesc FROM `userSubTasks` AS ust1 INNER JOIN userMainTasks AS umt ON ust1.mainTaskId = umt.id
		 INNER JOIN emsUsers AS eu1 ON ust1.assignedTo = eu1.empId LEFT JOIN userSubTasks AS ust2 ON ust1.dependencyId = ust2.subTaskId
		 LEFT JOIN emsUsers AS eu2 ON ust2.assignedTo = eu2.empId inner join emsUsers as eu3 on ust1.assignedBy=eu3.empId where
		 ust1.status='verified' and ust1.assignedTo='$empId'";
		 
				$sql_res=mysqli_query($con,$sql)  or (logToFile($logfile,"to list the all verified subtasks of maintask based on particular employee- get_subtasks.php"));
				
				
				if(mysqli_num_rows($sql_res)>0)
				{
					$r=0;
				
				while($fet=mysqli_fetch_assoc($sql_res))
				{
					$dependencyId=$fet['dependencyId'];
					$dependencyUser=$fet['dependencyUser'];
					$targetTime=$fet['targetDate'];
					$modifiedDate=$fet['modifiedDate'];
				
				if($dependencyId=='NA'){
					
					$fet['dependencyTitle']='NA';
					$fet['dependencyUser']='NA';
					
				}
				
			$data[$r]=$fet;
			$r++;
						
					}
				
							$response['status'] = 'True';
							$response['message'] = 'Success';	
							$response['data'] = $data;
						}
						else if(mysqli_num_rows($sql_res)==0){
							$response['status'] = 'NoData';
							$response['message'] = 'Success';	}
						
						else
						{
							$response['status'] = 'false';
							$response['message'] = $empId;	
							
							
							}
							 
							}	
							

				 else     
			{
				$result['status']='FALSE';
				$result['message']='Request method wrong!';
			}

			header("Content-Type:application/json");
			echo json_encode($response);	

	?>
