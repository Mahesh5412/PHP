<?php
function get_location($lat,$lng){
    $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&key=AIzaSyATaE4rWC3YNt0hd0x9TgkOJCN9RfzV6mE&sensor=true";
    $data = @file_get_contents($url);
    $jsondata = json_decode($data,true);
    $data = array();

    foreach($jsondata['results']['3']['address_components'] as $element){
        $data[ implode(' ',$element['types']) ] = $element['long_name'];
    }
        
	location(json_encode($data['neighborhood political']),json_encode($data['political sublocality sublocality_level_2']),json_encode($data['political sublocality sublocality_level_1']), json_encode($data['locality political']));

}
function location($local,$religion,$sublocality,$locality){
    	 $address = trim($local, '"').",".trim($religion, '"').",".trim($sublocality, '"').",".trim($locality, '"');
    // $address =$local.",".$religion.",".$sublocality.",".$locality;
    	 echo $address;
   		// return $locality;
    }

echo get_location('17.4427512', '78.3557125');
?>
