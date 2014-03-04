/* Map jQuery */

// When the page loads
$(function() {	
	// Load the google map
	
	var latlng = new google.maps.LatLng(54, -10);
	var myOptions = {
		zoom: 6,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	
	var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	
	var myParser = new geoXML3.parser({map: map, zoom: false});
	myParser.parse('places.php');
});