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
$price= $obj['price'];
$rest_id= $obj['rest_id'];

		// $g = $obj['cart'];
		
    //     	$a = $g[0];

    //     $id= $obj['id'];

// 	$a=["1","8","9"];
// 	$id='3';
	
	
//  if(count($a)>0){
     
//          for($i=0;$i<sizeof($a);$i++)
//           //for($i=0;$i<count($a);$i++)
//             {
//               $sel=$conn->query("select * from item_list where `id`='$a[$i]'") or die("error in select ");
//               while($row=mysqli_fetch_assoc($sel))
//               {
//                 $item_name=$row['item_name'];
//                 $item_cost=$row['item_cost'];
//                 $item_qty=$row['quantity'];
                
              
//                       if($sel)
//                       {
//                 	       $result= $conn->query("INSERT INTO `order_list`(`user_id`,`item_name`,`item_cost`,`item_qty`,`created_date`) VALUES('$id','$item_name','$item_cost','$item_qty','$time')") or die("error in insert");
                        
//                     		if($result){
//                     		//	echo json_encode('done');
//                     		}
//                     		else{
//                     	//	echo json_encode('not done');
//                     		}
//             	        }
//                     	else{
//                     	 // echo json_encode('try again');
//                     	}
//                 }
//              }
	    
// 	}
// 	else{
	    
// 	   // echo json_encode("no data");
// 	}
	
// 	$sel1=$conn->query("select * from order_list where `user_id`='$id'") or die("error in select ");
	
//             //   while($row=mysqli_fetch_array($sel))
//             //   {
//             //     $item_name=$row['item_name'];
//             //     $item_cost=$row['item_cost'];
//             //     $item_qty=$row['quantity'];
                
//             //   }
            
            
//             while($row[] = $sel1->fetch_array()) {

//                  $Item = $row;
                
//                  $json = json_encode($Item);
                
//                  }
//                 // 	$result['status']=true;
//                 // 	$result['data']=
                	
           echo json_encode($g);
?>

