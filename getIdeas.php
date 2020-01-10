 <?php

	/*
	FileName:ReactgetIdeas.php
	Version:1.1
	Purpose:Getting the List of all requested,approved and rejected projects
	Devloper:Rishitha,sriram
	*/

     session_start();
	
	$json = file_get_contents('php://input');
    $obj = json_decode($json,true);
    
   // $corp_code 		= 'ptmsreact';
    
      $corp_code 		= $obj['crop'];
     
     $_SESSION["corp_code"] = $corp_code;

			include 'connect.php';

        // for Logs 
        $logfile = 'log/log' .date('d-M-Y') . '.log';

			date_default_timezone_set('Asia/Kolkata'); 
			$time= date("Y-m-d H:i:s");
			
			
			$action     = $obj['action'];
			$empId 		= $obj['empId'];
			$userType	= $obj['userType'];
			$ideaId		= $obj['ideaId'];
		
		    $sql_getReqIdeas='';
		    $sql_getApprovedIdeas='';
		    
	//for getting all requested projects

		if($action == 'requested'){	
			
			if($userType  == 'admin'|| $userType == 'Approver' || $userType == 'Manager')
				{
				
				$sql_getReqIdeas="SELECT userIdeas.*,emsUsers.fullName,emsUsers.username FROM `userIdeas` inner join emsUsers on userIdeas.empId=emsUsers.empId 
				where approvalStatus='pending' and reopenStatus<>'deleted' ORDER BY `userIdeas`.`ideaId`  DESC";
				
			    }else{
				
				$sql_getReqIdeas="SELECT userIdeas.*,emsUsers.fullName,emsUsers.username FROM `userIdeas` inner join emsUsers on userIdeas.empId=emsUsers.empId
				 where approvalStatus='pending' and reopenStatus<>'deleted' and userIdeas.empId =$empId ORDER BY `userIdeas` . `ideaId`  DESC";
		         }
			
				$sql_getReqIdeas_res=mysqli_query($con,$sql_getReqIdeas)  or (logToFile($logfile," for getting all requested projects - ReactgetIdeas.php"));
				
				if(mysqli_num_rows($sql_getReqIdeas_res) > 0)
				{
					
				$r = 0;
				
				while($fet = mysqli_fetch_assoc($sql_getReqIdeas_res))
						{
								
					$temp['idea_id']               =  $fet['ideaId'];	
					$temp['emp_id']                =  $fet['empId'];
					$temp['idea_title']            =  $fet['ideaTitle'];
					$temp['idea_description']      =  $fet['ideaDesc'];
					
					$timestamp      			   = $fet['modifiedDate'];
					$splitTimeStamp 			   = explode(" ",$timestamp);
					$date          				   = $splitTimeStamp[0];
					$time           			   = $splitTimeStamp[1];
					$temp['created_on']            = $date;
					$temp['userName']              =  $fet['username'];
					
					$data[$r] = $temp;
							$r++;
						}
						$result['status']  = 'True';
						$result['message'] = 'Success';	
						$result['data']    = $data;
				}else
				{
						$result['status'] = 'False';
						$result['message'] = 'No Data Available';	
						$result['data'] = $data;
				}	
			}
			
	//for getting all approved projects

    else if($action == 'approved')
    {
		if($userType  == 'admin'|| $userType == 'Approver' || $userType == 'Manager')
				{
				  $sql_getApprovedIdeas="SELECT userIdeas. * , eu1.fullName, eu1.username as requestedBy, IFNULL(eu2.userName,'NA') AS releaseOwnerName
										FROM  `userIdeas` 
										INNER JOIN emsUsers AS eu1 ON userIdeas.empId = eu1.empId
										LEFT JOIN emsUsers AS eu2 ON userIdeas.releaseOwner = eu2.empId
										WHERE approvalStatus =  'approved'	&&	ideaStatus<>'completed'
										AND reopenStatus <>  'deleted' 
										ORDER BY  `acceptedDate` DESC";
			}
			else{
            $sql_getApprovedIdeas="SELECT userIdeas. * , eu1.username requestedBy, ifnull(eu2.userName,'NA') AS releaseOwnerName, eu3.userName AS acceptedBy
           FROM  `userIdeas` INNER JOIN emsUsers AS eu1 ON userIdeas.empId = eu1.empId LEFT JOIN emsUsers AS eu2 ON userIdeas.releaseOwner = eu2.empId
           INNER JOIN emsUsers AS eu3 ON userIdeas.acceptedBy = eu3.empId
           WHERE approvalStatus =  'approved' && ideaStatus <>  'completed' && ( userIdeas.empId ='$empId'
           OR userIdeas.releaseOwner ='$empId' ) && reopenStatus <>  'deleted'";
				}

				$sql_getApprovedIdeas_res=mysqli_query($con,$sql_getApprovedIdeas)  or (logToFile($logfile," for getting all approved projects - ReactgetIdeas.php"));
				if(mysqli_num_rows($sql_getApprovedIdeas_res) > 0)
				{
				$r = 0;
				
				while($fet = mysqli_fetch_assoc($sql_getApprovedIdeas_res))
						{
										
					$temp['idea_id']               =  $fet['ideaId'];	
					$temp['emp_id']                =  $fet['empId'];
					$temp['idea_title']            =  $fet['ideaTitle'];
					$temp['releaseOwner']          =  $fet['releaseOwnerName'];
					
					
					$id=$fet['ideaId'];
					
					$module_sql="SELECT COUNT(*) as count FROM `moduleTable` where ideaId='$id'";
					$module_sql_res=mysqli_query($con,$module_sql)  or (logToFile($logfile," for getting all approved projects module table - ReactgetIdeas.php"));
					
					$row11=mysqli_fetch_array($module_sql_res);
					
					$temp['count']               = $row11['count'];
					
					$temp['id']                  = $fet['ideaId'];
					
					$task_sql1="SELECT ifnull(SUM(CASE WHEN completeStatus='verified' THEN 1 ELSE 0 END),0) as num FROM `userMainTasks` WHERE ideaId='$id'";
				    $task_sql1_res=mysqli_query($con,$task_sql1);
					
					$row12=mysqli_fetch_array($task_sql1_res);
					
					
                     $temp['taskscount']               = $row12['num'];	
                     
					$timestamp   				= $fet['acceptedDate'];
					$splitTimeStamp 			= explode(" ",$timestamp);
					$date 						= $splitTimeStamp[0];
					$time 						= $splitTimeStamp[1];
					$temp['acceptedDate']       = $date;
					$temp['userName']           =  $fet['requestedBy'];
					
					$data[$r] = $temp;
							
							$r++;
						}
						
						$result['status']  = 'True';
						$result['message'] = 'Success';	
						$result['data']    = $data;
				}
				
				else
				{
						$result['status'] = 'False';
						$result['message'] = 'No Data Available';	
						$result['data'] = $data;
				}	
		
		}

		//to get the list of all rejected projects

		else if($action== 'rejected')

			{
				if($userType=='admin' || $userType=='manager' || $userType == 'approver')
				{
				
			$sql_getRejIdeas="SELECT userIdeas.*,emsUsers.fullName,emsUsers.userName FROM `userIdeas` inner join emsUsers on userIdeas.empId=emsUsers.empId 
			where approvalStatus='rejected' and reopenStatus<>'deleted' order by 'rejectedDate' desc";
	
				}else {
					
			$sql_getRejIdeas="SELECT userIdeas.*,emsUsers.fullName,emsUsers.userName FROM `userIdeas` inner join emsUsers on userIdeas.empId=emsUsers.empId 
			where approvalStatus='rejected' and userIdeas.empId='$empId'order by 'rejectedDate' desc";
			
					}	
					
				$sql_getRejIdeas_res=mysqli_query($con,$sql_getRejIdeas)  or (logToFile($logfile,"to get the list of all rejected projects - ReactgetIdeas.php"));
				
				$r =0;
				
				while($fet=mysqli_fetch_assoc($sql_getRejIdeas_res))
				{
					$data[$r]=$fet;
					
					$r++;
					}
					
					if($r == 0){
						
					$result['status']  = 'False';
					$result['message'] = 'No data available';	
					}else{
						
						$result['status']  = 'True';
						$result['message'] = 'Success';	
						$result['data']    = $data;
				}
				
				}

			//to get list of all completed projects	
		
		 else if($action == 'completed')
         {
		
		if($userType  == 'admin' || $userType=='Manager' || $userType == 'Approver')
				{

				$sql_getApprovedIdeas=" SELECT userIdeas. * , eu1.fullName, eu1.username, IFNULL(eu2.userName,'NA') AS releaseOwnerName
										FROM  `userIdeas` 
										INNER JOIN emsUsers AS eu1 ON userIdeas.empId = eu1.empId
										LEFT JOIN emsUsers AS eu2 ON userIdeas.releaseOwner = eu2.empId
										WHERE ideaStatus =  'completed' && reopenStatus <>  'deleted' 
										ORDER BY  `acceptedDate` DESC ";
			}else{
				 $sql_getApprovedIdeas="SELECT userIdeas. * , eu1.fullName, eu1.username, IFNULL(eu2.userName,'NA') AS releaseOwnerName
										FROM  `userIdeas` 
										INNER JOIN emsUsers AS eu1 ON userIdeas.empId = eu1.empId
										LEFT JOIN emsUsers AS eu2 ON userIdeas.releaseOwner = eu2.empId
										WHERE ideaStatus =  'completed' && reopenStatus <>  'deleted'&& userIdeas.empId='$empId'
										ORDER BY  `acceptedDate` DESC ";
				}
			
			
				$sql_getApprovedIdeas_res=mysqli_query($con,$sql_getApprovedIdeas)  or (logToFile($logfile,"to get list of all completed projects - ReactgetIdeas.php"));
				
				if(mysqli_num_rows($sql_getApprovedIdeas_res) > 0)
				{
				$r = 0;
				
				while($fet = mysqli_fetch_assoc($sql_getApprovedIdeas_res))
						{
								
					$temp['idea_id']               =  $fet['ideaId'];	
					$temp['emp_id']                =  $fet['empId'];
					$temp['idea_title']            =  $fet['ideaTitle'];
					$temp['releaseOwner']          =  $fet['releaseOwnerName'];
					
					
					$timestamp					   = $fet['acceptedDate'];
					$splitTimeStamp 			   = explode(" ",$timestamp);
					$date 						   = $splitTimeStamp[0];
					$time 						   = $splitTimeStamp[1];
					$temp['acceptedDate']          = $date;
					$temp['userName']              =  $fet['username'];
					
					
					$data[$r] = $temp;
							
							$r++;
						}
						$result['status']  = 'True';
						$result['message'] = 'Success';	
						$result['data']    = $data;
				}
				
				else
				{
						$result['status']  = 'False';
						$result['message'] = 'No Data Available';	
						$result['data']    = $data;
				}	
		
	}
	   // to make completed project reopen for project completion		
			else if($action == 'reopen'){
				
				$sql= "update `userIdeas` set reopenStatus='1',ideaStatus='pending' where ideaId='$ideaId'";
				$sql_res=mysqli_query($con,$sql) or (logToFile($logfile,"to make completed project reopen for project completion - ReactgetIdeas.php"));
		
				if($sql_res){
					
					$result['status']  = 'True';
					$result['message'] = 'Success';	
			
			}else{
				
				$result['status'] = 'False';
				$result['message'] = 'failed';	
				
				}

		}
		 else {
				$result['status']='FALSE';
				$result['message']='Request method wrong!';
			}
					
	header("Content-Type:application/json");
	echo json_encode($result);	

?>
