<?php

$servername = '192.168.0.26';
$username = 'qptms';
$password = 'qptms@123';

$json = file_get_contents( 'php://input' );
$obj = json_decode( $json, true );
$corp_code = 'novisalesptms';

// Create connection
$conn = new mysqli( $servername, $username, $password );
// Check connection
if ( $conn->connect_error ) {
    die( 'Connection failed: ' . $conn->connect_error );
    // echo $conn;
}

// Create database
$sql = "CREATE DATABASE $corp_code";
if ( $conn->query( $sql ) === TRUE ) {
     // echo 'Database created successfully';

    //grant permissions for database
   // $grant = GRANT ALL PRIVILEGES ON `$corp_code`.* TO 'qptms'@'%' identified by 'qptms@123';
  // if ( $conn->query( $grant ) {

        $con = mysqli_connect( $servername, $username, $password, $corp_code );

        $sql1 = "CREATE TABLE `chatGroup` (
  `id` int(10) NOT NULL,
  `groupId` varchar(100) NOT NULL,
  `users` varchar(1045) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1";

        $sql2 = "CREATE TABLE `chat_count` (
  `id` int(10) NOT NULL,
  `from` int(100) NOT NULL,
  `to` int(100) NOT NULL,
  `count` int(100) NOT NULL DEFAULT 0,
  `taskId` int(20) NOT NULL,
  `assignto` varchar(100) NOT NULL,
  `assignby` varchar(100) NOT NULL,
  `ncount` int(100) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1";

        $sql3 = "CREATE TABLE `columnValues` (
  `sno` int(10) NOT NULL,
  `roles` varchar(25) NOT NULL DEFAULT 'NA',
  `designation` varchar(25) NOT NULL DEFAULT 'NA',
  `team` varchar(25) NOT NULL DEFAULT 'NA',
  `status` varchar(25) NOT NULL DEFAULT 'NA',
  `workingStatus` varchar(25) NOT NULL DEFAULT 'NA'
) ENGINE=InnoDB DEFAULT CHARSET=latin1";

        $sql4 = "CREATE TABLE `empSchedule` (
  `sno` int(10) NOT NULL,
  `empId` varchar(20) NOT NULL,
  `weekName` varchar(50) NOT NULL,
  `startTime` time NOT NULL,
  `endTime` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1";

        $sql5 = "CREATE TABLE `emsUsers` (
  `employeeId` varchar(20) NOT NULL,
  `empId` varchar(20) NOT NULL,
  `fullName` varchar(100) NOT NULL,
  `mobileNumber` varchar(20) NOT NULL,
  `email` varchar(200) NOT NULL,
  `userName` varchar(50) NOT NULL,
  `password` varchar(300) NOT NULL,
  `role` varchar(25) NOT NULL,
  `designation` varchar(25) NOT NULL,
  `team` varchar(25) NOT NULL,
  `createdBy` varchar(20) NOT NULL,
  `createdDate` datetime NOT NULL,
  `modifiedBy` varchar(20) NOT NULL DEFAULT 'NA',
  `modifieddate` datetime NOT NULL,
  `empStatus` varchar(25) NOT NULL DEFAULT 'available',
  `workingStatus` varchar(25) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

        $sql6 = "CREATE TABLE `moduleTable` (
  `moduleId` int(10) NOT NULL,
  `ideaId` int(10) NOT NULL,
  `moduleDesc` varchar(100) NOT NULL,
  `createdBy` varchar(20) NOT NULL,
  `createdDate` datetime NOT NULL,
  `modifiedBy` varchar(20) NOT NULL,
  `modifiedDate` datetime NOT NULL,
  `status` enum('deleted','NA','pending','completed') NOT NULL DEFAULT 'NA'
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

        $sql7 = "CREATE TABLE `roadBlocks` (
  `sno` int(11) NOT NULL,
  `subTaskId` int(10) NOT NULL,
  `roadBlockDescription` varchar(100) NOT NULL,
  `roadBlockDate` datetime NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'not solved'
) ENGINE=InnoDB DEFAULT CHARSET=latin1";

        $sql8 = "CREATE TABLE `taskChat` (
  `sno` int(10) NOT NULL,
  `groupId` varchar(100) NOT NULL,
  `messagedBy` int(10) NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `messagedTime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1";

        $sql9 = "CREATE TABLE `userDeviceInfo` (
  `sno` int(10) NOT NULL,
  `deviceId` varchar(50) NOT NULL,
  `deviceToken` varchar(300) NOT NULL,
  `deviceType` enum('Android','IOS','','') NOT NULL,
  `empId` varchar(20) NOT NULL,
  `userType` varchar(100) NOT NULL,
  `modifiedDate` datetime NOT NULL,
  `status` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

        $sql10 = "CREATE TABLE `userIdeas` (
  `ideaId` int(10) NOT NULL,
  `empId` varchar(20) NOT NULL,
  `ideaTitle` varchar(100) NOT NULL,
  `ideaDesc` varchar(1050) NOT NULL,
  `createdDate` datetime NOT NULL,
  `approvalStatus` enum('pending','approved','rejected','') NOT NULL DEFAULT 'pending',
  `ideaStatus` enum('pending','completed','','') NOT NULL DEFAULT 'pending',
  `endDate` datetime NOT NULL,
  `reopenStatus` enum('0','1','deleted','','') NOT NULL DEFAULT '0',
  `acceptedBy` varchar(10) NOT NULL,
  `acceptedDate` datetime NOT NULL,
  `modifiedBy` varchar(20) NOT NULL DEFAULT 'NA',
  `modifiedDate` datetime NOT NULL,
  `releaseOwner` varchar(20) NOT NULL DEFAULT 'NA',
  `rejectedBy` varchar(10) NOT NULL,
  `rejectedDate` datetime NOT NULL,
  `rejectedReason` varchar(100) NOT NULL DEFAULT 'NA'
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

        $sql11 = "CREATE TABLE `userMainTasks` (
  `id` int(10) NOT NULL,
  `ideaId` int(10) NOT NULL,
  `moduleId` int(10) NOT NULL,
  `taskTitle` varchar(100) NOT NULL,
  `taskDesc` varchar(255) NOT NULL,
  `assignedBy` varchar(20) NOT NULL,
  `estimatedHours` int(10) NOT NULL,
  `assignedTo` varchar(20) NOT NULL,
  `taskStatus` int(10) NOT NULL,
  `taskStatusDesc` varchar(200) NOT NULL DEFAULT 'NA',
  `assignedDate` datetime NOT NULL,
  `targetDate` datetime NOT NULL,
  `taskEndDate` datetime NOT NULL,
  `completeStatus` enum('pending','completed','verified','deleted') NOT NULL DEFAULT 'pending',
  `createdDate` datetime NOT NULL,
  `modifiedBy` varchar(20) NOT NULL DEFAULT 'NA',
  `modifiedDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

        $sql12 = "CREATE TABLE `userSubTasks` (
  `subTaskId` int(10) NOT NULL,
  `mainTaskId` int(10) NOT NULL,
  `moduleId` int(10) NOT NULL,
  `taskTitle` varchar(100) NOT NULL,
  `taskDesc` varchar(200) NOT NULL,
  `assignedBy` varchar(20) NOT NULL,
  `estimatedHours` int(10) NOT NULL,
  `activeStatus` int(10) NOT NULL DEFAULT 0,
  `assignedTo` varchar(20) NOT NULL,
  `taskStatus` int(10) NOT NULL,
  `taskStatusDesc` varchar(100) NOT NULL DEFAULT 'NA',
  `activeTime` datetime NOT NULL,
  `assignedDate` datetime NOT NULL,
  `targetDate` datetime NOT NULL,
  `taskEndDate` datetime NOT NULL,
  `status` enum('pending','completed','verified','deleted','') NOT NULL DEFAULT 'pending',
  `modifiedBy` varchar(20) NOT NULL DEFAULT 'NA',
  `modifiedDate` datetime NOT NULL,
  `dependencyId` varchar(20) NOT NULL DEFAULT 'NA'
) ENGINE=InnoDB DEFAULT CHARSET=utf8";


//Indexes for table `columnValues`
$sqlalter="ALTER TABLE `columnValues`
  ADD PRIMARY KEY (`sno`),
  ADD UNIQUE KEY `roles` (`roles`),
  ADD UNIQUE KEY `designation` (`designation`),
  ADD UNIQUE KEY `team` (`team`),
  ADD UNIQUE KEY `status` (`status`),
  ADD UNIQUE KEY `workingStatus` (`workingStatus`);";
  //Indexes for table `chat_count`
$sqlalter.="ALTER TABLE `chatGroup`
  ADD PRIMARY KEY (`id`);";
  $sqlalter.="ALTER TABLE `chat_count`
  ADD PRIMARY KEY (`id`);";
  //Indexes for table `empSchedule`
  $sqlalter.="ALTER TABLE `empSchedule`
  ADD PRIMARY KEY (`empId`,`weekName`),
  ADD UNIQUE KEY `sno` (`sno`);";
  //Indexes for table `emsUsers`
  $sqlalter.="ALTER TABLE `emsUsers`
  ADD PRIMARY KEY (`empId`),
  ADD UNIQUE KEY `mobileNumber` (`mobileNumber`),
  ADD UNIQUE KEY `username` (`userName`);";
  //Indexes for table `moduleTable`
  $sqlalter.="ALTER TABLE `moduleTable`
  ADD PRIMARY KEY (`moduleId`);";
  //Indexes for table `roadBlocks`
  $sqlalter.="ALTER TABLE `roadBlocks`
  ADD PRIMARY KEY (`sno`);";
  //Indexes for table `taskChat`
  $sqlalter.="ALTER TABLE `taskChat`
  ADD PRIMARY KEY (`sno`);";
  //Indexes for table `userDeviceInfo`
  $sqlalter.="ALTER TABLE `userDeviceInfo`
  ADD PRIMARY KEY (`sno`);";
  //Indexes for table `userIdeas`
  $sqlalter.="ALTER TABLE `userIdeas`
  ADD PRIMARY KEY (`ideaId`);";
  //Indexes for table `userMainTasks`
  $sqlalter.="ALTER TABLE `userMainTasks`
  ADD PRIMARY KEY (`id`);";
  //Indexes for table `userSubTasks`
 $sqlalter.="ALTER TABLE `userSubTasks`
  ADD PRIMARY KEY (`subTaskId`);";
  //AUTO_INCREMENT for table `chatGroup`
  $sqlalter.="ALTER TABLE `chatGroup`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;";
// AUTO_INCREMENT for table `chat_count`

$sqlalter.="ALTER TABLE `chat_count`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;";


// AUTO_INCREMENT for table `columnValues`

$sqlalter.="ALTER TABLE `columnValues`
  MODIFY `sno` int(10) NOT NULL AUTO_INCREMENT;";


// AUTO_INCREMENT for table `empSchedule`

$sqlalter.="ALTER TABLE `empSchedule`
  MODIFY `sno` int(10) NOT NULL AUTO_INCREMENT;";


// AUTO_INCREMENT for table `moduleTable`

$sqlalter.="ALTER TABLE `moduleTable`
  MODIFY `moduleId` int(10) NOT NULL AUTO_INCREMENT;";

// AUTO_INCREMENT for table `roadBlocks`

$sqlalter.="ALTER TABLE `roadBlocks`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT;";


// AUTO_INCREMENT for table `taskChat`

$sqlalter.="ALTER TABLE `taskChat`
  MODIFY `sno` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;";


// AUTO_INCREMENT for table `userDeviceInfo`

$sqlalter.="ALTER TABLE `userDeviceInfo`
  MODIFY `sno` int(10) NOT NULL AUTO_INCREMENT;";


//AUTO_INCREMENT for table `userIdeas`

$sqlalter.="ALTER TABLE `userIdeas`
  MODIFY `ideaId` int(10) NOT NULL AUTO_INCREMENT;";


// AUTO_INCREMENT for table `userMainTasks`

$sqlalter.="ALTER TABLE `userMainTasks`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT";


// AUTO_INCREMENT for table `userSubTasks`

$sqlalter.="ALTER TABLE `userSubTasks`
  MODIFY `subTaskId` int(10) NOT NULL AUTO_INCREMENT;";

        if ( $con->query( $sql1 ) ) {
            if ( $con->query( $sql2 ) ) {
                if ( $con->query( $sql3 ) ) {

                
														

                    if ( $con->query( $sql4 ) ) {
                        if ( $con->query( $sql5 ) ) {
                            if ( $con->query( $sql6 ) ) {
                                if ( $con->query( $sql7 ) ) {
                                    if ( $con->query( $sql8 ) ) {
                                        if ( $con->query( $sql9 ) ) {
                                            if ( $con->query( $sql10 ) ) {
                                                if ( $con->query( $sql11 ) ) {
                                                    if ( $con->query( $sql12 ) ) {
														if(mysqli_multi_query($con,$sqlalter)){
															
															  	$sql13 = "INSERT INTO `columnValues` (`sno`, `roles`, `designation`, `team`, `status`, `workingStatus`) VALUES
																			(1, 'Employee', 'Trainee', 'Android', 'Available', 'Active'),
																			(2, 'Manager', 'Graduate', 'Analytics', 'Not_Available', 'Inactive'),
																			(3, 'Admin', 'Research', 'PHP', 'Lunch', 'Others'),
																			(4, 'Approver', 'Others', 'UI', 'Busy', 'NA'),
																			(5, 'NA', 'NA', 'Infra', 'Others', 'NA'),
																			(6, 'NA', 'NA', 'Operations', 'NA', 'NA'),
																			(7, 'Others', 'NA', 'Others', 'NA', 'NA')";
																			if ( $con->query( $sql13 ) ) {
																					$result['status'] = 'true';
																			}
																$result['status'] = 'true1';
															}
															else{
															$result['status'] = 'false1';
														}
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

 //  }
} else {
    $result['status'] = 'false';
    // echo 'Error creating database: ' . $conn->error;
}
header( 'content-type:application/json' );
echo json_encode( $result );

$conn->close();
?>
