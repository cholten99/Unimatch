<?php
header("Content-Type: application/vnd.google-earth.kml+xml");
echo("<?xml version='1.0' encoding='UTF-8'?>
<kml xmlns='http://www.opengis.net/kml/2.2'>");
$file_handle = fopen("https://spreadsheets.google.com/spreadsheet/pub?hl=en_GB&hl=en_GB&key=0AlmchlQZetn7dHJ2MW40UHU5MWZmNlVBR0dBbzNkNEE&single=true&gid=0&output=csv", "r");

$flag=true;
while (($line_of_text = fgetcsv($file_handle, 1000, ",")) !== FALSE) { 
if($flag) { $flag = false; continue; }

	if ($line_of_text[21] !== "" and $line_of_text[22] !== ""){
		if ($line_of_text[5] !== "") {$uniGroup = "<b>Uni group:</b> ".$line_of_text[5]."<br />";} else {$uniGroup = "";};
		if ($line_of_text[24] !== "") {$address = "<b>Address:</b> ".$line_of_text[24]."<br />";} else {$address = "";};
		if ($line_of_text[25] !== "") {$website = "<b>Website:</b> <a href=".$line_of_text[25]." target=_blank>".$line_of_text[25]."</a><br />";} else {$website = "";};
		if ($line_of_text[7] !== "") {$maxTuitionFee = "<b>Max tuition free:</b> ".$line_of_text[7]."<br />";} else {$maxTuitionFee = "";};
		if ($line_of_text[15] !== "") {$studentStaffRatio = "<b>Student/Staff Ratio:</b> ".$line_of_text[15]."<br />";} else {$studentStaffRatio = "";};
		if ($line_of_text[0] !== "") {$ranking = "<b>Ranking:</b> ".$line_of_text[0]."<br />";} else {$ranking = "";};
	echo ("
	<Placemark>
		<name>".$line_of_text[4]."</name>
		<Point>
			<coordinates>".$line_of_text[22].",".$line_of_text[21]."</coordinates>
			<description>
				<![CDATA[".$uniGroup.$address.$website.$maxTuitionFee.$studentStaffRatio.$ranking."]]>
			</description>
		</Point>
	</Placemark>");
	
	}
}

fclose($file_handle);

echo ("
</kml>");

?>