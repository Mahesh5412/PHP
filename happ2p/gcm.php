<?php
include 'connect.php';

     $API_KEY = 'AAAA9Jh6Nns:APA91bFPr2sPr4F6QU_L8WO0wtVIpAM1060STEMK_5Tc-nyvtzX-TmeTHgGpZs3YOd3w22090Lo-3527Kr72WoVPMafHxlUta6KZeR6sEP5geRydT74ZqCdujvPd9xvEIUnl3N1BnVA4';
     $url = 'https://fcm.googleapis.com/fcm/send';
     $id = 'elyWyBR6wso:APA91bEJ-_aEUMaBNRXUEP7Az6WwXG0Exru2Opf5uMmmA5ZLSTTtMM2V-6eSbkN8QFjyemgsdCQ4hWcyGHnfVQ-Y2_5hvGRkAz6zfT7-zDQy2W9YxkKCIH0xOV3ONM6bIggUqupPaNhR';
   
  $message = array('message' => "Hi",'title' => $title);
     
     $fields = array (
            'to' => $id,
            'data' => $message,
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
     return $result;
?>
