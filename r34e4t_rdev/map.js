/* Map jQuery */	

function getMap() { // Create a map object
	var uk = new google.maps.LatLng(53.5, -10);
	var myOptions = {
		zoom: 		6,
		center: 	uk,
		mapTypeId: 	google.maps.MapTypeId.ROADMAP,
		backgroundColor: "#A5BFDD",
		infoWindow: 	new google.maps.InfoWindow({
					content:  "Default",
					code:     "",
					maxWidth: 500 })
	};
	
	// Create a map
	return new google.maps.Map(document.getElementById("map_canvas"), myOptions);
}
function getResult() { // Create a result object
	return {
		valid: false, // Is it complete? Can we access the data?
		redraw: false, // Is a re-draw required when the user hits the search button?
		placemarks: [], // An array of the currently drawn markers
		bounds: new google.maps.LatLngBounds() // The bounds of the marker collection
	}
}

function requestPlaces(data, unimap, result) {
	// Clear all the current placemarks
	for (i = 0; i < result.placemarks.length; i++) {
		result.placemarks[i].setMap(null);
	}

	// A put in the request for the new ones
	$.ajax({
	        url: 'placesinjson.php',
	        dataType: "json",
	        data: data,
	        error: function(response) { alert("Help!"); },
	        success: function(placemarks) { parseJSON(placemarks, unimap, result); }
    	});
}
function parseJSON(placemarks, unimap, result) {
	if (!placemarks) { // Error retrieving the data
		alert("Fail Retrieving Data!");
	} else {
		// Reset the results object
		result.valid = false; // Is it complete? Can we access the data?
		result.redraw = false; // Is a re-draw required when the user hits the search button?
		result.placemarks = []; // An array of the currently drawn markers
		result.bounds = new google.maps.LatLngBounds(); // The bounds of the marker collection
		
		// In the results box
		$("#loading").hide();
		$("#results-container").html(""); // Should already be done, but just in case...
		
		// Foreach placemark
		for (i = 0; i < placemarks.length; i++) {
			// Extend the maps bounds
			result.bounds.extend(new google.maps.LatLng(placemarks[i].lat, placemarks[i].lng));
			
			// Concat the url we're going to get this marker's image from
			
			var icon = "http://chart.apis.google.com/chart?chst=";
			if (placemarks[i].star) { 
				icon = icon + "d_map_xpin_letter&chld=pin_star%7C%7C" + placemarks[i].colour + "%7C%7Cefef88"; 
			} else { 
				icon = icon + "d_map_pin_letter&chld=%7C" + placemarks[i].colour; 
			}
			var shadow = "http://chart.apis.google.com/chart?chst=d_map_pin_shadow";
			
			// Create a template for the marker
			var markerOptions = {
				map:      unimap,
				position: new google.maps.LatLng(placemarks[i].lat, placemarks[i].lng),
				title:    placemarks[i].name,
				code:     placemarks[i].code,
				course:   placemarks[i].course,
				hasLoadC: false,
				zIndex:   -i,
				icon:     { anchor: { x: 10, y: 32 }, url: icon },
				shadow:	  { anchor: { x: 12, y: 34 }, url: shadow }
			}; 
			
			// Create the marker object on the map
			result.placemarks.push(new google.maps.Marker(markerOptions));
			
			// Create a template for its infoWindow
			var infoWindowOptions = {
				content: 	'<div class="infowindow"><h3>' + placemarks[i].name + 
						'</h3><div>' + placemarks[i].info + 
						'</div></div>',
				pixelOffset: 	new google.maps.Size(0, 2)
			};
			
			// Create the info window object
			result.placemarks[i].infoWindow = new google.maps.InfoWindow(infoWindowOptions);  
			
			// Create a button for this in the results page
			$("#results-container").html(
				$("#results-container").html() +
				"<button class=\"ui-widget-content res-button\" id=\"b-" + i +
				"\" style=\"background: #" + placemarks[i].colour +
				"\">" + placemarks[i].name + "</button>");
	
			addHandlers(unimap, result, i);
		}
		if (placemarks.length < 1) { // No items!
		$("#results-container").html("<label id=\"no-res\">No Results Found!</label>"); }
		
		// Init the button-ness
		$(".res-button").button();
		
		// All done!
		result.valid = true;
	}
}
function addHandlers(unimap, result, i) {
	var index = i; // make a copy of 'i's current state
	
	// Attach a handler for opening the info when the marker is clicked
	google.maps.event.addListener(result.placemarks[i], 'click', function(even) {
		highlight(unimap, result, index);
	});
	
	$("#results-container").delegate("#b-"+i, 'click', function() { 
		highlight(unimap, result, index); 
	});
}
function highlight(unimap, result, index) {
	if (result.valid) {
		// If we need to load the courses 
		if (!result.placemarks[index].hasLoadC && result.placemarks[index].course != "NONE") { 
			// Do some AJAX to grab them
			$.ajax({
			        url: 'getcourses.php',
			        data: { code: result.placemarks[index].code,
			        	course: result.placemarks[index].course },
			        error: function(response) { alert("help!"); },
			        success: function(response) { loadCourses(response, unimap, result, index); }
		    	});
		}
	
		unimap.infoWindow.content = result.placemarks[index].infoWindow.content;
		unimap.infoWindow.code = result.placemarks[index].code;
		unimap.infoWindow.open(unimap, result.placemarks[index]);
	}
}
function loadCourses(courses, unimap, result, index) {
	// Set the flag to say we have got this data
	result.placemarks[index].hasLoadC = true;

	// Update the content we have
	var newContent = result.placemarks[index].infoWindow.content.replace(
					'<div id="courses">Loading...</div>', courses);
					
	result.placemarks[index].infoWindow.content = newContent;
	
	// And maybe the current infoWindows
	if (unimap.infoWindow.code == result.placemarks[index].code) { // If the infoWindow is still the same	
		unimap.infoWindow.content = newContent;
		unimap.infoWindow.open(unimap, result.placemarks[index]);
	}
}
