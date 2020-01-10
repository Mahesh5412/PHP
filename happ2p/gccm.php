<?php
     //passenger 
	 //$API_KEY = 'AAAAV4t6h3o:APA91bFMvaISPB3ecWAs3O8E_wRTuvCzM3858LiVcjzqCYr8EulnroxUqWP6Z0FgZkYymoZdoQPAFC30NAz5eEYRAP6VhP-1x_mxb4pcKmauWiOHwPSBeSJDTBM_uSpPxOENq2K6XsU4qS7nY7fC27fDc8zyhNAoLQ';
    //agent
     $API_KEY = 'AIzaSyA9ytFicM7wf06qk3lleWX7w_AhKrhXams';      

     $url =  'https://fcm.googleapis.com/fcm/send';
   
     
	 //$id= 'flOircd2SGY:APA91bFN9a4l8Tf4evqOUDBB2qLKTlL-y60FfUGqnzn7ewhOboc4Zb8qc9LKKX2uWeVSkeKGf7X7Pb6mL66B9jkz1LuRfhL5RKp_SmvVXm1uiUpIPLm9vsEi8q-0twXHTPRH9gTPqrU6';
	
	//agent
	 $id='eX3_eU-3ogU:APA91bFYkxjJ-dQqNDLFv6TJOxN8wa-h-7oG8Ptsd9RRs5E0U2rQ8MKOKUCd223g2UKYrL3iaHkEhq-8b-horwmNGEvo7P5DBBgVPGrkszHdOOHwx7TeAPM1HnvU54KVOFGKHgItwklj';
	
	//passenger vineetha
	 //$id='fXvjlyEZvt8:APA91bHxGUFf3F8VRZkPxfeAExIZ8ITL5cSAiMPB9ZQ7E1urXYdo3cj8WaVVfE6zF2zpnnH1pwmY_6EIF_n22J7iQbcU4xlDBjgTE9ZRsdgNoF84mjIbJuetfbDau4hRYBMXm5yG5Soh';
	 
	 $message = array('message' => "Hi",'title' => 'Hello');
    // $clickAction="currentRoutes";
     $fields = array (
            'to' => $id ,
            'data' => $message,
            // 'clickAction' => com.cadrac.hap.activity.passengerRequests
            );
    $fields = json_encode ( $fields );
   
   

    $headers = array (
            'Authorization: key=' .$API_KEY,
            'Content-Type: application/json'
    );

    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

     $result = curl_exec ( $ch );
     //echo $result;
     //header("Content-Type:application/json"); 
     echo json_encode($fields);
     curl_close ( $ch );
     return $result;




?>
