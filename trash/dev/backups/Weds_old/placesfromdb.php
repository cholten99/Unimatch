<?php
	header("Content-Type: application/vnd.google-earth.kml+xml");
	echo("<?xml version='1.0' encoding='UTF-8'?>
	<kml xmlns='http://www.opengis.net/kml/2.2'>");
	$con = mysql_connect("localhost","bowsy","VU8Jc7ccirsre73"); // database info
	if (!$con) {
  		die('Could not connect: ' . mysql_error()); // if can't connect
  	}
  	mysql_select_db("bowsy_yrs2011", $con);
  	// grab variables from querystring
  	$group = $_GET['group'];
  	$minTut = $_GET['min-tut'];
  	$maxTut = $_GET['max-tut'];
  	$minStaff = $_GET['min-staff'];
  	$maxStaff = $_GET['max-staff'];
  	$teach = $_GET['teach'];
  	$feedback = $_GET['feedback'];
  	$overall = $_GET['overall'];
  	$minPub = $_GET['min-pub'];
  	$maxPub = $_GET['max-pub'];
  	$minClub = $_GET['min-club'];
  	$maxClub = $_GET['max-club'];
  	
  	// parts of the SELECT statement - to be concatenated
  	$startString = "SELECT * FROM guardian_combo WHERE ";
  	$uniGroupString = "uni_group LIKE '%$group%'";
  	$tuitionString = "max_tuition >= $minTut AND max_tuition <= $maxTut";
  	//!!!!!UCAS points
  	$studentStaffRatioString = "student_staff_ratio >= $minStaff AND student_staff_ratio <= $maxStaff";
  	//!!!!!Career Prospects
  	$teacherRatingString = "nss_teaching >= $teach";
  	$feedbackRatingString = "nss_feedback >= $feedback";
  	$overallRatingString = "nss_overall >= $overall";
  	$pubString = "num_of_pubs >= $minPub AND num_of_pubs <= $maxPub";
  	$clubString = "num_of_clubs >= $minClub AND num_of_clubs <= $maxClub";
  	
  	// concatenate each part
  	$queryString = $startString . $uniGroupString . " AND " . $tuitionString . " AND " . $studentStaffRatioString . " AND " . $teacherRatingString . " AND " . $feedbackRatingString . " AND " . $overallRatingString . " AND " . $pubString . " AND " . $clubString;
  	
  	$result = mysql_query($queryString);
  	while($row = mysql_fetch_array($result)){
  		echo("
  			<Placemark>
  				<name>".$row['institution']."</name>
  				<Point><coordinates>".$row['longitude'].",".$row['latitude']."</coordinates></Point>
  				<description>
					<![CDATA[Data goes here.]]>
				</description>
  			</Placemark>
  		");
  	}
  echo "</kml>";
// Also need to sort by travel time, student:staff ratio, ucas points, career prospects, nss overall, fees.
?>