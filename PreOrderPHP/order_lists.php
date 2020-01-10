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
              $sel=$conn->query("select * from item_list where `id`='$key'") or die("error in select ");
           $result = $conn->query($sel);
 
if ($result->num_rows >0) {
 
 
 while($row[] = $result->fetch_assoc()) {
 
 $item = $row;
 
 $json = json_encode($item);
 
 }
 
} else {
 echo "No Results Found.";
}
 echo $json;
            }}

?>
