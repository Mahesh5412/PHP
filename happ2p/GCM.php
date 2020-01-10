<?php
include "connect.php";

class GCM 
{

    function __construct() 
    {
        
    }

    /**
     * Sending Push Notification
     */
    
    function sendGCM($id,$message,$clickAction) 
    {       
        define('API_ACCESS_KEY','AIzaSyBWvZ479A0gIEqG3kRomGfzkGK_Uh91kGo');//firebaseconnection test
     $API_KEY = 'AAAA9Jh6Nns:APA91bFPr2sPr4F6QU_L8WO0wtVIpAM1060STEMK_5Tc-nyvtzX-TmeTHgGpZs3YOd3w22090Lo-3527Kr72WoVPMafHxlUta6KZeR6sEP5geRydT74ZqCdujvPd9xvEIUnl3N1BnVA4';
     $url = 'https://fcm.googleapis.com/fcm/send';
   
 // $message = array('message' => "Hi",'title' => $title);

     $fields = array (
            'to' => $id ,
            'data' =>$message,
            'clickAction'=>$clickAction
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
     print($result) ;
     curl_close ( $ch );
     return $result;
}

}

?>
