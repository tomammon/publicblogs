<?php

// specify the API key
$APIKEY = "MySecretKey";

// find out which http verb was used in the request
$method = $_SERVER['REQUEST_METHOD'];

// get the URL used in the request
// this will be important later on because we will use the URL path to determine
// which API call the user is trying to use.
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));

// fetch the headers from the http request and store them in the $headers array
$headers = apache_request_headers();

//set up some canned error messages so that they can be returned as valid json
$methoderr = array("MethodError" => "Method not supported");
$notfound = array("NotFound" => "API Call not supported");

//add an authentication failed message
$autherr = array("AuthenticationError" => "Authentication failed");

if (in_array($APIKEY, $headers)){ //if the API key is in the http header, continue trying to service the API call
	if ($method == "GET"){ // if the method was GET, its okay to send data back to the requestor
		switch ($request[0]){
			case "testrequest": //if the first part of the URL was "testrequest"
				//then create an array with some static text in it and send it back to the
				//requestor as JSON
				$statictext = array("Testing" => "Your call to testrequest was successful");
				echo json_encode($statictext);
				break;

			default: //if the first part of the URL was not found in this switch statement
				//then send back our canned error message
				echo json_encode($notfound);
			}

		} else { // if the method was anything other than GET, send back a JSON block containing our canned error message
			echo json_encode($methoderr);
		}

} else { // if the API key was not found, return an error
        echo json_encode($autherr);
}



?>
