<?php

// create the variables 
$HOST = "localhost";
$DBUSER = "bowsy";
$DBPASS = "VU8Jc7ccirsre73";
$DBNAME = "bowsy_yrs2011";

//connect to db
function connect_to_db($HOST, $DBUSER, $DBPASS, $DBNAME){
	//connect to db
	$conn = mysql_connect($HOST, $DBUSER, $DBPASS) or die(mysql_error());

	if($conn){
		$db = mysql_select_db($DBNAME) or die(mysql_error());
	}
}

//parse and clean user input
function cleanQuery($dirtyData){
	$cleanData = stripslashes($dirtyData);
	$cleanData = htmlspecialchars($cleanData, ENT_QUOTES);
	$cleanData = strip_tags($cleanData);
	$cleanData = trim($cleanData);
	//$cleanData = mysql_real_escape_string($cleanData);
	return $cleanData;	
}


//execute db query
function db_query($sql){
	//access the variables globally
	global $HOST, $DBUSER, $DBPASS, $DBNAME;
	
	//call connect_to_db function
	connect_to_db($HOST, $DBUSER, $DBPASS, $DBNAME);
	$query = mysql_query($sql);

	//query success of db query
	if($query && mysql_num_rows==0){
		while($row = mysql_fetch_array($query)){
		extract($row);
		$data .= "Longitude: ".$longitude." and Latitude: ".$latitude;
		return $data;
		}
		
	}
	if(!$query){
	
		$message = "There was a problem executing your query";
		return $message;
	}
	else{
		$message = "No match was found for that postcode";
		return $message;
	}

}

function get_data($userinput){
	
	//capture user input $_POST
	$userinput = cleanQuery($userinput);

	//look up in the database the value the user has typed in
	$sql = "SELECT latitude, longitude FROM uk_postcodes WHERE postcode='$userinput'";
		return db_query($sql);	
}


echo get_data("AB10");
		
?>