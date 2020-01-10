<?php
 include 'DBconnection.php';
 header("Content-Type:application/json");
 if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
 } 
   $json = file_get_contents('php://input');
 $hotel_id = '1';
 $sql = "SELECT * FROM menuTypes where hotelId='$hotel_id'";
 $result = $conn->query($sql);

 if ($result->num_rows >0) {
 while($row = mysqli_fetch_assoc($result)) {
  $item["itemName"] = $row["itemName"];
  $itemId=$row["itemId"];
  $id=$row["hotelId"];
     $sql1 = "SELECT * FROM menuList where itemId ='$itemId'";
     $result1 = $conn->query($sql1) or die("eroor");
     if ($result1->num_rows >0) {
         $t = 0;
         while($row1= mysqli_fetch_array($result1)) {
              $data1['itemId']=$row1['itemId'];
              $data1['recipeId']=$row1['recipeId'];
              $data1['recipeName']=$row1['recipeName'];
              $data1['price']=$row1['price'];
              $data1['recipeName']=$row1['recipeName'];
              $data1['type']=$row1['type'];
              $data1['quantity'] = 1;
              $data1['status'] = 0;
            $item1[]=$data1;
			$item["data"] = $item1;
             $t++;
            
             }   
              foreach ($item1 as $i => $value) {
				unset($item1[$i]);
				$item1 = array();
			}
         }
  
         $data[]=$item;
  }
 } 
 else {
     $item["status"] ="superbbb";
  echo "No Results Found.";
 }
 $json = json_encode($data);
 echo $json;
 $conn->close();
 ?>
