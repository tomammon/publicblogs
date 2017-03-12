<?php
$curl = curl_init();

curl_setopt_array($curl, array(
 CURLOPT_URL => "http://203.0.113.3/api/v0/mycustomapi.php/testrequest",
 CURLOPT_RETURNTRANSFER => true,  // curl options
 CURLOPT_ENCODING => "",  // more options
   ));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
        // if something goes wrong, print out some diagnostic info but don't try to decode any data
        echo "THERE WAS AN ERROR\n";
        echo "cURL Error #:" . $err;
} else {
        // if no errors, decode the received JSON data into a
        // multidimensional array, then print the array
        $decoded = json_decode($response, true);
        print_r($decoded);
}

?>
