<?php

/*
FileName:manageMaintasks.php
Version:1.0.1
Purpose:to perform multiple actions on modules( add, modify, delete )
Devloper:Rishitha, krishna tulasi, jagadeesh
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

			
				$action  		    = $obj['action'];
		 	    $maintaskid  		= $obj['mainTaskId'];
		 	    $title				= $obj['title'];
				$description		= $obj['description'];
				$module_id  		= $obj['module_id'];
				$idea_id     		= $obj['idea_id'];
				$assignedTo     	= $obj['added_to'];
				$assignedBy			= $obj['added_by'];

		 	if($action == 'add')
				{
			
					$sql_add="insert into userMainTasks(`ideaId`,`moduleId`,`createdDate`,`modifiedBy`,`modifiedDate`,`taskTitle`,`taskDesc`,`assignedBy`,`assignedTo`,`assignedDate`) 
					values ('$idea_id','$module_id','$time','$assignedBy','$time','$title','$description','$assignedBy','$assignedTo','$time')";		
					$sql_add_res=mysqli_query($con,$sql_add) or (logToFile($logfile,"  adding user maintask   - manageMaintasks.php"));
					
					if($sql_add_res){
					$result['status'] = 'True';
					$result['message'] = 'Success';	
										
				}
				
				else
				{
					$result['status'] = 'false';
					$result['message'] = 'Something went wrong';
						}
					
				}
				
				if($action == 'maintaskdelete'){
					
					$sql1="select ifnull((sum(case when status in ('pending','completed','verified') then 1 else 0 end)),0) as remaining from userSubTasks where mainTaskId='$maintaskid'";
					
						$sql_res1=mysqli_query($con,$sql1) or (logToFile($logfile," maintaskdelete  - manageMaintasks.php"));
							
							$fet=mysqli_fetch_assoc($sql_res1);
						
						if($fet['remaining']==0 ){
										
							 $sql="UPDATE  `userMainTasks` SET  `completeStatus` =  'deleted' WHERE  `id` ='$maintaskid'";			
						
							$sql_res=mysqli_query($con,$sql) or (logToFile($logfile," maintask update - manageMaintasks.php"));
										
							$result['status'] = 'True';
							$result['message'] = 'Success';	
							
							
								}else
							{
							$result['status'] = 'false';
							$result['message'] = 'Having subtasks,you cannot delete this maintask';
								}
					
					}
					
					if($action == 'modify'){
						

							$sqll="UPDATE `userMainTasks` SET  `taskTitle`='$title',`taskDesc` ='$description',`assignedBy`='$assignedBy',`assignedTo`='$assignedTo',`modifiedBy`='$assignedBy',`modifiedDate`='$time' WHERE  `id` ='$maintaskid' and `ideaId`='$idea_id' ";
						
						     $sql_ress=mysqli_query($con,$sqll) or (logToFile($logfile," maintask modify  - manageMaintasks.php"));
					
					if($sql_ress){
										
						$result['status'] = 'True';
					    $result['message'] = 'Success';

					    
					    
						         }
						         else{
							
						$result['status'] = 'false';
					    $result['message'] = 'unSuccessfull';
							
							           }
						
						}//modify
						
					if($action == 'verify'){
						
						$sql="SELECT * FROM `userSubTasks` WHERE `mainTaskId`='$maintaskid'";
						
						$sql_res=mysqli_query($con,$sql) or (logToFile($logfile," maintask verify  - manageMaintasks.php"));
						
					     $count=mysqli_num_rows($sql_res);
					   
					     $rowscount=0;
					     
                         if(mysqli_num_rows($sql_res)>0){
							 
							  while($fet=mysqli_fetch_assoc($sql_res)){
								  
								  if($fet['taskStatus']=='100' && $fet['status']=='completed'){
									  $rowscount++;
									  
									  }else{
										    $rowscount=0;
										  }
								  
								  }
								  if($count==$rowscount){
									  
									  $sqlupdate="UPDATE  `userMainTasks` SET  `taskStatus` = '100',`completeStatus` = 'verified' WHERE  `id` ='$maintaskid' ";
									  
									  $sql_resupdate=mysqli_query($con,$sqlupdate);
									  
									  
									  $sqlsub="UPDATE userSubTasks SET status='verified' WHERE mainTaskId='$maintaskid'";
									  
									   $sqlsub_res=mysqli_query($con,$sqlsub);
									  
									  
									$result['status'] = 'True';
					                $result['message'] = 'Success';
					                
				
					                
					                
									  
									  }else{
										  
										  $result['status'] = 'false';
					                      $result['message'] = 'unSuccessfull';
										  
										  }
								  
							 }//rows
							 
						} //verify	

							
			header("Content-Type:application/json");
			echo json_encode($result);	
			?>
