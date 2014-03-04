/* Universities jQuery */

var normalGo = 0;
var infoWindow = new google.maps.InfoWindow({
	content: "Hello World",
	maxWidth: 500
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
	$("#loading").hide();

	var markup = "";
	
	for (var i = 0; i < allmarkers.length; i++) {
		markup = markup.concat("<button class=\"ui-widget-content res-button\" id=\"b-",
		i,
		"\" onclick=\"highlight_marker(",
		i,
		")\" style=\"background: #",
		col[i + 1],
		"\">", 
		allmarkers[i].title, 
		"</button>");
	}
	
	if (allmarkers.length > 0) {	
		$("#results-container").html(markup);
	} else {
		$("#results-container").html("<div id=\"no-res\">No Results Found!</div>");
	}
	
	$(".res-button").button();
}
function do_go() {
	show_sort_box();

	sort_stuff();
}
function sort_stuff() {
	$("#results-container").html("");
	$("#loading").fadeIn();

	// Strip out all the current markers (even though they are still in memory!!!)
	for (var i = 0; i < allmarkers.length; i++) {
		allmarkers[i].setMap(null);
	}
	
	var postcode_val = $("#postcode").val();
	if(!isValidPostcode(postcode_val)) { // Invalid postcode
		postcode_val = "";
	} else {
		postcode_val = formatPostcode(postcode_val);
	}
	
	// Pull the data from the server and display it on the map, 
	// with a callback to display it in the sort box
	myParser.parse("placesfromdb.php?".concat(
		"course=", $("#coursename").val(),
		"&group=", $("#group-combobox").val(),
		"&type=", $("#type-combobox").val(),
		"&postcode=", postcode_val,
		
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
		"&max-club=", $("#slider-clubs").slider("values", 1),
		
		"&sort=", $("#sort-combobox").val()
	), populate_results);
}

function validate() {
	var course_val = $("#coursename").val();
	var postcode_val = $("#postcode").val();
	
	if (course_val == "Course:" || course_val == "" || 	// Invalid Course or postcode
	(!isValidPostcode(postcode_val) && postcode_val != "" && postcode_val != "Home Postcode:")) {	
		$("#go").attr("disabled", "true");
		$("#go").css("color", "#aaaaaa");	 
	} else { // All correct
		$("#go").removeAttr("disabled");
		$("#go").css("color", normalGo);
	}
}

// When the page loads
$(function() {
	// Load the boxes
	$("#results-box").hide();
	
	$("#go").button();
	$("#back").button();

	$("#go").click(do_go);
	$("#back").click(show_search_box);
	
	$("#sort-combobox").change(sort_stuff);
	
	// Disable the Go Box to start with
	normalGo = $("#go").css("color");
	$("#go").attr("disabled", "true");
	$("#go").css("color", "#aaaaaa");
	
	$("#coursename").defaultvalue("Course:");
	$("#postcode").defaultvalue("Home Postcode:");
	
	$("#coursename").bind("change blur keyup", validate);
	$("#postcode").change(function () {
		validate(); 
		
		if (!isValidPostcode($("#postcode").val())) { // Invalid postcode
			alert("Please enter a valid postcode!");
		} 
	});
	
	
	// Load sort stuff
	// Load the distance slider
	$("#slider-distance").slider({
		range: true,
		min: 0, max: 10,
		values: [0, 10],
		slide: function(event, ui) {
			$("#amount-distance").html(ui.values[0] + " Hours - " + ui.values[1] + " Hours");
		}
	});
	// Load the tuition slider
	$("#slider-tuition").slider({
		range: true,
		min: 0, max: 9000,
		values: [0, 9000],
		step: 50,
		slide: function(event, ui) {
			$("#amount-tuition").html("£" + ui.values[0] + " - £" + ui.values[1]);
		}
	});
	// Load the student staff ratio slider
	$("#slider-staff").slider({
		range: true,
		min: 0, max: 30,
		values: [0, 30],
		slide: function(event, ui) {
			$("#amount-staff").html(ui.values[0] + " - " + ui.values[1] + " students per staff");
		}
	});
	// Load the career prospects slider
	$("#slider-career").slider({
		range: true,
		min: 0, max: 100,
		values: [0, 100],
		slide: function(event, ui) {
			$("#amount-career").html(ui.values[0] + "% employed - " + ui.values[1] + "% employed");
		}
	});
	// Load the NSS Teacher
	$("#slider-teacher").slider({
		range: "max",
		min: 0, max: 100,
		value: 0,
		slide: function(event, ui) {
			$("#amount-teacher").html(ui.value + "%+ positive about teaching");
		}
	});
	// Load the NSS Feedback
	$("#slider-feedback").slider({
		range: "max",
		min: 0, max: 100,
		value: 0,
		slide: function(event, ui) {
			$("#amount-feedback").html(ui.value + "%+ positive about feedback recieved");
		}
	});
	// Load the NSS Overall
	$("#slider-overall").slider({
		range: "max",
		min: 0, max: 100,
		value: 0,
		slide: function(event, ui) {
			$("#amount-overall").html(ui.value + "%+ positive overall rating");
		}
	});
	// Load the Pubs
	$("#slider-pubs").slider({
		range: true,
		min: 0, max: 1000,
		values: [0, 1000],
		slide: function(event, ui) {
			$("#amount-pubs").html(ui.values[0] + " - " + ui.values[1] + " Pubs");
		}
	});
	// Load the Pubs
	$("#slider-clubs").slider({
		range: true,
		min: 0, max: 200,
		values: [0, 200],
		slide: function(event, ui) {
			$("#amount-clubs").html(ui.values[0] + " - " + ui.values[1] + " Clubs");
		}
	});
});