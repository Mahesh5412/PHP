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
// $a = $g[0];
	
		 $a = $obj['cart'];
        
         $id= $obj['id'];
         $price= $obj['price'];
       $rest_id= $obj['rest_id'];
         $persons= $obj['persons'];
         $est_time= $obj['chosenTime'];
        //$estimatedTime= '45';

        // $g = $obj['cart'];
        // $a = $g[0];

 	//$a=["1","1","8","20","22"];
      //$id='109';
     // $rest_id= '1';
        $orderId = mt_rand(100, 999);
     //$persons= '10';
       // $estimatedTime= '45';
// $persons= '10';
        //$estimatedTime= '45';
        
        
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
              $sel=$conn->query("select * from menuList where `recipeId`='$key'") or die("error in select ");
              while($row=mysqli_fetch_assoc($sel))
              {
                $item_price=$row['price'];
                 $totalAmount = $item_price*$value;
                // echo $item_price." price  ".$value." value";
                 //echo $totalAmount."   ";
                      if($sel)
                      {
                         $result= $conn->query("INSERT INTO `ordersTable`(`hotelId`,`orderId`,`userId`,`recipeId`,`itemCount`,`totalAmount`,`orderedDate`)
                             VALUES('$rest_id','$orderId','$id','$key','$value','$totalAmount','$time')") or die("error in insert");

                           	if($result){
                              // echo "1";
                              //	$result['status']=true;         
                          		}
                          		else{
                               //	$result['status']=false;                                	
                                    }
                                         
                        }
                    	else{
                        // $result['status']="not inserted";
                    	  // echo json_encode($result);
                    	}
                }
             }
            //  $result['status']=true;
            //  $result['sddd']=true;
             // echo json_encode($result);
              //	$result['status']=true;


              $result= $conn->query("INSERT INTO `orderDetails`(`orderId`,`personsCount`,`estimatedTime`)
              VALUES('$orderId', '$persons','$est_time')") or die("error in insert111");

              
              $Item['status']=true;
              $Item['orderId']=$orderId;
              // 	$result['data']=
              $json = json_encode($Item);
              
         echo $json;

       

	}
	else{
	    
	    echo json_encode("no data");
	}
	
	// $sel1=$conn->query("select * from order_list where `user_id`='$id'") or die("error in select ");
	
  //           //   while($row=mysqli_fetch_array($sel))
  //           //   {
  //           //     $item_name=$row['item_name'];
  //           //     $item_cost=$row['item_cost'];
  //           //     $item_qty=$row['quantity'];
                
  //           //   }
            
            
  //           while($row[] = $sel1->fetch_array()) {

  //                $Item = $row;
                
  //                $json = json_encode($Item);
                
  //                }
  //               // 	$result['status']=true;
  //               // 	$result['data']=
                	
  //          echo $json;
 // echo json_encode($result);
?>
