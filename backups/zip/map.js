/* Map jQuery */

var myParser;
var map;

// When the page loads
$(function() {	
	// Load the google map
	
	var latlng = new google.maps.LatLng(53.5, -10);
	var myOptions = {
		zoom: 6,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		backgroundColor: "#ffffff"
	};
	
	map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	
	myParser = new geoXML3.parser({map: map, zoom: false});
	//myParser.parse('places.php', function() {});
	myParser.parse('placesfromdb.php?display=all', function() {});
});