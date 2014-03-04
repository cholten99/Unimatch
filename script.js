/* Universities jQuery */

// When the page loads
$(function() {
	// Load the boxes
	$("#results-box").hide();
	
	$("#go").button();
	$("#back").button();
	
	// Setup objects
	var unimap = getMap();
	var result = getResult();
	
	// Put in an AJAX request for a Grab & Render
	requestPlaces({display: "all"}, unimap, result);

	$("#go").click(function() {
		show_results_box();
		searchsort(unimap, result);
	});
	$("#sort-combobox").change(function() {
		searchsort(unimap, result);
	});
	$("#back").click(function() {
		show_search_box();
	});
	
	loadDumbUI();
});
function show_search_box() { 
	$("#search-box").show();	
	$("#results-box").hide();	
}
function show_results_box() { 
	$("#results-box").show();	
	$("#search-box").hide();
}

function searchsort(unimap, result) {
	$("#results-container").html(""); // Clear the current results
	$("#loading").show(); // Put up a loading message

	// Get & Display all the relevant places over AJAX
	requestPlaces({
		course: $("#coursename").val(),
		group:	$("#group-combobox").val(),
		type: 	$("#type-combobox").val(),
		
		mindist: $("#slider-distance").slider("values", 0),
		maxdist: $("#slider-distance").slider("values", 1),
		
		ucas: $("#ucas-combobox").val(),
		
		mintut: $("#slider-tuition").slider("values", 0),
		maxtut: $("#slider-tuition").slider("values", 1),
		
		minstaff: $("#slider-staff").slider("values", 0),
		maxstaff: $("#slider-staff").slider("values", 1),
		
		mincar: $("#slider-career").slider("values", 0),
		maxcar: $("#slider-career").slider("values", 1),
		
		teach: 	$("#slider-teacher").slider("value"),	
		feed: 	$("#slider-feedback").slider("value"),	
		over:	$("#slider-overall").slider("value"),
		
		minpub: $("#slider-pubs").slider("values", 0),
		maxpub: $("#slider-pubs").slider("values", 1),
		
		minclub: $("#slider-clubs").slider("values", 0),
		maxclub: $("#slider-clubs").slider("values", 1),
		
		sort: $("#sort-combobox").val()
	}, unimap, result);
}

function validate(normalGo) {
	var course_val = $("#coursename").val();
	
	if (course_val == "Course:" || course_val == "") { // Invalid course	
		$("#go").attr("disabled", "true");
		$("#go").css("color", "#aaaaaa");	 
	} else { // All correct
		$("#go").removeAttr("disabled");
		$("#go").css("color", normalGo);
	}
}

	
function loadDumbUI() { // Dumb basically means its events are self contained
	// Disable the Go Box to start with
	var normalGo = $("#go").css("color");
	$("#go").attr("disabled", "true");
	$("#go").css("color", "#aaaaaa");
	
	$("#coursename").val("Course:");
	$("#coursename").focus(function() { // Take out the default value if required
		if($("#coursename").val() == "Course:") {
			$("#coursename").val("");
		}
	});
	$("#coursename").blur(function() { // Put in the default value to a blank box
		if($("#coursename").val() == "") {
			$("#coursename").val("Course:");
		}
	});
	$("#coursename").bind("change blur keyup", function() { validate(normalGo); });
	
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
}