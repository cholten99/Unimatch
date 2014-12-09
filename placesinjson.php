<?php
 	function GetQuery() {
		// grab variables for the querystring
	  	$course = mysql_real_escape_string($_GET['course']);
	  	$group = mysql_real_escape_string($_GET['group']);
	  	$minTut = mysql_real_escape_string($_GET['mintut']);
	  	$maxTut = mysql_real_escape_string($_GET['maxtut']);
	  	$minStaff = mysql_real_escape_string($_GET['minstaff']);
	  	$maxStaff = mysql_real_escape_string($_GET['maxstaff']);
	  	$minCar = mysql_real_escape_string($_GET['mincar']);
	  	$maxCar = mysql_real_escape_string($_GET['maxcar']);
	  	$teach = mysql_real_escape_string($_GET['teach']);
	  	$feedback = mysql_real_escape_string($_GET['feed']);
	  	$overall = mysql_real_escape_string($_GET['over']);
	  	$minPub = mysql_real_escape_string($_GET['minpub']);
	  	$maxPub = mysql_real_escape_string($_GET['maxpub']);
	  	$minClub = mysql_real_escape_string($_GET['minclub']);
	  	$maxClub = mysql_real_escape_string($_GET['maxclub']);
	  	
	  	// parts of the SELECT statement - to be concatenated
	 	$startString = "SELECT DISTINCT guardian_combo_complete.* FROM courses INNER JOIN guardian_combo_complete USING (university_code) WHERE courses.course LIKE '%$course%' AND ";
	  	
	  	switch ($group) {
			case "Russell":
				$uniGroupString = "uni_group LIKE '%Russell%'";
				break;
			case "1994":
				$uniGroupString = "uni_group LIKE '%1994%'";
				break;
			case "Million+":
				$uniGroupString = "uni_group LIKE '%Million%'";
				break;
			case "Alliance":
				$uniGroupString = "uni_group LIKE '%Alliance%'";
				break;
			case "UKADIA":
				$uniGroupString = "uni_group LIKE '%UKADIA%'";
				break;
			default:
				$uniGroupString = "(uni_group LIKE '%Russell%' OR uni_group LIKE '%1994%' OR uni_group LIKE '%Million%' OR uni_group LIKE '%Alliance%' OR uni_group LIKE '%UKADIA%')";
		}
	  	
	  	$tuitionString = "max_tuition >= $minTut AND max_tuition <= $maxTut";
	  	
		switch (mysql_real_escape_string($_GET['ucas'])) {
			case 1:
				$ucasString = "entry_tariff >= 0 AND entry_tariff <= 180";
				break;
			case 2:
				$ucasString = "entry_tariff >= 180 AND entry_tariff <= 240";
				break;
			case 3:
				$ucasString = "entry_tariff >= 240 AND entry_tariff <= 300";
				break;
			case 4:
				$ucasString = "entry_tariff >= 300 AND entry_tariff <= 360";
				break;
			case 5:
				$ucasString = "entry_tariff >= 360";
				break;
			default:
				$ucasString = "entry_tariff >= 0";
		}
	  	
	  	$studentStaffRatioString = "student_staff_ratio >= $minStaff AND student_staff_ratio <= $maxStaff";
	  	$careerProspectsString = "career_prospects >= $minCar AND career_prospects <= $maxCar";
	  	$teacherRatingString = "nss_teaching >= $teach";
	  	$feedbackRatingString = "nss_feedback >= $feedback";
	  	$overallRatingString = "nss_overall >= $overall";
	  	$pubString = "num_of_pubs >= $minPub AND num_of_pubs <= $maxPub";
	  	$clubString = "num_of_clubs >= $minClub AND num_of_clubs <= $maxClub";
	  	
		switch (mysql_real_escape_string($_GET['sort'])) {
			case "staff":
				$orderby = "student_staff_ratio";
				break;
			case "ucas":
				$orderby = "entry_tariff";
				break;
			case "car":
				$orderby = "career_prospects DESC";
				break;
			case "over":
				$orderby = "nss_overall DESC";
				break;
			case "tut":
				$orderby = "max_tuition";
				break;
			default:
				$orderby = "institution";
		}
	  	
	  	$endString = " ORDER BY " . $orderby;
	  	
	  	// concatenate each part
	  	return $startString . $uniGroupString . " AND " . $tuitionString . " AND " . $ucasString . " AND " . $studentStaffRatioString . " AND " . $careerProspectsString . " AND " . $teacherRatingString . " AND " . $feedbackRatingString . " AND " . $overallRatingString . " AND " . $pubString . " AND " . $clubString . $endString;  	
	}
	
	function InitDB() {

                $user = getenv("DB1_USER");
                $pass = getenv("DB1_PASS");

                $con = mysql_connect("localhost", $user, $pass);

                if (!$con) {

TestLog("DB connect failed with" . mysql_error());

                        die('Could not connect: ' . mysql_error()); // if can't connect
                }
                mysql_select_db("bowsy_yrs2011", $con);

                return $con;

	}
	
	function GetInfoTable($row, $showCourses) { // I know one big string would work, but this prevents sending several kB of tabs...
		if ($showCourses) { 
			$courses = "<b>Courses:</b>"."<div id=\"courses\">Loading...</div>";
		}
		return "<table cellpadding=5>".
			"<tr>".
				"<td valign=top>".
					"<a href=\"".$row['website']."\" target=_blank>[Website]</a><br />".
					"<b>2012 rank:</b> ".$row['2012_rank']."<br />".
					"<b>Uni group:</b> ".$row['uni_group']."<br />".
					"<b>Student/staff ratio:</b> ".$row['student_staff_ratio']."<br />".
					"<b>Max tuition:</b> £".$row['max_tuition']."<br />".
					"<b>NSS teaching:</b> ".$row['nss_teaching']."%<br />".
					"<b>NSS feedback:</b> ".$row['nss_feedback']."%<br />".
					"<b>NSS overall:</b> ".$row['nss_overall']."%<br />".
					"<b>Career prospects:</b> ".$row['career_prospects']."%<br />".
					"<b>Average entry tariff:</b> ".$row['entry_tariff']." UCAS points".
				"</td>".
				"<td valign=top>".
					"<b>Address:</b> ".$row['address']."<br />".
					"<b>Pubs:</b> ".$row['num_of_pubs']."<br />".
					"<b>Clubs:</b> ".$row['num_of_clubs']."<br />".
					$courses.
				"</td>".
			"</tr>".
		"</table>";
	}
	
	// Begin Main ---------------------------------------------------------------
	
	// Mustn't forget this!
	header("Content-Type: application/json");
	
	// Figure out if the browser supports gzip
	if ($gzip = substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
		header("Content-Encoding: gzip");
	}

	// Load in the database
	$con = InitDB();
	// Load a flat-file database of colours
	$colours = file('colours.txt');

	// Assemble a Query String from the data we've been sent
  	if ($all = (mysql_real_escape_string($_GET['display']) == "all")) { // Display All Mode
  		$queryString = "SELECT * FROM guardian_combo_complete ORDER BY institution";
  	} else { // Normal Mode
  		$queryString = GetQuery();
  	}
  	
  	// Create something in which to store the output
  	$json = array();
  	
  	// We send this over to make a neat callback
  	$course = mysql_real_escape_string($_GET['course']);
  	
  	// Add stars if we're storting by something for a particular course
  	$star = !($all || mysql_real_escape_string($_GET['sort']) == "alpha");
  	 
  	// Put in that Query
  	$result = mysql_query($queryString);
  	
  	// Work out how many stars we should put on
  	$starCount = ceil(mysql_num_rows($result) / 10);  	
  	
	// Add an array element for each result
	$i = 0;
  	while($row = mysql_fetch_array($result)){
  		$json[] = array(
  			'name' => $row['institution'],
	  		'code' => $row['university_code'],
 			'lat'  => $row['latitude'],
 			'lng'  => $row['longitude'],		
 			'star' => $star && ($i < $starCount), // Maybe star the first 10%
 			'course' => $all ? "NONE" : $course, // If we're getting all, course is irrelevant.
 			'colour' => $colours[$i],
  			'info' => GetInfoTable($row, !$all) // Include the 'courses' title if we're not getting all.
  		); $i++;
  	}
  	
  	// And echo the result!
  	if ($gzip) { 
  		echo(gzencode(json_encode($json)));
  	} else {
  		echo(json_encode($json));
  	}
?>
