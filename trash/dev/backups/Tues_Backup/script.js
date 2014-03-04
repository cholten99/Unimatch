/* Universities jQuery */

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

// When the page loads
$(function() {
	// Load the boxes

	$("#results-box").hide();

	$("#go").button();
	$("#back").button();

	$("#go").click(show_sort_box);
	$("#back").click(show_search_box);
	
	// Load the slider
	$( "#slider-range" ).slider({
		range: true,
		min: 0,
		max: 9000,
		values: [ 0, 9000 ],
		step: 50,
		slide: function( event, ui ) {
			$( "#amount" ).val( "£" + ui.values[ 0 ] + " - £" + ui.values[ 1 ] );
		}
	});
	$( "#amount" ).val( "£" + $( "#slider-range" ).slider( "values", 0 ) +
		" - £" + $( "#slider-range" ).slider( "values", 1 ) )
});