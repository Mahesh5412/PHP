<?php
//include 'DatabaseConfig1.php';
include 'DBconnection.php';
date_default_timezone_set('Asia/Kolkata');
$time=date("Y-m-d H:i:s");
//$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);
  $json = file_get_contents('php://input');
	$obj = json_decode($json,true);
 
		$g = $obj['cart'];
        	$a = $g[0];
        $id= $obj['id'];
        $price= $obj['price'];
        $rest_id= $obj['rest_id'];


 	// $a=["1","1","8","20","22"];
  //     $id='109';
  //     $rest_id= '1';
        $orderId = mt_rand(100, 999);

  $vals = array_count_values($a);
	reset($vals);
 if(count($a)>0)
 {
           foreach ($vals as $key => $value) 
            {
              $sel=$conn->query("select * from menuList where `recipeId`='$key'") or die("error in select ");
              while($row=mysqli_fetch_assoc($sel))
              {
                $item_price=$row['price'];
                 $totalAmount = $item_price*$value;
                      if($sel)
                      {
                         $result= $conn->query("INSERT INTO `ordersTable`(`hotelId`,`orderId`,`userId`,`recipeId`,`itemCount`,`totalAmount`,`orderedDate`)
                             VALUES('$rest_id','$orderId','$id','$key','$value','$totalAmount','$time')") or die("error in insert");
                                         
                        }
                    	else{
                     
                    	}
                }
             }
              
             $Item['status']=true;
             // 	$result['data']=
             $json = json_encode($Item);
             
        echo $json;

    }
    else{


        $Item['status']=false;
        // 	$result['data']=
        $json = json_encode($Item);
        
   echo $json;
    }
	
	// $sel1=$conn->query("select * from ordersTable where `userId`='$id'") or die("error in select ");
	
    //         //   while($row[]=mysqli_fetch_array($sel))
    //         //   {
    //         //     $item=$row;
              
                
    //         //   }
            
    //         while($row[] = $sel1->fetch_array()) {

    //              $Item = $row;
                
                
                
    //              }
          //        	$Item['status']=true;
          //       // 	$result['data']=
          //       $json = json_encode($Item);
                
          //  echo $json;
  
  //echo json_encode($result);
?>
