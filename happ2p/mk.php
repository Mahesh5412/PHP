<?php
//include "connect.php";

class GCM 
{

    function __construct() 
    {
        
    }

    
    function sendGCM($token,$message) 
    { 

define('API_ACCESS_KEY','AIzaSyCiOfSbhm7jVej0U0lXMcahqjFYGeOfHE8');//firebaseconnection test
 $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
 /*$token = 'eDU0cokNhBY:APA91bFItnZRDFvom_gxffzK2jvur3nE30kTov4GgfTHPPSDjaXK05VhxAnZfb7WW0YPRuc3DAGCJf4Sv5oSydEMZ_VhN1T-yn8Ka9jzlRZi2P7VFdWzaiqvH7otcwifK-IicQr2zva6'*/;//my mobile token
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