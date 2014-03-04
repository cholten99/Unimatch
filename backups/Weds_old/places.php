<?php
header("Content-Type: application/vnd.google-earth.kml+xml");
echo("
<?xml version='1.0' encoding='UTF-8'?>
<kml xmlns='http://www.opengis.net/kml/2.2'>");
$file_handle = fopen("https://spreadsheets.google.com/spreadsheet/pub?hl=en_GB&hl=en_GB&key=0AlmchlQZetn7dHJ2MW40UHU5MWZmNlVBR0dBbzNkNEE&single=true&gid=0&output=csv", "r");

$flag=true;
while (($line_of_text = fgetcsv($file_handle, 1000, ",")) !== FALSE) { 
if($flag) { $flag = false; continue; }

	if ($line_of_text[21] !== "" and $line_of_text[22] !== ""){
		if ($line_of_text[5] !== "") {$uniGroup = "<b>Uni group:</b> ".$line_of_text[5]."<br />";} else {$uniGroup = "";};
		if ($line_of_text[24] !== "") {$address = "<b>Address:</b> ".$line_of_text[24]."<br />";} else {$address = "";};
		if ($line_of_text[25] !== "") {$website = "<b>Website:</b> <a href=".$line_of_text[25]." target=_blank>".$line_of_text[25]."</a><br />";} else {$website = "";};
		if ($line_of_text[7] !== "") {$maxTuitionFee = "<b>Max tuition fee:</b> £".$line_of_text[7]."<br />";} else {$maxTuitionFee = "";};
		if ($line_of_text[15] !== "") {$studentStaffRatio = "<b>Student/Staff Ratio:</b> ".$line_of_text[15]."<br />";} else {$studentStaffRatio = "";};
		if ($line_of_text[0] !== "") {$ranking = "<b>Ranking:</b> ".$line_of_text[0]."<br />";} else {$ranking = "";};
		if ($line_of_text[11] !== "") {$NSSTeaching = "<b>NSS Teaching:</b> ".$line_of_text[11]."%<br />";} else {$NSSTeaching = "";};
		if ($line_of_text[12] !== "") {$NSSOverall = "<b>NSS Overall:</b> ".$line_of_text[12]."%<br />";} else {$NSSOverall = "";};
		if ($line_of_text[13] !== "") {$NSSFeedback = "<b>NSS Feedback:</b> ".$line_of_text[13]."%<br />";} else {$NSSFeedback = "";};
		if ($line_of_text[27] !== "") {$pubs = "<b>Pubs:</b> ".$line_of_text[27]."<br />";} else {$pubs = "";};
		if ($line_of_text[28] !== "") {$clubs = "<b>Clubs:</b> ".$line_of_text[28]."<br />";} else {$clubs = "";};
		if ($line_of_text[16] !== "") {$careerProspects = "<b>Career prospects:</b> ".$line_of_text[16]."%<br />";} else {$careerProspects = "";};
		if ($line_of_text[18] !== "") {$ucas = "<b>Entry tariff:</b> ".$line_of_text[18]."<br />";} else {$ucas = "";};
//		$userpost = $_GET['postcode'];
//		$rq = sprintf("http://maps.googleapis.com/maps/api/distancematrix/json?origins=%s+GB&destinations=%d,%d&language=en-GB&sensor=false", $userpost, $line_of_text[21], $line_of_text[22]); 
//		$response = file_get_contents($rq);
//		$json = json_decode($response, true);
//		$timestring =  $json["rows"][0]["elements"][0]["duration"]["text"];
//		$time = "Travel time: ".$timestring;
	echo ("
	<Placemark>
		<name>".$line_of_text[4]."</name>
		<Point>
			<coordinates>".$line_of_text[22].",".$line_of_text[21]."</coordinates>
			<description>
				<![CDATA[
					<table>
						<tr>
							<td>".$ranking.$website.$uniGroup.$studentStaffRatio.$maxTuitionFee.$NSSTeaching.$NSSOverall.$NSSFeedback.$careerProspects.$ucas."</td>
							<td>".$address.$time.$pubs.$clubs."<b>Courses: </b><br />
								<table border=1>
									<tr><th>Name</th><th>Type</th><th>UCAS points</th></tr>
									<tr><td>CourseName1</td><td>CourseType1</td><td>UCASPoints1</td></tr>
									<tr><td>CourseName2</td><td>CourseType2</td><td>UCASPoints2</td></tr>
									<tr><td>CourseName3</td><td>CourseType3</td><td>UCASPoints3</td></tr>
								</table>"."</td>
						</tr>
					</table>
					<ul>
						<li>course: ".$_GET['course']."</li>
						<li>group: ".$_GET['group']."</li>
						<li>type: ".$_GET['type']."</li>
						<li>postcode: ".$_GET['postcode']."</li>
						<li>min-dist: ".$_GET['min-dist']."</li>
						<li>max-dist: ".$_GET['max-dist']."</li>
						<li>min-staff: ".$_GET['min-staff']."</li>
						<li>max-staff: ".$_GET['max-staff']."</li>
						<li>min-tut: ".$_GET['min-tut']."</li>
						<li>max-tut: ".$_GET['max-tut']."</li>
						<li>min-car: ".$_GET['min-car']."</li>
						<li>max-car: ".$_GET['max-car']."</li>
						<li>teach: ".$_GET['teach']."</li>
						<li>feed: ".$_GET['feed']."</li>
						<li>over: ".$_GET['over']."</li>
						<li>min-pub: ".$_GET['min-pub']."</li>
						<li>max-pub: ".$_GET['max-pub']."</li>
						<li>min-club: ".$_GET['min-club']."</li>
						<li>max-club: ".$_GET['max-club']."</li>
						<li>min-ucas: ".$_GET['ucas']."</li>
					</ul>
				]]>
			</description>
		</Point>
	</Placemark>");
	
	}
}

fclose($file_handle);

echo ("
</kml>");

?>