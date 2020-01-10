<?php
/*Version :1.0.0
 *FileName:Order.php
 *Purpose: placeing the order 
 *Developers Involved:TulasiRao
 */
 //connecting to server
include 'DBconnection.php';
//generating default date and time
date_default_timezone_set('Asia/Kolkata');
$time=date("Y-m-d H:i:s");
  $json = file_get_contents('php://input');
  // decoding the received variable and store into $obj variable.
	$obj = json_decode($json,true);
  //getting cart,id,price,rest_id,persons and chosenTime Cart1.js file  and store into
  // $a,$id,$price,$rest_id,$persons and $est_time
		     $a = $obj['cart'];
         $id= $obj['id'];
         $price= $obj['price'];
         $rest_id= $obj['rest_id'];
         $persons= $obj['persons'];
         $est_time= $obj['chosenTime'];
    //generate random orderId
        $orderId = mt_rand(100, 999);
  $vals = array_count_values($a);
	reset($vals);
 if(count($a)>0)
 {
           foreach ($vals as $key => $value) 
            {
              //SQL command for selecting the menuList based on recipeId
              $sel=$conn->query("select * from menuList where `recipeId`='$key'") or 
              (logToFile($logfile,"query not seleted in menulist.php"+$id+"user",1));
              while($row=mysqli_fetch_assoc($sel))
              {
                $item_price=$row['price'];
                 $totalAmount = $item_price*$value;
                // echo $item_price." price  ".$value." value";
                 //echo $totalAmount."   ";
                      if($sel)
                      {
                        //execute SQL command based on menuList recipeId  for inserting user order in ordersTable
                         $result= $conn->query("INSERT INTO `ordersTable`(`hotelId`,`orderId`,`userId`,`recipeId`,`itemCount`,
                         `totalAmount`,`orderedDate`)
                          VALUES('$rest_id','$orderId','$id','$key','$value','$totalAmount','$time')") or 
				                	(logToFile($logfile,"query not inserted in menulist.php"+$id+"user",1));
                                         
                        }
                }
             }
              $result= $conn->query("INSERT INTO `orderDetails`(`orderId`,`personsCount`,`estimatedTime`)
              VALUES('$orderId', '$persons','$est_time')") or 
				    (logToFile($logfile,"query not inserted in menulist.php"+$orderId+"order",1));

            if($result){
              $Item['status']=true;
              $Item['orderId']=$orderId;
              $json = json_encode($Item);
            }
	}
	else{
    $Item['status']="fail";
    $json = json_encode($Item);
	}
  
  echo $json;
?>
