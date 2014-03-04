<?php
$rq = sprintf("http://maps.googleapis.com/maps/api/distancematrix/json?origins=%s+GB&destinations=%d,%d&language=en-GB&sensor=false", $_GET["user"], $_GET["lat"], $_GET["long"]); 
$response = file_get_contents($rq);
$json = json_decode($response, true);

if ($_GET["format"] == "text") {
	echo $json["rows"][0]["elements"][0]["duration"]["text"];
} else {
	echo $json["rows"][0]["elements"][0]["duration"]["value"];
}
?>
