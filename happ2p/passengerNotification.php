<?php
//include "connect.php";

class PassengerGCM 
{

    function __construct() 
    {
        
    }

    
    function sendPassengerGCM($token,$message) 
    { 

define('API_ACCESS_KEY','AIzaSyBvm-jBzr3Vce5HZjvAVfFiOuGoC4jIJlo');//firebaseconnection test
 $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
 /*$token = 'dyIzdwQIBS0:APA91bGHNFZrfhL1ytrgOJwNk_c3aBZPFYHEelRCVGo2sEMDFI3M9o25dJ2bc5C2UpR1AR6rYLE-URyOBaRmqw5s_ashzfcJmnnrnuM4reC1dmP8fm90XyquZqzMevaC2CR_zRkx8dlU';*///my mobile token
 //$tokenList = array('cV2RyhcsxG4:APA91bGEnge6xeSa88YqjqJ7nuToD60dpGeFuyK83bUFsBoCQJ_lNSU-9NHQOmJaNLh0r6qSITYtJHMFjelFRxV5Wabxhzc4FDN7SYHCv1b-Qba5UF3wTlZHLk_Yf2mpsmogOBxmdEvZ', '', '');
   /* $notification = [
            'title' =>'test notification',
            'body' => 'body of message.',
            'icon' =>'myIcon', 
            'sound' => 'mySound'
        ];*/
        $extraNotificationData = ["message" => $message,"moredata" =>'dd'];

        $fcmNotification = [
            //'registration_ids' => $tokenList, //multiple token array
            'to'        => $token, //single token
            'notification' => $message,
            'data' => $extraNotificationData
        ];

        $headers = [
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);    
           // echo $result;
    }
}
?>