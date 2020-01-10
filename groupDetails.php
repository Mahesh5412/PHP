	<?php
		/*
		FileName:groupDetails.php
		Version:1.1
		Purpose:Getting the List of Group members for Maintask or Subtsak
		Devloper:Naveen
		*/

		 session_start();
		 
		$json = file_get_contents('php://input');
		$obj = json_decode($json,true);

		 $corp_code 		= $obj['corp_code'];
		 $_SESSION["corp_code"] =$corp_code;
		 
		include 'connect.php';

// for Logs 
$logfile = 'log/log' .date('d-M-Y') . '.log';

		if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD'] == "GET")
			{
				
				date_default_timezone_set('Asia/Kolkata'); 
				$time= date("Y-m-d H:i:s");
				
					$action  		        = $obj['action'];
					$groupId  				= $obj['groupId'];
					
					
				//message sending
							
				if($action=='subtask'){
					//getting the subtask group members

					$group="SELECT concat( 'S-', t2.subTaskId, ' ',  ideaTitle) AS name,
							IFNULL(eu1.userName, 'NA') AS ideaByName, 
							IFNULL(eu2.userName, 'NA') AS acceptedByName, 
							IFNULL(eu3.userName, 'NA') AS ideaModfiedName, 
							IFNULL(eu4.userName, 'NA') AS releaseOwner,
							IFNULL(eu5.userName, 'NA') AS rejectedByName, 
							IFNULL(eu6.userName, 'NA') AS moduleCreatedBy, 
							IF(IFNULL(eu7.userName, 'NA'), '', 'NA') AS moduleModifiedBy, 
							IFNULL(eu8.userName, 'NA') AS mainAssignedBy, 
							IFNULL(eu9.userName, 'NA') AS mainAssignedTo, 
							IFNULL(eu10.userName, 'NA') AS mainModifiedBy,
							IFNULL(eu11.userName, 'NA') AS subAssignedBy, 
							IFNULL(eu12.userName, 'NA') AS subAssignedTo, 
							IFNULL(eu13.userName, 'NA') AS subModifiedBy
							FROM (SELECT t1.*,
							ust.subTaskId, 
							ust.assignedBy AS subAssignedBy, 
							ust.assignedTo subAssignedTo, 
							ust.modifiedBy as subModifiedBy
							FROM (SELECT 
							ui.ideaId, 
							ui. ideaTitle,       
							umt.id, 
							empId, 
							acceptedBy, 
							ui.modifiedBy AS ideaModified, 
							releaseOwner, 
							rejectedBy,
							mt.moduleId,
							mt.createdBy, 
							mt.modifiedBy AS moduleModified, 
							umt.assignedBy AS mainAssignedBy, 
							umt.assignedTo AS mainAssignedTo, 
							umt.modifiedBy AS mainModifiedBy 
							FROM `userIdeas` AS ui
							INNER JOIN moduleTable as mt ON ui.ideaId= mt.ideaId
							INNER JOIN userMainTasks AS umt ON ui.ideaId= umt.ideaId) AS t1 
							INNER JOIN userSubTasks AS ust ON t1.id= ust.mainTaskId) AS t2 
							LEFT JOIN emsUsers AS eu1 ON t2.empId= eu1.empId 
							LEFT JOIN emsUsers AS eu2 ON t2.acceptedBy= eu2.empId 
							LEFT JOIN emsUsers AS eu3 ON t2.ideaModified= eu3.empId 
							LEFT JOIN emsUsers AS eu4 ON t2.releaseOwner= eu4.empId 
							LEFT JOIN emsUsers AS eu5 ON t2.rejectedBy= eu5.empId 
							LEFT JOIN emsUsers AS eu6 ON t2.createdBy= eu6.empId 
							LEFT JOIN emsUsers AS eu7 ON t2.moduleModified= eu7.empId 
							LEFT JOIN emsUsers AS eu8 ON t2.mainAssignedBy= eu8.empId 
							LEFT JOIN emsUsers AS eu9 ON t2.mainAssignedTo= eu9.empId 
							LEFT JOIN emsUsers AS eu10 ON t2.mainModifiedBy= eu10.empId
							LEFT JOIN emsUsers AS eu11 ON t2.subAssignedBy= eu11.empId
							LEFT JOIN emsUsers AS eu12 ON t2.subAssignedTo= eu12.empId
							LEFT JOIN emsUsers AS eu13 ON t2.subModifiedBy= eu13.empId
							WHERE t2.subTaskId='$groupId'";		
						
						
						$group_res=mysqli_query($con,$group)  or (logToFile($logfile,"  send message  - groupDetails.php"));
				
						if($grouprow=mysqli_fetch_assoc($group_res)){
							
							$result['subTaskId']=$grouprow['name'];
							$result['ideaByName']=$grouprow['ideaByName'];
							$result['acceptedByName']=$grouprow['acceptedByName'];
							$result['ideaModfiedName']=$grouprow['ideaModfiedName'];
							$result['releaseOwner']=$grouprow['releaseOwner'];
							$result['rejectedByName']=$grouprow['rejectedByName'];
							$result['moduleCreatedBy']=$grouprow['moduleCreatedBy'];
							$result['moduleModifiedBy']=$grouprow['moduleModifiedBy'];
							$result['mainAssignedBy']=$grouprow['mainAssignedBy'];
							$result['mainAssignedTo']=$grouprow['mainAssignedTo'];
							$result['mainModifiedBy']=$grouprow['mainModifiedBy'];
							$result['subAssignedBy']=$grouprow['subAssignedBy'];
							$result['subAssignedTo']=$grouprow['subAssignedTo'];
							$result['subModifiedBy']=$grouprow['subModifiedBy'];
							$result['task']='subtask';
							$result['status']='True';
						
						}
					else{
						$result['status'] = 'False';
						}
				
				//message getting
				
				}else if($action=='maintask'){

					//getting the list of maintask group members

					$group="SELECT concat( 'M-', t1.id, ' ',  ideaTitle) AS taskname,
									IFNULL(eu1.userName, 'NA') AS ideaByName, 
									IFNULL(eu2.userName, 'NA') AS acceptedByName, 
									IFNULL(eu3.userName, 'NA') AS ideaModfiedName, 
									IFNULL(eu4.userName, 'NA') AS releaseOwner,
									IFNULL(eu5.userName, 'NA') AS rejectedByName, 
									IFNULL(eu6.userName, 'NA') AS moduleCreatedBy, 
									IF(IFNULL(eu7.userName, 'NA'), '', 'NA') AS moduleModifiedBy, 
									IFNULL(eu8.userName, 'NA') AS mainAssignedBy, 
									IFNULL(eu9.userName, 'NA') AS mainAssignedTo, 
									IFNULL(eu10.userName, 'NA') AS mainModifiedBy
									FROM (SELECT 
									ui.ideaId, 
									ui. ideaTitle,       
									umt.id, 
									empId, 
									acceptedBy, 
									ui.modifiedBy AS ideaModified, 
									releaseOwner, 
									rejectedBy,
									mt.moduleId,
									mt.createdBy, 
									mt.modifiedBy AS moduleModified, 
									umt.assignedBy AS mainAssignedBy, 
									umt.assignedTo AS mainAssignedTo, 
									umt.modifiedBy AS mainModifiedBy 
									FROM `moduleTable` AS mt
									INNER JOIN userIdeas as ui ON ui.ideaId= mt.ideaId
									INNER JOIN userMainTasks AS umt ON mt.moduleId= umt.moduleId) AS t1 
									LEFT JOIN emsUsers AS eu1 ON t1.empId= eu1.empId 
									LEFT JOIN emsUsers AS eu2 ON t1.acceptedBy= eu2.empId 
									LEFT JOIN emsUsers AS eu3 ON t1.ideaModified= eu3.empId 
									LEFT JOIN emsUsers AS eu4 ON t1.releaseOwner= eu4.empId 
									LEFT JOIN emsUsers AS eu5 ON t1.rejectedBy= eu5.empId 
									LEFT JOIN emsUsers AS eu6 ON t1.createdBy= eu6.empId 
									LEFT JOIN emsUsers AS eu7 ON t1.moduleModified= eu7.empId 
									LEFT JOIN emsUsers AS eu8 ON t1.mainAssignedBy= eu8.empId 
									LEFT JOIN emsUsers AS eu9 ON t1.mainAssignedTo= eu9.empId 
									LEFT JOIN emsUsers AS eu10 ON t1.mainModifiedBy= eu10.empId
									WHERE t1.id='$groupId'
									";

				
									$group_res=mysqli_query($con,$group)  or (logToFile($logfile,"  getting the list of maintask group members  - groupDetails.php"));
									
									if($grouprow=mysqli_fetch_assoc($group_res)){
										$result['subTaskId']=$grouprow['taskname'];
										$result['ideaByName']=$grouprow['ideaByName'];
										$result['acceptedByName']=$grouprow['acceptedByName'];
										$result['ideaModfiedName']=$grouprow['ideaModfiedName'];
										$result['releaseOwner']=$grouprow['releaseOwner'];
										$result['rejectedByName']=$grouprow['rejectedByName'];
										$result['moduleCreatedBy']=$grouprow['moduleCreatedBy'];
										$result['moduleModifiedBy']=$grouprow['moduleModifiedBy'];
										$result['mainAssignedBy']=$grouprow['mainAssignedBy'];
										$result['mainAssignedTo']=$grouprow['mainAssignedTo'];
										$result['mainModifiedBy']=$grouprow['mainModifiedBy'];
										$result['task']='maintask';
										$result['status']='True';
									
								}
					else
					{
							$result['status'] = 'False';
							$result['message'] = 'No Data Available';	
							$result['data'] = $data;
					}
					
					}
			}else     
			{
				$result['status']='FALSE';
				$result['message']='Request method wrong!';
			}
			

			header("Content-Type:application/json");
			echo json_encode($result);	

	?>
