<?php
//include 'DatabaseConfig1.php';
include 'DBconnection.php';
date_default_timezone_set('Asia/Kolkata');
$time=date("Y-m-d H:i:s");
//$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);
  $json = file_get_contents('php://input');
	$obj = json_decode($json,true);

    //$g = eval($json);
	//$netJSON = json_encode($netflix);
// 	$g = $obj['cart'];
	

// 	$g = $obj['cart'];
//	$g = $obj[0];
// 	$bb= $obj['id'];
	
	
		$g = $obj['cart'];
        	$a = $g[0];
        $id= $obj['id'];

// 	$a=["1","1","8","20","22"];
// 	$id='100';
  
  $vals = array_count_values($a);
  //echo 'No. of NON Duplicate Items: '.count($vals).'<br><br>';
  //print_r($vals);
	reset($vals);
 if(count($a)>0)
 {
           foreach ($vals as $key => $value) 
          //for($i=0;$i<count($a);$i++)
            {
             $result= $conn->query("INSERT INTO `ordersTable`(`hotelId`,`orderId`,`item_cost`,`itemCount`,`orderedDate`) VALUES('$id','$item_name','$item_cost','$value','$time')") or die("error in insert");
                if($result){
        		    //echo json_encode('done');
                }
                else{
            	    echo json_encode('not done');
            	}
        
            
              
             }
	    
	}
	else{
	    
	    echo json_encode("no data");
	}
	
	$sel1=$conn->query("select * from order_list where `user_id`='$id'") or die("error in select ");
	
            //   while($row=mysqli_fetch_array($sel))
            //   {
            //     $item_name=$row['item_name'];
            //     $item_cost=$row['item_cost'];
            //     $item_qty=$row['quantity'];
                
            //   }
            
            
            while($row[] = $sel1->fetch_array()) {

                 $Item = $row;
                
                 $json = json_encode($Item);
                
                 }
                // 	$result['status']=true;
                // 	$result['data']=
                	
           echo $json;
?>
