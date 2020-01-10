<?php
//include 'DatabaseConfig1.php';
include 'DBconnection.php';
date_default_timezone_set('Asia/Kolkata');
$time=date("Y-m-d H:i:s");
//$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);
  $json = file_get_contents('php://input');
	$obj = json_decode($json,true);
	
		$g = $obj['cart'];
		
        $a=$g[0];

    	$id= $obj['id'];
	
	//$netJSON = json_encode($netflix);
// 	$g = $obj[0];
// 	$id = $obj[1];
//	echo $g;

// 	if(count($g[0])>0){
        if(count($a)>0){
        // for($i=0;$i<sizeof($g[0]);$i++)
          for($i=0;$i<sizeof($a);$i++)
        {
          $sel=$conn->query("select * from item_list where id='$a[0][$i]'") or die("error in select ");
          while($row=mysqli_fetch_assoc($sel))
          {
            $item_name=$row['item_name'];
            $item_cost=$row['item_cost'];
            $item_qty=$row['quantity'];
            
          
          if($sel)
          {
    	       $result= $conn->query("INSERT INTO `order_list`(`user
    	       _id`,`item_name`,`item_cost`,`item_qty`,`created_date`) VALUES('$id','$item_name','$item_cost','$item_qty','$time')") or die("error in insert");
            

		if($result){
			echo json_encode('done');
		}
		else{
		echo json_encode('not done');
		}
	}
	else{
	  echo json_encode('try again');
	}
}
}
	    
	}
	else{
	    echo json_encode("no data");
	}
?>

