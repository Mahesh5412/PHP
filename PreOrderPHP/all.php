 <?php
 include 'DBconnection.php';
 header("Content-Type:application/json");
 if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
 } 
   $json = file_get_contents('php://input');
 //$hotel_id = $obj["hotel_id"];
 $hotel_id = '1';
 $sql = "SELECT * FROM menuTypes where hotelId='$hotel_id'";
 $result = $conn->query($sql);
 //$row = $result->fetch_array();
 //echo $row["itemId"];
 if ($result->num_rows >0) {
     //$r = '0';
 // for($i='0';$i<=)
     // $sql1 = "SELECT * FROM menuList where itemId ='$row["itemId"]'";
     // $result1 = $conn->query($sql1);
     // foreach ($result as $row) {

     //   $data[] = [
     //     "title" => $row['itemId']
     //    // "data"  => arrat($result1)

     // ];
     //   }
 while($row = mysqli_fetch_assoc($result)) {
  $item["itemId"] = $row["itemId"];
  $item["hotelId"] = $row["hotelId"];
  $item["itemName"] = $row["itemName"];
  $itemId=$row["itemId"];
  $id=$row["hotelId"];
     $sql1 = "SELECT * FROM menuList where itemId ='$itemId'";
     $result1 = $conn->query($sql1) or die("eroor");

     if ($result1->num_rows >0) {
        // $t = '0';
         while($row1 = mysqli_fetch_array($result1)) {
             $item1['itemId'] = $row1['itemId'];
           // $item1 =  $row1;
             $data1[]=$item1;
             $item["data"] = $data1;
             }
         }
         $item["data"] = $data1;
         $data[]=$item;

  }
 } 
 else {
     $item["status"] ="superbbb";
  echo "No Results Found.";
 }
 $json = json_encode($data);
 echo $json;
 //echo json_encode($obj);
 $conn->close();
 ?>
