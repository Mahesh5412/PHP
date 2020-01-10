	<?php

		/*
		FileName:getmanagemaintasks.php
		Version:1.1
		Purpose:To get maintasks
		Devloper:rishitha,rakesh
		*/

				session_start();

				$json = file_get_contents('php://input');
				$obj = json_decode($json,true);
		
				$corp_code 				= $obj['crop'];
				$_SESSION["corp_code"] 	= $corp_code;

		include 'connect.php';

        // for Logs 
        $logfile = 'log/log' .date('d-M-Y') . '.log';

		   
			header('Access-Control-Allow-Origin: *');
			header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
		
			$maintaskId = array();
			
			date_default_timezone_set('Asia/Kolkata'); 
			$time= date("Y-m-d H:i:s");
		
		if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET")
			{
					  
				$action      = $obj['action'];
				$userType	 = $obj['userType']; 
				$userid      = $obj['empId'];
					
				$sql="";
				
		  //to get pending maintasks

			  if($action =='pending')
			  {  
					if($userType == 'admin' || $userType == 'Approver' || $userType == 'Manager' ){
					  
					  $sql="SELECT umt.*, ui.ideaTitle, eu1.username as assignedby, eu2.username as assignedto FROM `userMainTasks` as umt 
					  inner join userIdeas as ui on umt.ideaId=ui.ideaId inner join emsUsers as eu1 on umt.assignedBy=eu1.empId inner join emsUsers as eu2 
					  on umt. assignedTo= eu2. empId where umt.taskStatus<'100' && `completeStatus`='pending' order by umt.modifiedDate DESC,umt.id DESC";
				   }else{
					   
					   $sql="SELECT umt.*, ui.ideaTitle, eu1.username as assignedby, eu2.username as assignedto FROM `userMainTasks` as umt 
					   inner join userIdeas as ui on umt.ideaId=ui.ideaId inner join emsUsers as eu1 on umt.assignedBy=eu1.empId inner join emsUsers as eu2 
					   on umt. assignedTo= eu2. empId where umt.taskStatus<'100' && `completeStatus`='pending' && `assignedTo`='$userid' order by umt.modifiedDate DESC,umt.id DESC";
					   
					   }
					   $sql_res=mysqli_query($con,$sql)  or (logToFile($logfile," to get pending maintasks  - getmanagemaintasks.php"));
					   
					   if(mysqli_num_rows($sql_res)>0){
						   
						   $r=0;
						   
						while($fet=mysqli_fetch_assoc($sql_res)){
							
							 $temp['projectitle']      = $fet['ideaTitle'];
							 $temp['ideano']   		   = $fet['ideaId'];
							 $temp['taskid']  		   = $fet['id'];
							 $temp['taskStatus']       = $fet['taskStatus'];
							 $temp['tasktitle']        = $fet['taskTitle'];
							 $temp['taskdescription']  = $fet['taskDesc'];
							 $temp['assignby']         = $fet['assignedby'];
							 $temp['assigntto']        = $fet['assignedto'];
							 $temp['taskStatusDesc']   = $fet['taskStatusDesc'];
							 $temp['assignedon']       = $fet['assignedDate'];
							 $temp['taskEndDate']      = $fet['taskEndDate'];
							 $temp['completeStatus']   = $fet['completeStatus'];
							 $temp['createdon']        = $fet['modifiedDate'];
							 $temp['moduleId']         = $fet['moduleId'];
							 $temp['targettime']       = $fet['targetDate'];
							 $temp['projectitle']      = $fet['ideaTitle'];
							 $temp['assignedTo']       = $fet['assignedTo'];
							 $temp['cDate']			   = $time;
							 
							 $maintaskid=$fet['id'];
							 
								$targetTime=$fet['targetDate'];
											
								$time= date("Y-m-d H:i:s");
								$targetTime=$fet['targetDate'];
								
								$targetcompare=strtotime($targetTime);
								$currentdate=strtotime($time);
						//time left calculation starts									
									  $ssq="SELECT CONCAT(
											FLOOR(HOUR(TIMEDIFF('$targetTime','$time')) / 24), ' days, ',
											MOD(HOUR(TIMEDIFF('$targetTime','$time')), 24), ' hours, ',
											mod(minute(TIMEDIFF('$targetTime','$time')),60), ' minutes ')
											AS timeLeft FROM userSubTasks";
							 
								$ssq_res=mysqli_query($con,$ssq);
								
								while($row1=mysqli_fetch_assoc($ssq_res)){
									
									
									$timeLt = $row1['timeLeft'];
								}
								
								if($targetcompare>$currentdate){
									
										$temp['timeLeft']=$timeLt;
									}else{
										
										$temp['timeLeft']='-'.$timeLt;
										}
								
							//time left calculation ends	
								
								$sqldays="SELECT FLOOR(HOUR(TIMEDIFF('$targetTime','$time')) / 24)AS days FROM userSubTasks";
								
								$sqldays_res=mysqli_query($con,$sqldays)  or (logToFile($logfile," time left calculation ends  - getmanagemaintasks.php"));
								
								while($row12=mysqli_fetch_assoc($sqldays_res)){
									
									$days = $row12['days'];
									
								}
							//userpreference time calculation starts		
								$sqll="SELECT MOD(HOUR(TIMEDIFF('$targetTime','$time')),24)AS hours FROM userSubTasks";
											
								$sqll_res=mysqli_query($con,$sqll)  or (logToFile($logfile," userpreference time calculation starts  - getmanagemaintasks.php"));
								
								while($row11=mysqli_fetch_assoc($sqll_res)){
									
									$hours = $row11['hours'];
									
								}
										   
													$d = $days*24;
													$t = $d+$hours;
									 
												$temp['ptime']=$t;
			  
							 //userpreference time calculation ends

							$sql_chat="SELECT * FROM `chat_count` WHERE `taskId`= '$maintaskid'";
						    $sql_res_chat=mysqli_query($con,$sql_chat)  or (logToFile($logfile," userpreference time calculation ends  - getmanagemaintasks.php"));
								
							$count='0';
							
							while($fet1=mysqli_fetch_assoc($sql_res_chat)){
								$count=$fet1['ncount'];
								}
								
							$temp['count']=$count;
						  
							 $data[$r]=$temp;
							 
							 $r++;
							
							}

						  $result['status']='true';
						  $result['message']='success';
						  $result['data']=$data;
					   
							}else{
								
						  $result['status']='false';
						  $result['message']='no data';
								}
							  
			  }
					  
				//to get completed maintasks
								  
			  if($action =='completed')
			  {
						
				  if($userType == 'admin' || $userType == 'Approver' || $userType == 'Manager' ){
					 
					 $sql1="SELECT mainTaskId FROM (SELECT mainTaskId, if( p / y <=1 AND p / y >0, 1, 0 ) AS r FROM (SELECT mainTaskId, x, p, y FROM 
					 (SELECT mainTaskId, if( (sum( t.taskStatus ) / count( * ) = '100' ) , 1, 0) AS x, SUM(
						CASE WHEN STATUS = 'completed' THEN 1 ELSE 0 END ) AS p, COUNT( * ) AS y
						FROM `userSubTasks` AS t INNER JOIN `userMainTasks` AS umt ON t.mainTaskId = umt.id
						GROUP BY mainTaskId) AS tt WHERE x =1) AS ttt) AS tttt WHERE r =1";
					  
					  $sql_res1=mysqli_query($con,$sql1) or (logToFile($logfile," to get completed maintasks - getmanagemaintasks.php"));
					
					  
					  if(mysqli_num_rows($sql_res1)>0){
						  
						  $r=0;
					 
					 $maintaskId1="0";
					 
					  while($row1=mysqli_fetch_assoc($sql_res1)){
					  
						$maintaskId=$row1['mainTaskId'];
						
						$maintaskId1=$maintaskId1.",".$maintaskId;
					  
					 }  
					   $sql="SELECT umt.*, ideaTitle, eu1.userName as asignedBy, eu2.userName as asignedTo  FROM `userMainTasks` as umt INNER JOIN userIdeas 
					   as ui ON umt.ideaId=ui.ideaId INNER JOIN emsUsers as eu1 ON umt.assignedBy=eu1.empId INNER JOIN emsUsers AS eu2 ON umt.assignedTo=eu2.empId 
					   WHERE id IN ($maintaskId1) order by umt.modifiedDate DESC,umt.id DESC";
					 
				   }
				   }else{
					   
					  $sql1="SELECT mainTaskId FROM (SELECT mainTaskId, if( p / y <=1 AND p / y >0, 1, 0 ) AS r FROM (SELECT mainTaskId, x, p, y FROM 
					 (SELECT mainTaskId, if( (sum( t.taskStatus ) / count( * ) = '100' ) , 1, 0) AS x, SUM(
						CASE WHEN STATUS = 'completed' THEN 1 ELSE 0 END ) AS p, COUNT( * ) AS y
						FROM `userSubTasks` AS t INNER JOIN `userMainTasks` AS umt ON t.mainTaskId = umt.id
						GROUP BY mainTaskId) AS tt WHERE x =1) AS ttt) AS tttt WHERE r =1";
					  
						$sql_res1=mysqli_query($con,$sql1)  or (logToFile($logfile," to get completed maintasks - getmanagemaintasks.php"));
						
						if(mysqli_num_rows($sql_res1)>0){
							
						
							 $maintaskId1="0";
							 
							  while($row1=mysqli_fetch_assoc($sql_res1)){
					  
						$maintaskId=$row1['mainTaskId'];
						
						$maintaskId1=$maintaskId1.",".$maintaskId;
					  
						 }
						 $sql="SELECT umt.*, ideaTitle, eu1.userName as asignedBy, eu2.userName as asignedTo  FROM `userMainTasks` as umt INNER JOIN userIdeas 
						as ui ON umt.ideaId=ui.ideaId INNER JOIN emsUsers as eu1 ON umt.assignedBy=eu1.empId INNER JOIN emsUsers AS eu2 ON umt.assignedTo=eu2.empId 
						WHERE id IN ($maintaskId1) && `assignedTo`='$userid' order by umt.modifiedDate DESC,umt.id DESC";	
						
							}			
					   }
					   
					   $sql_res=mysqli_query($con,$sql);
					   
					   if(mysqli_num_rows($sql_res)>0){
						   $r=0;
						   
						while($fet=mysqli_fetch_assoc($sql_res)){
							
							 $temp['projectitle']  = $fet['ideaTitle'];
							 $temp['taskid']  = $fet['id'];
							 $temp['taskStatus']  = $fet['taskStatus'];
							 $temp['tasktitle']  = $fet['taskTitle'];
							 $temp['taskdescription']  = $fet['taskDesc'];
							 $temp['assignby']  = $fet['asignedBy'];
							 $temp['assigntto']  = $fet['asignedTo'];
							 $temp['taskStatusDesc']  = $fet['taskStatusDesc'];
							 $temp['assignedon']  = $fet['assignedDate'];
							 $temp['targettime']  = $fet['targetDate'];
							 $temp['taskEndDate']  = $fet['taskEndDate'];
							 $temp['completeStatus']  = $fet['completeStatus'];
							 $temp['createdon']  = $fet['modifiedDate'];
							 $temp['moduleId']  = $fet['moduleId'];
							 $temp['ideano']  = $fet['ideaId'];
							 $temp['targettime']       = $fet['targetDate'];
							 $temp['assignedTo']      = $fet['assignedTo'];
							 $temp['cDate']=$time;
							 $maintaskid=$temp['taskid'];
							 
							 $targetdate=$fet['targetDate'];
							 $taskenddate=$fet['taskEndDate'];
							 $targetTime=$fet['targetDate'];
							 
							  $taskendedtime=strtotime($taskenddate);
							 $currentdate=strtotime($targetTime);
						
						  //extra hours calculation starts	 
							$ssq="SELECT CONCAT(
									FLOOR(HOUR(TIMEDIFF('$taskenddate','$targetTime')) / 24), ' days, ',
									MOD(HOUR(TIMEDIFF('$taskenddate','$targetTime')), 24), ' hours, ',
									mod(minute(TIMEDIFF('$taskenddate','$targetTime')),60), ' minutes ')
									AS extratime FROM userSubTasks";
							 
								$ssq_res=mysqli_query($con,$ssq)  or (logToFile($logfile," extra hours calculation starts - getmanagemaintasks.php"));
								
								while($row1=mysqli_fetch_assoc($ssq_res)){
									
									 $timeLt  = $row1['extratime'];
								}
				
								  if($taskendedtime>$currentdate){
									
										$temp['extraHours']=$timeLt;
									}else{
										
										$temp['extraHours']='-'.$timeLt;
										}

									   
							 //extra hours calculation ends

							 $sqldays="SELECT FLOOR(HOUR(TIMEDIFF('$targetTime','$time')) / 24)AS days FROM userSubTasks";
								
								$sqldays_res=mysqli_query($con,$sqldays)  or (logToFile($logfile," extra hours calculation ends - getmanagemaintasks.php"));
								
								while($row12=mysqli_fetch_assoc($sqldays_res)){
									
									$days = $row12['days'];
									
								}
										
								$sqll="SELECT MOD(HOUR(TIMEDIFF('$targetTime','$time')),24)AS hours FROM userSubTasks";
											
								$sqll_res=mysqli_query($con,$sqll) or (logToFile($logfile," extra hours calculation ends - getmanagemaintasks.php"));
								
								while($row11=mysqli_fetch_assoc($sqll_res)){
									
									$hours = $row11['hours'];
									
								}
							  
										$d = $days*24;
										$t = $d+$hours;
									 
									
									$temp['ptime']=$t;
								
							 
							 $data[$r]=$temp;
							 
							 $r++;
							
							}   
						  $result['status']='true';
						  $result['message']='success';
						  $result['data']=$data;
							}else{
								
						  $result['status']='false';
						  $result['message']='unsuccess';
								}  
			  }
			  
			 // to get verified maintasks 
			   if($action =='verified')
			  {
				
				  if($userType == 'admin' || $userType == 'Approver' || $userType == 'Manager' ){
					  
					  $sql1="SELECT mainTaskId FROM (select mainTaskId, ititle, IF(p/y='1',1, 0) as q from (SELECT mainTaskId, if((sum(t.taskStatus)/count(*)='100'),1,0) as x, ui.ideaTitle as ititle,SUM(case when status = 'verified' then 1 else 0 end) AS p, COUNT(*) as y FROM `userSubTasks` as t inner join  userMainTasks as umt on t.mainTaskId=umt.id left join userIdeas as ui on ui.ideaId=umt.ideaId group by mainTaskId) tt where x=1)as tttt WHERE q=1";
					  
					  $sql_res1=mysqli_query($con,$sql1) or (logToFile($logfile,"  to get verified maintasks - getmanagemaintasks.php"));
					  
					   if(mysqli_num_rows($sql_res1)>0){
						   
						  $r=0;
						  
					 $maintaskId1="0";
					 
					while($row1=mysqli_fetch_assoc($sql_res1)){
					 
						$maintaskId=$row1['mainTaskId'];
						
						$maintaskId1=$maintaskId1.",".$maintaskId;
						
						$r++;
					}
				
					$sql="SELECT umt.*, ideaTitle, eu1.userName as asignedBy, eu2.userName as asignedTo  FROM `userMainTasks` as umt INNER JOIN userIdeas as ui ON umt.ideaId=ui.ideaId INNER JOIN emsUsers as eu1 ON umt.assignedBy=eu1.empId INNER JOIN emsUsers AS eu2 ON umt.assignedTo=eu2.empId WHERE id IN ($maintaskId1)";

					 
				  } 
				   }else{
					   
					   
						 $sql1="SELECT mainTaskId FROM (select mainTaskId, ititle, IF(p/y='1',1, 0) as q from (SELECT mainTaskId, if((sum(t.taskStatus)/count(*)='100'),1,0) as x, ui.ideaTitle as ititle,SUM(case when status = 'verified' then 1 else 0 end) AS p, COUNT(*) as y FROM `userSubTasks` as t inner join  userMainTasks as umt on t.mainTaskId=umt.id left join userIdeas as ui on ui.ideaId=umt.ideaId group by mainTaskId) tt where x=1)as tttt WHERE q=1";
					  
						 $sql_res1=mysqli_query($con,$sql1);
					  
						  if(mysqli_num_rows($sql_res1)>0){
						   
						  $r=0;
						  
					 $maintaskId1="0";
					 
					while($row1=mysqli_fetch_assoc($sql_res1)){
					 
						$maintaskId=$row1['mainTaskId'];
						
						$maintaskId1=$maintaskId1.",".$maintaskId;
						
						$r++;
					}
				
					$sql="SELECT umt.*, ideaTitle, eu1.userName as asignedBy, eu2.userName as asignedTo  FROM `userMainTasks` as umt INNER JOIN userIdeas as ui 
						ON umt.ideaId=ui.ideaId INNER JOIN emsUsers as eu1 ON umt.assignedBy=eu1.empId INNER JOIN emsUsers AS eu2 ON umt.assignedTo=eu2.empId 
						WHERE id IN ($maintaskId1) && `assignedTo`='$userid' order by umt.modifiedDate DESC,umt.id DESC";

					 
				  }
						
					
					   }
					   $sql_res=mysqli_query($con,$sql)  or (logToFile($logfile,"  to get verified maintasks - getmanagemaintasks.php"));
					   
							 if(mysqli_num_rows($sql_res)>0){
								 
						   $r=0;
						   
						while($fet=mysqli_fetch_assoc($sql_res)){
							
							 $temp['projectitle']  = $fet['ideaTitle'];
							 $temp['taskid']  = $fet['id'];
							 $temp['taskStatus']  = $fet['taskStatus'];
							 $temp['tasktitle']  = $fet['taskTitle'];
							 $temp['taskdescription']  = $fet['taskDesc'];
							 $temp['assignby']  = $fet['asignedBy'];
							 $temp['assigntto']  = $fet['asignedTo'];
							 $temp['taskStatusDesc']  = $fet['taskStatusDesc'];
							 $temp['assignedon']  = $fet['assignedDate'];
							 $temp['targettime']  = $fet['targetDate'];
							 $temp['taskEndDate']  = $fet['taskEndDate'];
							 $temp['completeStatus']  = $fet['completeStatus'];
							 $temp['createdon']  = $fet['modifiedDate'];
							 $temp['moduleId']  = $fet['moduleId'];
							  $temp['ideano']  = $fet['ideaId'];
							  $temp['cDate']=$time;
						   
							 $maintaskid=$temp['taskid'];
							 
							$targetdate=$fet['targetDate'];
							 $taskenddate=$fet['taskEndDate'];
							 $targetTime=$fet['targetDate'];
							 
							  $taskendedtime=strtotime($taskenddate);
							 $currentdate=strtotime($targetTime);
							 
						//extra hours calculation starts	 
							 $ssq="SELECT CONCAT(
									FLOOR(HOUR(TIMEDIFF('$taskenddate','$targetTime')) / 24), ' days, ',
									MOD(HOUR(TIMEDIFF('$taskenddate','$targetTime')), 24), ' hours, ',
									mod(minute(TIMEDIFF('$taskenddate','$targetTime')),60), ' minutes ')
									AS extratime FROM userSubTasks";
							 
								$ssq_res=mysqli_query($con,$ssq)  or (logToFile($logfile,"  to get verified maintasks extra hours calculation starts - getmanagemaintasks.php"));
								
								while($row1=mysqli_fetch_assoc($ssq_res)){
									
									 $timeLt  = $row1['extratime'];
									 $temp['extraHours']      =$row1['extratime'];
								}

								  if($taskendedtime>$currentdate){
									
										$temp['extraHours']=$timeLt;
									}else{
										
										$temp['extraHours']='-'.$timeLt;
										}
							//extra hours calculation ends  
							 
							 $data[$r]=$temp;
							 
							 $r++;
							
							}

							$result['status']='true';
						  $result['message']='success';
						  $result['data']=$data;
						   
							}else{
								
						$result['status']='false';
						  $result['message']='no data';
								}			  
			  }
				
		  }
							   
		 else     
			{
				$result['status']='FALSE';
				$result['message']='Request method wrong!';
			}
		 header("Content-Type:application/json"); 
		 echo json_encode($result);
		?>
