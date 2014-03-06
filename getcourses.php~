<?php

	function InitDB() {

		if (isset($_SERVER["DB1_HOST"])) {
			// Pagodabox
			$db['default']['hostname'] = $_SERVER["DB1_HOST"].':'.$_SERVER["DB1_PORT"];
			$db['default']['username'] = $_SERVER["DB1_USER"];
			$db['default']['password'] = $_SERVER["DB1_PASS"];
			$db['default']['database'] = $_SERVER["DB1_NAME"];
			$db['default']['port'] = $_SERVER["DB1_PORT"];
		} else {
			 // my localhost configuration here
		}

		$con = mysql_connect($db['default']['hostname'], $db['default']['username'], $db['default']['password']); // database info
		if (!$con) {
	  		die('Could not connect: ' . mysql_error()); // if can't connect
	  	}
	  	mysql_select_db($db['default']['database'], $con);
	  	
	  	return $con;
	}
	
	// Begin Main ---------------------------------------------------------------
	
	// Mustn't forget this!
	header("Content-Type: text/html");
	
	if (isset($_GET['course']) && isset($_GET['code'])) {
		$con = InitDB();
		
		$code = mysql_real_escape_string($_GET['code']);
	  	$course = mysql_real_escape_string($_GET['course']);
	  	
		echo("<table border=1><tr><th>Name</th><th>Type</th><th>Code</th></tr>");
		
		$result = mysql_query("SELECT * FROM courses WHERE university_code = '$code' AND course LIKE '%$course%'");
		while($row = mysql_fetch_array($result)){
			echo "<tr>";
			echo "<td>" . $row['course'] . "</td>";
			echo "<td>" . $row['course_type'] . "</td>";
			echo "<td>" . $row['course_code'] . "</td>";
			echo "</tr>";
		}
		
		echo ("</table>");
	} else { echo("'course' and 'code' are required parameters!"); }
?>