<?php
	header("Content-Type: application/vnd.google-earth.kml+xml");
	echo("<?xml version='1.0' encoding='UTF-8'?>
	<kml xmlns='http://www.opengis.net/kml/2.2'>
	<Document>");
	$con = mysql_connect("localhost","bowsy","VU8Jc7ccirsre73"); // database info
	if (!$con) {
  		die('Could not connect: ' . mysql_error()); // if can't connect
  	}
  	mysql_select_db("bowsy_yrs2011", $con);
  	
  	$colours = file('colours.txt');
  	
  	// grab variables from querystring
  	$course = mysql_real_escape_string($_GET['course']);
  	$group = mysql_real_escape_string($_GET['group']);
  	$minTut = mysql_real_escape_string($_GET['min-tut']);
  	$maxTut = mysql_real_escape_string($_GET['max-tut']);
  	$minStaff = mysql_real_escape_string($_GET['min-staff']);
  	$maxStaff = mysql_real_escape_string($_GET['max-staff']);
  	$minCar = mysql_real_escape_string($_GET['min-car']);
  	$maxCar = mysql_real_escape_string($_GET['max-car']);
  	$teach = mysql_real_escape_string($_GET['teach']);
  	$feedback = mysql_real_escape_string($_GET['feed']);
  	$overall = mysql_real_escape_string($_GET['over']);
  	$minPub = mysql_real_escape_string($_GET['min-pub']);
  	$maxPub = mysql_real_escape_string($_GET['max-pub']);
  	$minClub = mysql_real_escape_string($_GET['min-club']);
  	$maxClub = mysql_real_escape_string($_GET['max-club']);
  	
  	// parts of the SELECT statement - to be concatenated
 // 	$startString = "SELECT * FROM guardian_combo_complete WHERE ";
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
  	$queryString = $startString . $uniGroupString . " AND " . $tuitionString . " AND " . $ucasString . " AND " . $studentStaffRatioString . " AND " . $careerProspectsString . " AND " . $teacherRatingString . " AND " . $feedbackRatingString . " AND " . $overallRatingString . " AND " . $pubString . " AND " . $clubString . $endString;
  	// display all mode
  	if (mysql_real_escape_string($_GET['display']) == "all") {
  		$queryString = "SELECT * FROM guardian_combo_complete ORDER BY institution";
  	}
  	//echo $queryString;
  	
  	$result = mysql_query($queryString);
  	while($row = mysql_fetch_array($result)){
  		$code = mysql_real_escape_string($row['university_code']);
 // 			<Style id=$code>
//				<Icon>
//					<href><![CDATA[https://chart.googleapis.com/chart?chst=d_map_xpin_letter&chld=pin%7C+%7C29ad8%7C000000%7CFF0000]]></href>
//				</Icon>
//			</Style>
  			echo("<Placemark>
  				<name>".$row['institution']."</name>
  				<Point><coordinates>".$row['longitude'].",".$row['latitude']."</coordinates></Point>
  				<description>
					<![CDATA[
						<table cellpadding=5>
							<tr>
								<td valign=top>
									<a href=".$row['website'].">[Website]</a><br />
									<b>2012 rank:</b> ".$row['2012_rank']."<br />
									<b>Uni group:</b> ".$row['uni_group']."<br />
									<b>Student/staff ratio:</b> ".$row['student_staff_ratio']."<br />
									<b>Max tuition:</b> £".$row['max_tuition']."<br />
									<b>NSS teaching:</b> ".$row['nss_teaching']."%<br />
									<b>NSS feedback:</b> ".$row['nss_feedback']."%<br />
									<b>NSS overall:</b> ".$row['nss_overall']."%<br />
									<b>Career prospects:</b> ".$row['career_prospects']."%<br />
									<b>Average entry tariff:</b> ".$row['entry_tariff']." UCAS points
								</td>
								<td valign=top>
									<b>Address:</b> ".$row['address']."<br />
									<b>Pubs:</b> ".$row['num_of_pubs']."<br />
									<b>Clubs:</b> ".$row['num_of_clubs']."<br />");
if (isset($_GET['course'])) {
	echo("								<b>Courses:</b>
									<table border=1>
										<tr><th>Name</th><th>Type</th><th>Code</th></tr>");
	$result2 = mysql_query("SELECT * FROM courses WHERE university_code = '$code' AND course LIKE '%$course%'");
	while($row2 = mysql_fetch_array($result2)){
		echo "<tr>";
		echo "<td>" . $row2['course'] . "</td>";
		echo "<td>" . $row2['course_type'] . "</td>";
		echo "<td>" . $row2['course_code'] . "</td>";
		echo "</tr>";
	}
	echo ("
									</table>");
}
echo("								</td>
							</tr>
						</table>
					]]>
				</description>
" /*				<styleUrl>#$code</styleUrl> */ ."
  			</Placemark>
  		");
  	}
  echo "
  </Document>
  </kml>";
// Also need to sort by travel time, student:staff ratio, ucas points, career prospects, nss overall, fees.
?>