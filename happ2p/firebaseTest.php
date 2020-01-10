<?php
include "connect.php";

class GCM 
{



    /**
     * Sending Push Notification
     */
    
    function sendGCM($id,$message) 
    {       
  $API_KEY = 'AAAA5fjD6KA:APA91bGFjBM7Q2AyB1V3kfcvIGc9gwqRS_wuIlzHyZ2yOvbAxWDcH-tdTwyUOGZRM9InhfUSIK8KVXxHAH9sdl9l00XtUs1EWAXNOIw7UGwxvQHXXHezffSTA3c9fDKHJUoRKLept5av';
     $url = 'https://fcm.googleapis.com/fcm/send';
   
 // $message = array('message' => "Hi",'title' => $title);
     
     $fields = array (
            'to' => 'ffD6R1iW3ps:APA91bGvazpamtUZ6YTUzlQDRfxRCg0-S9V2ZyXEO6aCSb5kEf3QTMBCaDHbYqyaTIePNhIMB5leyx2mD3TrMjbStm0IIJ-ZCeBP__0kSavXohn-hGiBeZZhQG0-Xpjta-iOrJBxTnPt',
            'data' =>'amith',
           );
    $fields = json_encode ( $fields );
   //print_r($fields);
   

    $headers = array (
            'Authorization: key=' .$API_KEY,
            'Content-Type: application/json'
    );

    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

     $result = curl_exec ( $ch );
     echo $result;
     curl_close ( $ch );
     //return $result;
}

}

?>
