<?php
 
//include 'DatabaseConfig1.php';
include 'DBconnection.php';

//$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);
 
 $json = file_get_contents('php://input');
 
 $obj = json_decode($json,true);
 
 //biryani
 
$CheckSQL = "SELECT * FROM item_list where item_type='biryani'";

$result = $conn->query($CheckSQL);
 
 
 $r=0;
 while($row = $result->fetch_array()) {
 
    $Item['id'] = $row['id'];
 $Item['item_name'] = $row['item_name'];
 $Item['area_name'] = $row['area_name'];
 $Item['res_name'] = $row['res_name'];
 $Item['item_cost']=$row['item_cost'];
 $Item['item_pic'] = $row['item_pic'];
  $Item['quantity'] = $row['quantity'];
  
 $data[$r]=$Item;
 $r++;
 
 }
 
 //soups
 
 $CheckSQL1 = "SELECT * FROM item_list where item_type='soup'";

$result1 = $conn->query($CheckSQL1);
 
 $rr=0;
 while($row1 = $result1->fetch_array()) {
 
 $Item['id'] = $row1['id'];
 $Item['item_name'] = $row1['item_name'];
 $Item['area_name'] = $row1['area_name'];
 $Item['res_name'] = $row1['res_name'];
 $Item['item_cost']=$row1['item_cost'];
 $Item['item_pic'] = $row1['item_pic'];
  $Item['quantity'] = $row1['quantity'];
  
 $data1[$rr]=$Item;
 $rr++;
 
 }
 //starters
 
 $CheckSQL2 = "SELECT * FROM item_list where item_type='starters'";

$result2 = $conn->query($CheckSQL2);
 
 $rrr=0;
 while($row2 = $result2->fetch_array()) {
 
 $Item['id'] = $row2['id'];
 $Item['item_name'] = $row2['item_name'];
 $Item['area_name'] = $row2['area_name'];
 $Item['res_name'] = $row2['res_name'];
 $Item['item_cost']=$row2['item_cost'];
 $Item['item_pic'] = $row2['item_pic'];
  $Item['quantity'] = $row2['quantity'];
  
 $data2[$rrr]=$Item;
 $rrr++;
 
 }
 
 //deserts
 
 $CheckSQL3 = "SELECT * FROM item_list where item_type='deserts'";

$result3 = $conn->query($CheckSQL3);
 
 $rrrr=0;
 while($row3 = $result3->fetch_array()) {
 
 $Item['id'] = $row3['id'];
 $Item['item_name'] = $row3['item_name'];
 $Item['area_name'] = $row3['area_name'];
 $Item['res_name'] = $row3['res_name'];
 $Item['item_cost']=$row3['item_cost'];
 $Item['item_pic'] = $row3['item_pic'];
  $Item['quantity'] = $row3['quantity'];
  
 
 $data3[$rrrr]=$Item;
 $rrrr++;
 
 }
 
 //Breads
 
 $CheckSQL4 = "SELECT * FROM item_list where item_type='Breads'";

$result4 = $conn->query($CheckSQL4);
 
 $rrrrr=0;
 while($row4 = $result4->fetch_array()) {
 
 $Item['id'] = $row4['id'];
 $Item['item_name'] = $row4['item_name'];
 $Item['area_name'] = $row4['area_name'];
 $Item['res_name'] = $row4['res_name'];
 $Item['item_cost']=$row4['item_cost'];
 $Item['item_pic'] = $row4['item_pic'];
  $Item['quantity'] = $row4['quantity'];
  
 
 $data4[$rrrrr]=$Item;
 $rrrrr++;
 
 }
 
 //curries
 
 $CheckSQL5 = "SELECT * FROM item_list where item_type='curries'";

$result5 = $conn->query($CheckSQL5);
 
 $rrrrrr=0;
 while($row5 = $result5->fetch_array()) {
 
 $Item['id'] = $row5['id'];
 $Item['item_name'] = $row5['item_name'];
 $Item['area_name'] = $row5['area_name'];
 $Item['res_name'] = $row5['res_name'];
 $Item['item_cost']=$row5['item_cost'];
 $Item['item_pic'] = $row5['item_pic'];
   $Item['quantity'] = $row5['quantity'];
 
 $data5[$rrrrrr]=$Item;
 $rrrrrr++;
 
 }
 
 $response['status']="success";
   
 $response['biryani']=$data;
 $response['soup']=$data1;
 $response['starters']=$data2;
 $response['deserts']=$data3;
 $response['Breads']=$data4;
 $response['curries']=$data5;
 
 
 
//  $response = array();

// $response = [
//  "biryani"=>$data,
//  "soup"=>$data1,
//  "starters"=>$data2,
// "deserts"=>$data3,
//  "Breads"=>$data4,
//  "curries"=>$data5
    
//  $response['biryani']=> (array)$data,
//  $response['soup']=>(array)$data1,
//  $response['starters']=>(array)$data2,
//  $response['deserts']=>(array)$data3,
//  $response['Breads']=>(array)$data4,
//  $response['curries']=>(array)$data5
 //];
 
 // $response1=get_object_vars($response);
 
  $json = json_encode($response);
 
echo $json;
 
$conn->close();
?>
