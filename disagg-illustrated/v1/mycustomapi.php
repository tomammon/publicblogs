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

			case "ospfneighbors": //if the first part of the URL was "ospfneighbors"
				//then use vtysh to find the current ospf neighbors
				//and then return that output as JSON to the client
				$neigh_raw = shell_exec('/usr/bin/vtysh -c "show ip ospf neighbor detail"');
				$nbr_count = substr_count($neigh_raw, 'Neighbor') / 2 ; //find out how many neighbors there are
				$neigh_array = preg_split("/((\r?\n)|(\r\n?))/", $neigh_raw); //dump the output to an array
				echo "{"; //start encoding the JSON
				foreach($neigh_array as $line){ //loop through the output looking for specific neighbor details
						if (preg_match('/Neighbor [1-2]/', $line)){ //if this line has Neighbor with an IP address following
								$neighborset = explode(", ", $line); //split the KV pair raw strings into an array
								echo "\"".ltrim($neighborset[0])."\":\"$neighborset[1]\""; //print the KV pair out in JSON format
								if ($nbr_count>1){ //if this is not the last neighbor, insert a comma to separate KV pairs
										echo ",";
										$nbr_count--;
								}
						}
				}
				echo "}"; //stop encoding the JSON
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
