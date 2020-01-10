<?php
//include "connect.php";
class Mails{


function __construct() 
    {
        
    }	
	 //echo sendOTP('maheshgoud71@gmail.com','12345');

	function sendRideInformation($startDate, $endDate,$amount, $driverName,$passengerName, $vehicleType,$paymentType,$email,$source, 
		$destination) {
		require('phpmailer/class.phpmailer.php');
		require('phpmailer/class.smtp.php');

$message_body ='<html>
    <head>
        <title>HR invoice</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="border">
            <div class="header-text">
                <h4>'.$endDate.'</h4>
                <h4>Invoice Serial id:COYQFPG155152</h4>
                <img src="images/Hap Ride logo.png" class="img1" alt="logo" align="right">
            </div>
            <div class="center">
                <hr class="hr1">
                <h1>₹'.$amount.'</h1>
                <h3>CRN3489897182</h3>
                <h5>Thanks for travelling with us,'.$passengerName.'</h5>
            </div>
            <hr class="hr2">
            <div class="details">
               <h2><b>Ride details</b></h2>
               <img src="images/img.jpeg" class="img2" alt="maps">
               <p style="font-size: 20px;"><img src="images/img2.jpeg" alt="maps">'.$driverName.'</p>
               <div class="details2">
                   <h2><b class="bill">Bill details</b></h2><hr  align="right" style="width: 30.5%; margin-right: -10%;">
                   <table>
                        <tr>
                            <th>Ride Fair</th>
                            <th>₹'.$amount.'</th>
                        </tr>
                        <tr>
                            <td>Special dicount</td>
                            <td>-₹10.4</td>
                        </tr>
                        <tr>
                            <td>Total Access Free</th>
                            <td>₹'.$amount.'</td>
                        </tr>
                        <tr>
                            <th>Total(rounded off)</th>
                            <th>₹'.$amount.'</th>
                        </tr>
                        <tr>
                            <th style="opacity: 80%; font-size: 15px">includes ₹0.39 taxes</th>
                            <th></th>
                        </tr>
                        <tr>
                            <td>*Access Fee is charged for availing the Hapride platform</td>
                        </tr>
                        <tr>
                            <td>We&#39;ve fulfilled our promise to take you to destination for pre-agreed Total Fare. Modifying the drop/route can change this fare.</td>
                        </tr>
                        <tr>
                            <td>Have queries or complaints? Get support.</td>
                        </tr>
                    </table>
                </div>
                <hr class="hr3">
                <div class="bottom">
                     <p style="font-size: 20px"><img class="auto" src="images/autonew.png" alt="logo"><b>'.$vehicleType.'</b</p>
                </div>
                <hr class="hr3.1">
                <div class="bottom-text">
                    <h4>'.$startDate.'</h4>
                        <h4>'.$source.'</h4>
                            <h4>'.$destination.'</h4>

                
                    <h4>'.$endDate.'</h4>
                    <div class="address1">
                            
                    </div>
                </div>
                <h6>payment</h6>
                <hr class="hr4">
                <p class="text1">Paid by '.$paymentType.'</p>
                <p class="text2">₹'.$amount.'</p>
                <hr class="hr5">
                <p class="text3">For T&C and fare details,<a href="#">visit our website</a></p>
                <p class="text4">Didn&#39;t make this booking?<a href="#"> Report it</a></p>
                <hr class="hr5">
                <p style="margin-left: 50px">Please Note: 1) Insurance Service is not provided by ANI Technologies Private Limited. Invoice for the insurance fee collected for the ride will be raised by the respective Insurance company.</p>
                
                
            </div>
        </div>
        
    </body>
</html>';

		$mail = new PHPMailer();

		$mail->IsSMTP();
		$mail->SMTPDebug = 0;
		$mail->SMTPAuth = TRUE;
		$mail->SMTPSecure = 'tls'; // tls or ssl
		$mail->Port     = "587";
		$mail->Username = "narendra.v@novisync.com";
		$mail->Password = "Nv1234novi";
		$mail->Host     = "smtp.globat.com";
		$mail->Mailer   = "smtp";
		$mail->SetFrom("cadbank@novisync.com", "Cadrac Bank");
		$mail->AddAddress($email);
		$mail->Subject = "OTP for Login Cadrac Bank Account";
		$mail->MsgHTML($message_body);
		$mail->IsHTML(true);		
		$result = $mail->Send();		
				
		return $result;
	}
}
    
?>