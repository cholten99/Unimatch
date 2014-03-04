/* Universities jQuery */

var oldGoColor = 0;
var infoWindow = new google.maps.InfoWindow({
	content: "Hello World",
	maxWidth: 300
});

// When the search box gets clicked
function show_search_box() { 
	$("#search-box").show();	
	$("#results-box").hide();	
}
// When the sort box gets clicked
function show_sort_box() { 
	$("#results-box").show();	
	$("#search-box").hide();
}

function highlight_marker(i) {
	infoWindow.content = allmarkers[i].infoWindow.content;
	infoWindow.open(map, allmarkers[i]);
}
function highlight_marker_from_obj(obj) {
	infoWindow.content = obj.infoWindow.content;
	infoWindow.open(map, obj);
}

// tests to see if string is in correct UK style postcode: AL1 1AB, BM1 5YZ etc.
function isValidPostcode(p) {
	var postcodeRegEx = /[A-Z]{1,2}[0-9]{1,2} ?[0-9][A-Z]{2}/i;
	return postcodeRegEx.test(p);
}

// formats a VALID postcode nicely: AB120XY -> AB1 0XY
function formatPostcode(p) {
	if (isValidPostcode(p)) {
		var postcodeRegEx = /(^[A-Z]{1,2}[0-9]{1,2})([0-9][A-Z]{2}$)/i;
		return p.replace(postcodeRegEx,"$1 $2");
	} else {
		return p;
	}
}

function populate_results() {
	var markup = "";
	
	for (var i = 0; i < allmarkers.length; i++) {
		markup = markup.concat("<button class=\"ui-widget-content\" id=\"b-",
		i,
		"\" onclick=\"highlight_marker(",
		i,
		")\">", 
		allmarkers[i].title, 
		"</button>");
	}
	
	$("#results-container").html(markup);
}
function do_go() {
	show_sort_box();
	
	// Strip out all the current markers (even though they are still in memory!!!)
	for (var i = 0; i < allmarkers.length; i++) {
		allmarkers[i].setMap(null);
	}
	
	// Pull the data from the server and display it on the map
	myParser.parse("places.php?".concat(
		"course=", $("#coursename").val(),
		"&group=", $("#group-combobox").val(),
		"&type=", $("#type-combobox").val(),
		"&postcode=", $("#postcode").val(),
		
		"&min-dist=", $("#slider-distance").slider("values", 0),
		"&max-dist=", $("#slider-distance").slider("values", 1),
		
		"&ucas=", $("#ucas-combobox").val(),
		
		"&min-tut=", $("#slider-tuition").slider("values", 0),
		"&max-tut=", $("#slider-tuition").slider("values", 1),
		
		"&min-staff=", $("#slider-staff").slider("values", 0),
		"&max-staff=", $("#slider-staff").slider("values", 1),
		
		"&min-car=", $("#slider-career").slider("values", 0),
		"&max-car=", $("#slider-career").slider("values", 1),
		
		"&teach=", $("#slider-teacher").slider("value"),	
		"&feed=", $("#slider-feedback").slider("value"),	
		"&over=", $("#slider-overall").slider("value"),
		
		"&min-pub=", $("#slider-pubs").slider("values", 0),
		"&max-pub=", $("#slider-pubs").slider("values", 1),
		
		"&min-club=", $("#slider-clubs").slider("values", 0),
		"&max-club=", $("#slider-clubs").slider("values", 1)
	));
	
	// Fill up the sort box
	populate_results();
}

// When the page loads
$(function() {
	// Load the boxes
	$("#results-box").hide();

	$("#go").button();
	$("#back").button();

	$("#go").click(do_go);
	$("#back").click(show_search_box);
	
	$("#postcode").change(function () { 
		if (!isValidPostcode($("#postcode").val())) { // Invalid postcode
			alert("Please enter a valid postcode!");
			$("#go").attr("disabled", "true");
			
			oldGoColor = $("#go").css("color");
			$("#go").css("color", "#aaaaaa");
		} else { 
			$("#go").removeAttr("disabled");
			
			if (oldGoColor != 0) {
				$("#go").css("color", oldGoColor);
			}		
		}
	});
	
	// Load sort stuff
	// Load the distance slider
	$("#slider-distance").slider({
		range: true,
		min: 0, max: 10,
		values: [0, 10],
		slide: function(event, ui) {
			$("#amount-distance").val(ui.values[0] + " Hours - " + ui.values[1] + " Hours");
		}
	});
	// Load the tuition slider
	$("#slider-tuition").slider({
		range: true,
		min: 0, max: 9000,
		values: [0, 9000],
		step: 50,
		slide: function(event, ui) {
			$("#amount-tuition").val("£" + ui.values[0] + " - £" + ui.values[1]);
		}
	});
	// Load the student staff ratio slider
	$("#slider-staff").slider({
		range: true,
		min: 0, max: 30,
		values: [0, 30],
		slide: function(event, ui) {
			$("#amount-staff").val(ui.values[0] + " - " + ui.values[1] + " students per staff");
		}
	});
	// Load the career prospects slider
	$("#slider-career").slider({
		range: true,
		min: 0, max: 100,
		values: [0, 100],
		slide: function(event, ui) {
			$("#amount-career").val(ui.values[0] + "% employed - " + ui.values[1] + "% employed");
		}
	});
	// Load the NSS Teacher
	$("#slider-teacher").slider({
		range: true,
		min: 0, max: 100,
		values: [0],
		slide: function(event, ui) {
			$("#amount-teacher").val(ui.values[0] + "% positive about teaching - " + 
				ui.values[1] + "% positive about teaching");
		}
	});
	// Load the NSS Feedback
	$("#slider-feedback").slider({
		min: 0, max: 100,
		values: [0],
		slide: function(event, ui) {
			$("#amount-feedback").val(ui.values[0] + "% positive about feedback recieved - " +
				ui.values[1] + "% positive about feedback recieved");
		}
	});
	// Load the NSS Overall
	$("#slider-overall").slider({
		min: 0, max: 100,
		values: [0],
		slide: function(event, ui) {
			$("#amount-overall").val(ui.values[0] + "% positive overall rating - " + 
				ui.values[1] + "% positive overall rating");
		}
	});
	// Load the Pubs
	$("#slider-pubs").slider({
		range: true,
		min: 0, max: 1000,
		values: [0, 1000],
		slide: function(event, ui) {
			$("#amount-pubs").val(ui.values[0] + " - " + ui.values[1] + " Pubs");
		}
	});
	// Load the Pubs
	$("#slider-clubs").slider({
		range: true,
		min: 0, max: 200,
		values: [0, 200],
		slide: function(event, ui) {
			$("#amount-clubs").val(ui.values[0] + " - " + ui.values[1] + " Clubs");
		}
	});
});