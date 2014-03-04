<?php
	function InitDB() {
		$con = mysql_connect("localhost","bowsy","VU8Jc7ccirsre73"); // database info
		if (!$con) {
	  		die('Could not connect: ' . mysql_error()); // if can't connect
	  	}
	  	mysql_select_db("bowsy_yrs2011", $con);
	  	
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